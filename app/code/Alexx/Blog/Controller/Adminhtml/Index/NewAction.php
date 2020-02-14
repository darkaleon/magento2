<?php
declare(strict_types=1);

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Admin blog new Controller that displays page with form for new blogs post
 */
class NewAction extends Edit implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Alexx_Blog::manage';

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /**@var ResultInterface $result*/
        $result = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $result->getConfig()->getTitle()->set(__('Add new post'));
        return $result;
    }
}
