<?php

namespace Alexx\Description\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Alexx\Description\Api\DescriptionRepositoryInterface;
use Alexx\Description\Api\Data\DescriptionInterface;

class InlineEdit extends Action implements HttpPostActionInterface
{



    /**
     * @var \Magento\Cms\Api\PageRepositoryInterface
     */
    protected $pageRepository;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;
    private $dataObjectHelper;

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        DescriptionRepositoryInterface $pageRepository,
        DataObjectHelper $dataObjectHelper,
        JsonFactory $jsonFactory
    ) {
        $this->dataObjectHelper = $dataObjectHelper;
        $this->pageRepository = $pageRepository;
        $this->jsonFactory = $jsonFactory;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
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

        foreach (array_keys($postItems) as $pageId) {
            /** @var \Magento\Cms\Model\Page $page */
            $postModel = $this->pageRepository->getById($pageId);
            try {
                $formPostData = $postItems[$pageId];


                $this->dataObjectHelper->populateWithArray($postModel, $formPostData, DescriptionInterface::class);

                $this->pageRepository->save($postModel);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithPageId($page, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithPageId($page, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithPageId(
                    $page,
                    __('Something went wrong while saving the page.')
                );
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
    protected function getErrorWithPageId(PageInterface $page, $errorText)
    {
        return '[Page ID: ' . $page->getId() . '] ' . $errorText;
    }
}

