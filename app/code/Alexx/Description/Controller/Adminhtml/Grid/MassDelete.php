<?php


namespace Alexx\Description\Controller\Adminhtml\Grid;


use Alexx\Description\Api\DescriptionRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Alexx\Description\Model\ResourceModel\Description\CollectionFactory;

class MassDelete extends Action implements HttpPostActionInterface
{


    /**
     * @var Filter
     */
    protected $filter;
    private $blogRepository;
    protected $redirect;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory,
                                \Magento\Framework\App\Response\RedirectInterface $redirect,

                                DescriptionRepositoryInterface $repository)
    {
        $this->redirect = $redirect;
        $this->blogRepository = $repository;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $redirectUrl = $this->redirect->getRefererUrl();
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $page) {
            $this->blogRepository->delete($page);
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

//        return $resultRedirect->setPath('*/*/');
        return $resultRedirect->setPath($redirectUrl);
    }
}
