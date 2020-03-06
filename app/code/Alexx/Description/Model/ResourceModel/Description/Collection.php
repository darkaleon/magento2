<?php
declare(strict_types=1);

namespace Alexx\Description\Model\ResourceModel\Description;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Alexx\Description\Model\Description;
use Alexx\Description\Model\ResourceModel\Description as DescriptionResourceModel;

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
            Description::class,
            DescriptionResourceModel::class
        );
    }

    /**
     * Set filter for queue by customer
     *
     * @param int $customerId
     * @return \Magento\Newsletter\Model\ResourceModel\Queue\Collection
     */
    public function addCustomerFilter(int $customerId): Collection
    {
        $this->getSelect()
            ->where(
                'customer_entity_id = ?',
                $customerId
            );

        return $this;
    }
}
