<?php

namespace Alexx\Blog\Block;

use Alexx\Blog\Model\BlogPostsFactory;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Registry;

/**
 *  Block which injects to catalog_product_view
 *
 *  Displays blogs posts list
 */
class BlogList extends Template
{
    const XML_PATH_BLOG_VISIBLE = 'catalog_blog/general/applied_to';

    protected $_scopeConfig;
    private $_blogsFactory;
    private $_currentProduct;

    /**
     * @param Context $context
     * @param BlogPostsFactory $blogsFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param Registry $coreRegistry
     * @param array $data
     *
     * @return void
     * */
    public function __construct(
        Context $context,
        BlogPostsFactory $blogsFactory,
        ScopeConfigInterface $scopeConfig,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->_currentProduct = $coreRegistry->registry('current_product');
        $this->_scopeConfig = $scopeConfig;
        $this->_blogsFactory = $blogsFactory;
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
     * @return \Magento\Framework\Data\Collection
     * */
    public function getPosts()
    {
        return $this->_blogsFactory->create()->getLatestPosts();
    }
}
