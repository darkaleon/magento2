<?php

declare(strict_types=1);

namespace Alexx\Description\Model;


use Alexx\Description\Model\ResourceModel\Description as ResourceCustomerNote;
use Alexx\Description\Api\DescriptionRepositoryInterface;
use Alexx\Description\Api\Data\DescriptionInterface;
use Alexx\Description\Api\Data\DescriptionInterfaceFactory;
use Alexx\Description\Model\ResourceModel\Description\CollectionFactory as DescriptionCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class DescriptionRepository implements DescriptionRepositoryInterface
{
    /**
     * @var ResourceCustomerNote
     */
    private $resourceModel;


    /**
     * @var ResourceCustomerNote
     */
    private $blogFactory;

    /**
     * @var DescriptionCollectionFactory
     */
    private $blogCollectionFactory;

    /**
     * @var SearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;


    public function __construct(
        ResourceCustomerNote $resource,
        DescriptionInterfaceFactory $blogFactory,
        DescriptionCollectionFactory $blogCollectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    )
    {
//        var_dump('123');exit();

        $this->resourceModel = $resource;
        $this->blogFactory = $blogFactory;
        $this->blogCollectionFactory = $blogCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }


    public function save(DescriptionInterface $customerNote): DescriptionInterface
    {
        try {
            $this->resourceModel->save($customerNote);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $customerNote;
    }

    public function getById(string $customerNoteId): DescriptionInterface
    {
        $customerNote = $this->blogFactory->create();
        $this->resourceModel->load($customerNote, (int)$customerNoteId);
        if (!$customerNote->getId()) {
            throw new NoSuchEntityException(__('The customer note with the "%1" ID doesn\'t exist.', $customerNoteId));
        }
        return $customerNote;
    }

    public function getByProductAndCustomer($product_id,$customer_id){
        $customerDescription = $this->blogFactory->create();
        $params=['product_entity_id'=>$product_id,'customer_entity_id'=>$customer_id];
        $this->resourceModel->loadByMultiParams($customerDescription,$params);

        if (!$customerDescription->getId()) {
            throw new NoSuchEntityException(__('The customer note doesn\'t exist.'));
        }
        return $customerDescription;

    }


    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->blogCollectionFactory->create();
        try {
            $this->collectionProcessor->process($searchCriteria, $collection);
        } catch (\InvalidArgumentException $exception) {
            throw new LocalizedException(__($exception->getMessage()));
        }
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        return $searchResults->setTotalCount($collection->getSize());
    }

    public function delete(DescriptionInterface $customerNote): void
    {
        try {
            $this->resourceModel->delete($customerNote);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
    }


    /**
     * @inheritDoc
     */
    public function deleteById(string $customerNoteId): void
    {
        $this->delete($this->getById($customerNoteId));
    }
}
