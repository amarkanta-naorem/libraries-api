<?php

namespace App\Services\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class ImageService
{
    /**
     * Default validation rules per file type.
     */
    private array $defaultRules = [
        'image' => ['required', 'file', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
        'document' => ['required', 'file', 'mimes:pdf,doc, docx, txt', 'max:2048'],
        'media' => ['required', 'file', 'mimes:mp4, mov, mp3, avi, wav', 'max:2048'],
    ];

    public function upload(
        UploadedFile $file,
        ?string $pathPrefix = null,
        bool $generateUniqueName = true,
        string $disk = 'public',
        ?string $fileType = 'image',
        ?bool $publicVisibility = null,
        array $customRules = [],
    ): array {

        // Validate disk existence
        if (! array_key_exists($disk, config('filesystems.disks'))) {
            throw new InvalidArgumentException("Storage disk '{$disk}' is not configured.");
        }

        // Validate file type for defaults
        if (! array_key_exists($fileType, $this->defaultRules)) {
            throw new InvalidArgumentException("Unsupported file type '{$fileType}'. Use 'image', 'document', or 'media'.");
        }

        $rules = array_merge($this->defaultRules[$fileType], $customRules);
        $validator = Validator::make(['file' => $file], ['file' => $rules]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $extension = $file->getClientOriginalExtension();
        $fileName = $generateUniqueName ? Str::uuid()->toString().'.'.$extension : $file->getClientOriginalName();

        $basePath = $fileType.'s'; // e.g., 'images', 'documents', 'medias'
        $directory = trim($pathPrefix ? "{$basePath}/{$pathPrefix}" : $basePath, '/');

        $visibility = $publicVisibility ?? ($disk === 'public');

        $storedPath = $file->storeAs($directory, $fileName, [
            'disk' => $disk,
            'visibility' => $visibility ? 'public' : 'private',
        ]);

        /** @var \Illuminate\Filesystem\FilesystemAdapter $storage */
        $storage = Storage::disk($disk);
        $url = $this->generateUrl($storedPath, $disk);

        return [
            'path' => $storedPath,
            'url' => $url,
            'filename' => $fileName,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ];
    }

    /**
     * Generate a full URL for a stored file.
     */
    protected function generateUrl(string $path, string $disk): string
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $storage */
        $storage = Storage::disk($disk);

        $configKey = 'filesystems.disks.'.$disk.'.driver';
        $driver = config($configKey);

        if ($driver === 's3') {
            return $storage->url($path);
        }

        if ($disk === 'public') {
            $baseUrl = config('app.url');

            return rtrim($baseUrl, '/').'/storage/'.ltrim($path, '/');
        }

        // Local or private disk
        return $storage->path($path);
    }
}
