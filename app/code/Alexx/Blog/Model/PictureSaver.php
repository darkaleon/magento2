<?php
declare(strict_types=1);

namespace Alexx\Blog\Model;

use Alexx\Blog\Model\Media\Config as BlogMediaConfig;
use Magento\Backend\App\Action;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\AdapterFactory;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\MediaStorage\Model\File\UploaderFactory;

/**
 * Class for saving uploaded image to media directory
 * */
class PictureSaver
{
    /**
     * @var Filesystem
     */
    private $_fileSystem;
    private $blogMediaConfig;
    private $_currentAction;
    private $_fileUploaderFactory;
    /**
     * @var \Magento\Framework\Image\AdapterFactory
     */
    private $adapterFactory;
    private $coreFileStorageDatabase;
    private $mediaDirectory;

    /**
     * @var array
     */
    private $allowedMimeTypes = [
        'jpg' => 'image/jpg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/png',
        'png' => 'image/gif'
    ];

    /**
     * @param Database $coreFileStorageDatabase
     * @param Filesystem $fileSystem
     * @param BlogMediaConfig $blogMediaConfig
     * @param Action $currentAction
     * @param UploaderFactory $fileUploaderFactory
     * @param AdapterFactory $adapterFactory
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        Database $coreFileStorageDatabase,
        Filesystem $fileSystem,
        BlogMediaConfig $blogMediaConfig,
        Action $currentAction,
        UploaderFactory $fileUploaderFactory,
        AdapterFactory $adapterFactory
    ) {
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->mediaDirectory = $fileSystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);

        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_currentAction = $currentAction;

        $this->_fileSystem = $fileSystem;
        $this->blogMediaConfig = $blogMediaConfig;

        $this->adapterFactory = $adapterFactory;
    }

    /**
     * File Uploader
     *
     * @return array
     * @throws \Exception
     */
    public function saveFile()
    {
        /** @var \Magento\MediaStorage\Model\File\Uploader $uploader */
        $uploader = $this->_fileUploaderFactory->create(['fileId' => 'picture']);
        $uploader->setAllowedExtensions($this->getAllowedExtensions());

        $imageAdapter = $this->adapterFactory->create();
        $uploader->addValidateCallback('blog_image', $imageAdapter, 'validateUploadFile');
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(true);
        $uploader->setAllowCreateFolders(true);

        $result = $uploader->save($this->blogMediaConfig->getTmpUploadDir());

        $fullFilePath = $result['path'] . $result['file'];
        $result['url'] = $this->blogMediaConfig->getUrlToSavedFile($fullFilePath);

        unset($result['tmp_name']);
        unset($result['path']);

        return $result;
    }

    /**
     * Managing image upload
     *
     * @param array $tmpPicture
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function uploadImage($tmpPicture)
    {
        $baseTmpPath = 'tmp/blog';
        $basePath = 'blog';

        $sourceTmpFile = $this->blogMediaConfig->getFilePath($baseTmpPath, $tmpPicture['file']);

        $baseImagePath = $this->blogMediaConfig->getFilePath($basePath, $tmpPicture['file']);

        $destinationFile = $this->blogMediaConfig->getNewFileName($baseImagePath);

        $uploadedPicture = [
            'name' => $destinationFile,
            'url' => $this->blogMediaConfig->getUrlToSavedFile($destinationFile)
        ];

        try {
            $this->coreFileStorageDatabase->copyFile(
                $sourceTmpFile,
                $destinationFile
            );
            $this->mediaDirectory->renameFile(
                $sourceTmpFile,
                $destinationFile
            );
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Something went wrong while saving the file(s).')
            );
        }
        return $uploadedPicture;
    }

    /**
     * Get the set of allowed file extensions.
     *
     * @return array
     */
    private function getAllowedExtensions()
    {
        return array_keys($this->allowedMimeTypes);
    }
}
