import fs from 'node:fs';
import path from 'node:path';
import sharp from 'sharp';

const assetsDir = path.resolve('public/assets');

let totalBefore = 0;
let totalAfter = 0;
let count = 0;

async function walkAndCompress(dir) {
    const entries = fs.readdirSync(dir, { withFileTypes: true });

    for (const entry of entries) {
        const fullPath = path.join(dir, entry.name);

        if (entry.isDirectory()) {
            await walkAndCompress(fullPath);
        } else if (entry.isFile()) {
            const ext = path.extname(entry.name).toLowerCase();
            if (!['.png', '.jpg', '.jpeg', '.webp'].includes(ext)) continue;

            const statBefore = fs.statSync(fullPath);
            const sizeBefore = statBefore.size;
            totalBefore += sizeBefore;

            try {
                let pipeline = sharp(fullPath);
                const metadata = await pipeline.metadata();

                // Determine maximum bounds to prevent over-allocation
                let maxWidth = 1600;
                let maxHeight = 1600;

                const nameLower = entry.name.toLowerCase();
                const dirLower = dir.toLowerCase();

                if (nameLower.includes('avatar') || nameLower.includes('cat-') || nameLower.includes('category')) {
                    maxWidth = 400;
                    maxHeight = 400;
                } else if (nameLower.includes('prod') || nameLower.includes('step') || dirLower.includes('info')) {
                    maxWidth = 800;
                    maxHeight = 800;
                }

                if (metadata.width && (metadata.width > maxWidth || metadata.height > maxHeight)) {
                    pipeline = pipeline.resize({
                        width: maxWidth,
                        height: maxHeight,
                        fit: 'inside',
                        withoutEnlargement: true,
                    });
                }

                let buffer;
                if (ext === '.png') {
                    buffer = await pipeline
                        .png({
                            compressionLevel: 9,
                            quality: 80,
                            palette: true,
                            effort: 8,
                        })
                        .toBuffer();
                } else if (['.jpg', '.jpeg'].includes(ext)) {
                    buffer = await pipeline
                        .jpeg({
                            quality: 80,
                            mozjpeg: true,
                        })
                        .toBuffer();
                } else if (ext === '.webp') {
                    buffer = await pipeline
                        .webp({
                            quality: 80,
                            effort: 6,
                        })
                        .toBuffer();
                }

                // Only overwrite if compressed buffer is actually smaller
                if (buffer && buffer.length < sizeBefore) {
                    fs.writeFileSync(fullPath, buffer);
                    totalAfter += buffer.length;
                } else {
                    totalAfter += sizeBefore;
                }

                count++;
            } catch (err) {
                totalAfter += sizeBefore;
                console.error(`Skipped ${entry.name}:`, err.message);
            }
        }
    }
}

console.log('Optimizing public/assets images with Sharp engine...');
await walkAndCompress(assetsDir);

const savedMB = ((totalBefore - totalAfter) / (1024 * 1024)).toFixed(2);
const percent = (((totalBefore - totalAfter) / totalBefore) * 100).toFixed(1);

console.log(`\nOptimization Complete!`);
console.log(`Processed: ${count} assets`);
console.log(`Size Before: ${(totalBefore / (1024 * 1024)).toFixed(2)} MB`);
console.log(`Size After:  ${(totalAfter / (1024 * 1024)).toFixed(2)} MB`);
console.log(`Saved:       ${savedMB} MB (${percent}% smaller!)\n`);
