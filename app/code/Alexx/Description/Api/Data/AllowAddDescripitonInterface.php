<?php
declare(strict_types=1);

namespace Alexx\Description\Api\Data;

/**
 * Retreive data for customer extension attribute
 */
interface AllowAddDescripitonInterface
{
    const ALLOW_ADD_DESCRIPTIONS_DATA_TABLE = 'alexx_customer_allow_descriptions';
    const FIELD_ID = 'entity_id';
    const FIELD_CUSTOMER_ID = 'customer_entity_id';
    const FIELD_EMAIL = 'customer_email';
    const FIELD_ALLOW_ADD_DESCRIPTION = 'customer_allow_add_description';
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
     * Get static::FIELD_CUSTOMER_ID field
     *
     * @return string
     */
    public function getCustomerEntityId(): string;

    /**
     * Get static::FIELD_EMAIL field
     *
     * @return string
     */
    public function getCustomerEmail(): string;

    /**
     * Get static::FIELD_ALLOW_ADD_DESCRIPTION field
     *
     * @return string
     */
    public function getCustomerAllowAddDescription(): string;

    /**
     * Set static::FIELD_CUSTOMER_ID field
     *
     * @param string $data
     * @return AllowAddDescripitonInterface
     */
    public function setCustomerEntityId(string $data): AllowAddDescripitonInterface;

    /**
     * Set static::FIELD_EMAIL field
     *
     * @param string $data
     * @return AllowAddDescripitonInterface
     */
    public function setCustomerEmail(string $data): AllowAddDescripitonInterface;

    /**
     * Set static::FIELD_ALLOW_ADD_DESCRIPTION field
     *
     * @param string $data
     * @return AllowAddDescripitonInterface
     */
    public function setCustomerAllowAddDescription(string $data): AllowAddDescripitonInterface;
}
