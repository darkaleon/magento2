<?php

namespace Alexx\Blog\Model;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Asset\Repository;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;

/**
 * Urls for blog pictures
 */
class PictureConfig
{
    private $_storeManager;
    private $_context;
    private $_repository;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param Repository $repository
     *
     * @return void
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Repository $repository
    ) {
        $this->_context = $context;
        $this->_storeManager = $storeManager;
        $this->_repository = $repository;
    }

    /**
     * Retrieve url for media file
     *
     * @param string $file
     * @return string
     */
    public function getMediaUrl($file)
    {
        return $this->getStoreMediaUrl() . $this->_prepareFile($file);
    }

    /**
     * Retrieve url for image to display
     *
     * @param string $file
     * @return string
     */
    public function getBlogImageUrl($file)
    {
        return (
        empty($file) ?
            $this->getViewFileUrl('Alexx_Blog::images/image-placeholder.png') :
            $this->getMediaUrl($file));
    }

    /**
     * Retrieve url of a view file
     *
     * @param string $fileId
     * @param array $params
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
     * Get filesystem directory path for product images relative to the media directory.
     *
     * @return string
     */
    public function getBaseMediaPath()
    {
        return 'blog';
    }

    /**
     * Process file path.
     *
     * @param string $file
     * @return string
     */
    protected function _prepareFile($file)
    {
        return ltrim(str_replace('\\', '/', $file), '/');
    }

    /**
     * Retrieve store base url
     *
     * @return string
     * */
    private function getStoreMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }
}
