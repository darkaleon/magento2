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
use Magento\Catalog\Model\ImageUploader;
/**
 * Admin blog save Controller that perform saving data posted from the form to database
 */
class Save extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Alexx_Blog::manage';

    /**@var BlogPostSaver*/
    private $blogPostSaver;

    /**@var DataPersistorInterface*/
    private $dataPersistor;
    private $imageUploader;

    /**
     * @param ActionContext $context
     * @param BlogPostSaver $blogPostSaver
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        ActionContext $context,
        BlogPostSaver $blogPostSaver,
        ImageUploader $imageUploader,
        DataPersistorInterface $dataPersistor
    ) {
        $this->imageUploader = $imageUploader;
        $this->dataPersistor = $dataPersistor;
        $this->blogPostSaver = $blogPostSaver;
        parent::__construct($context);
    }

    /**
     * Redirect with error message
     *
     * @param string $message
     * @param string $path
     * @param array $arguments
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function redirectError($message, $path, $arguments = [])
    {
        $this->messageManager->addError($message);
        return $this->_redirect($path, $arguments);
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
        $this->dataPersistor->set('BlogPostForm', $formData);

        if (empty($formData)) {
            return $this->redirectError($message, '*/*/');
        } elseif (isset($formData['entity_id'])) {
            return $this->redirectError($message, '*/*/edit', ['id' => $formData['entity_id']]);
        } else {
            return $this->redirectError($message, '*/*/new');
        }
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if ($this->getRequest()->getPost()) {
            try {
                $this->blogPostSaver->loadFormData();
            } catch (NoSuchEntityException $exception) {
                return $this->errorRedirect($exception->getMessage());
            }

            try {
                $this->blogPostSaver->loadPictureData($this->imageUploader);
            } catch (\Exception $e) {
                return  $this->errorRedirect($e->getMessage(), $this->blogPostSaver->getFormData());
            }

            try {
                $modelId = $this->blogPostSaver->save();
                return $this->redirectSuccess($modelId);
            } catch (CouldNotSaveException $e) {
                return $this->errorRedirect($e->getMessage(), $this->blogPostSaver->getFormData());
            }
        }
        return $this->errorRedirect(__('Wrong request type.'), $this->blogPostSaver->getFormData());
    }
}
