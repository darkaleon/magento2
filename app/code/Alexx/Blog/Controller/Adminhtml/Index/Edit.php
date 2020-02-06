<?php

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Alexx\Blog\Model\BlogPostsFactory;
use Magento\Backend\App\Action\Context as ActionContext;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Registry;

/**
 * Admin blog edit Controller that displays page with form for edit blogs post
 */
class Edit extends Action implements HttpGetActionInterface
{
    private $_postsFactory;
    private $_coreRegistry;

    /**
     * @param ActionContext $context
     * @param Registry $coreRegistry
     * @param BlogPostsFactory $postsFactory
     *
     * @return void
     */
    public function __construct(
        ActionContext $context,
        Registry $coreRegistry,
        BlogPostsFactory $postsFactory
    ) {
        parent::__construct($context);
        $this->_postsFactory = $postsFactory;
        $this->_coreRegistry = $coreRegistry;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $postId = $this->getRequest()->getParam('id');

        $model = $this->_postsFactory->create();

        if ($postId) {
            $model->load($postId);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This post no longer exists.'));
                $this->_redirect('*/*/');
            }
        }

        // Restore previously entered form data from session
        $data = $this->_getSession()->getFormData(true);

        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('blognews', $model);
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
