<?php
declare(strict_types=1);

namespace Alexx\Description\Api;

use Alexx\Description\Api\Data\AllowAddDescripitonInterface;
use Magento\Customer\Api\Data\CustomerInterface;

interface AllowAddDescripitonRepositoryInterface
{
    /**
     * Retreive AllowAddDescripiton model by customer model
     *
     * @param CustomerInterface $customer
     *
     * @return AllowAddDescripitonInterface
     */
    public function getByCustomer(CustomerInterface $customer): AllowAddDescripitonInterface;

    /**
     * Delete AllowAddDescripiton entity by customer model
     *
     * @param CustomerInterface $customer
     */
    public function deleteByCustomer(CustomerInterface $customer): void;

    /**
     * Save post to db
     *
     * @param AllowAddDescripitonInterface $customerAllowAddDescription
     * @return AllowAddDescripitonInterface
     */
    public function save(AllowAddDescripitonInterface $customerAllowAddDescription): AllowAddDescripitonInterface;

    /**
     * Delete post from db
     *
     * @param AllowAddDescripitonInterface $customerAllowAddDescription
     * @return void
     */
    public function delete(AllowAddDescripitonInterface $customerAllowAddDescription): void;

}
