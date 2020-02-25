<?php

declare(strict_types=1);

namespace Alexx\Description\Api;

use Alexx\Description\Api\Data\DescriptionInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface DescriptionRepositoryInterface
{

    public function getByProductAndCustomer($product_id,$customer_id);

        /**
     * Save post to db
     *
     * @param DescriptionInterface $customerNote
     * @return DescriptionInterface
     */
    public function save(DescriptionInterface $customerNote): DescriptionInterface;

    /**
     * Retrieve post
     *
     * @param string $customerNoteId
     * @return DescriptionInterface
     */
    public function getById(string $customerNoteId): DescriptionInterface;

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
     * @param DescriptionInterface $customerNote
     * @return void
     */
    public function delete(DescriptionInterface $customerNote): void;

    /**
     * Delete post by ID
     *
     * @param integer $customerNoteId
     * @return void
     */
    public function deleteById(string $customerNoteId): void;
}
