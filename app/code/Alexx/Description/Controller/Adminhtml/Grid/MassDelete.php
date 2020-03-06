<?php
declare(strict_types=1);

namespace Alexx\Description\Controller\Adminhtml\Grid;

use Alexx\Description\Api\DescriptionRepositoryInterface;
use Alexx\Description\Model\ResourceModel\Description\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Customer description listing mass delete action
 */
class MassDelete extends Action implements HttpPostActionInterface
{
    /**@var Filter */
    private $filter;

    /**@var DescriptionRepositoryInterface */
    private $descriptionRepository;

    /**@var RedirectInterface */
    private $redirect;

    /**@var CollectionFactory */
    private $collectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param RedirectInterface $redirect
     * @param DescriptionRepositoryInterface $repository
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        RedirectInterface $redirect,
        DescriptionRepositoryInterface $repository
    ) {
        $this->redirect = $redirect;
        $this->descriptionRepository = $repository;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $redirectUrl = $this->redirect->getRefererUrl();
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        foreach ($collection as $page) {
            $this->descriptionRepository->delete($page);
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath($redirectUrl);
    }
}
