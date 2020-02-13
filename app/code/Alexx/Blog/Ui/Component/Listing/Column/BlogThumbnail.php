<?php
declare(strict_types=1);

namespace Alexx\Blog\Ui\Component\Listing\Column;

use Alexx\Blog\Model\Media\Config as BlogMediaConfig;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * BlogThumbnail column
 */
class BlogThumbnail extends Column
{
    private $blogMediaConfig;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param BlogMediaConfig $blogMediaConfig
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        BlogMediaConfig $blogMediaConfig,
        array $components = [],
        array $data = []
    ) {
        $this->blogMediaConfig = $blogMediaConfig;
        parent::__construct($context, $uiComponentFactory, $components, $data);
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
                $item[$fieldName . '_src'] = $this->blogMediaConfig->getBlogImageUrl($model->getPicture() ?? '');
                $item[$fieldName . '_alt'] = $model->getTheme();
            }
        }

        return $dataSource;
    }
}
