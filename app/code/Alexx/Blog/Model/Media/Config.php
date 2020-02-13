<?php
declare(strict_types=1);

namespace Alexx\Blog\Model\Media;

use Magento\Framework\App\Action\Context;
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

    /**@var Context */
    private $context;

    /**@var Repository */
    private $repository;

    /**@var DirectoryList */
    private $dir;

    /**
     * @param Context $context
     * @param DirectoryList $dir
     * @param Repository $repository
     * @param File $file
     */
    public function __construct(
        Context $context,
        DirectoryList $dir,
        Repository $repository,
        File $file
    ) {
        $this->file = $file;
        $this->dir = $dir;
        $this->context = $context;
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
            $result = $this->getViewFileUrl('Alexx_Blog::images/image-placeholder.png');
        } else {
            try {
                $result = (
                    $this->file->isExists($this->getRootFolder() . $file) ?
                    $file :
                    $this->getViewFileUrl('Alexx_Blog::images/image-placeholder.png')
                );
            } catch (FileSystemException $exception) {
                $result = $this->getViewFileUrl('Alexx_Blog::images/image-placeholder.png');
            }
        }
        return $result;
    }

    /**
     * Retrieve url of a view file
     *
     * @param string $fileId
     * @param array $params
     *
     * @return string
     */
    private function getViewFileUrl(string $fileId, array $params = [])
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
    private function getRootFolder()
    {
        return $this->dir->getRoot();
    }
}
