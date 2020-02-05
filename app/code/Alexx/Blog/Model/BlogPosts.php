<?php

namespace Alexx\Blog\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\App\ObjectManager;

/**
 * Simple Model BlogPosts
 */
class BlogPosts extends AbstractModel
{
    const BLOG_TABLE = 'alexx_blog_posts';
    const BLOG_ID = 'entity_id';

    /**
     * Generates url to image
     *
     * @return string
     */
    public function getImageUrl()
    {
        $picureConfig = ObjectManager::getInstance()->get(PictureConfig::class);
        return $picureConfig->getBlogImageUrl($this->getPicture());
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
}
