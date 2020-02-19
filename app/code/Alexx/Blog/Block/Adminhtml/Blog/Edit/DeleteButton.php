<?php
declare(strict_types=1);

namespace Alexx\Blog\Block\Adminhtml\Blog\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\RequestInterface;

/**
 * Class that controls delete button on blog edit form
 */
class DeleteButton implements ButtonProviderInterface
{
    /**@var UrlInterface */
    private $urlInterface;

    /**@var RequestInterface */
    private $requestInterface;

    /**
     * @param UrlInterface $urlInterface
     * @param RequestInterface $requestInterface
     */
    public function __construct(UrlInterface $urlInterface, RequestInterface $requestInterface)
    {
        $this->urlInterface = $urlInterface;
        $this->requestInterface = $requestInterface;
    }

    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        $requestedId = $this->requestInterface->getParam('id');
        if ($requestedId) {
            $url = $this->urlInterface->getUrl('*/*/delete', ['id' => $requestedId]);
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
