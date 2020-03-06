<?php
declare(strict_types=1);

namespace Alexx\Description\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Alexx\Description\Api\Data\DescriptionInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DB\Select;

/**
 * Resource model fo DescriptionInterface::DESCRIPTIONS_DATA_TABLE table
 */
class Description extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(DescriptionInterface::DESCRIPTIONS_DATA_TABLE, DescriptionInterface::FIELD_ID);
    }

    /**
     * Load an object
     *
     * @param AbstractModel $object
     * @param array $params
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function loadByArrayOfParams(AbstractModel$object, array $params): void
    {
        $connection = $this->getConnection();

        $select = $this->_getParamsSelect($params);
        $data = $connection->fetchRow($select);
        if ($data) {
            $object->setData($data);
        }

        $this->unserializeFields($object);
        $this->_afterLoad($object);
        $object->afterLoad();
        $object->setOrigData();
        $object->setHasDataChanges(false);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param array $params
     * @return Select
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function _getParamsSelect($params): Select
    {
        $select = $this->getConnection()->select()->from($this->getMainTable());
        foreach ($params as $k => $param) {
            $field = $this->getConnection()->quoteIdentifier(sprintf('%s.%s', $this->getMainTable(), $k));
            $select->where($field . '=?', $param);
        }
        return $select;
    }
}
