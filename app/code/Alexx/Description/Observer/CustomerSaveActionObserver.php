<?php
declare(strict_types=1);

namespace Alexx\Description\Observer;

use Alexx\Description\Model\AllowAddDescripitonRepository;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CustomerSaveActionObserver implements ObserverInterface
{
    private $customerAdditionalDescriptionRepository;


    public function __construct(AllowAddDescripitonRepository $customerAdditionalDescriptionRepository)
    {
        $this->customerAdditionalDescriptionRepository = $customerAdditionalDescriptionRepository;
    }

    public function execute(Observer $observer)
    {
        $customer = $observer->getCustomer();
        $request = $observer->getRequest();
        $allowAddDescriptionStatus = $request->getParam('allow_add_description');
        $extensionAttributes = $customer->getExtensionAttributes();
        if (empty($extensionAttributes->getAllowAddDescription())) {
            $current = $this->customerAdditionalDescriptionRepository->getByCustomer($customer);
            $extensionAttributes->setAllowAddDescription($current);
            $current->setCustomerAllowAddDescription($allowAddDescriptionStatus);
        } else {
            $extensionAttributes->getAllowAddDescription()->setCustomerAllowAddDescription($allowAddDescriptionStatus);
        }
    }
}
