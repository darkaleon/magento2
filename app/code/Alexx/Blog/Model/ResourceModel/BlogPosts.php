<?php
declare(strict_types=1);

namespace Alexx\Blog\Model\ResourceModel;

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
        $this->_init(\Alexx\Blog\Model\BlogPosts::BLOG_TABLE, \Alexx\Blog\Model\BlogPosts::BLOG_ID);
    }
}
