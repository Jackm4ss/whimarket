<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Creasi\Nusa\Models\District;
use Creasi\Nusa\Models\Province;
use Creasi\Nusa\Models\Regency;
use Creasi\Nusa\Models\Village;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RegionController extends Controller
{
    /**
     * Get all provinces in Indonesia (ordered alphabetically).
     */
    public function provinces(): JsonResponse
    {
        $provinces = Province::orderBy('name')->get(['code', 'name']);

        return response()->json([
            'success' => true,
            'data' => $provinces,
        ]);
    }

    /**
     * Get regencies/cities in a province.
     */
    public function regencies(Request $request): JsonResponse
    {
        $provinceCode = $request->query('province_code');

        if (! $provinceCode) {
            return response()->json(['success' => false, 'data' => []]);
        }

        $regencies = Regency::where('province_code', $provinceCode)
            ->orderBy('name')
            ->get(['code', 'name']);

        return response()->json([
            'success' => true,
            'data' => $regencies,
        ]);
    }

    /**
     * Get districts/kecamatan in a regency/city.
     */
    public function districts(Request $request): JsonResponse
    {
        $regencyCode = $request->query('regency_code');

        if (! $regencyCode) {
            return response()->json(['success' => false, 'data' => []]);
        }

        $districts = District::where('regency_code', $regencyCode)
            ->orderBy('name')
            ->get(['code', 'name']);

        return response()->json([
            'success' => true,
            'data' => $districts,
        ]);
    }

    /**
     * Get villages and postal codes in a district/kecamatan.
     */
    public function villages(Request $request): JsonResponse
    {
        $districtCode = $request->query('district_code');

        if (! $districtCode) {
            return response()->json(['success' => false, 'data' => []]);
        }

        $villages = Village::where('district_code', $districtCode)
            ->orderBy('name')
            ->get(['code', 'name', 'postal_code']);

        $postalCodes = $villages->pluck('postal_code')->filter()->unique()->values()->all();

        return response()->json([
            'success' => true,
            'data' => $villages,
            'postal_codes' => $postalCodes,
        ]);
    }

    /**
     * Instant search for location (City, District, Village, or Postal Code).
     * Cached for zero-bottleneck high throughput performance.
     */
    public function search(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }

        $cacheKey = 'nusa_search_'.md5(strtolower($q));

        $results = Cache::remember($cacheKey, now()->addHours(24), function () use ($q) {
            $formatted = collect();

            // 1. Numeric query: Search primarily by postal code in Village
            if (is_numeric($q)) {
                $villages = Village::with(['district.regency.province'])
                    ->where('postal_code', 'like', "{$q}%")
                    ->take(10)
                    ->get();

                foreach ($villages as $v) {
                    $d = $v->district;
                    $r = $d?->regency;
                    $p = $r?->province;

                    if ($d && $r && $p) {
                        $formatted->push([
                            'id' => 'v_'.$v->code,
                            'title' => "Kec. {$d->name}, {$v->name} ({$v->postal_code})",
                            'subtitle' => "{$r->name}, {$p->name}",
                            'village' => $v->name,
                            'village_code' => $v->code,
                            'district' => $d->name,
                            'district_code' => $d->code,
                            'city' => $r->name,
                            'regency_code' => $r->code,
                            'province' => $p->name,
                            'province_code' => $p->code,
                            'postal_code' => (string) $v->postal_code,
                        ]);
                    }
                }

                return $formatted->take(10)->values()->all();
            }

            // 2. Text query: Search in District (highest accuracy for e-commerce kecamatan)
            $districts = District::with(['regency.province'])
                ->where('name', 'like', "%{$q}%")
                ->take(8)
                ->get();

            foreach ($districts as $d) {
                $r = $d->regency;
                $p = $r?->province;
                $postal = $d->postal_codes?->first() ?? '';

                if ($r && $p) {
                    $formatted->push([
                        'id' => 'd_'.$d->code,
                        'title' => "Kec. {$d->name}".($postal ? " ({$postal})" : ''),
                        'subtitle' => "{$r->name}, {$p->name}",
                        'village' => '',
                        'village_code' => '',
                        'district' => $d->name,
                        'district_code' => $d->code,
                        'city' => $r->name,
                        'regency_code' => $r->code,
                        'province' => $p->name,
                        'province_code' => $p->code,
                        'postal_code' => (string) $postal,
                    ]);
                }
            }

            // 3. Search in Regency / City if not enough results
            if ($formatted->count() < 8) {
                $regencies = Regency::with(['province', 'districts'])
                    ->where('name', 'like', "%{$q}%")
                    ->take(5)
                    ->get();

                foreach ($regencies as $r) {
                    $p = $r->province;
                    $firstDist = $r->districts->first();
                    $postal = $firstDist?->postal_codes?->first() ?? '';

                    if ($p) {
                        $formatted->push([
                            'id' => 'r_'.$r->code,
                            'title' => "{$r->name}",
                            'subtitle' => "Provinsi {$p->name}".($firstDist ? " • contoh Kec. {$firstDist->name}" : ''),
                            'village' => '',
                            'village_code' => '',
                            'district' => $firstDist?->name ?? '',
                            'district_code' => $firstDist?->code ?? '',
                            'city' => $r->name,
                            'regency_code' => $r->code,
                            'province' => $p->name,
                            'province_code' => $p->code,
                            'postal_code' => (string) $postal,
                        ]);
                    }
                }
            }

            // 4. Search in Village if still few results
            if ($formatted->count() < 8) {
                $villages = Village::with(['district.regency.province'])
                    ->where('name', 'like', "%{$q}%")
                    ->take(5)
                    ->get();

                foreach ($villages as $v) {
                    $d = $v->district;
                    $r = $d?->regency;
                    $p = $r?->province;

                    if ($d && $r && $p) {
                        $formatted->push([
                            'id' => 'v_'.$v->code,
                            'title' => "Kel./Desa {$v->name} ({$v->postal_code})",
                            'subtitle' => "Kec. {$d->name}, {$r->name}, {$p->name}",
                            'village' => $v->name,
                            'village_code' => $v->code,
                            'district' => $d->name,
                            'district_code' => $d->code,
                            'city' => $r->name,
                            'regency_code' => $r->code,
                            'province' => $p->name,
                            'province_code' => $p->code,
                            'postal_code' => (string) $v->postal_code,
                        ]);
                    }
                }
            }

            return $formatted->unique('id')->take(8)->values()->all();
        });

        return response()->json([
            'success' => true,
            'data' => $results,
        ]);
    }
}
