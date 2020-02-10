<?php
declare(strict_types=1);

namespace Alexx\Blog\Block\Adminhtml\Blog\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class that controls delete button on blog edit form
 */
class DeleteButton implements ButtonProviderInterface
{
    private $_context;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->_context = $context;
    }

    /**
     * Gets data for generation delete button
     *
     * @return array
     */
    public function getButtonData()
    {
        if ($this->_context->getRequest()->getParam('id')) {
            $id = $this->_context->getRequest()->getParam('id');
            $url = $this->_context->getUrlBuilder()->getUrl('*/*/delete', ['id' => $id]);
            return [
                'label' => __('Delete'),
                'on_click' => 'deleteConfirm(\'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $url . '\', {"data": {}})',
                'class' => 'delete'
            ];
        } else {
            return [];
        }
    }
}
