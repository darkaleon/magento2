<?php
declare(strict_types=1);

namespace Alexx\Description\Api;

use Alexx\Description\Api\Data\DescriptionInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Customer product descrition CRUD interface.
 */
interface DescriptionRepositoryInterface
{
    /**
     * Retrieve post params
     *
     * @param string $productId
     * @param string $customerId
     * @return DescriptionInterface
     * @throws NoSuchEntityException
     */
    public function getByProductAndCustomer(string $productId, string $customerId): DescriptionInterface;

    /**
     * Save post to db
     *
     * @param DescriptionInterface $customerDescription
     * @return DescriptionInterface
     */
    public function save(DescriptionInterface $customerDescription): DescriptionInterface;

    /**
     * Retrieve post by id
     *
     * @param string $customerDescriptionId
     * @return DescriptionInterface
     */
    public function getById(string $customerDescriptionId): DescriptionInterface;

    /**
     * Retrieve posts matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * Delete post from db
     *
     * @param DescriptionInterface $customerDescription
     * @return void
     */
    public function delete(DescriptionInterface $customerDescription): void;

    /**
     * Delete post by ID
     *
     * @param integer $customerDescriptionId
     * @return void
     */
    public function deleteById(string $customerDescriptionId): void;
}
