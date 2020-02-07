<?php

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Alexx\Blog\Model\BlogPostSaver;
use Alexx\Blog\Model\BlogPostsFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context as ActionContext;
use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Admin blog save Controller that perform saving data posted from the form to database
 */
class Save extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Alexx_Blog::menu';

    private $_currentAction;
    private $_postsFactory;
    private $_blogPostSaver;

    /**
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
        $this->_currentAction = $context;

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
    public function execute2()
    {
        if ($this->getRequest()->getPost()) {
            if (!$this->_blogPostSaver->loadFormData()) {
                $this->redirectError(__('This post no longer exists.'), '*/*/');
                return $this->getResponse();
            }

            try {
                $this->_blogPostSaver->loadPictureData();
            } catch (\Exception $e) {
                $this->redirectError(
                    $e->getMessage(),
                    '*/*/edit',
                    ['id' => $this->_blogPostSaver->getFormData('entity_id')]
                );
                return $this->getResponse();
            }

            try {
                $modelId = $this->_blogPostSaver->save();
                if ($modelId) {
                    $this->redirectSuccess($modelId);
                    return $this->getResponse();
                }
            } catch (\Exception $e) {
                $this->redirectError(
                    $e->getMessage(),
                    '*/*/edit',
                    ['id' => $this->_blogPostSaver->getFormData('entity_id')]
                );
                return $this->getResponse();
            }

            $this->_getSession()->setFormData($this->_blogPostSaver->getFormData());
            $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getPost()['entity_id']]);
        }
        return $this->getResponse();
    }



    public function execute()
    {

        if ($this->getRequest()->getPost()) {
            if (!$this->_blogPostSaver->loadFormData2()) {
                $this->redirectError(__('This post no longer exists.'), '*/*/');
                return $this->getResponse();
            }




            try {
                $modelId = $this->_blogPostSaver->save();
                if ($modelId) {
                    $this->redirectSuccess($modelId);
                    return $this->getResponse();
                }
            } catch (\Exception $e) {
                $this->redirectError(
                    $e->getMessage(),
                    '*/*/edit',
                    ['id' => $this->_blogPostSaver->getFormData('entity_id')]
                );
                return $this->getResponse();
            }



        }


        var_dump($this->_blogPostSaver->getFormData());exit;
        $model->setData($data);
        $model->save();
    }

}
