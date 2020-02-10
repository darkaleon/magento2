<?php
declare(strict_types=1);

namespace Alexx\Blog\Model;

use Alexx\Blog\Api\BlogInterface;
use Alexx\Blog\Model\Media\Config as BlogMediaConfig;
use Magento\Backend\App\Action;

/**
 * Class for create/edit BlogPost data row
 * */
class BlogPostSaver
{
    /**@var BlogInterface $model */
    private $model;
    private $pictureSaver;
    private $_currentAction;
    private $formData = [];
    private $blogMediaConfig;

    /**
     * @param PictureSaver $pictureSaver
     * @param BlogMediaConfig $blogMediaConfig
     * @param Action $currentAction
     * @param BlogInterface $model
     */
    public function __construct(
        PictureSaver $pictureSaver,
        BlogMediaConfig $blogMediaConfig,
        Action $currentAction,
        BlogInterface $model
    ) {
        $this->blogMediaConfig = $blogMediaConfig;
        $this->_currentAction = $currentAction;
        $this->model = $model;
        $this->pictureSaver = $pictureSaver;
    }

    /**
     * Loads form data from form to model
     *
     * @return bool
     **/
    public function loadFormData()
    {
        $dataFields=['entity_id','theme','content','picture'];
        foreach ($dataFields as $fieldName) {
            if (!empty($this->_currentAction->getRequest()->getParam($fieldName))) {
                $this->formData[$fieldName]=$this->_currentAction->getRequest()->getParam($fieldName);
            }
        }
        $postId = $this->formData[$this->model::BLOG_ID] ?? null;

        if ($postId) {
            $this->model->load($postId);

            if (empty($this->model->getData())) {
                return false;
            }
        }

        return true;
    }

    /**
     * Uploads  image posted by form
     *
     * @return void
     **/
    public function loadPictureData()
    {
        //Replace icon with fileuploader field name

        if (isset($this->formData['picture'][0]['file'])) {
            $this->formData['picture'][0]= $this->pictureSaver->uploadImage($this->formData['picture'][0]);
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
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function save()
    {
        $this->model->setData($this->adaptFormData($this->formData));
        if (!$this->model->getId()) {
            $this->model->setData('entity_id', null);
        }
        try {
            // Save news
            $this->model->save();
            return $this->model->getId();
        } catch (\Exception $e) {
            throw $e;
        }
        return false;
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
            $formData['picture']=$formData['picture'][0]['url'];
        } else {
            $formData['picture']=null;
        }
        return $formData;
    }
}
