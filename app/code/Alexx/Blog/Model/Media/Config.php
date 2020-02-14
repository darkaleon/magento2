<?php
declare(strict_types=1);

namespace Alexx\Blog\Model\Media;

use Magento\Catalog\Model\Category\FileInfo;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\View\Asset\Repository;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Urls for blog pictures
 */
class Config
{
    /**@var File */
    private $file;

    /**@var RequestInterface */
    private $requestInterface;

    /**@var Repository */
    private $repository;

    /**@var DirectoryList */
    private $dir;

    /**@var StoreManagerInterface */
    private $storeManager;

    /**@var FileInfo */
    private $fileInfo;

    /**
     * @param RequestInterface $requestInterface
     * @param DirectoryList $dir
     * @param Repository $repository
     * @param File $file
     * @param StoreManagerInterface $storeManager
     * @param FileInfo $fileInfo
     */
    public function __construct(
        RequestInterface $requestInterface,
        DirectoryList $dir,
        Repository $repository,
        File $file,
        StoreManagerInterface $storeManager,
        FileInfo $fileInfo
    ) {
        $this->fileInfo = $fileInfo;
        $this->storeManager = $storeManager;
        $this->file = $file;
        $this->dir = $dir;
        $this->requestInterface = $requestInterface;
        $this->repository = $repository;
    }

    /**
     * Convert  picture route or url to relative path
     *
     * @param string $givenFileName
     *
     * @return string
     */
    public function extractRelativePath(string $givenFileName)
    {
        $mediaDir = $this->storeManager->getStore()->getBaseMediaDir();
        $arr = explode($mediaDir, $givenFileName);
        $result = ltrim(array_pop($arr), '/');
        return $result;
    }

    /**
     * Convert relative picture route to url
     *
     * @param string $givenFileName
     *
     * @return array
     */
    public function convertPictureForUploader(string $givenFileName)
    {
        $fileName = $this->extractRelativePath($givenFileName);
        $mediaDir = $this->storeManager->getStore()->getBaseMediaDir();
        $filePath = $mediaDir . "/" . $fileName;

        $stat = $this->fileInfo->getStat($filePath);
        $mime = $this->fileInfo->getMimeType($filePath);

        // The use of function basename() is discouraged
        $basename = preg_replace('|.*?([^/]+)$|', '\1', $fileName, 1);
        return [[
            'name' => $basename,
            'url' => '/' . $filePath,
            'path' => $fileName,
            'size' => (isset($stat) ? $stat['size'] : 0),
            'type' => $mime
        ]];
    }

    /**
     * Retrieve url for image to display
     *
     * @param string $file
     *
     * @return string
     */
    public function getBlogImageUrl(string $file)
    {
        $filePath = '/' . $this->storeManager->getStore()->getBaseMediaDir() . '/' . $file;

        $fullPath = $this->dir->getRoot() . $filePath;
        if (empty($file)) {
            $result = $this->getPlaceholderUrl();
        } else {
            try {
                $result = ($this->file->isExists($fullPath) ? $filePath : $this->getPlaceholderUrl());
            } catch (FileSystemException $exception) {
                $result = $this->getPlaceholderUrl();
            }
        }
        return $result;
    }

    /**
     * Retrieve url of the placeholder file
     *
     * @param string $fileId
     * @param array $params
     *
     * @return string
     */
    private function getPlaceholderUrl()
    {
        return $this->repository->getUrlWithParams(
            'Alexx_Blog::images/image-placeholder.png',
            ['_secure' => $this->requestInterface->isSecure()]
        );
    }
}
