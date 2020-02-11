<?php
declare(strict_types=1);

namespace Alexx\Blog\Model\Media;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Filesystem;
use Magento\Framework\View\Asset\Repository;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Filesystem\Io\File as IoFile;

/**
 * Urls for blog pictures
 */
class Config
{
    private $_file;
    private $_ioFile;
    private $_storeManager;
    private $_context;
    private $_repository;
    private $mediaDirectory;
    private $_dir;

    /**
     * @param Context $context
     * @param Filesystem $fileSystem
     * @param DirectoryList $dir
     * @param StoreManagerInterface $storeManager
     * @param Repository $repository
     * @param File $file
     * @param IoFile $ioFile
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        Context $context,
        Filesystem $fileSystem,
        DirectoryList $dir,
        StoreManagerInterface $storeManager,
        Repository $repository,
        File $file,
        IoFile $ioFile
    ) {
        $this->mediaDirectory = $fileSystem->getDirectoryWrite('media');//\Magento\Framework\Filesystem\DirectoryList::MEDIA
        $this->_file = $file;
        $this->_ioFile = $ioFile;
        $this->_dir = $dir;
        $this->_context = $context;
        $this->_storeManager = $storeManager;
        $this->_repository = $repository;
    }

    /**
     * Retrieve url for image to display
     *
     * @param string $file
     *
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getBlogImageUrl($file)
    {
        if (empty($file)) {
            return $this->getViewFileUrl('Alexx_Blog::images/image-placeholder.png');
        } else {

            if ($this->_file->isExists($this->getRootFolder() . $file)) {
                return $file;
            } else {
                return $this->getViewFileUrl('Alexx_Blog::images/image-placeholder.png');
            }
        }
    }

    /**
     * Retrieve url of a view file
     *
     * @param string $fileId
     * @param array $params
     *
     * @return string
     */
    public function getViewFileUrl($fileId, array $params = [])
    {
        $params = array_merge(
            [
                '_secure' => $this->_context->getRequest()->isSecure()
            ],
            $params
        );
        return $this->_repository->getUrlWithParams($fileId, $params);
    }

    /**
     * Gets absolute path to file
     *
     * @param string $path
     * @param string $imageName
     *
     * @return string
     */
    public function getFilePath($path, $imageName)
    {
        return $this->mediaDirectory->getAbsolutePath(rtrim($path, '/') . '/' . ltrim($imageName, '/'));
    }

    /**
     * Cuts $remove string from $str
     *
     * @param string $str
     * @param string $remove
     *
     * @return string
     * */
    public function extractFilePath($str, $remove)
    {
        $str = (string)$str;
        $remove = (string)$remove;
        $offset = strlen($str) - strlen($remove);
        $str = substr($str, strlen($remove), $offset);
        return $str;
    }

    /**
     * Path to temp dir
     */
    public function getTmpUploadDir()
    {
        return $this->mediaDirectory->getAbsolutePath('tmp/blog');
    }

    /**
     * Path to root of the shop
     */
    public function getRootFolder()
    {
        return $this->_dir->getRoot();
    }

    /**
     * Extracts relative url to file
     *
     * @param string $file
     *
     * @return string
     */
    public function getUrlToSavedFile($file)
    {
        return $this->extractFilePath($file, $this->getRootFolder());
    }

    /**
     * Get new file name if the same is already exists
     *
     * @param string $destinationFile
     * @return string
     */
    public function getNewFileName($destinationFile)
    {
        $fileInfo = $this->_ioFile->getPathInfo($destinationFile);
        if ($this->_file->isExists($destinationFile)) {
            $index = 1;
            $baseName = $fileInfo['filename'] . '.' . $fileInfo['extension'];
            while ($this->_file->isExists($fileInfo['dirname'] . '/' . $baseName)) {
                $baseName = $fileInfo['filename'] . '_' . $index . '.' . $fileInfo['extension'];
                $index++;
            }
            $destFileName = $baseName;
        } else {
            return $fileInfo['dirname'] . '/' . $fileInfo['basename'];
        }

        return $fileInfo['dirname'] . '/' . $destFileName;
    }
}
