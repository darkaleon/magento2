<?php
declare(strict_types=1);

namespace Alexx\Blog\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context as ActionContext;
use Alexx\Blog\Model\ResourceModel\BlogPosts\CollectionFactory as BlogCollectionFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Alexx\Blog\Api\BlogRepositoryInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Psr\Log\LoggerInterface;

/**
 * Class to delete selected posts through massaction
 */
class MassDelete extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Alexx_Blog::manage';

    /**@var BlogRepositoryInterface */
    private $blogRepsitory;

    /**@var BlogCollectionFactory */
    private $collectionFactory;

    /**@var Filter */
    private $filter;

    /**@var LoggerInterface */
    private $logger;

    /**
     * @param ActionContext $context
     * @param Filter $filter
     * @param BlogCollectionFactory $collectionFactory
     * @param BlogRepositoryInterface $blogRepsitory
     * @param LoggerInterface $logger
     */
    public function __construct(
        ActionContext $context,
        Filter $filter,
        BlogCollectionFactory $collectionFactory,
        BlogRepositoryInterface $blogRepsitory,
        LoggerInterface $logger
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->blogRepsitory = $blogRepsitory;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $postsDeleted = 0;
        $postsDeletedError = 0;
        /** @var \Magento\Catalog\Model\Product $product */
        foreach ($collection->getItems() as $product) {
            try {
                $this->blogRepsitory->delete($product);
                $postsDeleted++;
            } catch (LocalizedException $exception) {
                $this->logger->error($exception->getLogMessage());
                $postsDeletedError++;
            }
        }

        if ($postsDeleted) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been deleted.', $postsDeleted)
            );
        }

        if ($postsDeletedError) {
            $this->messageManager->addErrorMessage(
                __(
                    'A total of %1 record(s) haven\'t been deleted. Please see server logs for more details.',
                    $postsDeletedError
                )
            );
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('blog/*/index');
    }
}
