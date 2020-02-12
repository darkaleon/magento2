<?php
declare(strict_types=1);

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;

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
        $this->_forward('edit');
    }
}
