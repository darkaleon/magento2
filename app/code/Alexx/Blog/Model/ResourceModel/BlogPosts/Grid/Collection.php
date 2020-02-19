<?php
declare(strict_types=1);

namespace Alexx\Blog\Model\ResourceModel\BlogPosts\Grid;

use Alexx\Blog\Model\ResourceModel\BlogPosts\Collection as BlogPostsCollection;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Document;
use Alexx\Blog\Model\ResourceModel\BlogPosts;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\AggregationInterface;

/**
 * BlogPosts Collection model for blog_grid_listing_data_source
 */
class Collection extends BlogPostsCollection implements SearchResultInterface
{
    /**@var AggregationInterface */
    private $_aggregations;

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(Document::class, BlogPosts::class);
    }

    /**
     * @inheritDoc
     */
    public function getAggregations()
    {
        return $this->_aggregations;
    }

    /**
     * @inheritDoc
     */
    public function setAggregations($aggregations)
    {
        $this->_aggregations = $aggregations;
    }

    /**
     * @inheritDoc
     */
    public function getSearchCriteria()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function setSearchCriteria(SearchCriteriaInterface $searchCriteria = null)
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * @inheritDoc
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setItems(array $items = null)
    {
        return $this;
    }
}
