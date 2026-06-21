<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImageService
{
    /**
     * Traite et stocke une couverture de livre.
     */
    public function processCover($file): string
    {
        $filename = 'covers/' . uniqid() . '.jpg';

        $image = Image::read($file)
            ->cover(400, 600)
            ->toJpeg(85);

        Storage::disk('public')->put($filename, $image);

        return $filename;
    }

    /**
     * Supprime un fichier du storage.
     */
    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}