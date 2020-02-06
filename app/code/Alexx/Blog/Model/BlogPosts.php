<?php

namespace Alexx\Blog\Model;

use Alexx\Blog\Api\BlogInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * Simple Model BlogPosts
 */
class BlogPosts extends AbstractModel implements BlogInterface
{
    const BLOG_TABLE = 'alexx_blog_posts';
    const BLOG_ID = 'entity_id';

    private $_pictureConfig;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param PictureConfig $pictureConfig
     * @param AbstractResource $resource
     * @param AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        PictureConfig $pictureConfig,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_pictureConfig = $pictureConfig;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Generates url to image
     *
     * @return string
     */
    public function getPictureUrl()
    {
        return $this->_pictureConfig->getBlogImageUrl($this->getPicture());
    }

    /**
     * Getting 5 last posts
     *
     * @return \Magento\Framework\Data\Collection
     */
    public function getLatestPosts()
    {
        return $this->getCollection()->addOrder('main_table.created_at', 'desc')->setPageSize(5);
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\BlogPosts::class);
    }

    /**
     * @inheritDoc
     */
    public function getPicture()
    {
        return $this->getData('picture');
    }

    /**
     * @inheritDoc
     */
    public function getTheme()
    {
        return $this->getData('theme');
    }

    /**
     * @inheritDoc
     */
    public function getContent()
    {
        return $this->getData('content');
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData('created_at');
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return $this->getData('updated_at');
    }
}
