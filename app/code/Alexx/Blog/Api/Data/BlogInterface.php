<?php
declare(strict_types=1);

namespace Alexx\Blog\Api\Data;

/**
 * Blog storage is used to retrieve posts data.
 */
interface BlogInterface
{

    /**
     * Identifier getter
     *
     * @return mixed
     */
    public function getId();

    /**
     * Get theme field
     *
     * @return string
     */
    public function getTheme();

    /**
     * Get content field
     *
     * @return string
     */
    public function getContent();

    /**
     * Get picture field
     *
     * @return string
     */
    public function getPicture();

    /**
     * Get created_at field
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Get updated_at field
     *
     * @return string
     */
    public function getUpdatedAt();

    /**
     * Overwrite data in the object.
     *
     * The $key parameter can be string or array.
     * If $key is string, the attribute value will be overwritten by $value
     *
     * If $key is an array, it will overwrite all the data in the object.
     *
     * @param string|array $key
     * @param mixed $value
     * @return $this
     */
    public function setData($key, $value = null);

    /**
     * Save object data
     */
    public function save();
}
