<?php
declare(strict_types=1);


namespace Alexx\Description\Model\ResourceModel\Description;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Alexx\Description\Model\Description;
use Alexx\Description\Model\ResourceModel\Description as DescriptionResourceModel;


class Collection extends AbstractCollection
{
    /**@var string*/
    protected $_idFieldName = 'entity_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            Description::class,
            DescriptionResourceModel::class
        );
    }
}
