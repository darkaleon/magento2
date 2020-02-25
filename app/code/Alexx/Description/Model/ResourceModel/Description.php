<?php
declare(strict_types=1);


namespace Alexx\Description\Model\ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Alexx\Description\Api\Data\CustomerNoteInterface;


class Description extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('alexx_customer_descriptions', 'entity_id');
    }

    public function loadByMultiParams($object,$params){
//        $object->beforeLoad($value, $field);
        $connection = $this->getConnection();

        $select=     $this->_getParamsSelect($params);
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


    protected function _getParamsSelect($params)
    {
//        $field = $this->getConnection()->quoteIdentifier(sprintf('%s.%s', $this->getMainTable(), $field))
//
        $select = $this->getConnection()->select()->from($this->getMainTable());
        foreach ($params as $k=>$param){
            $field = $this->getConnection()->quoteIdentifier(sprintf('%s.%s', $this->getMainTable(), $k));
            $select->where($field . '=?', $param);
        }

        return $select;
    }
}
