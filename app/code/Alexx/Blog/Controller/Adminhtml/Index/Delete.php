<?php

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Alexx\Blog\Model\BlogPostsFactory;
use Alexx\Blog\Model\PictureSaver;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context as ActionContext;
use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Admin blog delete Controller that perform deleting data from the database
 */
class Delete extends Action implements HttpPostActionInterface
{
    private $_postsFactory;
    private $pictureSaver;

    /**
     * @param ActionContext $context
     * @param BlogPostsFactory $postsFactory
     * @param PictureSaver $pictureSaver
     *
     * @return void
     */
    public function __construct(
        ActionContext $context,
        BlogPostsFactory $postsFactory,
        PictureSaver $pictureSaver
    ) {
        parent::__construct($context);
        $this->_postsFactory = $postsFactory;
        $this->pictureSaver = $pictureSaver;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if ($this->getRequest()->getPost()) {
            $postId = $this->getRequest()->getParam('id');
            $model = $this->_postsFactory->create();
            if ($postId) {
                $blogPost = $model->load($postId);

                if (!empty($blogPost->getData())) {

                    if (!empty($blogPost->getPicture())) {
                        $this->pictureSaver->deleteFile($blogPost->getPicture());
                    }
                    $blogPost->delete();
                    $this->messageManager->addSuccess(__('The post has been deleted.'));
                } else {
                    $this->messageManager->addError(__('This post no longer exists.'));
                }
            } else {
                $this->messageManager->addError(__('Wrong request. Try again'));
            }
        } else {
            $this->messageManager->addError(__('Wrong request. Try again'));
        }
        return $this->_redirect('*/*/');
    }
}
