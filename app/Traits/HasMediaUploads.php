<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait HasMediaUploads
{
    /**
     * Normalize the input into an array of UploadedFile
     */
    private function normalizeFiles($filesRequest): array
    {
        if (!$filesRequest) {
            return [];
        }

        // If single file → wrap in array
        if ($filesRequest instanceof UploadedFile) {
            return [$filesRequest];
        }

        // If array → filter valid UploadedFile
        if (is_array($filesRequest)) {
            $files = [];
            $existingIds = [];

            foreach ($filesRequest as $file) {
                if (isset($file['file']) && $file['file'] instanceof UploadedFile) {
                    $files[] = $file['file'];
                }
                elseif (isset($file['id'])) {
                    $existingIds[] = $file['id'];
                }
            }

            return [
                'files' => $files,
                'existingIds' => $existingIds,
            ];
        }

        return [];
    }

    /**
     * Store new media images (no deletion)
     *
     * @param UploadedFile|UploadedFile[]|array|null $files
     * @param string $collection
     */
    public function storeMediaImages($filesRequest, string $collection)
    {
        $data = $this->normalizeFiles($filesRequest);
        $files = $data['files'] ?? $data;

        foreach ($files as $file) {
            $this->addMedia($file)->toMediaCollection($collection);
        }

        return $this;
    }

    /**
     * Update media images
     * 
     * @param UploadedFile|UploadedFile[]|array|null $files
     * @param string $collection
     * @param bool $replaceOld   If true → remove old images first
     */
    public function updateMediaImages($filesRequest, string $collection, bool $replaceOld = true)
    {
        $data = $this->normalizeFiles($filesRequest);
        $files = $data['files'] ?? $data;
        $existingIds = $data['existingIds'] ?? [];

        // Replace old images?
        if ($replaceOld || empty($existingIds)) {
            $this->clearMediaCollection($collection);

        }  
        elseif (!empty($existingIds)) {
            $this->media()
                ->where('collection_name', $collection)
                ->whereNotIn('id', $existingIds)
                ->delete();
        }

        foreach ($files as $file) {
            $this->addMedia($file)->toMediaCollection($collection);
        }

        return $this;
    }
}
