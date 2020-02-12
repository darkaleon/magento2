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
    private $loadedData;
    private $storeManager;
    private $dataPersistor;
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
        $this->storeManager = $storeManager;
        $this->dataPersistor = $dataPersistor;
        $this->collection = $mycollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $data);
    }

    /**
     * Adapt collection data to form data
     */
    public function getData()
    {
        $blogPostedForm = $this->dataPersistor->get('BlogPostForm');

        if ($blogPostedForm) {
            $this->loadedData[$blogPostedForm['entity_id']] = $blogPostedForm;
            $this->dataPersistor->clear('BlogPostForm');
        }

        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        foreach ($this->collection->getItems() as $blogPost) {

            $this->loadedData[$blogPost->getId()] = $blogPost->getData();

            if ($blogPost->getPicture()) {
                $this->loadedData[$blogPost->getId()]['picture'] = [['name'=>$blogPost->getPicture(),'url'=>$blogPost->getPicture()]];
            }
        }

        return $this->loadedData;
    }
}
