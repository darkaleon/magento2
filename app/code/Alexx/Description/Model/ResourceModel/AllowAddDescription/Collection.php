<?php
declare(strict_types=1);

namespace Alexx\Description\Model\ResourceModel\AllowAddDescription;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Alexx\Description\Model\AllowAddDescription;
use Alexx\Description\Model\ResourceModel\AllowAddDescription as AllowAddDescriptionResourceModel;

/**
 * Collection model fo DescriptionInterface::DESCRIPTIONS_DATA_TABLE table
 */
class Collection extends AbstractCollection
{
    /**@var string */
    protected $_idFieldName = 'entity_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            AllowAddDescription::class,
            AllowAddDescriptionResourceModel::class
        );
    }
}
