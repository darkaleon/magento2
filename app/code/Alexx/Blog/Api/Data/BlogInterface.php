<?php
declare(strict_types=1);

namespace Alexx\Blog\Api\Data;

/**
 * Blog storage is used to retrieve posts data.
 */
interface BlogInterface
{
    const BLOG_TABLE = 'alexx_blog_posts';
    const BLOG_ID = 'entity_id';
    const FIELD_PICTURE = 'picture';
    const FIELD_THEME = 'theme';
    const FIELD_CONTENT = 'content';
    const FIELD_CREATED_AT = 'created_at';
    const FIELD_UPDATED_AT = 'updated_at';

    /**
     * Identifier getter
     *
     * @return string|null
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
     * Set theme field
     *
     * @param string $data
     * @return string
     */
    public function setTheme(string $data);

    /**
     * Set picture field
     *
     * @param string $data
     * @return string
     */
    public function setPicture(string $data);

    /**
     * Set content field
     *
     * @param string $data
     * @return string
     */
    public function setContent(string $data);

    /**
     * Set created_at field
     *
     * @param string $data
     * @return string
     */
    public function setCreatedAt(string $data);

    /**
     * Set updated_at field
     *
     * @param string $data
     * @return string
     */
    public function setUpdatedAt(string $data);
}
