<?php


namespace Alexx\Description\Test\Integration;

use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Registry;
use Alexx\Description\Block\ProductBlock;

/**
 * @magentoAppArea frontend
 * @magentoDataFixture loadFixture
 */
class ProductBlockWithoutAuthorizationTest extends TestCase
{
    private static $fakeProduct;
    private $objectManager;
    private $testedBlock;
    private $productRepository;


    public function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();

        $this->objectManager->get(Registry::class)->unregister('current_product');
        $this->objectManager->get(Registry::class)->register('current_product', self::$fakeProduct);

        $this->testedBlock = $this->objectManager->get(ProductBlock::class);
        $templateForBlock = 'Alexx_Description::product_tab.phtml';
        $this->testedBlock->setTemplate($templateForBlock);
    }
    public function tearDown()
    {
        $this->objectManager->get(Registry::class)->unregister('current_product');
    }
    public function testProductBlockReturningEmptyHtml(){
        $this->assertEmpty( $this->testedBlock->toHtml());
    }

    public static function loadFixture()
    {
        include __DIR__ . '/_files/product.php';
        self::$fakeProduct=$product;

    }
}
