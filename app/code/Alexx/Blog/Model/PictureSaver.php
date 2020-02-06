<?php

namespace Alexx\Blog\Model;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Magento\MediaStorage\Model\File\Uploader;
use Magento\MediaStorage\Model\File\UploaderFactory;

/**
 * Class for saving uploaded image to media directory
 * */
class PictureSaver
{
    private $currentPicture = '';
    private $newPicture = false;
    private $deleteCurrentPicture = false;
    private $_fileSystem;
    private $_pictureConfig;
    private $_file;
    private $pictureDataField;
    private $_currentAction;
    private $_fileUploaderFactory;

    /**
     * @param Filesystem $fileSystem
     * @param PictureConfig $pictureConfig
     * @param File $file
     * @param Action $currentAction
     * @param UploaderFactory $fileUploaderFactory,
     * @param string $pictureDataField
     *
     * @return void
     */
    public function __construct(
        Filesystem $fileSystem,
        PictureConfig $pictureConfig,
        File $file,
        Action $currentAction,
        UploaderFactory $fileUploaderFactory,
        $pictureDataField
    ) {
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_currentAction = $currentAction;
        $this->pictureDataField = $pictureDataField;
        $this->_fileSystem = $fileSystem;
        $this->_pictureConfig = $pictureConfig;
        $this->_file = $file;
    }

    /**
     * File Uploader
     *
     * @return bool|array
     * @throws \Exception
     */
    private function saveFile()
    {
        /** @var Uploader $uploader */
        $uploader = $this->_fileUploaderFactory->create(['fileId' => $this->pictureDataField]);
        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(true);
        $uploader->setAllowCreateFolders(true);
        $result = $uploader->save($this->getMediaPath() . $this->getBlogPath());
        return $result;
    }

    /**
     * Delete file
     *
     * @param string $name
     * @return void
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function deleteFile($name)
    {
        $fileName = $this->getMediaPath() . $name;
        if ($this->_file->isExists($fileName)) {
            $this->_file->deleteFile($fileName);
        }
    }

    /**
     * Delete prev picture if saving success
     *
     * @return void
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function clearOnSuccess()
    {
        if ($this->deleteCurrentPicture) {
            $this->deleteFile($this->currentPicture);
        }
    }

    /**
     * Delete new picture if saving not success
     *
     * @return void
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function clearOnError()
    {
        if ($this->newPicture) {
            $this->deleteFile($this->newPicture);
        }
    }

    /**
     * Upload file to media path
     *
     * @param BlogPosts $model
     * @param array $picData
     *
     * @return array|string
     */
    private function getImageData()
    {
        return $this->newPicture ?
            $this->newPicture : ($this->deleteCurrentPicture ? '' : $this->currentPicture);
    }

    /**
     * Managing image upload
     *
     * @param string $currentPicture
     *
     * @return array
     */
    public function uploadImage($currentPicture)
    {
        $picturePostData = $this->_currentAction->getRequest()->getParam($this->pictureDataField);
        $picturePostFiles = $this->_currentAction->getRequest()->getFiles($this->pictureDataField);
        if (!$picturePostData) {
            $picturePostData = [];
        }

        $this->currentPicture = $currentPicture;
        if (array_key_exists("delete", $picturePostData)) {
            $this->deleteCurrentPicture = true;
        }
        if ($picturePostFiles["size"] > 0) {
            $file = $this->saveFile();
            $this->newPicture = $this->getBlogPath() . ltrim($file['file'], '/');
            if ($this->currentPicture != '') {
                $this->deleteCurrentPicture = true;
            }
        }
        return $this->getImageData();
    }

    /**
     * Media path to files from picture config
     *
     * @return string
     */
    private function getBlogPath()
    {
        return $this->_pictureConfig->getBaseMediaPath() . "/";
    }

    /**
     * Media path to files
     *
     * @return string
     */
    private function getMediaPath()
    {
        return $this->_fileSystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();
    }
}
