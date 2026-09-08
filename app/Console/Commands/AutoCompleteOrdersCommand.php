<?php

namespace App\Console\Commands;

use App\Enums\PayoutStatus;
use App\Models\EscrowBalance;
use App\Models\Order;
use App\Models\Payout;
use App\States\Order\Completed;
use App\States\Order\Disputed;
use Illuminate\Console\Command;

class AutoCompleteOrdersCommand extends Command
{
    protected $signature = 'orders:auto-complete';

    protected $description = 'Otomatis menyelesaikan pesanan yang telah melewati masa pemeriksaan 48 jam dan melepaskan dana escrow ke seller';

    public function handle(): int
    {
        $this->info('Memulai pengecekan pesanan delivered yang melewati batas pemeriksaan 48 jam...');

        $count = 0;

        Order::where('status', 'delivered')
            ->whereNotNull('inspection_deadline_at')
            ->where('inspection_deadline_at', '<=', now())
            ->chunkById(100, function ($orders) use (&$count) {
                foreach ($orders as $order) {
                    if ($order->status->equals(Disputed::class)) {
                        continue;
                    }

                    $order->status->transitionTo(Completed::class);
                    $order->update([
                        'completed_at' => now(),
                    ]);

                    // Release escrow balance
                    $escrow = EscrowBalance::where('order_id', $order->id)->first();
                    if ($escrow) {
                        $escrow->update([
                            'is_released' => true,
                            'released_at' => now(),
                        ]);
                    }

                    // Create pending payout for seller
                    Payout::firstOrCreate(
                        ['order_id' => $order->id],
                        [
                            'seller_id' => $order->seller_id,
                            'amount' => $order->total_amount,
                            'bank_details_snapshot' => [
                                'bank_name' => $order->seller->bank_name,
                                'account_number' => $order->seller->bank_account_number,
                                'account_name' => $order->seller->bank_account_name,
                            ],
                            'status' => PayoutStatus::PENDING,
                        ]
                    );

                    $count++;
                    $this->line("Pesanan #{$order->order_number} berhasil auto-completed.");
                }
            });

        $this->info("Selesai. Total {$count} pesanan berhasil diselesaikan.");

        return Command::SUCCESS;
    }
}
