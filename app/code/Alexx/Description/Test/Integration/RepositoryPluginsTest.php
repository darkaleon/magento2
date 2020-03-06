<?php
declare(strict_types=1);

namespace Alexx\Description\Test\Integration;

use Alexx\Description\Api\AllowAddDescripitonRepositoryInterface;
use Alexx\Description\Model\AllowAddDescripitonRepository;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\DataObject;
use Magento\Framework\ObjectManagerInterface;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Alexx\Description\Api\DescriptionRepositoryInterface;

/**
 * Test methods that controls creation an deletion module extension attributes
 *
 * @magentoAppArea adminhtml
 * @magentoDataFixture loadFixture
 */
class RepositoryPluginsTest extends TestCase
{
    /**@var CustomerInterface */
    private static $fakeCustomer;

    /**@var ProductInterface */
    private static $fakeProduct;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**@var CustomerRepositoryInterface */
    private $customerRepository;

    /**@var ProductRepositoryInterface */
    private $productRepository;

    private $customerDescriptionRepository;

    private $customerAllowAddDescriptionRepository;

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();

        $this->customerRepository = $this->objectManager->get(CustomerRepositoryInterface::class);
        $this->productRepository = $this->objectManager->get(ProductRepositoryInterface::class);

        $this->customerDescriptionRepository = $this->objectManager->get(DescriptionRepositoryInterface::class);
        $this->customerAllowAddDescriptionRepository = $this->objectManager->get(AllowAddDescripitonRepository::class);
    }

    /**
     * Test extension attribute after changing customer email
     */
    public function testCustomerRepositoryPluginSavesNewEmail()
    {
        self::$fakeCustomer->setEmail('fake@email.com');
        $this->customerRepository->save(self::$fakeCustomer);
        $newExtensionAttributeData = self::$fakeCustomer->getExtensionAttributes()->getAllowAddDescription();
        $this->assertEquals('fake@email.com', $newExtensionAttributeData->getCustomerEmail());
    }

    /**
     * Test customer repository deleting plugins
     */
    public function testCustomerRepositoryPluginDeletesExtensionAttributeOnCustomerDelete()
    {
        $customerExtensionAttributeData = self::$fakeCustomer->getExtensionAttributes()->getAllowAddDescription();
        $productExtensionAttributeData = $this->customerDescriptionRepository->getByProductAndCustomer(
            (string)self::$fakeProduct->getId(),
            (string)self::$fakeCustomer->getId()
        );

        $this->assertNotEmpty($customerExtensionAttributeData);
        $this->assertNotEmpty($productExtensionAttributeData);

        $this->customerRepository->delete(self::$fakeCustomer);

        $this->expectException(\Magento\Framework\Exception\NoSuchEntityException::class);

        $productExtensionAttributeData = $this->customerDescriptionRepository->getByProductAndCustomer(
            (string)self::$fakeProduct->getId(),
            (string)self::$fakeCustomer->getId()
        );

        $this->assertEmpty($productExtensionAttributeData);

        $customerExtensionAttributeData =
            $this->customerAllowAddDescriptionRepository->getByCustomer(self::$fakeCustomer);

        $this->assertNull($customerExtensionAttributeData->getId());
    }

    /**
     * Test product repository deleting plugins
     */
    public function testProductRepositoryPluginDeletesExtensionAttributeOnProductDelete()
    {
        $productExtensionAttributeData = $this->customerDescriptionRepository->getByProductAndCustomer(
            (string)self::$fakeProduct->getId(),
            (string)self::$fakeCustomer->getId()
        );
        $this->assertNotEmpty($productExtensionAttributeData);

        $this->productRepository->delete(self::$fakeProduct);

        $this->expectException(\Magento\Framework\Exception\NoSuchEntityException::class);

        $productExtensionAttributeData = $this->customerDescriptionRepository->getByProductAndCustomer(
            (string)self::$fakeProduct->getId(),
            (string)self::$fakeCustomer->getId()
        );
        $this->assertEmpty($productExtensionAttributeData);
    }

    /**
     * Test product and customer repositories for deleting limitations
     */
    public function testCustomerRepositoryPluginNotDeletesOtherData()
    {
        $customerExtensionAttributeData = self::$fakeCustomer->getExtensionAttributes()->getAllowAddDescription();
        $productExtensionAttributeData = $this->customerDescriptionRepository->getByProductAndCustomer(
            (string)self::$fakeProduct->getId(),
            (string)self::$fakeCustomer->getId()
        );

        $newFakeCustomer = clone self::$fakeCustomer;
        $newFakeCustomer->setId(null);
        $newFakeCustomer->setEmail('another@email.com');
        $this->customerRepository->save($newFakeCustomer);

        $newCustomerExtensionAttributeData = clone $customerExtensionAttributeData;
        $newCustomerExtensionAttributeData->setEntityId(null);
        $newCustomerExtensionAttributeData->setCustomerEntityId((string)$newFakeCustomer->getId());
        $this->customerAllowAddDescriptionRepository->save($newCustomerExtensionAttributeData);

        $newProductExtensionAttributeData = clone $productExtensionAttributeData;
        $newProductExtensionAttributeData->setEntityId(null);
        $newProductExtensionAttributeData->setCustomerEntityId((string)$newFakeCustomer->getId());
        $newProductExtensionAttributeData->setProductEntityId('100');
        $this->customerDescriptionRepository->save($newProductExtensionAttributeData);

        $this->customerRepository->delete(self::$fakeCustomer);
        $this->productRepository->delete(self::$fakeProduct);

        $otherCustomerExtensionAttributeData =
            $this->customerAllowAddDescriptionRepository->getByCustomer($newFakeCustomer);
        $otherProductExtensionAttributeData =
            $this->customerDescriptionRepository->getByProductAndCustomer('100', (string)$newFakeCustomer->getId());

        $this->assertNotNull($otherCustomerExtensionAttributeData->getId());
        $this->assertNotEmpty($otherProductExtensionAttributeData);
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
