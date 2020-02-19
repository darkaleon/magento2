<?php
declare(strict_types=1);

namespace Alexx\Blog\Ui\Component\Listing\Column;

use Alexx\Blog\Api\Data\BlogInterface;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * ProductActions column
 */
class ProductActions extends Column
{
    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource)
    {
        if (!empty($dataSource['data']['items'])) {
            $storeId = $this->context->getFilterParam('store_id');
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')]['edit'] = [
                    'href' => $this->context->getUrl(
                        'blog/index/edit',
                        ['id' => $item[BlogInterface::FIELD_ID], 'store' => $storeId]
                    ),
                    'label' => __('Edit'),
                    'hidden' => false,
                    '__disableTmpl' => true
                ];
                $item[$this->getData('name')]['delete'] = [
                    'href' => $this->context->getUrl(
                        'blog/index/delete',
                        ['id' => $item[BlogInterface::FIELD_ID], 'store' => $storeId]
                    ),
                    'label' => __('Delete'),
                    'hidden' => false,
                    'post' => true,
                    'confirm' => [
                        'title' => __('Delete %1', $item[BlogInterface::FIELD_ID]),
                        'message' => __('Are you sure you want to delete  "%1" ?', $item[BlogInterface::FIELD_THEME]),
                    ],
                    '__disableTmpl' => true
                ];
            }
        }

        return $dataSource;
    }
}
