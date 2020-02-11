<?php
declare(strict_types=1);

namespace Alexx\Blog\Api;

use Alexx\Blog\Api\Data\BlogInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\LocalizedException;

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
     * @throws LocalizedException
     */
    public function save(BlogInterface $blogPost);

    /**
     * Retrieve post
     *
     * @param string $blogPostId
     * @return BlogInterface
     * @throws LocalizedException
     */
    public function getById($blogPostId);

    /**
     * Retrieve posts matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete post from db
     *
     * @param BlogInterface $blogPost
     * @return bool
     * @throws LocalizedException
     */
    public function delete(BlogInterface $blogPost);

    /**
     * Delete post by ID
     *
     * @param string $blogPostId
     * @return bool
     * @throws LocalizedException
     */
    public function deleteById($blogPostId);
}
