<?php
declare(strict_types=1);

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Alexx\Blog\Api\BlogRepositoryInterfaceFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context as ActionContext;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Admin blog delete Controller that perform deleting data from the database
 */
class Delete extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Alexx_Blog::menu';

    private $_blogRepsitoryFactory;

    /**
     * @param ActionContext $context
     * @param BlogRepositoryInterfaceFactory $blogRepsitoryFactory
     */
    public function __construct(
        ActionContext $context,
        BlogRepositoryInterfaceFactory $blogRepsitoryFactory
    ) {
        parent::__construct($context);
        $this->_blogRepsitoryFactory = $blogRepsitoryFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if ($this->getRequest()->getPost()) {
            $postId = $this->getRequest()->getParam('id');
            $repository = $this->_blogRepsitoryFactory->create();
            if ($postId) {
                try {
                    $repository->deleteById($postId);
                    $this->messageManager->addSuccess(__('The post has been deleted.'));
                } catch (NoSuchEntityException $exception) {
                    $this->messageManager->addError($exception->getMessage());
                } catch (CouldNotDeleteException $exception) {
                    $this->messageManager->addError($exception->getMessage());
                }
            }
        } else {
            $this->messageManager->addError(__('Wrong request. Try again'));
        }
        return $this->_redirect('*/*/');
    }
}
