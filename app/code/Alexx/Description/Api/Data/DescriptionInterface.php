<?php
declare(strict_types=1);

namespace Alexx\Description\Api\Data;

/**
 * Storage is used to retrieve customer descrition data.
 */
interface DescriptionInterface //extends \Magento\Framework\Api\ExtensibleDataInterface
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
     * Get static::FIELD_ID field
     *
     * @return string
     */
    public function getEntityId();

    /**
     * Set static::FIELD_ID field
     *
     * @param string $entityId
     * @return $this
     */
    public function setEntityId($entityId);

    /**
     * Get static::FIELD_CUSTOMER_EMAIL field
     *
     * @return string
     */
    public function getCustomerEmail(): string;

    /**
     * Get static::FIELD_PRODUCT_ID field
     *
     * @return string
     */
    public function getProductEntityId(): string;

    /**
     * Get static::FIELD_CUSTOMER_ID field
     *
     * @return string
     */
    public function getCustomerEntityId(): string;

    /**
     * Get static::FIELD_DESCRIPTION field
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Get static::FIELD_CREATED_AT field
     *
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * Get static::FIELD_UPDATED_AT field
     *
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * Set static::FIELD_CUSTOMER_EMAIL field
     *
     * @param string $data
     * @return DescriptionInterface
     */
    public function setCustomerEmail(string $data): DescriptionInterface;

    /**
     * Set static::FIELD_PRODUCT_ID field
     *
     * @param string $data
     * @return DescriptionInterface
     */
    public function setProductEntityId(string $data): DescriptionInterface;

    /**
     * Set static::FIELD_CUSTOMER_ID field
     *
     * @param string $data
     * @return DescriptionInterface
     */
    public function setCustomerEntityId(string $data): DescriptionInterface;

    /**
     * Set static::FIELD_DESCRIPTION field
     *
     * @param string $data
     * @return DescriptionInterface
     */
    public function setDescription(string $data): DescriptionInterface;

    /**
     * Set static::FIELD_CREATED_AT field
     *
     * @param string $data
     * @return DescriptionInterface
     */
    public function setCreatedAt(string $data): DescriptionInterface;

    /**
     * Set static::FIELD_UPDATED_AT field
     *
     * @param string $data
     * @return DescriptionInterface
     */
    public function setUpdatedAt(string $data): DescriptionInterface;
}
