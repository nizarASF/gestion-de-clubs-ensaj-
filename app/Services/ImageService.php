<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

/** Redimensionnement et gestion physique des fichiers (driver GD). */
class ImageService
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * WebP quand GD le supporte ; sinon JPEG (documenté dans le README).
     * Le format choisi est toujours cohérent avec l'extension du fichier.
     */
    private function extension(): string
    {
        return function_exists('imagewebp') ? 'webp' : 'jpg';
    }

    private function encode(mixed $image): string
    {
        return function_exists('imagewebp')
            ? (string) $image->toWebp(80)
            : (string) $image->toJpeg(80);
    }

    /**
     * @return array{path: string, thumbnail: string|null}
     */
    public function store(UploadedFile $file, string $dir, int $maxWidth, ?int $thumbWidth = null): array
    {
        // Nom aléatoire : jamais le nom d'origine, jamais de chemin exposé.
        $name = Str::random(40) . '.' . $this->extension();
        $path = $dir . '/' . $name;

        $image = $this->manager->read($file->getContent());
        $image->scaleDown(width: $maxWidth); // n'agrandit jamais
        Storage::disk('public')->put($path, $this->encode($image));

        $thumbnail = null;
        if ($thumbWidth !== null) {
            $thumbnail = $dir . '/thumb-' . $name;
            $small = $this->manager->read($file->getContent());
            $small->scaleDown(width: $thumbWidth);
            Storage::disk('public')->put($thumbnail, $this->encode($small));
        }

        return ['path' => $path, 'thumbnail' => $thumbnail];
    }

    /** Photo carrée pour les fiches du bureau. */
    public function storeSquare(UploadedFile $file, string $dir, int $size): array
    {
        $name = Str::random(40) . '.' . $this->extension();
        $path = $dir . '/' . $name;
        $image = $this->manager->read($file->getContent());
        $image->cover($size, $size);
        Storage::disk('public')->put($path, $this->encode($image));

        return ['path' => $path, 'thumbnail' => null];
    }

    /** Vidéo : extension déduite du MIME réel, jamais du nom fourni par le client. */
    public function storeVideo(UploadedFile $file, string $dir): string
    {
        $extension = match ($file->getMimeType()) {
            'video/mp4' => 'mp4',
            'video/webm' => 'webm',
            default => 'bin',
        };
        $path = $dir . '/' . Str::random(40) . '.' . $extension;
        Storage::disk('public')->put($path, file_get_contents($file->getRealPath()));

        return $path;
    }

    /** Suppression des fichiers physiques (la cascade SQL ne s'en occupe pas). */
    public function delete(?string ...$paths): void
    {
        foreach (array_filter($paths) as $path) {
            Storage::disk('public')->delete($path);
        }
    }
}
