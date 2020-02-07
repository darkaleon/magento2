<?php

namespace Alexx\Blog\Model;

use Alexx\Blog\Model\ResourceModel\BlogPosts\CollectionFactory;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;
    protected $_loadedData;
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $data
     */
    public function __construct(
        CollectionFactory $mycollectionFactory,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $data);
        $this->collection = $mycollectionFactory->create();
    }

    public function getData()
    {
        if(isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        foreach($this->collection->getItems() as $blogPost)
        {
            $this->_loadedData[$blogPost->getId()] = $blogPost->getData();
        }
        return $this->_loadedData;
    }
}
