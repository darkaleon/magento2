<?php
declare(strict_types=1);

namespace Alexx\Blog\Block;

use Alexx\Blog\Api\BlogRepositoryInterface;
use Magento\Catalog\Model\Locator\RegistryLocator;
use Magento\Framework\Api\AbstractSimpleObject;
use Magento\Framework\Api\Search\SearchCriteriaInterface;
use Magento\Framework\Api\Search\SearchCriteriaInterfaceFactory;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Psr\Log\LoggerInterface;

/**
 *  Block which injects to catalog_product_view
 *
 *  Displays blogs posts list
 */
class BlogList extends Template
{
    const XML_PATH_BLOG_VISIBLE = 'catalog_blog/general/applied_to';

    /**@var RegistryLocator */
    private $productRegistryLocator;

    /**@var BlogRepositoryInterface */
    private $blogRepsitory;

    /**@var SearchCriteriaInterfaceFactory */
    private $searchCriteriaFactory;

    /**@var SortOrderBuilder */
    private $sortOrderBuilder;

    /**@var LoggerInterface */
    private $logger;

    /**
     * @param Context $context
     * @param RegistryLocator $productRegistryLocator
     * @param BlogRepositoryInterface $blogRepsitory
     * @param SearchCriteriaInterfaceFactory $searchCriteriaFactory
     * @param SortOrderBuilder $sortOrderBuilder
     * @param LoggerInterface $logger
     * @param array $data
     */
    public function __construct(
        Context $context,
        RegistryLocator $productRegistryLocator,
        BlogRepositoryInterface $blogRepsitory,
        SearchCriteriaInterfaceFactory $searchCriteriaFactory,
        SortOrderBuilder $sortOrderBuilder,
        LoggerInterface $logger,
        array $data = []
    ) {
        $this->productRegistryLocator = $productRegistryLocator;
        $this->blogRepsitory = $blogRepsitory;
        $this->searchCriteriaFactory = $searchCriteriaFactory;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->logger = $logger;
        parent::__construct($context, $data);
    }

    /**
     * Get Product Type Id
     *
     * @return string
     * @throws NotFoundException
     */
    public function getCurrentProductTypeId()
    {
        return $this->productRegistryLocator->getProduct()->getTypeId();
    }

    /**
     * Gets system config value for checking applying blog to current product type
     *
     * @return string
     */
    private function getBlogSettingIsApplied()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_BLOG_VISIBLE);
    }

    /**
     * Checking all paramerets
     *
     * @return bool
     */
    public function isBlogEnabled()
    {
        try {
            return in_array($this->getCurrentProductTypeId(), explode(',', $this->getBlogSettingIsApplied()));
        } catch (NotFoundException $exception) {
            $this->logger->error($exception->getLogMessage());
            return false;
        }
    }

    /**
     * Gets latest blog posts
     *
     * @return array
     */
    public function getLastPosts()
    {
        /**@var SearchCriteriaInterface $searchCriteria */
        $searchCriteria = $this->searchCriteriaFactory->create();

        $searchCriteria->setPageSize(5);

        /**@var  AbstractSimpleObject $defaultSortOrder */
        $defaultSortOrder = $this->sortOrderBuilder
            ->setField('created_at')
            ->setDirection('desc')
            ->create();
        $searchCriteria->setSortOrders([$defaultSortOrder]);

        try {
            return $this->blogRepsitory->getList($searchCriteria)->getItems();
        } catch (LocalizedException $exception) {
            $this->logger->error($exception->getLogMessage());
            return [];
        }
    }
}
