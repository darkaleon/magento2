<?php

namespace Alexx\Blog\Block\Adminhtml;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Backend\Block\Widget\Context as WidgetContext;
use Magento\Framework\Registry;

/**
 * Block for blog_index_edit layout.
 */
class Edit extends Container
{
    /**
     * Core registry
     *
     * @var Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    protected $_objectId = 'id';

    protected $_controller = 'adminhtml_index';

    protected $_blockGroup = 'Alexx_Blog';

    /**
     * @param WidgetContext $context
     * @param Registry $registry
     * @param array $data
     *
     * @return void
     */
    public function __construct(
        WidgetContext $context,
        Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => [
                            'event' => 'saveAndContinueEdit',
                            'target' => '#edit_form'
                        ]
                    ]
                ]
            ],
            -100
        );
        $this->buttonList->update('delete', 'label', __('Delete'));
    }
}
