<?php
declare(strict_types=1);

namespace Alexx\Blog\Block;

use Magento\Framework\Api\Search\SearchCriteriaInterfaceFactory;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Registry;
use Alexx\Blog\Api\BlogRepositoryInterfaceFactory;

/**
 *  Block which injects to catalog_product_view
 *
 *  Displays blogs posts list
 */
class BlogList extends Template
{
    const XML_PATH_BLOG_VISIBLE = 'catalog_blog/general/applied_to';

    protected $_scopeConfig;
    private $_currentProduct;
    private $_blogRepsitoryFactory;
    private $searchCriteriaFactory;
    private $sortOrderBuilder;

    /**
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param Registry $coreRegistry
     * @param BlogRepositoryInterfaceFactory $blogRepsitoryFactory
     * @param SearchCriteriaInterfaceFactory $searchCriteriaFactory
     * @param SortOrderBuilder $sortOrderBuilder
     * @param array $data
     *
     * @return void
     * */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        Registry $coreRegistry,
        BlogRepositoryInterfaceFactory $blogRepsitoryFactory,
        SearchCriteriaInterfaceFactory $searchCriteriaFactory,
        SortOrderBuilder $sortOrderBuilder,
        array $data = []
    ) {
        $this->_currentProduct = $coreRegistry->registry('current_product');
        $this->_blogRepsitoryFactory = $blogRepsitoryFactory;
        $this->searchCriteriaFactory = $searchCriteriaFactory;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    /**
     * Get Product Type Id
     *
     * @return string
     * */
    public function getCurrentProductTypeId()
    {
        return $this->_currentProduct->getTypeId();
    }

    /**
     * Gets system config value for checking applying blog to current product type
     *
     * @return string
     * */
    private function getBlogSettingIsApplied()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_BLOG_VISIBLE);
    }

    /**
     * Checking all paramerets
     *
     * @return bool
     * */
    public function isBlogIsEnabled()
    {
        return in_array($this->getCurrentProductTypeId(), explode(',', $this->getBlogSettingIsApplied()));
    }

    /**
     * Gets latest blog posts
     *
     * @return array
     * */
    public function getLastPosts()
    {
        $searchCriteria=$this->searchCriteriaFactory->create();

        $searchCriteria->setPageSize(5);

        $defaultSortOrder = $this->sortOrderBuilder
            ->setField('created_at')
            ->setDirection('desc')
            ->create();
        $searchCriteria->setSortOrders([$defaultSortOrder]);

        return $this->_blogRepsitoryFactory->create()->getList($searchCriteria)->getItems();
    }
}
