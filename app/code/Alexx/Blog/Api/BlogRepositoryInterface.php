<?php
declare(strict_types=1);

namespace Alexx\Blog\Api;

use Alexx\Blog\Api\Data\BlogInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Blog post CRUD interface.
 */
interface BlogRepositoryInterface
{
    /**
     * Save post to db
     *
     * @param BlogInterface $blogPost
     * @return BlogInterface
     * @throws CouldNotSaveException
     */
    public function save(BlogInterface $blogPost): BlogInterface;

    /**
     * Retrieve post
     *
     * @param integer $blogPostId
     * @return BlogInterface
     * @throws NoSuchEntityException
     */
    public function getById($blogPostId): BlogInterface;

    /**
     * Retrieve posts matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * Delete post from db
     *
     * @param BlogInterface $blogPost
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(BlogInterface $blogPost): bool;

    /**
     * Delete post by ID
     *
     * @param integer $blogPostId
     * @return bool
     * @throws CouldNotDeleteException|NoSuchEntityException
     */
    public function deleteById($blogPostId): bool;
}
