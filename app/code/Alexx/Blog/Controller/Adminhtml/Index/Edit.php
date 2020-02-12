<?php
declare(strict_types=1);

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

/**
 * Admin blog edit Controller that displays page with form for edit blogs post
 */
class Edit extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Alexx_Blog::manage';

    /**
     * @inheritDoc
     */
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
