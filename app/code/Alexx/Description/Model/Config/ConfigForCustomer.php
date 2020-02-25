<?php


namespace Alexx\Description\Model\Config;

use Magento\Customer\Model\Session;

class ConfigForCustomer
{
    protected $customerSession;

    public function __construct( Session $customerSession){
        $this->customerSession = $customerSession;
    }

    public function isDescriptionAddAllowed(){
      return   boolval($this->customerSession->getCustomer()->getAllowAddDescription());
    }

    public function getCustomerId(){
        return $this->customerSession->getCustomer()->getId();
    }
    public function isCustomerLoggedIn(){

        return $this->getCustomerId()!==null;
    }

    public function isFront(){
        return $this->getCurrentArea()=='frontend';

    }


    public function isAdmin(){

        return $this->getCurrentArea()=='adminhtml';

    }

    public function getCurrentArea(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $state =  $objectManager->get('Magento\Framework\App\State');
        return $state->getAreaCode(); //frontend or adminhtml or webapi_rest
    }
}
