<?php
declare(strict_types=1);

namespace Alexx\Description\Model\Edit;

use Alexx\Description\Api\Data\DescriptionInterface;
use Alexx\Description\Api\Data\DescriptionInterfaceFactory;
use Alexx\Description\Api\DescriptionEditInterface;
use Alexx\Description\Api\DescriptionRepositoryInterface;
use Alexx\Description\Model\Config\CustomerAccessManagerToDescription;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Webapi\Rest\Request as RestRequest;

/**
 * Processing storefront Rest api Post requests
 */
class DescriptionEdit implements DescriptionEditInterface
{
    /**@var RestRequest */
    private $request;

    /**@var Json */
    private $serializer;

    /**@var DescriptionRepositoryInterface */
    private $repository;

    /**@var CustomerAccessManagerToDescription */
    private $customerAccessManagerToDescription;

    /**@var DescriptionRepositoryInterface */
    private $descriptionRepository;

    /**@var DescriptionInterfaceFactory */
    private $descriptionFactory;

    /**@var DataObjectHelper */
    private $dataObjectHelper;

    /**
     * @param RestRequest $request
     * @param Json $serializer
     * @param DescriptionRepositoryInterface $repository
     * @param CustomerAccessManagerToDescription $customerAccessManagerToDescription
     * @param DescriptionRepositoryInterface $descriptionRepository
     * @param DescriptionInterfaceFactory $descriptionFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        RestRequest $request,
        Json $serializer,
        DescriptionRepositoryInterface $repository,
        CustomerAccessManagerToDescription $customerAccessManagerToDescription,
        DescriptionRepositoryInterface $descriptionRepository,
        DescriptionInterfaceFactory $descriptionFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->dataObjectHelper = $dataObjectHelper;
        $this->descriptionFactory = $descriptionFactory;
        $this->descriptionRepository = $descriptionRepository;
        $this->customerAccessManagerToDescription = $customerAccessManagerToDescription;
        $this->repository = $repository;
        $this->request = $request;
        $this->serializer = $serializer;
    }

    /**
     * Converts json posted serialized array to associative array
     *
     * @return array
     */
    public function parsePostData(): array
    {
        $ret = [];
        foreach ($this->request->getBodyParams() as $serializedParam) {
            $ret[$serializedParam['name']] = $serializedParam['value'];
        }
        return $ret;
    }

    /**
     * @inheritDoc
     */
    public function editDescription(): string
    {
        if (!$this->customerAccessManagerToDescription->isCustomerLoggedIn()) {
            return $this->prepareResponse(true, __('You are not logged in. Refresh page and login again'));
        }
        if (!$this->customerAccessManagerToDescription->isDescriptionAddAllowed()) {
            return $this->prepareResponse(true, __('You are not allowed to add or edit description'));
        }
        $data = $this->parsePostData();
        $productId = $data['product_entity_id'];
        $customerId = $this->customerAccessManagerToDescription->getCustomerId();
        try {
            $description = $this->descriptionRepository->getByProductAndCustomer($productId, $customerId);
        } catch (NoSuchEntityException $exception) {
            $description = $this->descriptionFactory->create();
        }
        $data['customer_entity_id'] = $customerId;
        $this->dataObjectHelper->populateWithArray($description, $data, DescriptionInterface::class);
        $this->repository->save($description);
        return $this->prepareResponse(false);
    }

    /**
     * @inheritDoc
     */
    public function deleteDescription(): string
    {
        $data = $this->parsePostData();
        $productId = $data['product_entity_id'];
        $customerId = $this->customerAccessManagerToDescription->getCustomerId();
        try {
            $description = $this->descriptionRepository->getByProductAndCustomer($productId, $customerId);
        } catch (NoSuchEntityException $exception) {
            return $this->prepareResponse(true, __('Description is already deleted'));
        }
        try {
            $this->repository->delete($description);
        } catch (CouldNotDeleteException $exception) {
            return $this->prepareResponse(true, $exception->getMessage());
        }
        return $this->prepareResponse(false);
    }

    /**
     * Combine response values to array and converts it to json
     *
     * @param bool $error
     * @param Phrase|null $message
     *
     * @return string
     */
    public function prepareResponse(bool $error, $message = null): string
    {
        $result = ['error' => $error];
        if ($message) {
            $result['message'] = $message;
        }
        return $this->serializer->serialize($result);
    }
}
