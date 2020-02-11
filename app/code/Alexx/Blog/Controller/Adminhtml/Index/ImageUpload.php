<?php
declare(strict_types=1);

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Alexx\Blog\Model\PictureSaver;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Backend\App\Action\Context;

/**
 * Admin Controller that perform uploading image from form and store it in tmp directory
 */
class ImageUpload extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Alexx_Blog::menu';

    /**
     * @var RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var PictureSaver
     */
    private $pictureSaver;

    /**
     * @param Context $context
     * @param RawFactory $resultRawFactory
     * @param PictureSaver $pictureSaver
     */
    public function __construct(
        Context $context,
        RawFactory $resultRawFactory,
        PictureSaver $pictureSaver
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->pictureSaver = $pictureSaver;
    }

    /**
     * Upload image to the blog gallery.
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        try {
            $result = $this->pictureSaver->saveFile();

        } catch (\Exception $e) {
            $result = ['error' => __($e->getMessage()), 'errorcode' => $e->getCode()];
        }

        /** @var \Magento\Framework\Controller\Result\Raw $response */
        $response = $this->resultRawFactory->create();
        $response->setHeader('Content-type', 'text/plain');
        $response->setContents(json_encode($result));
        return $response;
    }
}
