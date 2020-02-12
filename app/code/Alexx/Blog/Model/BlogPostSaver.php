<?php
declare(strict_types=1);

namespace Alexx\Blog\Model;

use Alexx\Blog\Api\BlogRepositoryInterface;
use Alexx\Blog\Api\Data\BlogInterface;
use Alexx\Blog\Model\Media\Config as BlogMediaConfig;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Class for create/edit BlogPost data row
 */
class BlogPostSaver
{
    /**@var BlogInterface  */
    private $model;

    /**@var Action*/
    private $currentAction;

    /**@var array*/
    private $formData = [];

    /**@var BlogMediaConfig*/
    private $blogMediaConfig;

    /**@var BlogRepository*/
    private $blogRepsitory;

    /**
     * @param BlogMediaConfig $blogMediaConfig
     * @param Action $currentAction
     * @param BlogRepositoryInterface $blogRepsitory
     * @param BlogInterface $model
     */
    public function __construct(
        BlogMediaConfig $blogMediaConfig,
        Action $currentAction,
        BlogRepositoryInterface $blogRepsitory,
        BlogInterface $model
    ) {
        $this->blogMediaConfig = $blogMediaConfig;
        $this->currentAction = $currentAction;
        $this->model = $model;
        $this->blogRepsitory = $blogRepsitory;
    }

    /**
     * Loads form data from form to model
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function loadFormData()
    {
        $dataFields = ['entity_id', 'theme', 'content', 'picture'];
        foreach ($dataFields as $fieldName) {
            if (!empty($this->currentAction->getRequest()->getParam($fieldName))) {
                $this->formData[$fieldName] = $this->currentAction->getRequest()->getParam($fieldName);
            }
        }
        $postId = $this->formData[$this->model::BLOG_ID] ?? null;

        if ($postId) {
            $this->model = $this->blogRepsitory->getById($postId);
        }
    }

    /**
     * Uploads image posted by form
     */
    public function loadPictureData($newFileUploader)
    {
        //Replace icon with fileuploader field name

        if (isset($this->formData['picture'][0]['file'])) {
            $newImgRelativePath=$newFileUploader->moveFileFromTmp($this->formData['picture'][0]['file'],true);
            $result=[
                'name'=>$newImgRelativePath,
                'url' => '/' . $this->blogMediaConfig->getBaseMediaDir() . '/' . $newImgRelativePath
            ];

            $this->formData['picture'][0] =$result;
        }
    }

    /**
     * Gets data from currently posted form
     *
     * @param string $field
     *
     * @return array
     */
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
        $this->model = $this->blogRepsitory->save($this->model);
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
        $formData['picture'] = $formData['picture'][0]['url'] ?? null;
        return $formData;
    }
}
