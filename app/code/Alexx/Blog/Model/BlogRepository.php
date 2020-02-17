<?php
declare(strict_types=1);

namespace Alexx\Blog\Model;

use Alexx\Blog\Api\BlogRepositoryInterface;
use Alexx\Blog\Api\Data\BlogInterface;
use Alexx\Blog\Api\Data\BlogInterfaceFactory;
use Alexx\Blog\Model\ResourceModel\BlogPosts as ResourceBlog;
use Alexx\Blog\Model\ResourceModel\BlogPosts\CollectionFactory as BlogCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Repository for BlogPosts model
 */
class BlogRepository implements BlogRepositoryInterface
{
    /**
     * @var ResourceBlog
     */
    private $resource;

    /**
     * @var BlogInterfaceFactory
     */
    private $blogFactory;

    /**
     * @var BlogCollectionFactory
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

    /**
     * @param ResourceBlog $resource
     * @param BlogInterfaceFactory $blogFactory
     * @param BlogCollectionFactory $blogCollectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceBlog $resource,
        BlogInterfaceFactory $blogFactory,
        BlogCollectionFactory $blogCollectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->blogFactory = $blogFactory;
        $this->blogCollectionFactory = $blogCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @inheritDoc
     */
    public function save(BlogInterface $blogPost): BlogInterface
    {
        try {
            $this->resource->save($blogPost);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $blogPost;
    }

    /**
     * @inheritDoc
     */
    public function getById(string $blogPostId): BlogInterface
    {
        $blogPost = $this->blogFactory->create();
        $this->resource->load($blogPost, (int)$blogPostId);
        if (!$blogPost->getId()) {
            throw new NoSuchEntityException(__('The blogs post with the "%1" ID doesn\'t exist.', $blogPostId));
        }
        return $blogPost;
    }

    /**
     * @inheritDoc
     */
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

        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(BlogInterface $blogPost): void
    {
        try {
            $this->resource->delete($blogPost);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
    }

    /**
     * @inheritDoc
     */
    public function deleteById(string $blogPostId): void
    {
        $this->delete($this->getById($blogPostId));
    }
}
