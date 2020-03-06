<?php
declare(strict_types=1);

namespace Alexx\Description\Plugin;

use Alexx\Description\Api\Data\DescriptionInterface;
use Alexx\Description\Api\DescriptionRepositoryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Customer additionla product description plugin for product repository
 */
class ProductRepositoryPlugin
{
    /**
     * @var DescriptionRepositoryInterface $customerAdditionalDescriptionRepository
     */
    private $customerAdditionalDescriptionRepository;

    /**
     * @var SearchCriteriaBuilder $searchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var FilterBuilder $filterBuilder
     */
    private $filterBuilder;

    /**
     * @param DescriptionRepositoryInterface $customerAdditionalDescriptionRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        DescriptionRepositoryInterface $customerAdditionalDescriptionRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder
    ) {
        $this->customerAdditionalDescriptionRepository = $customerAdditionalDescriptionRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * Plugin around delete product by id.
     *
     * Deletes extension attribute entities for
     * product model that may have existed.
     *
     * @param ProductRepositoryInterface $subject
     * @param callable $deleteById
     * @param integer $productId
     *
     * @return ProductInterface
     */
    public function aroundDeleteById(
        ProductRepositoryInterface $subject,
        callable $deleteById,
        $productId
    ) {
        try {
            $product = $subject->getById($productId);
        } catch (NoSuchEntityException $exception) {
            $product = null;
        }
        $result = $deleteById($productId);
        if ($product) {
            $this->deleteModuleData($product);
        }
        return $result;
    }

    /**
     * Plugin after delete product.
     *
     * Deletes extension attribute entities for
     * product model that may have existed.
     *
     * @param ProductRepositoryInterface $subject
     * @param ProductInterface $result
     * @param ProductInterface $product
     *
     * @return ProductInterface
     */
    public function afterDelete(
        ProductRepositoryInterface $subject,
        $result,
        ProductInterface $product
    ) {
        $this->deleteModuleData($product);
        return $result;
    }

    /**
     * Deletes product extension attribute entities for description module
     *
     * @param ProductInterface $product
     */
    private function deleteModuleData(ProductInterface $product)
    {
        while ($listToDelete = $this->getAddedDescriptionsList($product)) {
            foreach ($listToDelete as $descriptionItem) {
                $this->customerAdditionalDescriptionRepository->delete($descriptionItem);
            }
        }
    }

    /**
     * Searches list of additional product descriptions of given product
     *
     * @param ProductInterface $product
     *
     * @return ExtensibleDataInterface[]
     */
    private function getAddedDescriptionsList(ProductInterface $product)
    {
        $filter = $this->filterBuilder
            ->setField(DescriptionInterface::FIELD_PRODUCT_ID)
            ->setValue($product->getId())
            ->create();

        $this->searchCriteriaBuilder->addFilters([$filter]);
        $this->searchCriteriaBuilder->setPageSize(100);

        $searchCriteria = $this->searchCriteriaBuilder->create();
        return $this->customerAdditionalDescriptionRepository->getList($searchCriteria)->getItems();
    }
}
