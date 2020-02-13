<?php
declare(strict_types=1);

namespace Alexx\Blog\Model\Media;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\View\Asset\Repository;

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

    /**
     * @param RequestInterface $requestInterface
     * @param DirectoryList $dir
     * @param Repository $repository
     * @param File $file
     */
    public function __construct(
        RequestInterface $requestInterface,
        DirectoryList $dir,
        Repository $repository,
        File $file
    ) {
        $this->file = $file;
        $this->dir = $dir;
        $this->requestInterface = $requestInterface;
        $this->repository = $repository;
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
        if (empty($file)) {
            $result = $this->getPlaceholderUrl();
        } else {
            try {
                $result = ($this->file->isExists($this->dir->getRoot() . $file) ? $file : $this->getPlaceholderUrl());
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
