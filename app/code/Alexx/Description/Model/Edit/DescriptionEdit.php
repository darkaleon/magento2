<?php


namespace Alexx\Description\Model\Edit;

use Alexx\Description\Api\DescriptionEditInterface;
use Alexx\Description\Model\Search\SearchDescription;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Webapi\Rest\Request as RestRequest;
use PHPUnit\Util\Log\JSON;
use Alexx\Description\Api\DescriptionRepositoryInterface;
use  Alexx\Description\Model\Config\ConfigForCustomer;

class DescriptionEdit implements DescriptionEditInterface
{

    protected $request;
    private $serializer;
    private $repository;
    private $configForCustomer;
    private $searchDescription;

    public function __construct(

        RestRequest $request,
        \Magento\Framework\Serialize\Serializer\Json $serializer,
        DescriptionRepositoryInterface $repository,
        ConfigForCustomer $configForCustomer,
        SearchDescription $searchDescription

    )
    {
        $this->searchDescription = $searchDescription;

        $this->configForCustomer = $configForCustomer;

        $this->repository = $repository;
        $this->request = $request;
        $this->serializer = $serializer;
    }

    public function parsePostData()
    {

        $ret = [];
        foreach ($this->request->getBodyParams() as $serializedParam) {
            $ret[$serializedParam['name']] = $serializedParam['value'];
        }
        return $ret;

    }

    public function editDescription()
    {

        if(!$this->configForCustomer->isCustomerLoggedIn()){
            return  $this->serializer->serialize(['message' => 'You are not logged in. Refresh page and login again']);

        }
        if(!$this->configForCustomer->isDescriptionAddAllowed()){
            return  $this->serializer->serialize(['message' => 'You are not allowed to add or edit description']);
        }

        $data = $this->parsePostData();
        $product_id = $data['product_entity_id'];
        $customer_id = $this->configForCustomer->getCustomerId();
//    $description= $this->repository->getByProductAndCustomer($data['product_entity_id'], $this->configForCustomer->getCustomerId());

        $description = $this->searchDescription->searchOne($product_id, $customer_id);

        $description->setData('description', $data['description']);

        $this->repository->save($description);
        return $this->serializer->serialize(['message' => 'save ok', 'form_data' => $data, 'entity' => $description->getData()]);
    }

    public function deleteDescription()
    {
        $data = $this->parsePostData();

//        $description= $this->repository->getByProductAndCustomer($data['product_entity_id'], $this->configForCustomer->getCustomerId());
        $product_id = $data['product_entity_id'];
        $customer_id = $this->configForCustomer->getCustomerId();
        $description = $this->searchDescription->searchOne($product_id, $customer_id);

        $this->repository->delete($description);

        return $this->serializer->serialize(['message' => 'delete ok', 'form_data' => $data]);


    }
}
