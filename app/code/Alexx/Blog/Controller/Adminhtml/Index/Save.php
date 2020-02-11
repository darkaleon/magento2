<?php
declare(strict_types=1);

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Alexx\Blog\Model\BlogPostSaver;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context as ActionContext;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Admin blog save Controller that perform saving data posted from the form to database
 */
class Save extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Alexx_Blog::menu';

    private $_currentAction;
    private $_blogPostSaver;
    private $_dataPersistor;

    /**
     * @param ActionContext $context
     * @param BlogPostSaver $blogPostSaver
     * @param DataPersistorInterface $dataPersistor
     * */
    public function __construct(
        ActionContext $context,
        BlogPostSaver $blogPostSaver,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->_currentAction = $context;
        $this->_dataPersistor = $dataPersistor;
        $this->_blogPostSaver = $blogPostSaver;
    }

    /**
     * Redirect with error message
     *
     * @param string $message
     * @param string $path
     * @param array $arguments
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
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function redirectSuccess($result)
    {
        $this->messageManager->addSuccess(__('The post has been saved.'));

        // Check if 'Save and Continue'
        if ($this->getRequest()->getParam('back')) {
            return $this->_redirect('*/*/edit', ['id' => $result, '_current' => true]);
        }
        // Go to grid page
        return $this->_redirect('*/*/');
    }

    /**
     * Handling redirect when error
     *
     * @param string $message
     * @param array $formData
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    private function errorRedirect($message, $formData = [])
    {
        $this->_dataPersistor->set('BlogPostForm', $formData);

        if (empty($formData)) {
            $this->redirectError($message, '*/*/');
        } elseif (isset($formData['entity_id'])) {
            $this->redirectError($message, '*/*/edit', ['id' => $formData['entity_id']]);
        } else {
            $this->redirectError($message, '*/*/new');
        }
        return $this->getResponse();
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if ($this->getRequest()->getPost()) {
            try {
                $this->_blogPostSaver->loadFormData();
            } catch (NoSuchEntityException $exception) {
                return $this->errorRedirect($exception->getMessage());
            }

            try {
                $this->_blogPostSaver->loadPictureData();
            } catch (\Exception $e) {
                return  $this->errorRedirect($e->getMessage(), $this->_blogPostSaver->getFormData());
            }

            try {
                $modelId = $this->_blogPostSaver->save();
                return $this->redirectSuccess($modelId);
            } catch (CouldNotSaveException $e) {
                return $this->errorRedirect($e->getMessage(), $this->_blogPostSaver->getFormData());
            }
        }
        return $this->errorRedirect(__('Not saved.'), $this->_blogPostSaver->getFormData());
    }
}
