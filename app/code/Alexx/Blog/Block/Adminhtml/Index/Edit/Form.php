<?php

namespace Alexx\Blog\Block\Adminhtml\Index\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Framework\App\Action\Context as ActionContext;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

/**
 * Generates form with specific configuration
 */
class Form extends Generic
{
    const DATA_FIELD_NAME = 'blog_data';
    const PICTURE_FIELD_NAME = 'blog_picture';
    const MODEL_REGISTY_NAME = 'blognews';
    private $_wysiwygConfig;
    private $_objectManager;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param ActionContext $actionContext
     * @param array $data
     *
     * @return void
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        ActionContext $actionContext,
        array $data = []
    ) {
        $this->_objectManager = $actionContext->getObjectManager();
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Form fields options
     *
     * @return \Magento\Backend\Block\Widget\Form
     * */
    protected function _prepareForm()
    {
        /** @var Magento\Framework\Data\Form $form */
        $model = $this->_coreRegistry->registry(self::MODEL_REGISTY_NAME);

        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                ]
            ]
        );
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General')]
        );

        if ($model->getId()) {
            $fieldset->addField(
                'entity_id',
                'hidden',
                ['name' => self::DATA_FIELD_NAME . '[entity_id]']
            );
        }
        $fieldset->addField(
            'theme',
            'text',
            [
                'name' => self::DATA_FIELD_NAME . '[theme]',
                'label' => __('Title'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'picture',
            'image',
            [
                'name' => self::PICTURE_FIELD_NAME,
                'label' => __('Picture'),
                'required' => false,
            ]
        );

        $wysiwygConfig = $this->_wysiwygConfig->getConfig();
        $fieldset->addField(
            'content',
            'editor',
            [
                'name' => self::DATA_FIELD_NAME . '[content]',
                'label' => __('Content'),
                'required' => true,
                'config' => $wysiwygConfig
            ]
        );

        $data = $model->getData();
        $form->setValues($data);
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
