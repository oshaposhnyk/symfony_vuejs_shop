<?php

namespace App\Utils\File;

use App\Utils\Filesystem\FilesystemWorker;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileSaver
{
    private SluggerInterface $slugger;
    private string $uploadsTempDir;
    private FilesystemWorker $filesystemWorker;

    public function __construct(SluggerInterface $slugger, string $uploadsTempDir, FilesystemWorker $filesystemWorker)
    {
        $this->slugger = $slugger;
        $this->uploadsTempDir = $uploadsTempDir;
        $this->filesystemWorker = $filesystemWorker;
    }

    public function saveFileUploadedFileIntoTemp(UploadedFile $uploadedFile): ?string
    {
        $originalFilename = pathinfo(path: $uploadedFile->getClientOriginalName(), flags: PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);

        $fileName = sprintf('%s-%s.%s', $safeFilename, uniqid(), $uploadedFile->guessExtension());

        $this->filesystemWorker->createFolderIfItNotExist(folder: $this->uploadsTempDir);

        try {
            $uploadedFile->move(directory: $this->uploadsTempDir, name: $fileName);
        } catch (\Exception $e) {
            return null;
        }

        return $fileName;
    }
}
