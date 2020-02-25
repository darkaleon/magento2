<?php
declare(strict_types=1);

namespace Alexx\Description\Api;

/**
 * Processing storefront Rest api Post requests
 */
interface DescriptionEditInterface
{
    /**
     * Manages edit request
     *
     * @return string
     */
    public function editDescription();

    /**
     * Manages delete request
     *
     * @return string
     */
    public function deleteDescription();
}
