<?php
declare(strict_types=1);

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Alexx\Blog\Api\BlogRepositoryInterface;
use Alexx\Blog\Api\Data\BlogInterface;
use Alexx\Blog\Model\BlogPostSaver;
use Alexx\Blog\Model\BlogRepository;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context as ActionContext;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\App\ResponseInterface;

/**
 * Admin blog save Controller that perform saving data posted from the form to database
 */
class Save extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Alexx_Blog::manage';

    /**@var DataPersistorInterface */
    private $dataPersistor;

    /**@var BlogRepository */
    private $blogRepsitory;

    /**
     * @param ActionContext $context
     * @param BlogRepositoryInterface $blogRepsitory
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        ActionContext $context,
        BlogRepositoryInterface $blogRepsitory,
        DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->blogRepsitory = $blogRepsitory;

        parent::__construct($context);
    }

    /**
     * Redirect with error message
     *
     * @param string $message
     * @param string $path
     * @param array $arguments
     *
     * @return ResponseInterface
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
     * @return ResponseInterface
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
     * @return ResponseInterface
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
        $formPostData = $this->getRequest()->getPostValue();
        $isNewPost = !isset($formPostData['entity_id']);
        if (!$isNewPost) {
            try {
                $postModel = $this->blogRepsitory->getById((int)$formPostData['entity_id']);
            } catch (NoSuchEntityException $exception) {
                return $this->errorRedirect($exception->getMessage());
            }
        } else {
            $postModel = $this->blogRepsitory->getFactory()->create();
        }
        try {
            /**@var BlogInterface $postModel */
            $this->blogRepsitory->save($postModel, $formPostData);
            return $this->redirectSuccess($postModel->getId());
        } catch (CouldNotSaveException $e) {
            return $this->errorRedirect($e->getMessage(), $postModel->getData());
        }
    }
}
