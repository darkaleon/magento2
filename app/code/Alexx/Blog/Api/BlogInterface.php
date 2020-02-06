<?php

namespace Alexx\Blog\Api;

/*
 * Blog storage is used to retrieve posts data.
 * */
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
}
