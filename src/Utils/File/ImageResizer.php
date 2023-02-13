<?php

namespace App\Utils\File;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ImageResizer
{
    private Imagine $imagine;

    public function __construct()
    {
        $this->imagine = new Imagine();
    }

    public function resizeImageAndSave(string $originalFileDir, string $originalFilename, array $targetParams): string
    {
        $originalFilePath = $originalFileDir.'/'.$originalFilename;

        list($imageWidth, $imageHeight) = getimagesize($originalFilePath);

        $ration = $imageWidth / $imageHeight;

        $targetWidth = $targetParams['width'];
        $targetHeight = $targetParams['height'];

        if ($targetHeight) {
            if ($targetWidth / $targetHeight > $ration) {
                $targetWidth = $targetHeight * $ration;
            } else {
                $targetHeight = $targetWidth / $ration;
            }
        } else {
            $targetHeight = $targetWidth / $ration;
        }

        $targetFolder = $targetParams['newFolder'];
        $targetFilename = $targetParams['newFilename'];

        $targetFilePath = sprintf('%s/%s', $targetFolder, $targetFilename);

        $imagineFile = $this->imagine->open($originalFilePath);
        $imagineFile
            ->resize(new Box($targetWidth, $targetHeight))
            ->save($targetFilePath);

        return $targetFilename;
    }
}
