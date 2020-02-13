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
     * @param array $data
     * @return BlogInterface
     * @throws LocalizedException
     */
    public function save(BlogInterface $blogPost, array $data = null);

    /**
     * Retrieve post
     *
     * @param integer $blogPostId
     * @return BlogInterface
     * @throws LocalizedException
     */
    public function getById(int $blogPostId);

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
     * @param integer $blogPostId
     * @return bool
     * @throws LocalizedException
     */
    public function deleteById(int $blogPostId);
}
