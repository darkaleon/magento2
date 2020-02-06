<?php

namespace Alexx\Blog\Ui\Component\Listing\Column;

use Alexx\Blog\Model\PictureConfig;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\App\Action\Context as ActionContext;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\DataObject;

/**
 * BlogThumbnail column
 */
class BlogThumbnail extends Column
{
    private $_pictureConfig;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param PictureConfig $pictureConfig
     * @param array $components
     * @param array $data
     *
     * @return void
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        PictureConfig $pictureConfig,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->_pictureConfig = $pictureConfig;
    }

    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $model = new DataObject($item);
                $item[$fieldName . '_src'] = $this->_pictureConfig->getBlogImageUrl($model->getPicture());
                $item[$fieldName . '_alt'] = $model->getTheme();
            }
        }
        return $dataSource;
    }
}
