<?php
declare(strict_types=1);

namespace Alexx\Description\Model;

use Alexx\Description\Api\Data\DescriptionInterface;
use Alexx\Description\Api\Data\DescriptionInterfaceFactory;
use Alexx\Description\Api\DescriptionRepositoryInterface;
use Alexx\Description\Model\ResourceModel\Description as ResourceCustomerDescription;
use Alexx\Description\Model\ResourceModel\Description\CollectionFactory as DescriptionCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Customer additional description repository
 */
class DescriptionRepository implements DescriptionRepositoryInterface
{
    /**@var ResourceCustomerDescription */
    private $resourceModel;

    /**@var DescriptionInterfaceFactory */
    private $descriptionFactory;

    /**@var DescriptionCollectionFactory */
    private $descriptionCollectionFactory;

    /**@var SearchResultsInterfaceFactory */
    private $searchResultsFactory;

    /**@var CollectionProcessorInterface */
    private $collectionProcessor;

    /**
     * @param ResourceCustomerDescription $resource
     * @param DescriptionInterfaceFactory $descriptionFactory
     * @param DescriptionCollectionFactory $descriptionCollectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceCustomerDescription $resource,
        DescriptionInterfaceFactory $descriptionFactory,
        DescriptionCollectionFactory $descriptionCollectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resourceModel = $resource;
        $this->descriptionFactory = $descriptionFactory;
        $this->descriptionCollectionFactory = $descriptionCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @inheritDoc
     */
    public function getByProductAndCustomer(string $productId, string $customerId): DescriptionInterface
    {
        $customerDescription = $this->descriptionFactory->create();
        $params = ['product_entity_id' => $productId, 'customer_entity_id' => $customerId];
        $this->resourceModel->loadByArrayOfParams($customerDescription, $params);
        if (!$customerDescription->getId()) {
            throw new NoSuchEntityException(__('The customer note doesn\'t exist.'));
        }
        return $customerDescription;
    }

    /**
     * @inheritDoc
     */
    public function save(DescriptionInterface $customerDescription): DescriptionInterface
    {
        try {
            $this->resourceModel->save($customerDescription);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $customerDescription;
    }

    /**
     * @inheritDoc
     */
    public function getById(string $customerDescriptionId): DescriptionInterface
    {
        $customerDescription = $this->descriptionFactory->create();
        $this->resourceModel->load($customerDescription, (int)$customerDescriptionId);
        if (!$customerDescription->getId()) {
            throw new NoSuchEntityException(
                __('The customer note with the "%1" ID doesn\'t exist.', $customerDescriptionId)
            );
        }
        return $customerDescription;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->descriptionCollectionFactory->create();
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

    /**
     * @inheritDoc
     */
    public function delete(DescriptionInterface $customerDescription): void
    {
        try {
            $this->resourceModel->delete($customerDescription);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
    }

    /**
     * @inheritDoc
     */
    public function deleteById(string $customerDescriptionId): void
    {
        $this->delete($this->getById($customerDescriptionId));
    }
}
