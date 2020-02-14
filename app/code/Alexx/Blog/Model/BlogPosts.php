<?php
declare(strict_types=1);

namespace Alexx\Blog\Model;

use Alexx\Blog\Api\Data\BlogInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Simple Model BlogPosts
 */
class BlogPosts extends AbstractModel implements BlogInterface
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\BlogPosts::class);
    }

    /**
     * @inheritDoc
     */
    public function getPicture(): string
    {
        return ($this->getData(BlogInterface::FIELD_PICTURE) ?? '');
    }

    /**
     * @inheritDoc
     */
    public function getTheme(): string
    {
        return $this->getData(BlogInterface::FIELD_THEME);
    }

    /**
     * @inheritDoc
     */
    public function getContent(): string
    {
        return $this->getData(BlogInterface::FIELD_CONTENT);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): string
    {
        return $this->getData(BlogInterface::FIELD_CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): string
    {
        return $this->getData(BlogInterface::FIELD_UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setTheme(string $data): BlogInterface
    {
        return $this->setData(BlogInterface::FIELD_THEME, $data);
    }

    /**
     * @inheritDoc
     */
    public function setPicture($data): BlogInterface
    {
        return $this->setData(BlogInterface::FIELD_PICTURE, $data);
    }

    /**
     * @inheritDoc
     */
    public function setContent(string $data): BlogInterface
    {
        return $this->setData(BlogInterface::FIELD_CONTENT, $data);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(string $data): BlogInterface
    {
        return $this->setData(BlogInterface::FIELD_CREATED_AT, $data);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt(string $data): BlogInterface
    {
        return $this->setData(BlogInterface::FIELD_UPDATED_AT, $data);
    }
}
