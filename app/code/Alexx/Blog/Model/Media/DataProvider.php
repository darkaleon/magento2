<?php
declare(strict_types=1);

namespace Alexx\Blog\Model\Media;

use Alexx\Blog\Api\Data\BlogInterface;
use Alexx\Blog\Model\Media\Config as BlogMediaConfig;
use Alexx\Blog\Model\ResourceModel\BlogPosts\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class DataProvider for edit form
 */
class DataProvider extends AbstractDataProvider
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
     * @param CollectionFactory $myCollectionFactory
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param StoreManagerInterface $storeManager
     * @param DataPersistorInterface $dataPersistor
     * @param BlogMediaConfig $blogMediaConfig
     * @param array $data
     */
    public function __construct(
        CollectionFactory $myCollectionFactory,
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        StoreManagerInterface $storeManager,
        DataPersistorInterface $dataPersistor,
        BlogMediaConfig $blogMediaConfig,
        array $data = []
    ) {
        $this->blogMediaConfig = $blogMediaConfig;
        $this->storeManager = $storeManager;
        $this->dataPersistor = $dataPersistor;
        $this->collection = $myCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $data);
    }

    /**
     * Adapt collection data to form data
     */
    public function getData()
    {
        /**@var array $blogPostedForm */
        $blogPostedForm = $this->dataPersistor->get('BlogPostForm');

        if ($blogPostedForm) {
            $this->loadedData[$blogPostedForm[BlogInterface::FIELD_ID]] = $blogPostedForm;
            $this->dataPersistor->clear('BlogPostForm');
        }

        if ($this->loadedData === null) {

            /** @var \Alexx\Blog\Model\BlogPosts $blogPost */
            foreach ($this->collection->getItems() as $blogPost) {
                $dataToEdit = $blogPost->getData();

                unset($dataToEdit[BlogInterface::FIELD_CREATED_AT]);
                unset($dataToEdit[BlogInterface::FIELD_UPDATED_AT]);
                if ($blogPost->getPicture()) {
                    $dataToEdit[BlogInterface::FIELD_PICTURE] =
                        $this->blogMediaConfig->convertPictureForUploader($blogPost->getPicture());
                }
                $this->loadedData[$blogPost->getId()] = $dataToEdit;
            }
        }

        return $this->loadedData;
    }
}
