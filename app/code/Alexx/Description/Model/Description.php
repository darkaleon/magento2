<?php

declare(strict_types=1);

namespace Alexx\Description\Model;

use Alexx\Description\Model\ResourceModel\Description as DescriptionResource;
use Alexx\Description\Api\Data\DescriptionInterface;
use Magento\Framework\Model\AbstractModel;


class Description extends AbstractModel  implements DescriptionInterface // extends \Magento\Framework\Model\AbstractExtensibleModel
{
    protected $_idFieldName = 'entity_id';


    protected function _construct()
    {
        $this->_init(DescriptionResource::class);
    }

    /**
     * @inheritDoc
     */
    public function getEntityId()
    {
        return $this->getData(DescriptionInterface::FIELD_ID) ?? '';
    }

    /**
     * @inheritDoc
     */
    public function setEntityId($data)
    {
        return $this->setData(DescriptionInterface::FIELD_ID, $data);
    }


    public function getProductEntityId(): string{
        return $this->getData(DescriptionInterface::FIELD_PRODUCT_ID) ?? '';
    }


    public function getCustomerEntityId(): string{
        return $this->getData(DescriptionInterface::FIELD_CUSTOMER_ID) ?? '';
    }

    public function getDescription(): string{
        return $this->getData(DescriptionInterface::FIELD_DESCRIPTION) ?? '';
    }

    public function getCreatedAt(): string{
        return $this->getData(DescriptionInterface::FIELD_CREATED_AT) ?? '';
    }

    public function getUpdatedAt(): string{
        return $this->getData(DescriptionInterface::FIELD_UPDATED_AT) ?? '';
    }

    public function getCustomerEmail(): string{
        return $this->getData(DescriptionInterface::FIELD_CUSTOMER_EMAIL) ?? '';
    }

    public function setProductEntityId(string $data): DescriptionInterface{
        return $this->setData(DescriptionInterface::FIELD_PRODUCT_ID, $data);
    }

    public function setCustomerEntityId(string $data): DescriptionInterface{
        return $this->setData(DescriptionInterface::FIELD_CUSTOMER_ID, $data);
    }

    public function setDescription(string $data): DescriptionInterface{
        return $this->setData(DescriptionInterface::FIELD_DESCRIPTION, $data);
    }

    public function setCreatedAt(string $data): DescriptionInterface{
        return $this->setData(DescriptionInterface::FIELD_CREATED_AT, $data);
    }

    public function setUpdatedAt(string $data): DescriptionInterface{
        return $this->setData(DescriptionInterface::FIELD_UPDATED_AT, $data);
    }

    public function setCustomerEmail(string $data): DescriptionInterface{
        return $this->setData(DescriptionInterface::FIELD_CUSTOMER_EMAIL, $data);
    }
}
