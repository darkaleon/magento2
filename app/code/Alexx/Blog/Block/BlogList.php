<?php
declare(strict_types=1);

namespace Alexx\Blog\Block;

use Alexx\Blog\Api\BlogRepositoryInterface;
use Alexx\Blog\Api\Data\BlogInterface;
use Alexx\Blog\Model\Media\Config as BlogMediaConfig;
use Alexx\Blog\Model\BlogConfig;
use Magento\Framework\Api\AbstractSimpleObject;
use Magento\Framework\Api\Search\SearchCriteriaInterface;
use Magento\Framework\Api\Search\SearchCriteriaInterfaceFactory;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Psr\Log\LoggerInterface;

/**
 *  Block which injects to catalog_product_view
 *
 *  Displays blogs posts list
 */
class BlogList extends Template
{
    /**@var BlogRepositoryInterface */
    private $blogRepository;

    /**@var SearchCriteriaInterfaceFactory */
    private $searchCriteriaFactory;

    /**@var SortOrderBuilder */
    private $sortOrderBuilder;

    /**@var LoggerInterface */
    private $logger;

    /**@var BlogMediaConfig */
    private $blogMediaConfig;

    /**@var BlogConfig */
    private $blogConfig;

    /**@var array*/
    private $loadedPosts;

    /**
     * @param Context $context
     * @param BlogRepositoryInterface $blogRepository
     * @param SearchCriteriaInterfaceFactory $searchCriteriaFactory
     * @param SortOrderBuilder $sortOrderBuilder
     * @param LoggerInterface $logger
     * @param BlogMediaConfig $blogMediaConfig
     * @param BlogConfig $blogConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        BlogRepositoryInterface $blogRepository,
        SearchCriteriaInterfaceFactory $searchCriteriaFactory,
        SortOrderBuilder $sortOrderBuilder,
        LoggerInterface $logger,
        BlogMediaConfig $blogMediaConfig,
        BlogConfig $blogConfig,
        array $data = []
    ) {
        $this->blogConfig = $blogConfig;
        $this->blogMediaConfig = $blogMediaConfig;
        $this->blogRepository = $blogRepository;
        $this->searchCriteriaFactory = $searchCriteriaFactory;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->logger = $logger;
        parent::__construct($context, $data);
    }

    /**
     * Checks blog enabling in system config
     */
    public function isBlogVisible(): bool
    {
        return $this->blogConfig->isBlogVisible();
    }

    /**
     * Gets latest blog posts
     *
     * @return array
     */
    public function getLastPosts(): array
    {
        $resultPosts = [];
        if ($this->loadedPosts) {
            $resultPosts = $this->loadedPosts;
        } else {
            /**@var SearchCriteriaInterface $searchCriteria */
            $searchCriteria = $this->searchCriteriaFactory->create();

            $searchCriteria->setPageSize(5);

            /**@var  AbstractSimpleObject $defaultSortOrder */
            $defaultSortOrder = $this->sortOrderBuilder
                ->setField(BlogInterface::FIELD_CREATED_AT)
                ->setDirection('desc')
                ->create();
            $searchCriteria->setSortOrders([$defaultSortOrder]);

            try {
                $resultPosts = $this->blogRepository->getList($searchCriteria)->getItems();
            } catch (LocalizedException $exception) {
                $this->logger->error($exception->getLogMessage());
            }
            $this->loadedPosts = $resultPosts;
        }
        return $resultPosts;
    }

    /**
     * Generates url to image
     *
     * @param BlogInterface $post
     *
     * @return string
     */
    public function getPictureUrl(BlogInterface $post): string
    {
        return $this->blogMediaConfig->getBlogImageUrl($post->getPicture());
    }
}
