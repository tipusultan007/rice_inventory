<?php

namespace App\CustomPath;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class Purchase implements PathGenerator
{
    public function getPath(Media $media): string
    {
        return $media->collection_name.'/' . $media->id.'/';
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . '/conversions';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . '/responsive';
    }
}
