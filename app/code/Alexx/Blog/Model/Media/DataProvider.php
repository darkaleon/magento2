<?php
declare(strict_types=1);

namespace Alexx\Blog\Model\Media;

use Alexx\Blog\Model\Media\Config as BlogMediaConfig;
use Alexx\Blog\Model\ResourceModel\BlogPosts\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class DataProvider for edit form
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;
    protected $_loadedData;
    private $_storeManager;
    private $_dataPersistor;
    private $blogMediaConfig;

    /**
     * @param CollectionFactory $mycollectionFactory
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param StoreManagerInterface $storeManager
     * @param DataPersistorInterface $dataPersistor
     * @param BlogMediaConfig $blogMediaConfig
     * @param array $data
     */
    public function __construct(
        CollectionFactory $mycollectionFactory,
        $name,
        $primaryFieldName,
        $requestFieldName,
        StoreManagerInterface $storeManager,
        DataPersistorInterface $dataPersistor,
        BlogMediaConfig $blogMediaConfig,
        array $data = []
    ) {
        $this->blogMediaConfig = $blogMediaConfig;
        $this->_storeManager = $storeManager;
        $this->_dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $data);
        $this->collection = $mycollectionFactory->create();
    }

    /**
     * Adapt collection data to form data
     */
    public function getData()
    {
        $blogPostedForm=$this->_dataPersistor->get('BlogPostForm');

        if ($blogPostedForm) {
            $this->_loadedData[$blogPostedForm["entity_id"]]=$blogPostedForm;
            $this->_dataPersistor->clear('BlogPostForm');
        }

        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }

        foreach ($this->collection->getItems() as $blogPost) {

            $this->_loadedData[$blogPost->getId()] = $blogPost->getData();

            if ($blogPost->getPicture()) {
                $this->_loadedData[$blogPost->getId()]['picture'] = [0=>[]];

                $this->_loadedData[$blogPost->getId()]['picture'][0]['name'] = $blogPost->getPicture();
                $this->_loadedData[$blogPost->getId()]['picture'][0]['url'] = $blogPost->getPicture();
            }
        }

        return $this->_loadedData;
    }
}
