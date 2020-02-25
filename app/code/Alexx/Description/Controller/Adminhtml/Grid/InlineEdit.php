<?php
declare(strict_types=1);

namespace Alexx\Description\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Json;
use Alexx\Description\Api\DescriptionRepositoryInterface;
use Alexx\Description\Api\Data\DescriptionInterface;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Customer description listing inline edit action
 */
class InlineEdit extends Action implements HttpPostActionInterface
{
    /**@var DescriptionRepositoryInterface */
    private $descriptionRepository;

    /**@var JsonFactory */
    private $jsonFactory;

    /**@var DataObjectHelper */
    private $dataObjectHelper;

    /**
     * @param Context $context
     * @param DescriptionRepositoryInterface $descriptionRepository
     * @param DataObjectHelper $dataObjectHelper
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        DescriptionRepositoryInterface $descriptionRepository,
        DataObjectHelper $dataObjectHelper,
        JsonFactory $jsonFactory
    ) {
        $this->dataObjectHelper = $dataObjectHelper;
        $this->descriptionRepository = $descriptionRepository;
        $this->jsonFactory = $jsonFactory;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {

        /** @var Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData(
                [
                    'messages' => [__('Please correct the data sent.')],
                    'error' => true,
                ]
            );
        }
        foreach (array_keys($postItems) as $descriptionId) {
            $descriptionModel = $this->descriptionRepository->getById($descriptionId);
            try {
                $formPostData = $postItems[$descriptionId];
                $this->dataObjectHelper
                    ->populateWithArray($descriptionModel, $formPostData, DescriptionInterface::class);
                $this->descriptionRepository->save($descriptionModel);
            } catch (CouldNotSaveException $exception) {
                $messages[] = $exception->getMessage();
                $error = true;
            }
        }

        return $resultJson->setData(
            [
                'messages' => $messages,
                'error' => $error
            ]
        );
    }
}
