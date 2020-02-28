<?php

namespace Alexx\Description\Test\Integration\Block;

use Alexx\Description\Block\ProductBlock;
use PHPUnit\Framework\TestCase;
use Magento\TestFramework\Helper\Bootstrap;
use Alexx\Description\Api\DescriptionRepositoryInterface;

/**
 * @magentoAppArea frontend
 * @magentoDataFixture loadFixture
*/
class ProductBlockTest extends TestCase
{
    private $block;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Magento\Persistent\Helper\Session
     */
    protected $_persistentSessionHelper;

    public function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();


        $productRepository = $this->objectManager->create(\Magento\Catalog\Api\ProductRepositoryInterface::class);
        $product = $productRepository->getById(1);

//        $descriptionRepository =$this->objectManager->create(DescriptionRepositoryInterface::class);




//        $productPlugin =$this->objectManager->get(\Alexx\Description\Plugin\ProductPlugin::class);
//        $pluginExtensionAttribute = $productPlugin->afterGetExtensionAttributes($product);
//        var_dump($pluginExtensionAttribute);exit();


//        $extension = $product->getExtensionAttributes();
//        var_dump(get_class($extension));exit();
//        $this->assertInstanceOf(Magento\Catalog\Api\Data\ProductExtension::class,$extension);
        var_dump(array_keys($product->getExtensionAttributes()->__toArray()));exit();//->getAdditionalDescription()

        $this->objectManager->get(\Magento\Framework\Registry::class)->unregister('current_product');
        $this->objectManager->get(\Magento\Framework\Registry::class)->register('current_product', $product);


//        /** @var \Magento\Persistent\Helper\Session $persistentSessionHelper */
//        $this->_persistentSessionHelper = $this->objectManager->create(\Magento\Persistent\Helper\Session::class);
        $this->customerSession = $this->objectManager->get(\Magento\Customer\Model\Session::class);

        $this->block = $this->objectManager->get(ProductBlock::class);
    }

    public function testToHtml(){

        $this->customerSession->loginById(1);

        $template = 'Alexx_Description::product_tab.phtml';
        $this->block->setTemplate($template);
        $html = $this->block->toHtml();
        var_dump($html);
        var_dump($this->customerSession->getCustomer()->getAllowAddDescription());
        $this->customerSession->logout();
    }

    public static function loadFixture(){
        include __DIR__.'/_files/customer_and_product.php';
    }
}
