<?php
declare(strict_types=1);

namespace Alexx\Description\Model\Edit;

use Alexx\Description\Api\Data\DescriptionInterface;
use Alexx\Description\Api\Data\DescriptionInterfaceFactory;
use Alexx\Description\Api\DescriptionEditInterface;
use Alexx\Description\Api\DescriptionRepositoryInterface;
use Alexx\Description\Model\Config\ConfigForCustomer;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Webapi\Rest\Request as RestRequest;
use Magento\Framework\Api\DataObjectHelper;

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

    /**@var ConfigForCustomer */
    private $configForCustomer;

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
     * @param ConfigForCustomer $configForCustomer
     * @param DescriptionRepositoryInterface $descriptionRepository
     * @param DescriptionInterfaceFactory $descriptionFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        RestRequest $request,
        Json $serializer,
        DescriptionRepositoryInterface $repository,
        ConfigForCustomer $configForCustomer,
        DescriptionRepositoryInterface $descriptionRepository,
        DescriptionInterfaceFactory $descriptionFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->dataObjectHelper = $dataObjectHelper;
        $this->descriptionFactory = $descriptionFactory;
        $this->descriptionRepository = $descriptionRepository;
        $this->configForCustomer = $configForCustomer;
        $this->repository = $repository;
        $this->request = $request;
        $this->serializer = $serializer;
    }

    /**
     * Converts json posted serialized array to associative array
     */
    public function parsePostData()
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
    public function editDescription()
    {
        if (!$this->configForCustomer->isCustomerLoggedIn()) {
            return $this->serializer->serialize(
                [
                    'message' => __('You are not logged in. Refresh page and login again'),
                    'error' => true
                ]
            );
        }
        if (!$this->configForCustomer->isDescriptionAddAllowed()) {
            return $this->serializer->serialize(
                [
                    'message' => __('You are not allowed to add or edit description'),
                    'error' => true
                ]
            );
        }
        $data = $this->parsePostData();
        $productId = $data['product_entity_id'];
        $customerId = $this->configForCustomer->getCustomerId();
        try {
            $description = $this->descriptionRepository->getByProductAndCustomer($productId, $customerId);
        } catch (NoSuchEntityException $exception) {
            $description = $this->descriptionFactory->create();
        }
        $data['customer_entity_id'] = $customerId;
        $this->dataObjectHelper->populateWithArray($description, $data, DescriptionInterface::class);
        $this->repository->save($description);
        return $this->serializer->serialize([
            'message' => __('save ok'),
            'form_data' => $data,
            'error' => false,
            'entity' => $description->getData()
        ]);
    }

    /**
     * @inheritDoc
     */
    public function deleteDescription()
    {
        $data = $this->parsePostData();
        $productId = $data['product_entity_id'];
        $customerId = $this->configForCustomer->getCustomerId();
        try {
            $description = $this->descriptionRepository->getByProductAndCustomer($productId, $customerId);
        } catch (NoSuchEntityException $exception) {
            return $this->serializer->serialize(
                [
                    'message' => __('Already deleted'),
                    'error' => true,
                    'deleted' => true,
                    'form_data' => $data
                ]
            );
        }
        try {
            $this->repository->delete($description);
        } catch (CouldNotDeleteException $exception) {
            return $this->serializer->serialize(
                [
                    'message' => $exception->getMessage(),
                    'error' => true,
                    'deleted' => false,
                    'form_data' => $data
                ]
            );
        }
        return $this->serializer->serialize(
            [
                'message' => __('delete ok'),
                'error' => false,
                'deleted' => true,
                'form_data' => $data
            ]
        );
    }
}
