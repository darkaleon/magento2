<?php
declare(strict_types=1);

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Alexx\Blog\Model\Media\Config as BlogMediaConfig;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

/**
 * Admin Controller that perform uploading image from form and store it in tmp directory
 */
class ImageUpload extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Alexx_Blog::manage';


    private $imageUploader;
    private $blogMediaConfig;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        \Magento\Catalog\Model\ImageUploader $imageUploader,
        BlogMediaConfig $blogMediaConfig
    )
    {
        $this->imageUploader = $imageUploader;
        $this->blogMediaConfig = $blogMediaConfig;

        parent::__construct($context);
    }

    /**
     * Upload image to the blog gallery.
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $imageId = $this->_request->getParam('param_name', 'image');


        try {
            $result = $this->imageUploader->saveFileToTmpDir($imageId);
            $result['url'] = $this->blogMediaConfig->adaptUrl($result['url']);

        } catch (\Exception $e) {
            $result = ['error' => __($e->getMessage()), 'errorcode' => $e->getCode()];
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);

    }
}
