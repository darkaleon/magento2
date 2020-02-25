<?php


namespace Alexx\Description\Plugin;

use Alexx\Description\Model\Search\SearchDescription;
use Magento\Catalog\Api\Data\ProductExtensionInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductExtensionFactory;
use Magento\Customer\Model\Session;
use  Alexx\Description\Model\Config\ConfigForCustomer;

class ProductPlugin
{

    /**
     * @var ProductExtensionFactory
     */
    private $extensionFactory;
    protected $customerSession;
    private $searchDescription;
    private $configForCustomer;

    /**
     * @param ProductExtensionFactory $extensionFactory
     */
    public function __construct(
        SearchDescription $searchDescription,
        ProductExtensionFactory $extensionFactory,
        Session $customerSession,
        ConfigForCustomer $configForCustomer
    )
    {
        $this->configForCustomer = $configForCustomer;
        $this->customerSession = $customerSession;
        $this->searchDescription = $searchDescription;

        $this->extensionFactory = $extensionFactory;
    }

    /**
     * Loads product entity extension attributes
     *
     * @param ProductInterface $entity
     * @param ProductExtensionInterface|null $extension
     * @return ProductExtensionInterface
     */
    public function afterGetExtensionAttributes(
        ProductInterface $entity,
        ProductExtensionInterface $extension = null
    )
    {
        if ($extension === null) {
            $extension = $this->extensionFactory->create();
        }


        if ($extension->getAdditionalDescription() === null) {
            if ($this->configForCustomer->isFront()) {
                $extension->setAdditionalDescription($this->loadData($entity));
            }
        }

        return $extension;
    }


    public function loadData($entity)
    {

        $product_id = $entity->getId();
        $customer_id = $this->configForCustomer->getCustomerId();


        if ($customer_id != null) {
            try {
                $model = $this->searchDescription->searchOne($product_id, $customer_id);
                if($model){
                    $ourCustomData = new \Magento\Framework\DataObject($this->searchDescription->searchOne($product_id, $customer_id)->getData());
                }else{
                    $ourCustomData = null;
                }

            } catch (\Exception $e) {

                var_dump('Exception message 2 =');

                var_dump($e->getMessage());
            }

            return $ourCustomData;


        }
    }
    /*
    public function loadAdminData($entity)
    {
        $product_id = $entity->getId();
        try {
            foreach ($this->searchDescription->searchAllList($product_id) as $d) {
                $ourCustomData [] = new \Magento\Framework\DataObject($d->getData());
            }
        } catch (\Exception $e) {
            var_dump('Exception message 1 =');
            var_dump($e->getMessage());
        }

        return $ourCustomData;
    }*/

}
