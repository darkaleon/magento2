<?php

namespace Alexx\Blog\Ui\Component\Listing\Column;

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
        if (isset($dataSource['data']['items'])) {
            $storeId = $this->context->getFilterParam('store_id');
            foreach ($dataSource['data']['items'] as &$item) {

                $item[$this->getData('name')]['editform'] = [
                    'href' => $this->context->getUrl(
                        'blog/index/editform',
                        ['id' => $item['entity_id'], 'store' => $storeId]
                    ),
                    'label' => __('EditForm'),
                    'hidden' => false,
                    '__disableTmpl' => true
                ];

                $item[$this->getData('name')]['edit'] = [
                    'href' => $this->context->getUrl(
                        'blog/index/edit',
                        ['id' => $item['entity_id'], 'store' => $storeId]
                    ),
                    'label' => __('Edit'),
                    'hidden' => false,
                    '__disableTmpl' => true
                ];
                $item[$this->getData('name')]['delete'] = [
                    'href' => $this->context->getUrl(
                        'blog/index/delete',
                        ['id' => $item['entity_id'], 'store' => $storeId]
                    ),
                    'label' => __('Delete'),
                    'hidden' => false,
                    'post' => true,
                    'confirm' => [
                        'title' => __('Delete %1', $item['entity_id']),
                        'message' =>__('Are you sure you want to delete  "%1" ?', $item['theme']),
                    ],
                    '__disableTmpl' => true
                ];
            }
        }
        return $dataSource;
    }
}
