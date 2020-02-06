<?php

namespace Alexx\Blog\Model;

use Alexx\Blog\Api\BlogInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Model\AbstractModel;

/**
 * Class for create/edit BlogPost data row
 * */
class BlogPostSaver
{
    /**@var BlogInterface $model */
    private $model;
    private $pictureSaver;
    private $_currentAction;
    private $formData;
    private $postDataField;
    private $pictureDataField;

    /**
     * @param PictureSaver $pictureSaver
     * @param Action $currentAction
     * @param BlogInterface $model
     * @param string $postDataField
     * @param string $pictureDataField
     */
    public function __construct(
        PictureSaver $pictureSaver,
        Action $currentAction,
        BlogInterface $model,
        $postDataField,
        $pictureDataField
    ) {
        $this->postDataField = $postDataField;
        $this->pictureDataField = $pictureDataField;
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
        $this->formData = $this->_currentAction->getRequest()->getParam($this->postDataField);
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
        $currentPicture = $this->model->getPicture();
        $picturePostData = $this->_currentAction->getRequest()->getParam($this->pictureDataField);
        $picturePostFiles = $this->_currentAction->getRequest()->getFiles($this->pictureDataField);
        if (!$picturePostData) {
            $picturePostData = [];
        }
        $this->pictureSaver->create($this->pictureDataField);
        $this->formData['picture'] =
            $this->pictureSaver->uploadImage($currentPicture, $picturePostData, $picturePostFiles);
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
     **/
    public function save()
    {
        $this->model->setData($this->formData);
        try {
            // Save news
            $this->model->save();
            // Display success message
            if ($this->pictureSaver) {
                $this->pictureSaver->clearOnSuccess();
            }
            return $this->model->getId();
        } catch (\Exception $e) {
            if ($this->pictureSaver) {
                $this->pictureSaver->clearOnError();
            }
            throw $e;
        }
        return false;
    }
}
