<?php
declare(strict_types=1);

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\ImageUploader;

/**
 * Admin Controller that perform uploading image from form and store it in tmp directory
 */
class ImageUpload extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Alexx_Blog::manage';

    /**@var StoreManagerInterface */
    private $storeManager;

    /**@var ImageUploader */
    private $imageUploader;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        ImageUploader $imageUploader
    ) {
        $this->storeManager = $storeManager;
        $this->imageUploader = $imageUploader;

        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $imageId = $this->_request->getParam('param_name', 'image');

        try {
            $result = $this->imageUploader->saveFileToTmpDir($imageId);
            $result['url'] = $this->adaptUrl($result['url']);

        } catch (\Exception $e) {
            $result = ['error' => __($e->getMessage()), 'errorcode' => $e->getCode()];
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }

    /**
     * Extracts relative url to file
     *
     * @param string $file
     *
     * @return string
     */
    private function adaptUrl(string $file)
    {
        $remove = rtrim($this->storeManager->getStore()->getBaseUrl(), '/');
        return substr($file, strlen($remove), strlen($file) - strlen($remove));
    }
}
