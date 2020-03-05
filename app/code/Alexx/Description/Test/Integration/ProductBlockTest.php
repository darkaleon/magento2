<?php

namespace Alexx\Description\Test\Integration\Block;

use Alexx\Description\Api\DescriptionRepositoryInterface;
use Alexx\Description\Block\ProductBlock;
use Alexx\Description\Model\Config\CustomerAccessManagerToDescription;
use Magento\Catalog\Api\Data\ProductExtensionInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Registry;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\Customer\Api\CustomerRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Alexx\Description\Plugin\LoadAdditionalDescriptionExtensionAttributeProductPlugin;
use Magento\Catalog\Api\Data\ProductExtensionFactory;
use Alexx\Description\Api\Data\DescriptionInterfaceFactory;

/**
 * @magentoAppArea frontend
 * @magentoDataFixture loadFixture
 */
class ProductBlockTest extends TestCase
{

    private static $fakeCustomer;
    private static $fakeProduct;

    private $testedBlock;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;


    private $plugin;

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();

        $this->objectManager->get(Registry::class)->unregister('current_product');
        $this->objectManager->get(Registry::class)->register('current_product',self::$fakeProduct);

        $this->customerSession = $this->objectManager->get(Session::class);
        $this->customerSession->loginById(self::$fakeCustomer->getId());


        $this->testedBlock = $this->objectManager->get(ProductBlock::class);
        $templateForBlock = 'Alexx_Description::product_tab.phtml';
        $this->testedBlock->setTemplate($templateForBlock);
    }

    public function tearDown()
    {
        $this->objectManager->get(Registry::class)->unregister('current_product');
        $this->customerSession->logout();
    }

     public function testCustomerHasAllowAddDescriptionExtensionAttribute()
     {
         $this->assertNotEmpty($this->customerSession->getCustomer()->getAllowAddDescription());
     }

     public function testFrontendBlockDisplaysForm()
     {
         $this->assertContains('customer-description-form', $this->testedBlock->toHtml());
     }

     public function testFrontentBlockDisplaysSavedDescriptionData()
     {
         $this->assertContains('Fake description', $this->testedBlock->toHtml());
     }

    public function testPluginReactsOnDifferentConfigForCustomerStates()
    {
        $descriptionRepository = $this->objectManager->get(DescriptionRepositoryInterface::class);
        $extensionFactory = $this->objectManager->get(ProductExtensionFactory::class);
        $extension = $this->objectManager->get(ProductExtensionInterfaceFactory::class)->create();
        $descriptionFactory = $this->objectManager->get(DescriptionInterfaceFactory::class);

        $customerAccessManagerToDescription = $this->getMockBuilder(CustomerAccessManagerToDescription::class)->disableOriginalConstructor()->getMock();

        $this->plugin = new LoadAdditionalDescriptionExtensionAttributeProductPlugin($descriptionRepository, $extensionFactory, $customerAccessManagerToDescription, $descriptionFactory);

        $customerAccessManagerToDescription->expects($this->at(0))->method('isStorefront')->willReturn(false);
        $customerAccessManagerToDescription->expects($this->at(1))->method('isStorefront')->willReturn(true);
        $customerAccessManagerToDescription->expects($this->at(2))->method('getCustomerId')->willReturn('');
        $customerAccessManagerToDescription->expects($this->at(3))->method('isStorefront')->willReturn(true);
        $customerAccessManagerToDescription->expects($this->at(4))->method('getCustomerId')->willReturn(self::$fakeCustomer->getId());

        $this->assertNull($this->plugin->afterGetExtensionAttributes(self::$fakeProduct, $extension)->getAdditionalDescription());
        $this->assertNull($this->plugin->afterGetExtensionAttributes(self::$fakeProduct, $extension)->getAdditionalDescription());
        $result = $this->plugin->afterGetExtensionAttributes(self::$fakeProduct, $extension)->getAdditionalDescription();
        $this->assertEquals(self::$fakeProduct->getId(),$result->getProductEntityId());
        $this->assertEquals(self::$fakeCustomer->getId(),$result->getCustomerEntityId());
    }


    public static function loadFixture()
    {
        include __DIR__ . '/_files/customer.php';
        include __DIR__ . '/_files/product.php';
        include __DIR__ . '/_files/customer_product_description.php';

        self::$fakeCustomer=$customer;
        self::$fakeProduct=$product;
    }
}
