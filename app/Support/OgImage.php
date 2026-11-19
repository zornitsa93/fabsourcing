<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

/**
 * 1200x630 JPEG copy of an image on the public disk, for og:image / twitter:image.
 * The copy sits next to the original as "<name>_og.jpg" and is created on first use.
 */
class OgImage
{
    public static function pathFor(string $path): string
    {
        return pathinfo($path, PATHINFO_DIRNAME) . '/' . pathinfo($path, PATHINFO_FILENAME) . '_og.jpg';
    }

    /** URL of the OG copy; falls back to the original if it cannot be made. Null when there is no image. */
    public static function url(?string $path): ?string
    {
        if (!$path) return null;

        $disk = Storage::disk('public');
        $og   = self::pathFor($path);

        if (!$disk->exists($og) && $disk->exists($path)) {
            try {
                (new ImageManager(new Driver()))
                    ->read($disk->path($path))
                    ->cover(1200, 630)
                    ->toJpeg(82)
                    ->save($disk->path($og));
            } catch (\Throwable) {
                // GD unavailable or unreadable file: use the original
            }
        }

        return Storage::url($disk->exists($og) ? $og : $path);
    }
}
