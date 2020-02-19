<?php
declare(strict_types=1);

namespace Alexx\Blog\Model;

use Magento\Catalog\Model\Locator\RegistryLocator;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Psr\Log\LoggerInterface;

/**
 * Class for retreive system configuration
 */
class BlogConfig
{
    const XML_PATH_BLOG_VISIBLE = 'catalog_blog/general/applied_to';

    /**@var RegistryLocator */
    private $productRegistryLocator;

    /**@var RegistryLocator */
    private $scopeConfig;

    /**@var LoggerInterface */
    private $logger;

    /**
     * @param RegistryLocator $productRegistryLocator
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        RegistryLocator $productRegistryLocator,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    ) {
        $this->productRegistryLocator = $productRegistryLocator;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * Compare current Product Type Id with configured system value
     *
     * @return bool
     */
    public function isBlogVisible(): bool
    {
        $isVisible = false;
        try {
            $isVisible = in_array(
                $this->productRegistryLocator->getProduct()->getTypeId(),
                explode(',', $this->scopeConfig->getValue(self::XML_PATH_BLOG_VISIBLE))
            );
        } catch (NotFoundException $exception) {
            $this->logger->error($exception->getLogMessage());
        }
        return $isVisible;
    }
}
