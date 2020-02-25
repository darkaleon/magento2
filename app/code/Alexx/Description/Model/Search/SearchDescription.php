<?php


namespace Alexx\Description\Model\Search;

use Alexx\Description\Api\Data\DescriptionInterfaceFactory;
use Alexx\Description\Model\Config\ConfigForCustomer;
use Alexx\Description\Model\DescriptionRepository;
use Magento\Framework\Exception\NoSuchEntityException;

class SearchDescription
{


    protected $descriptionRepository;
    protected $searchCriteriaBuilder;
    private $configForCustomer;

    protected $filterGroup;
    protected $filterBuilder;
    private $blogFactory;

    public function __construct(
      DescriptionRepository $descriptionRepository,
      DescriptionInterfaceFactory $blogFactory,

      \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder,
      ConfigForCustomer $configForCustomer

    )
    {
        $this->blogFactory = $blogFactory;
        $this->configForCustomer = $configForCustomer;

        $this->descriptionRepository = $descriptionRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterGroup = $filterGroupBuilder;
        $this->filterBuilder = $filterBuilder;
    }



       public function searchOne($product_id,$customer_id){

        if ($this->configForCustomer->isDescriptionAddAllowed()){


        try {
        $model = $this->descriptionRepository->getByProductAndCustomer($product_id,$customer_id);
        } catch (NoSuchEntityException $e) {
           $model = $this->blogFactory->create();
            $model->setData('product_entity_id',$product_id);
            $model->setData('customer_entity_id',$customer_id);


        }
        }else{
            $model = null;
        }
           return   $model;
       }
    /*   public function searchAllList($product_id){
        $this->searchCriteriaBuilder->addFilter('product_entity_id',$product_id);
        $searchCriteria = $this->searchCriteriaBuilder->create();

        return $this->descriptionRepository->getList($searchCriteria)->getItems();
    }*/
}
