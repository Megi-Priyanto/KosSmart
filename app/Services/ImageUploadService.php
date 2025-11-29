<?php
// app/Services/ImageUploadService.php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    public function uploadSingle(UploadedFile $file, string $directory = 'rooms', int $maxWidth = 1200): string
    {
        $filename = Str::random(20) . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = "{$directory}/{$filename}";
        
        Storage::disk('public')->put($path, file_get_contents($file));
        
        return $path;
    }
    
    public function uploadMultiple(array $files, string $directory = 'rooms'): array
    {
        $paths = [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $this->uploadSingle($file, $directory);
            }
        }
        
        return $paths;
    }
    
    public function delete(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        
        return false;
    }
    
    public function deleteMultiple(array $paths): void
    {
        foreach ($paths as $path) {
            $this->delete($path);
        }
    }
    
    public function replace(?array $oldPaths, array $newFiles, string $directory = 'rooms'): array
    {
        if ($oldPaths) {
            $this->deleteMultiple($oldPaths);
        }
        
        return $this->uploadMultiple($newFiles, $directory);
    }
}