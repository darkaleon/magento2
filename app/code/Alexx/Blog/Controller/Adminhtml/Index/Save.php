<?php

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Alexx\Blog\Model\BlogPostsFactory;
use Magento\Backend\App\Action\Context as ActionContext;
use Magento\Backend\App\Action;
use Alexx\Blog\Model\BlogPostSaver;
use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Admin blog save Controller that perform saving data posted from the form to database
 */
class Save extends Action implements HttpPostActionInterface
{
    private $_postsFactory;
    private $_blogPostSaver;

    /**
     * Constructor
     *
     * @param ActionContext $context
     * @param BlogPostsFactory $postsFactory
     * @param BlogPostSaver $blogPostSaver
     *
     * @return void
     * */
    public function __construct(
        ActionContext $context,
        BlogPostsFactory $postsFactory,
        BlogPostSaver $blogPostSaver
    ) {
        parent::__construct($context);

        $this->_postsFactory = $postsFactory;
        $this->_blogPostSaver = $blogPostSaver;
    }

    /**
     * Redirect with error message
     *
     * @param string $message
     * @param string $path
     * @param array $arguments
     *
     * @return void
     */
    public function redirectError($message, $path, $arguments = [])
    {
        $this->messageManager->addError($message);
        $this->_redirect($path, $arguments);
    }

    /**
     * Redirect with success message
     *
     * @param string $result
     *
     * @return void
     */
    public function redirectSuccess($result)
    {
        $this->messageManager->addSuccess(__('The post has been saved.'));

        // Check if 'Save and Continue'
        if ($this->getRequest()->getParam('back')) {
            $this->_redirect('*/*/edit', ['id' => $result, '_current' => true]);
            return;
        }
        // Go to grid page
        $this->_redirect('*/*/');
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if ($this->getRequest()->getPost()) {
            $postModel = $this->_blogPostSaver->create($this, $this->_postsFactory);

            if (!$postModel->loadFormData('blog_data')) {
                $this->redirectError(__('This post no longer exists.'), '*/*/');
                return $this->getResponse();
            }

            try {
                $postModel->loadPictureData('blog_picture');
            } catch (\Exception $e) {
                $this->redirectError($e->getMessage(), '*/*/edit', ['id' => $postModel->getFormData('entity_id')]);
                return $this->getResponse();
            }

            try {
                $modelId = $postModel->save();
                if ($modelId) {
                    $this->redirectSuccess($modelId);
                    return $this->getResponse();
                }
            } catch (\Exception $e) {
                $this->redirectError($e->getMessage(), '*/*/edit', ['id' => $postModel->getFormData('entity_id')]);
                return $this->getResponse();
            }

            $this->_getSession()->setFormData($postModel->getFormData());
            $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getPost()['entity_id']]);
        }
        return $this->getResponse();
    }
}
