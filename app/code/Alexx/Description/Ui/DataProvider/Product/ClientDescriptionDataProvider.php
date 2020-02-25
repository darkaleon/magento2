<?php
declare(strict_types=1);

namespace Alexx\Description\Ui\DataProvider\Product;

use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Alexx\Description\Model\ResourceModel\Description\CollectionFactory;

/**
 * Retreive data for for customer_description_grid
 */
class ClientDescriptionDataProvider extends AbstractDataProvider
{
    /** * @var RequestInterface */
    private $request;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->request = $request;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $currentProductId = $this->request->getParam('current_product_id');

        $this->getCollection()->addFieldToFilter('product_entity_id', ['eq' => $currentProductId]);

        $resultItems = [
            'totalRecords' => $this->getCollection()->getSize(),
            'items' => [],
        ];

        foreach ($this->getCollection() as $item) {
            $resultItems['items'][] = $item->toArray([]);
        }
        return $resultItems;
    }
}
