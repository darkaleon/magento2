<?php

namespace Alexx\Description\Ui\DataProvider\Product;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Form\Fieldset;

class ClientDescriptionTab extends AbstractModifier
{
    const SAMPLE_FIELDSET_NAME = 'custom_grid_fieldset';
    const SAMPLE_FIELD_NAME = 'custom_grid';
    protected $_backendUrl;
    protected $_productloader;
    protected $_modelCustomgridFactory;
    /** * @var \Magento\Catalog\Model\Locator\LocatorInterface */
    protected $locator;
    /** * @var ArrayManager */
    protected $arrayManager;
    /** * @var UrlInterface */
    protected $urlBuilder;
    /** * @var array */
    protected $meta = [];

    /** * @param LocatorInterface $locator * @param ArrayManager $arrayManager * @param UrlInterface $urlBuilder */
    public function __construct(
        LocatorInterface $locator,
        ArrayManager $arrayManager,
        UrlInterface $urlBuilder,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Backend\Model\UrlInterface $backendUrl
    ) {
        $this->locator = $locator;
        $this->arrayManager = $arrayManager;
        $this->urlBuilder = $urlBuilder;
        $this->_productloader = $_productloader;
        $this->_backendUrl = $backendUrl;
    }

    public function modifyData(array $data)
    {
        return $data;
    }

    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;
        $this->addCustomTab();

        return $this->meta;
    }

    protected function addCustomTab()
    {
        $this->meta = array_merge_recursive(
            $this->meta,
            [
                static::SAMPLE_FIELDSET_NAME => $this->getTabConfig(),
            ]
        );
    }

    protected function getTabConfig()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Custom Grid Tab'),
                        'componentType' => Fieldset::NAME,
                        'dataScope' => '',
                        'provider' => static::FORM_NAME . '.product_form_data_source',
                        'ns' => static::FORM_NAME,
                        'collapsible' => true,
                    ],
                ],
            ],
            'children' => [
                static::SAMPLE_FIELD_NAME => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'autoRender' => true,
                                'componentType' => 'insertListing',
                                'dataScope' => 'customer_descriptions_grid_listing',
                                'externalProvider' => 'customer_descriptions_grid_listing.customer_descriptions_grid_listing_data_source',
                                'selectionsProvider' => 'customer_descriptions_grid_listing.customer_descriptions_grid_listing.product_columns.ids',
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
        ];
    }
}
