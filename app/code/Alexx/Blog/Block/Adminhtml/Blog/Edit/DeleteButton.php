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
    /**@var Context */
    private $context;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        if ($this->context->getRequest()->getParam('id')) {
            $id = $this->context->getRequest()->getParam('id');
            $url = $this->context->getUrlBuilder()->getUrl('*/*/delete', ['id' => $id]);
            return [
                'label' => __('Delete'),
                'on_click' => 'deleteConfirm(\'' .
                    __('Are you sure you want to do this?') .
                    '\', \'' . $url . '\', {"data": {}})',
                'class' => 'delete'
            ];
        } else {
            return [];
        }
    }
}
