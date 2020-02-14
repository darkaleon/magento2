<?php
declare(strict_types=1);

namespace Alexx\Blog\Model\ResourceModel;

use Alexx\Blog\Api\Data\BlogInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * BlogPosts ResourceModel
 */
class BlogPosts extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(BlogInterface::BLOG_TABLE, BlogInterface::BLOG_ID);
    }
}
