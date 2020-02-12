<?php
declare(strict_types=1);

namespace Alexx\Blog\Model\Media;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Filesystem;
use Magento\Framework\View\Asset\Repository;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;

/**
 * Urls for blog pictures
 */
class Config
{
    private $file;
    private $storeManager;
    private $context;
    private $repository;
    private $dir;

    /**
     * @param Context $context
     * @param Filesystem $fileSystem
     * @param DirectoryList $dir
     * @param StoreManagerInterface $storeManager
     * @param Repository $repository
     * @param File $file
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        Context $context,
        Filesystem $fileSystem,
        DirectoryList $dir,
        StoreManagerInterface $storeManager,
        Repository $repository,
        File $file
    ) {
        $this->file = $file;
        $this->dir = $dir;
        $this->context = $context;
        $this->storeManager = $storeManager;
        $this->repository = $repository;
    }

    public function getBaseMediaDir(){
        return $this->storeManager->getStore()->getBaseMediaDir();
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

            if ($this->file->isExists($this->getRootFolder() . $file)) {
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
                '_secure' => $this->context->getRequest()->isSecure()
            ],
            $params
        );
        return $this->repository->getUrlWithParams($fileId, $params);
    }


    /**
     * Path to root of the shop
     */
    public function getRootFolder()
    {
        return $this->dir->getRoot();
    }

    /**
     * Path to root of the shop
     */
    public function getRootUrl()
    {
        return $this->storeManager
            ->getStore()
            ->getBaseUrl();
    }

    /**
     * Extracts relative url to file
     *
     * @param string $file
     *
     * @return string
     */
    public function adaptUrl($file){
        return $this->extractFilePath($file, rtrim($this->getRootUrl(), '/'));
    }

    /**
     * Cuts $remove string from $str
     *
     * @param string $str
     * @param string $remove
     *
     * @return string
     */
    private function extractFilePath($str, $remove)
    {
        $str = (string)$str;
        $remove = (string)$remove;
        $offset = strlen($str) - strlen($remove);
        $str = substr($str, strlen($remove), $offset);
        return $str;
    }


}
