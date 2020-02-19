<?php
declare(strict_types=1);

namespace Alexx\Blog\Model\Media;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Filesystem;
use Magento\Framework\View\Asset\Repository;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem\Directory\ReadInterface;

/**
 * Manages paths parsing for blog picture
 */
class Config
{
    /**@var Filesystem */
    private $fileSystem;

    /**@var RequestInterface */
    private $requestInterface;

    /**@var Repository */
    private $repository;

    /**@var DirectoryList */
    private $dir;

    /**@var StoreManagerInterface */
    private $storeManager;

    /**@var ReadInterface */
    private $mediaDirectory;

    /**@var Mime */
    private $mime;

    /**
     * @param RequestInterface $requestInterface
     * @param DirectoryList $dir
     * @param Repository $repository
     * @param Filesystem $fileSystem
     * @param StoreManagerInterface $storeManager
     * @param Mime $mime
     */
    public function __construct(
        RequestInterface $requestInterface,
        DirectoryList $dir,
        Repository $repository,
        Filesystem $fileSystem,
        StoreManagerInterface $storeManager,
        Mime $mime
    ) {
        $this->storeManager = $storeManager;
        $this->fileSystem = $fileSystem;
        $this->dir = $dir;
        $this->requestInterface = $requestInterface;
        $this->repository = $repository;
        $this->mime = $mime;
        $this->mediaDirectory = $fileSystem->getDirectoryRead(DirectoryList::MEDIA);
    }

    /**
     * Convert picture route or url to relative path. Accepts all types of file path.
     *
     * @param string $givenFileName
     *
     * @return string
     */
    public function extractRelativePath(string $givenFileName): string
    {
        $pathParts = explode($this->fileSystem->getUri(DirectoryList::MEDIA), $givenFileName);
        return ltrim(array_pop($pathParts), '/');
    }

    /**
     * Convert relative picture route to url
     *
     * @param string $givenFileName
     *
     * @return array
     */
    public function convertPictureForUploader(string $givenFileName): array
    {
        $fileName = $this->extractRelativePath($givenFileName);
        $filePath = $this->fileSystem->getUri(DirectoryList::MEDIA) . '/' . $fileName;
        $stat = $this->mediaDirectory->stat($fileName);
        return [[
            'name' => preg_replace('|.*?([^/]+)$|', '\1', $fileName, 1),
            'url' => '/' . $filePath,
            'path' => $fileName,
            'size' => (!empty($stat) ? $stat['size'] : 0),
            'type' => $this->mime->getMimeType($filePath)
        ]];
    }

    /**
     * Retrieve url for image to display
     *
     * @param string $file
     *
     * @return string
     */
    public function getBlogImageUrl(string $file): string
    {
        $filePath = '/' . $this->fileSystem->getUri(DirectoryList::MEDIA) . '/' . $file;
        $fullPath = $this->dir->getRoot() . $filePath;

        if (empty($file)) {
            $result = $this->getPlaceholderUrl();
        } else {
            try {

                $result = ($this->mediaDirectory->isExist($fullPath) ? $filePath : $this->getPlaceholderUrl());
            } catch (FileSystemException | ValidatorException $exception) {
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
    private function getPlaceholderUrl(): string
    {
        return $this->repository->getUrlWithParams(
            'Alexx_Blog::images/image-placeholder.png',
            ['_secure' => $this->requestInterface->isSecure()]
        );
    }
}
