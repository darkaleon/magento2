<?php
declare(strict_types=1);

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Magento\Catalog\Controller\Adminhtml\Category\Image\Upload;

/**
 * Admin Controller that perform uploading image from form and store it in tmp directory
 */
class ImageUpload extends Upload
{
    const ADMIN_RESOURCE = 'Alexx_Blog::manage';

    /**
     * @inheritDoc
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }
}
