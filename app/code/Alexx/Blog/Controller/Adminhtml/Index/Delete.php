<?php
declare(strict_types=1);

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Alexx\Blog\Api\BlogRepositoryInterfaceFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context as ActionContext;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Admin blog delete Controller that perform deleting data from the database
 */
class Delete extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Alexx_Blog::manage';

    /**@var BlogRepositoryInterfaceFactory */
    private $blogRepsitoryFactory;

    /**
     * @param ActionContext $context
     * @param BlogRepositoryInterfaceFactory $blogRepsitoryFactory
     */
    public function __construct(
        ActionContext $context,
        BlogRepositoryInterfaceFactory $blogRepsitoryFactory
    ) {
        $this->blogRepsitoryFactory = $blogRepsitoryFactory;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if ($this->getRequest()->getPost()) {
            $postId = (int)$this->getRequest()->getParam('id');
            $repository = $this->blogRepsitoryFactory->create();
            if ($postId) {
                try {
                    $repository->deleteById($postId);
                    $this->messageManager->addSuccess(__('The post has been deleted.'));
                } catch (LocalizedException $exception) {
                    $this->messageManager->addError($exception->getMessage());
                }
            }
        } else {
            $this->messageManager->addError(__('Wrong request. Try again'));
        }
        return $this->_redirect('*/*/');
    }
}
