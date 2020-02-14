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
    public function getTheme(): string;

    /**
     * Get content field
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Get picture field
     *
     * @return string
     */
    public function getPicture(): string;

    /**
     * Get created_at field
     *
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * Get updated_at field
     *
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * Set theme field
     *
     * @param string $data
     * @return BlogInterface
     */
    public function setTheme(string $data): BlogInterface;

    /**
     * Set picture field
     *
     * @param string $data
     * @return BlogInterface
     */
    public function setPicture(string $data): BlogInterface;

    /**
     * Set content field
     *
     * @param string $data
     * @return BlogInterface
     */
    public function setContent(string $data): BlogInterface;

    /**
     * Set created_at field
     *
     * @param string $data
     * @return BlogInterface
     */
    public function setCreatedAt(string $data): BlogInterface;

    /**
     * Set updated_at field
     *
     * @param string $data
     * @return BlogInterface
     */
    public function setUpdatedAt(string $data): BlogInterface;
}
