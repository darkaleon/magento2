<?php
declare(strict_types=1);

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Alexx\Blog\Api\BlogRepositoryInterface;
use Alexx\Blog\Api\Data\BlogInterface;
use Alexx\Blog\Api\Data\BlogInterfaceFactory;
use Alexx\Blog\Model\BlogRepository;
use Alexx\Blog\Model\Media\Config as BlogMediaConfig;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context as ActionContext;
use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Admin blog save Controller that perform saving data posted from the form to database
 */
class Save extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Alexx_Blog::manage';

    /**@var DataPersistorInterface */
    private $dataPersistor;

    /**@var BlogRepository */
    private $blogRepository;

    /**@var DataObjectHelper */
    private $dataObjectHelper;

    /**@var BlogInterfaceFactory */
    private $blogFactory;

    /**@var ImageUploader */
    private $imageUploader;

    /**@var BlogMediaConfig */
    private $blogMediaConfig;

    /**
     * @param ActionContext $context
     * @param BlogRepositoryInterface $blogRepository
     * @param DataPersistorInterface $dataPersistor
     * @param DataObjectHelper $dataObjectHelper
     * @param BlogInterfaceFactory $blogFactory
     * @param ImageUploader $imageUploader
     * @param BlogMediaConfig $blogMediaConfig
     */
    public function __construct(
        ActionContext $context,
        BlogRepositoryInterface $blogRepository,
        DataPersistorInterface $dataPersistor,
        DataObjectHelper $dataObjectHelper,
        BlogInterfaceFactory $blogFactory,
        ImageUploader $imageUploader,
        BlogMediaConfig $blogMediaConfig
    ) {
        $this->blogMediaConfig = $blogMediaConfig;
        $this->blogFactory = $blogFactory;
        $this->dataPersistor = $dataPersistor;
        $this->blogRepository = $blogRepository;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->imageUploader = $imageUploader;

        parent::__construct($context);
    }

    /**
     * Redirect with success message
     *
     * @param string $postId
     * @return ResponseInterface
     */
    private function redirectSuccess(string $postId)
    {
        $this->messageManager->addSuccess(__('The post has been saved.'));

        // Check if 'Save and Continue'
        $backRoute = $this->getRequest()->getParam('back');

        return $backRoute ?
            $this->_redirect('*/*/' . $backRoute, ['id' => $postId, '_current' => true]) :
            $this->_redirect('*/*/');
    }

    /**
     * Handling redirect with error message
     *
     * @param string $message
     * @param array $formData
     *
     * @return ResponseInterface
     */
    private function errorRedirect(string $message, array $formData = [])
    {
        $this->dataPersistor->set('BlogPostForm', $formData);
        $redirectArguments = [];
        if (empty($formData)) {
            $redirectPath = '*/*/';
        } elseif (!empty($formData[BlogInterface::FIELD_ID])) {
            $redirectPath = '*/*/edit';
            $redirectArguments['id'] = $formData[BlogInterface::FIELD_ID];
        } else {
            $redirectPath = '*/*/new';
        }
        $this->messageManager->addError($message);
        return $this->_redirect($redirectPath, $redirectArguments);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $formPostData = $this->getRequest()->getPostValue();
        $isNewPost = empty($formPostData[BlogInterface::FIELD_ID]);
        if (!$isNewPost) {
            try {
                $postModel = $this->blogRepository->getById($formPostData[BlogInterface::FIELD_ID]);
            } catch (NoSuchEntityException $exception) {
                return $this->errorRedirect($exception->getMessage());
            }
        } else {
            $formPostData[BlogInterface::FIELD_ID] = null;
            $postModel = $this->blogFactory->create();
        }
        /**@var BlogInterface $postModel */
        try {
            $this->preparePicture($formPostData);
            $this->dataObjectHelper->populateWithArray($postModel, $formPostData, BlogInterface::class);
            $this->blogRepository->save($postModel);
            return $this->redirectSuccess($postModel->getId());
        } catch (LocalizedException | CouldNotSaveException $e) {
            return $this->errorRedirect($e->getMessage(), $postModel->getData());
        }
    }

    /**
     * Perform file upload whenever picture is uploaded. Also, converts array posted by imageUploader ext. to string.
     *
     * @param array $formPostData
     *
     * @return void
     * @throws LocalizedException
     */
    private function preparePicture(array &$formPostData): void
    {
        if (!empty($formPostData[BlogInterface::FIELD_PICTURE])) {
            $pictureData = $formPostData[BlogInterface::FIELD_PICTURE];
            if (!empty($pictureData[0]['file'])) {
                $pictureData = $this->imageUploader->moveFileFromTmp($pictureData[0]['file'], true);
            }

            if (is_array($pictureData)) {
                $pictureData =
                    $pictureData[0]['path'] ?? $this->blogMediaConfig->extractRelativePath($pictureData[0]['url']);
            }
        } else {
            $pictureData = '';
        }
        $formPostData[BlogInterface::FIELD_PICTURE] = $pictureData;
    }
}
