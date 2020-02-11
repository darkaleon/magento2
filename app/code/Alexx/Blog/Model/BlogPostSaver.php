<?php
declare(strict_types=1);

namespace Alexx\Blog\Model;

use Alexx\Blog\Api\BlogRepositoryInterfaceFactory;
use Alexx\Blog\Api\Data\BlogInterface;
use Alexx\Blog\Model\Media\Config as BlogMediaConfig;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class for create/edit BlogPost data row
 * */
class BlogPostSaver
{
    /**@var BlogInterface  */
    private $model;

    /**@var PictureSaver  */
    private $pictureSaver;

    /**@var Action*/
    private $_currentAction;

    /**@var array*/
    private $formData = [];

    /**@var BlogMediaConfig*/
    private $blogMediaConfig;

    /**@var BlogRepository*/
    private $_blogRepsitoryFactory;

    /**
     * @param PictureSaver $pictureSaver
     * @param BlogMediaConfig $blogMediaConfig
     * @param Action $currentAction
     * @param BlogRepositoryInterfaceFactory $blogRepsitoryFactory
     * @param BlogInterface $model
     */
    public function __construct(
        PictureSaver $pictureSaver,
        BlogMediaConfig $blogMediaConfig,
        Action $currentAction,
        BlogRepositoryInterfaceFactory $blogRepsitoryFactory,
        BlogInterface $model
    ) {
        $this->blogMediaConfig = $blogMediaConfig;
        $this->_currentAction = $currentAction;
        $this->model = $model;
        $this->pictureSaver = $pictureSaver;
        $this->_blogRepsitoryFactory = $blogRepsitoryFactory->create();
    }

    /**
     * Loads form data from form to model
     *
     * @throws NoSuchEntityException
     **/
    public function loadFormData()
    {
        $dataFields = ['entity_id', 'theme', 'content', 'picture'];
        foreach ($dataFields as $fieldName) {
            if (!empty($this->_currentAction->getRequest()->getParam($fieldName))) {
                $this->formData[$fieldName] = $this->_currentAction->getRequest()->getParam($fieldName);
            }
        }
        $postId = $this->formData[$this->model::BLOG_ID] ?? null;

        if ($postId) {
            $this->model = $this->_blogRepsitoryFactory->getById($postId);
        }
    }

    /**
     * Uploads image posted by form
     **/
    public function loadPictureData()
    {
        //Replace icon with fileuploader field name

        if (isset($this->formData['picture'][0]['file'])) {
            $this->formData['picture'][0] = $this->pictureSaver->uploadImage($this->formData['picture'][0]);
        }
    }

    /**
     * Gets data from currently posted form
     *
     * @param string $field
     *
     * @return array
     **/
    public function getFormData($field = null)
    {
        if ($field) {
            if (array_key_exists($field, $this->formData)) {
                return $this->formData[$field];
            } else {
                return null;
            }
        }
        return $this->formData;
    }

    /**
     * Saves model data to db
     *
     * @return void|bool
     *
     * @throws CouldNotSaveException
     */
    public function save()
    {
        $this->model->setData($this->adaptFormData($this->formData));
        if (!$this->model->getId()) {
            $this->model->setData('entity_id', null);
        }
        $this->model = $this->_blogRepsitoryFactory->save($this->model);
        return $this->model->getId();
    }

    /**
     * Returns parsed form data
     *
     * @param array $formData
     *
     * @return array
     */
    public function adaptFormData($formData)
    {
        if (isset($formData['picture'])) {
            $formData['picture'] = $formData['picture'][0]['url'];
        } else {
            $formData['picture'] = null;
        }
        return $formData;
    }
}
