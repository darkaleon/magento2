<?php
declare(strict_types=1);

namespace Alexx\Description\Ui\DataProvider\Product;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Form\Fieldset;

/**
 * Adminhtml catalog/product/edit form page modificator
 */
class ClientDescriptionTab extends AbstractModifier
{
    const FIELDSET_NAME = 'customer_description_grid_fieldset';
    const FIELD_NAME = 'customer_description_grid';

    /**@var UrlInterface */
    private $urlBuilder;

    /**@var LocatorInterface */
    private $locator;

    /**@var array */
    private $meta = [];

    /**
     * @param UrlInterface $urlBuilder
     * @param LocatorInterface $locator
     */
    public function __construct(UrlInterface $urlBuilder, LocatorInterface $locator)
    {
        $this->locator = $locator;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @inheritDoc
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;
        if ($this->locator->getProduct()->getId()) {
            $this->addCustomTab();
        }
        return $this->meta;
    }

    /**
     * Adding custom tab on adminhtml edit product page. Also, configures content of this tab
     */
    private function addCustomTab()
    {
        $this->meta = array_merge_recursive(
            $this->meta,
            [static::FIELDSET_NAME => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Additional customer descripiton'),
                            'componentType' => Fieldset::NAME,
                            'dataScope' => '',
                            'provider' => static::FORM_NAME . '.product_form_data_source',
                            'ns' => static::FORM_NAME,
                            'collapsible' => true,
                        ],
                    ],
                ],
                'children' => [
                    static::FIELD_NAME => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'autoRender' => true,
                                    'componentType' => 'insertListing',
                                    'dataScope' => 'customer_descriptions_grid_listing',
                                    'externalProvider' => 'customer_descriptions_grid_listing.' .
                                        'customer_descriptions_grid_listing_data_source',
                                    'selectionsProvider' => 'customer_descriptions_grid_listing.' .
                                        'customer_descriptions_grid_listing.product_columns.ids',
                                    'ns' => 'customer_descriptions_grid_listing',
                                    'render_url' => $this->urlBuilder->getUrl('mui/index/render'),
                                    'realTimeLink' => false,
                                    'behaviourType' => 'simple',
                                    'externalFilterMode' => true,
                                    'imports' => [
                                        'productId' => '${ $.provider }:data.product.current_product_id'
                                    ],
                                    'exports' => [
                                        'productId' => '${ $.externalProvider }:params.current_product_id'
                                    ],

                                ],
                            ],
                        ],
                        'children' => [],
                    ],
                ],
            ],
            ]
        );
    }
}
