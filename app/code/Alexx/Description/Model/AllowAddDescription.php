<?php
declare(strict_types=1);

namespace Alexx\Description\Model;

use Alexx\Description\Api\Data\AllowAddDescripitonInterface;
use Alexx\Description\Api\Data\DescriptionInterface;
use Alexx\Description\Model\ResourceModel\AllowAddDescription as AllowAddDescriptionResource;
use Magento\Framework\Model\AbstractModel;

class AllowAddDescription extends AbstractModel implements AllowAddDescripitonInterface
{

    /**@var string */
    protected $_idFieldName = 'entity_id';
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(AllowAddDescriptionResource::class);
    }
    /**
     * @inheritDoc
     */
    public function getEntityId()
    {
        return $this->getData(AllowAddDescripitonInterface::FIELD_ID) ?? '';
    }

    /**
     * @inheritDoc
     */
    public function setEntityId($data)
    {
        return $this->setData(AllowAddDescripitonInterface::FIELD_ID, $data);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerEntityId(): string{
        return $this->getData(AllowAddDescripitonInterface::FIELD_CUSTOMER_ID) ?? '';

    }
    /**
     * @inheritDoc
     */
    public function getCustomerEmail(): string{
        return $this->getData(AllowAddDescripitonInterface::FIELD_EMAIL) ?? '';

    }
    /**
     * @inheritDoc
     */
    public function getCustomerAllowAddDescription(): string{
        return $this->getData(AllowAddDescripitonInterface::FIELD_ALLOW_ADD_DESCRIPTION) ?? '';
    }

    /**
     * @inheritDoc
     */
    public function setCustomerEntityId(string $data): AllowAddDescripitonInterface{
        return $this->setData(AllowAddDescripitonInterface::FIELD_CUSTOMER_ID, $data);

    }
    /**
     * @inheritDoc
     */
    public function setCustomerEmail(string $data): AllowAddDescripitonInterface{
        return $this->setData(AllowAddDescripitonInterface::FIELD_EMAIL, $data);

    }
    /**
     * @inheritDoc
     */
    public function setCustomerAllowAddDescription(string $data): AllowAddDescripitonInterface{
        return $this->setData(AllowAddDescripitonInterface::FIELD_ALLOW_ADD_DESCRIPTION, $data);

    }



}
