<?php
declare(strict_types=1);

namespace Alexx\Blog\Model\ResourceModel\BlogPosts;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Alexx\Blog\Model\BlogPosts;
use Alexx\Blog\Model\ResourceModel\BlogPosts as BlogPostsResourceModel;

/**
 * BlogPosts Collection ResourceModel
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            BlogPosts::class,
            BlogPostsResourceModel::class
        );
    }
}
