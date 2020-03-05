<?php
declare(strict_types=1);

namespace Alexx\Description\Test\Integration\Block;

use Alexx\Description\Api\Data\DescriptionInterfaceFactory;
use Alexx\Description\Api\DescriptionRepositoryInterface;
use Alexx\Description\Block\ProductBlock;
use Alexx\Description\Model\Config\CustomerAccessManagerToDescription;
use Alexx\Description\Plugin\LoadAdditionalDescriptionExtensionAttributeProductPlugin;
use Magento\Catalog\Api\Data\ProductExtensionFactory;
use Magento\Catalog\Api\Data\ProductExtensionInterfaceFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Registry;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

/**
 * @magentoAppArea frontend
 * @magentoDataFixture loadFixture
 */
class ProductBlockTest extends TestCase
{
    /**@var CustomerInterface*/
    private static $fakeCustomer;

    /**@var ProductInterface*/
    private static $fakeProduct;

    /**@var ProductBlock*/
    private $testedBlock;

    /**@var Session*/
    private $customerSession;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var LoadAdditionalDescriptionExtensionAttributeProductPlugin
     */
    private $plugin;

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();

        $this->objectManager->get(Registry::class)->unregister('current_product');
        $this->objectManager->get(Registry::class)->register('current_product', self::$fakeProduct);

        $this->customerSession = $this->objectManager->get(Session::class);
        $this->customerSession->loginById(self::$fakeCustomer->getId());

        $this->testedBlock = $this->objectManager->get(ProductBlock::class);
        $templateForBlock = 'Alexx_Description::product_tab.phtml';
        $this->testedBlock->setTemplate($templateForBlock);
    }

    /**
     * @inheritDoc
     */
    public function tearDown()
    {
        $this->objectManager->get(Registry::class)->unregister('current_product');
        $this->customerSession->logout();
    }

    /**
     * Customer session has needed extension attributes data
     */
    public function testCustomerHasAllowAddDescriptionExtensionAttribute()
    {
        $this->assertNotEmpty(
            $this->customerSession->getCustomerData()->getExtensionAttributes()->getAllowAddDescription()
        );
    }

    /**
     * Test for form presence in template result
     */
    public function testFrontendBlockDisplaysForm()
    {
        $this->assertContains('customer-description-form', $this->testedBlock->toHtml());
    }

    /**
     * Test for data presence in form
     */
    public function testFrontentBlockDisplaysSavedDescriptionData()
    {
        $this->assertContains('Fake description', $this->testedBlock->toHtml());
    }

    /**
     * Test plugin for data loading from db limitations
     */
    public function testPluginReactsOnDifferentConfigForCustomerStates()
    {
        $descriptionRepository = $this->objectManager->get(DescriptionRepositoryInterface::class);
        $extensionFactory = $this->objectManager->get(ProductExtensionFactory::class);
        $extension = $this->objectManager->get(ProductExtensionInterfaceFactory::class)->create();
        $descriptionFactory = $this->objectManager->get(DescriptionInterfaceFactory::class);

        $customerAccessManagerToDescription = $this->getMockBuilder(CustomerAccessManagerToDescription::class)
            ->disableOriginalConstructor()->getMock();

        $this->plugin = new LoadAdditionalDescriptionExtensionAttributeProductPlugin(
            $descriptionRepository,
            $extensionFactory,
            $customerAccessManagerToDescription,
            $descriptionFactory
        );

        $customerAccessManagerToDescription->expects($this->at(0))
            ->method('isStorefront')->willReturn(false);
        $customerAccessManagerToDescription->expects($this->at(1))
            ->method('isStorefront')->willReturn(true);
        $customerAccessManagerToDescription->expects($this->at(2))
            ->method('getCustomerId')->willReturn('');
        $customerAccessManagerToDescription->expects($this->at(3))
            ->method('isStorefront')->willReturn(true);
        $customerAccessManagerToDescription->expects($this->at(4))
            ->method('getCustomerId')->willReturn(self::$fakeCustomer->getId());

        $this->assertNull($this->plugin->afterGetExtensionAttributes(self::$fakeProduct, $extension)
            ->getAdditionalDescription());
        $this->assertNull($this->plugin->afterGetExtensionAttributes(self::$fakeProduct, $extension)
            ->getAdditionalDescription());
        $result = $this->plugin->afterGetExtensionAttributes(self::$fakeProduct, $extension)
            ->getAdditionalDescription();
        $this->assertEquals(self::$fakeProduct->getId(), $result->getProductEntityId());
        $this->assertEquals(self::$fakeCustomer->getId(), $result->getCustomerEntityId());
    }

    /**
     * Creates fixtures for tests
     */
    public static function loadFixture()
    {
        include __DIR__ . '/_files/customer.php';
        include __DIR__ . '/_files/product.php';
        include __DIR__ . '/_files/customer_product_description.php';

        self::$fakeCustomer = $customer;
        self::$fakeProduct = $product;
    }
}
