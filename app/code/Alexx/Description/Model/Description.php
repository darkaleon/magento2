<?php
declare(strict_types=1);

namespace Alexx\Description\Model;

use Alexx\Description\Model\ResourceModel\Description as DescriptionResource;
use Alexx\Description\Api\Data\DescriptionInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Simple model fo DescriptionInterface::DESCRIPTIONS_DATA_TABLE table
 */
class Description extends AbstractModel implements DescriptionInterface
{
    /**@var string */
    protected $_idFieldName = 'entity_id';

    /**
     * @inheritDoc
     */
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

    /**
     * @inheritDoc
     */
    public function getProductEntityId(): string
    {
        return $this->getData(DescriptionInterface::FIELD_PRODUCT_ID) ?? '';
    }

    /**
     * @inheritDoc
     */
    public function getCustomerEntityId(): string
    {
        return $this->getData(DescriptionInterface::FIELD_CUSTOMER_ID) ?? '';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return $this->getData(DescriptionInterface::FIELD_DESCRIPTION) ?? '';
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): string
    {
        return $this->getData(DescriptionInterface::FIELD_CREATED_AT) ?? '';
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): string
    {
        return $this->getData(DescriptionInterface::FIELD_UPDATED_AT) ?? '';
    }

    /**
     * @inheritDoc
     */
    public function getCustomerEmail(): string
    {
        return $this->getData(DescriptionInterface::FIELD_CUSTOMER_EMAIL) ?? '';
    }

    /**
     * @inheritDoc
     */
    public function setProductEntityId(string $data): DescriptionInterface
    {
        return $this->setData(DescriptionInterface::FIELD_PRODUCT_ID, $data);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerEntityId(string $data): DescriptionInterface
    {
        return $this->setData(DescriptionInterface::FIELD_CUSTOMER_ID, $data);
    }

    /**
     * @inheritDoc
     */
    public function setDescription(string $data): DescriptionInterface
    {
        return $this->setData(DescriptionInterface::FIELD_DESCRIPTION, $data);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(string $data): DescriptionInterface
    {
        return $this->setData(DescriptionInterface::FIELD_CREATED_AT, $data);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt(string $data): DescriptionInterface
    {
        return $this->setData(DescriptionInterface::FIELD_UPDATED_AT, $data);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerEmail(string $data): DescriptionInterface
    {
        return $this->setData(DescriptionInterface::FIELD_CUSTOMER_EMAIL, $data);
    }
}
