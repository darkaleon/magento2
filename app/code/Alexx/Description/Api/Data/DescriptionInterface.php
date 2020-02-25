<?php
declare(strict_types=1);

namespace Alexx\Description\Api\Data;

interface DescriptionInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const DESCRIPTIONS_DATA_TABLE = 'alexx_customer_descriptions';
    const FIELD_ID = 'entity_id';
    const FIELD_PRODUCT_ID = 'product_entity_id';
    const FIELD_CUSTOMER_ID = 'customer_entity_id';
    const FIELD_DESCRIPTION = 'description';
    const FIELD_CUSTOMER_EMAIL = 'customer_email';
    const FIELD_CREATED_AT = 'created_at';
    const FIELD_UPDATED_AT = 'updated_at';

    /**
     * Identifier getter
     *
     * @return string|null
     */
    public function getId();

    /**
     * Get entity_id field
     *
     * @return string
     */
    public function getEntityId();

    public function setEntityId($entityId);

    public function getCustomerEmail(): string;

    public function getProductEntityId(): string;

    public function getCustomerEntityId(): string;

    public function getDescription(): string;

    public function getCreatedAt(): string;

    public function getUpdatedAt(): string;

    public function setCustomerEmail(string $data): DescriptionInterface;

    public function setProductEntityId(string $data): DescriptionInterface;

    public function setCustomerEntityId(string $data): DescriptionInterface;

    public function setDescription(string $data): DescriptionInterface;

    public function setCreatedAt(string $data): DescriptionInterface;

    public function setUpdatedAt(string $data): DescriptionInterface;
}
