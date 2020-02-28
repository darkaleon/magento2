<?php

namespace Alexx\Description\Test\Integration\Block;

use Alexx\Description\Block\ProductBlock;
use Alexx\Description\Api\DescriptionRepositoryInterface;
use Alexx\Description\Api\Data\DescriptionInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Registry;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\Customer\Api\CustomerRepositoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * @magentoAppArea frontend
 * @magentoDataFixture loadFixture
 */
class ProductBlockTest extends TestCase
{
    private $testedBlock;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;
    private $productRepository;

    private $fakeProduct;
    private $fakeCustomer;

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();

        $this->productRepository = $this->objectManager->create(ProductRepositoryInterface::class);
        $this->fakeProduct = $this->productRepository->getById(1);

        $this->objectManager->get(Registry::class)->unregister('current_product');
        $this->objectManager->get(Registry::class)->register('current_product', $this->fakeProduct);
        $this->fakeCustomer = $this->objectManager->get(CustomerRepositoryInterface::class)->getById(1);

        $this->customerSession = $this->objectManager->get(Session::class);
        $this->customerSession->loginById($this->fakeCustomer->getId());

        $customerDescriptionRepository = $this->objectManager->get(DescriptionRepositoryInterface::class);
        $customerDescriptionFactory = $this->objectManager->get(DescriptionInterfaceFactory::class);

        $newCustomerProductDescription = $customerDescriptionFactory->create();
        $newCustomerProductDescription->setProductEntityId($this->fakeProduct->getId());
        $newCustomerProductDescription->setCustomerEntityId($this->fakeCustomer->getId());
        $newCustomerProductDescription->setDescription('Fake description');
        $customerDescriptionRepository->save($newCustomerProductDescription);


        $this->testedBlock = $this->objectManager->get(ProductBlock::class);
        $templateForBlock = 'Alexx_Description::product_tab.phtml';
        $this->testedBlock->setTemplate($templateForBlock);
    }

    public function tearDown()
    {
        $this->customerSession->logout();
    }

    public function testFrontendBlockDisplaysForm()
    {
        $this->assertContains('customer-description-form', $this->testedBlock->toHtml());
    }

    public function testFrontentBlockDisplaysSavedDescriptionData()
    {
        $this->assertContains('Fake description', $this->testedBlock->toHtml());
    }

    public static function loadFixture()
    {
        include __DIR__ . '/_files/customer_and_product.php';
    }
}
