<?php
declare(strict_types=1);

namespace Alexx\Description\Model\ResourceModel;

use Alexx\Description\Api\Data\AllowAddDescripitonInterface;
use Alexx\Description\Api\Data\DescriptionInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class AllowAddDescription extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(AllowAddDescripitonInterface::ALLOW_ADD_DESCRIPTIONS_DATA_TABLE, AllowAddDescripitonInterface::FIELD_ID);
    }
}
