<?php
declare(strict_types=1);

namespace Alexx\Blog\Model;

use Alexx\Blog\Api\Data\BlogInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Alexx\Blog\Model\Media\Config as BlogMediaConfig;

/**
 * Simple Model BlogPosts
 */
class BlogPosts extends AbstractModel implements BlogInterface
{


    private $blogMediaConfig;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param  BlogMediaConfig $blogMediaConfig
     * @param AbstractResource $resource
     * @param AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        BlogMediaConfig $blogMediaConfig,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->blogMediaConfig = $blogMediaConfig;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Generates url to image
     *
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getPictureUrl()
    {
        return $this->blogMediaConfig->getBlogImageUrl($this->getPicture());
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
        return $this->getData(BlogInterface::FIELD_PICTURE);
    }

    /**
     * @inheritDoc
     */
    public function getTheme()
    {
        return $this->getData(BlogInterface::FIELD_THEME);
    }

    /**
     * @inheritDoc
     */
    public function getContent()
    {
        return $this->getData(BlogInterface::FIELD_CONTENT);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(BlogInterface::FIELD_CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return $this->getData(BlogInterface::FIELD_UPDATED_AT);
    }
}
