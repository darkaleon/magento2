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
    /**@var array */
    private $loadedData;

    /**@var StoreManagerInterface */
    private $storeManager;

    /**@var DataPersistorInterface */
    private $dataPersistor;

    /**@var BlogMediaConfig */
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
        /**@var array $blogPostedForm*/
        $blogPostedForm = $this->dataPersistor->get('BlogPostForm');

        if ($blogPostedForm) {
            $this->loadedData[$blogPostedForm['entity_id']] = $blogPostedForm;
            $this->dataPersistor->clear('BlogPostForm');
        }

        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        /** @var \Alexx\Blog\Model\BlogPosts $blogPost */
        foreach ($this->collection->getItems() as $blogPost) {
            $dataToEdit = $blogPost->getData();

            unset($dataToEdit['created_at']);
            unset($dataToEdit['updated_at']);
            if ($blogPost->getPicture()) {
                $dataToEdit['picture'] = [['name' => $blogPost->getPicture(), 'url' => $blogPost->getPicture()]];
            }

            $this->loadedData[$blogPost->getId()] = $dataToEdit;
        }

        return $this->loadedData;
    }
}
