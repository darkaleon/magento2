<?php
declare(strict_types=1);

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context as ActionContext;
use Alexx\Blog\Model\ResourceModel\BlogPosts\CollectionFactory as BlogCollectionFactory;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Alexx\Blog\Api\BlogRepositoryInterfaceFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Psr\Log\LoggerInterface;

/**
 * Class to delete selected posts through massaction
 */
class MassDelete extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Alexx_Blog::menu';

    private $_blogRepsitoryFactory;
    private $collectionFactory;
    private $filter;
    private $logger;

    /**
     * @param ActionContext $context
     * @param Filter $filter
     * @param BlogCollectionFactory $collectionFactory
     * @param BlogRepositoryInterfaceFactory $blogRepsitoryFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        ActionContext $context,
        Filter $filter,
        BlogCollectionFactory $collectionFactory,
        BlogRepositoryInterfaceFactory $blogRepsitoryFactory,
        LoggerInterface $logger = null
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->_blogRepsitoryFactory = $blogRepsitoryFactory;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {

        $repsitory = $this->_blogRepsitoryFactory->create();
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $productDeleted = 0;
        $productDeletedError = 0;
        /** @var \Magento\Catalog\Model\Product $product */
        foreach ($collection->getItems() as $product) {
            try {
                $repsitory->delete($product);
                $productDeleted++;
            } catch (LocalizedException $exception) {
                $this->logger->error($exception->getLogMessage());
                $productDeletedError++;
            }
        }

        if ($productDeleted) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been deleted.', $productDeleted)
            );
        }

        if ($productDeletedError) {
            $this->messageManager->addErrorMessage(
                __(
                    'A total of %1 record(s) haven\'t been deleted. Please see server logs for more details.',
                    $productDeletedError
                )
            );
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('blog/*/index');
    }
}
