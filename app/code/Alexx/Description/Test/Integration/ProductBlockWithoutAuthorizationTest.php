<?php
declare(strict_types=1);

namespace Alexx\Description\Test\Integration;

use Alexx\Description\Block\ProductBlock;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Registry;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

/**
 * @magentoAppArea frontend
 * @magentoDataFixture loadFixture
 */
class ProductBlockWithoutAuthorizationTest extends TestCase
{
    /**@var ProductInterface*/
    private static $fakeProduct;

    /**@var ObjectManagerInterface*/
    private $objectManager;

    /**@var ProductBlock*/
    private $testedBlock;

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();

        $this->objectManager->get(Registry::class)->unregister('current_product');
        $this->objectManager->get(Registry::class)->register('current_product', self::$fakeProduct);

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
    }

    /**
     * Test adding description limitation
     */
    public function testProductBlockReturningEmptyHtml()
    {
        $this->assertEmpty($this->testedBlock->toHtml());
    }

    /**
     * Creates fixtures for tests
     */
    public static function loadFixture()
    {
        include __DIR__ . '/_files/product.php';
        self::$fakeProduct = $product;
    }
}
