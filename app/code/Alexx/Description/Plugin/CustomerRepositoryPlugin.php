<?php
declare(strict_types=1);

namespace Alexx\Description\Plugin;

use Alexx\Description\Api\Data\AllowAddDescripitonInterface;
use Alexx\Description\Api\DescriptionRepositoryInterface;
use Alexx\Description\Model\AllowAddDescripitonRepository;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Customer additionla product description plugin for customer repository
 */
class CustomerRepositoryPlugin
{
    /**
     * @var AllowAddDescripitonRepository
     */
    private $allowAddDescriptionRepository;

    /**
     * @var DescriptionRepositoryInterface
     */
    private $customerAdditionalDescriptionRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @param AllowAddDescripitonRepository $allowAddDescriptionRepository
     * @param DescriptionRepositoryInterface $customerAdditionalDescriptionRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        AllowAddDescripitonRepository $allowAddDescriptionRepository,
        DescriptionRepositoryInterface $customerAdditionalDescriptionRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder
    ) {
        $this->allowAddDescriptionRepository = $allowAddDescriptionRepository;
        $this->customerAdditionalDescriptionRepository = $customerAdditionalDescriptionRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * Plugin after getById customer that obtains extension attribute for given customer
     *
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $customer
     *
     * @return CustomerInterface
     */
    public function afterGetById(CustomerRepositoryInterface $subject, CustomerInterface $customer)
    {
        $customer->getExtensionAttributes()
            ->setAllowAddDescription($this->allowAddDescriptionRepository->getByCustomer($customer));
        return $customer;
    }

    /**
     * Plugin after create customer that updates extension attribute options that may have existed
     *
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $result
     * @param CustomerInterface $customer
     *
     * @return CustomerInterface
     */
    public function afterSave(
        CustomerRepositoryInterface $subject,
        CustomerInterface $result,
        CustomerInterface $customer
    ) {
        $loadedExtensionAttribute = $customer->getExtensionAttributes()->getAllowAddDescription();
        if (!empty($loadedExtensionAttribute)) {
            $loadedExtensionAttribute->setCustomerEmail($result->getEmail());
            $loadedExtensionAttribute->setCustomerEntityId($result->getId());
            try {
                $this->allowAddDescriptionRepository->save($loadedExtensionAttribute);
            } catch (CouldNotSaveException $exception) {
                $error = true;
            }
            $result->getExtensionAttributes()->setAllowAddDescription($loadedExtensionAttribute);
        }
        return $result;
    }

    /**
     * Plugin after delete customer.
     *
     * Deletes extension attribute entities for
     * customer model and product model that may have existed.
     *
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $result
     * @param CustomerInterface $customer
     *
     * @return CustomerInterface
     */
    public function afterDelete(
        CustomerRepositoryInterface $subject,
        $result,
        CustomerInterface $customer
    ) {
        $this->deleteModuleData($customer);
        return $result;
    }

    /**
     * Plugin around delete customer by id.
     *
     * Deletes extension attribute entities for
     * customer model and product model that may have existed.
     *
     * @param CustomerRepositoryInterface $subject
     * @param callable $deleteCustomerById
     * @param integer $customerId
     *
     * @return CustomerInterface
     */
    public function aroundDeleteById(
        CustomerRepositoryInterface $subject,
        callable $deleteCustomerById,
        $customerId
    ) {
        try {
            $customer = $subject->getById($customerId);
        } catch (LocalizedException | NoSuchEntityException $exception) {
            $customer = null;
        }
        $result = $deleteCustomerById($customerId);
        if ($customer) {
            $this->deleteModuleData($customer);
        }
        return $result;
    }

    /**
     * Deletes extension attribute entities for description module
     *
     * @param CustomerInterface $customer
     */
    private function deleteModuleData(CustomerInterface $customer)
    {
        $this->allowAddDescriptionRepository->deleteByCustomer($customer);
        while ($listToDelete = $this->getAddedDescriptionsList($customer)) {
            foreach ($listToDelete as $descriptionItem) {
                $this->customerAdditionalDescriptionRepository->delete($descriptionItem);
            }
        }
    }

    /**
     * Searches list of additional product descriptions of given customer
     *
     * @param CustomerInterface $customer
     *
     * @return ExtensibleDataInterface[]
     */
    private function getAddedDescriptionsList(CustomerInterface $customer)
    {
        $filter = $this->filterBuilder
            ->setField(AllowAddDescripitonInterface::FIELD_CUSTOMER_ID)
            ->setValue($customer->getId())
            ->create();

        $this->searchCriteriaBuilder->addFilters([$filter]);
        $this->searchCriteriaBuilder->setPageSize(100);

        $searchCriteria = $this->searchCriteriaBuilder->create();
        return $this->customerAdditionalDescriptionRepository->getList($searchCriteria)->getItems();
    }
}
