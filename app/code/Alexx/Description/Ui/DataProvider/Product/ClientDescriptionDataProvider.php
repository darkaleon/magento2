<?php

namespace Alexx\Description\Ui\DataProvider\Product;


use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Alexx\Description\Model\ResourceModel\Description\CollectionFactory;
use Alexx\Description\Model\ResourceModel\Description\Collection;
use Alexx\Description\Model\Description;
use Magento\Catalog\Model\Locator\RegistryLocator;


class ClientDescriptionDataProvider extends AbstractDataProvider
{
    /** * @var CollectionFactory */
    protected $collectionFactory;
    /** * @var RequestInterface */
    protected $request;
    private $productRegistryLocator;

    /** * @param string $name * @param string $primaryFieldName * @param string $requestFieldName * @param CollectionFactory $collectionFactory * @param RequestInterface $request * @param array $meta * @param array $data */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        RegistryLocator $productRegistryLocator,
        array $meta = [],
        array $data = []
    )
    {
        $this->productRegistryLocator = $productRegistryLocator;
        $this->collectionFactory = $collectionFactory;
        $this->collection = $this->collectionFactory->create();
        $this->request = $request;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $current_product_id = $this->request->getParam('current_product_id');

        $this->getCollection()->addFieldToFilter('product_entity_id', ['eq' => $current_product_id]);

        $arrItems = [
            'totalRecords' => $this->getCollection()->getSize(),
            'items' => [],
        ];

        foreach ($this->getCollection() as $item) {
            $arrItems['items'][] = $item->toArray([]);
        }
//        var_dump($arrItems);exit();

        return $arrItems;
    }

    /**
     * {@inheritdoc}
     */
//    public function addFilter(\Magento\Framework\Api\Filter $filter)
//    {
//        $field = $filter->getField();
//
//
////        var_dump($field);exit();
////        var_dump( $filter->getValue());exit();
//        if (in_array($field, ['entity_id'])) { //, 'title', 'created_at', 'is_active'
//            $filter->setField($field);
//        }
//
//
//        parent::addFilter($filter);
//    }
}
