<?php

namespace App\Components;

use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\UnableToCheckFileExistence;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class EdsparkTinyEditor extends TinyEditor
{
    protected function handleUploadedAttachmentUrlRetrieval(mixed $file): ?string
    {
        /** @var FilesystemAdapter $storage */
        $storage = $this->getFileAttachmentsDisk();

        try {
            if (!$storage->exists($file)) {
                return null;
            }
        } catch (UnableToCheckFileExistence $exception) {
            return null;
        }

        return $storage->url($file);
    }
}