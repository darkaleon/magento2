<?php
declare(strict_types=1);

namespace Alexx\Blog\Model\Media;

use Alexx\Blog\Model\Media\Config as BlogMediaConfig;
use Alexx\Blog\Model\ResourceModel\BlogPosts\CollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class DataProvider for edit form
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;
    protected $_loadedData;
    private $_storeManager;
    private $_session;
    private $blogMediaConfig;

    /**
     * @param CollectionFactory $mycollectionFactory
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param StoreManagerInterface $storeManager
     * @param Session $session
     * @param BlogMediaConfig $blogMediaConfig
     * @param array $data
     */
    public function __construct(
        CollectionFactory $mycollectionFactory,
        $name,
        $primaryFieldName,
        $requestFieldName,
        StoreManagerInterface $storeManager,
        Session $session,
        BlogMediaConfig $blogMediaConfig,
        array $data = []
    ) {
        $this->blogMediaConfig = $blogMediaConfig;
        $this->_storeManager = $storeManager;
        $this->_session = $session;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $data);
        $this->collection = $mycollectionFactory->create();
    }

    /**
     * Adapt collection data to form data
     */
    public function getData()
    {
        $blogPostedForm=$this->_session->getBlogPostForm();

        if ($blogPostedForm) {
//            try {
                $this->_loadedData[$blogPostedForm["entity_id"]]=$blogPostedForm;
//            } catch (\Exception $e) {

//            }
            $this->_session->setBlogPostForm(null);
        }

        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }

        foreach ($this->collection->getItems() as $blogPost) {
            $this->_loadedData[$blogPost->getId()] = $blogPost->getData();
            if ($blogPost->getPicture()) {
                $this->_loadedData[$blogPost->getId()]['picture'][0]['name'] = $blogPost->getPicture();
                $this->_loadedData[$blogPost->getId()]['picture'][0]['url'] = $blogPost->getPicture();
            }
        }
        return $this->_loadedData;
    }
}
