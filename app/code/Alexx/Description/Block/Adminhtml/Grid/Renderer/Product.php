<?php
declare(strict_types=1);

namespace Alexx\Description\Block\Adminhtml\Grid\Renderer;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;

/**
 * Adminhtml customer descriptions grid block Product item renderer
 */
class Product extends AbstractRenderer
{

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /**
     * @param Context $context
     * @param ProductRepositoryInterface $productRepository
     * @param array $data
     */
    public function __construct(Context $context, ProductRepositoryInterface $productRepository, array $data = [])
    {
        $this->productRepository = $productRepository;
        parent::__construct($context, $data);
    }

    /**
     * @inheritDoc
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $product = $this->productRepository->getById($row->getProductEntityId());

        return '<a href="' . $this->getUrl('catalog/product/edit', ['id' => $product->getId()]) . '" target="_blank">' . $product->getName() . '</a>';
    }
}
