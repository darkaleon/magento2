<?php
declare(strict_types=1);

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action;

/**
 * Admin blog index Controller that displays page with list of saved blogs posts
 */
class Index extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Alexx_Blog::menu';

    /**
     * @inheritDoc
     */
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
