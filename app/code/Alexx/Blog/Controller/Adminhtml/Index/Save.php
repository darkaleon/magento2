<?php
declare(strict_types=1);

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Alexx\Blog\Model\BlogPostSaver;
use Alexx\Blog\Model\BlogPostsFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context as ActionContext;
use Magento\Customer\Model\Session;
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
    protected $_session;

    /**
     * @param ActionContext $context
     * @param BlogPostsFactory $postsFactory
     * @param BlogPostSaver $blogPostSaver
     * @param Session $session
     *
     * @return void
     * */
    public function __construct(
        ActionContext $context,
        BlogPostsFactory $postsFactory,
        BlogPostSaver $blogPostSaver,
        Session $session
    ) {
        parent::__construct($context);
        $this->_currentAction = $context;
        $this->_session = $session;

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
            $this->_redirect('*/*/editform', ['id' => $result, '_current' => true]);
            return;
        }
        // Go to grid page
        $this->_redirect('*/*/');
    }

    /**
     * Handling redirect when error
     *
     * @param string $message
     * @param array $formData
     * */
    private function errorRedirect($message, $formData = [])
    {
        $this->_session->setBlogPostForm($formData);

        if (empty($formData)) {
            $this->redirectError($message, '*/*/');
        } elseif (isset($formData['entity_id'])) {
            $this->redirectError($message, '*/*/editform', ['id' => $formData['entity_id']]);
        } else {
            $this->redirectError($message, '*/*/newform');
        }
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if ($this->getRequest()->getPost()) {
            if (!$this->_blogPostSaver->loadFormData()) {
                $this->errorRedirect(__('This post no longer exists.'));
                return $this->getResponse();
            }

            try {
                $this->_blogPostSaver->loadPictureData();
            } catch (\Exception $e) {
                $this->errorRedirect($e->getMessage(), $this->_blogPostSaver->getFormData());
                return $this->getResponse();
            }

            try {
                $modelId = $this->_blogPostSaver->save();
                if ($modelId) {
                    $this->redirectSuccess($modelId);
                    return $this->getResponse();
                }
            } catch (\Exception $e) {
                $this->errorRedirect($e->getMessage(), $this->_blogPostSaver->getFormData());
                return $this->getResponse();
            }
        }
        $this->errorRedirect(__('Not saved.'), $this->_blogPostSaver->getFormData());
        return $this->getResponse();
    }
}
