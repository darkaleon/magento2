<?php
declare(strict_types=1);

namespace Alexx\Description\Block\Adminhtml\Grid;

use Alexx\Description\Block\Adminhtml\Grid\Renderer\Product;
use Alexx\Description\Model\ResourceModel\Description\CollectionFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Magento\Customer\Controller\RegistryConstants;
use Magento\Framework\Registry;

/**
 * Adminhtml customer descriptions grid block
 */
class CustomerFormAllowAddDescriptionGrid extends Extended
{
    /**
     * Core registry
     *
     * @var Registry|null
     */
    protected $_coreRegistry = null;
    protected $_filterVisibility = false;

    /** @var CollectionFactory */
    protected $_collectionFactory;

    public function __construct(
        Context $context,
        Data $backendHelper,
        CollectionFactory $collectionFactory,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('allow_add_description_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('desc');
        $this->setUseAjax(true);
        $this->setEmptyText(__('No Newsletter Found'));
    }

    /**
     * @inheritdoc
     */
    protected function _prepareCollection()
    {
        $customerId = $this->getCurrentCustomerId();
        $collection = $this->_collectionFactory->create()->addCustomerFilter($customerId);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @inheritdoc
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            [
                'header' => __('ID'),
                'align' => 'left',
                'index' => 'entity_id',
                'width' => 10
            ]
        );
        $this->addColumn(
            'description',
            [
                'header' => __('Customer description'),
                'type' => 'text',
                'align' => 'center',
                'index' => 'description',
                'default' => ' ---- ',
            ]
        );
        $this->addColumn(
            'product_entity_id',
            [
                'header' => __('Product'),
                'type' => 'text',
                'align' => 'center',
                'index' => 'product_entity_id',
                'default' => ' ---- ',
                'renderer' => Product::class,

            ]
        );
        $this->addColumn(
            'created_at',
            [
                'header' => __('Create date'),
                'type' => 'datetime',
                'align' => 'center',
                'index' => 'created_at',
                'default' => ' ---- ',
            ]
        );
        $this->addColumn(
            'updated_at',
            [
                'header' => __('Update date'),
                'type' => 'datetime',
                'align' => 'center',
                'index' => 'updated_at',
                'default' => ' ---- ',
            ]
        );
        return parent::_prepareColumns();
    }

    /**
     * Get current customer id
     *
     * @return int
     */
    private function getCurrentCustomerId(): int
    {
        return (int)$this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
    }
}
