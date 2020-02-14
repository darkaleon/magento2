<?php
declare(strict_types=1);

namespace Alexx\Blog\Model;

use Magento\Catalog\Model\Locator\RegistryLocator;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\App\Config\ScopeConfigInterface;

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

    /**
     * @param RegistryLocator $productRegistryLocator
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(RegistryLocator $productRegistryLocator, ScopeConfigInterface $scopeConfig)
    {
        $this->productRegistryLocator = $productRegistryLocator;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Compare current Product Type Id with configured system value
     *
     * @return bool
     */
    public function isBlogVisible(): bool
    {
        try {
            return in_array(
                $this->productRegistryLocator->getProduct()->getTypeId(),
                explode(',', $this->scopeConfig->getValue(self::XML_PATH_BLOG_VISIBLE))
            );
        } catch (NotFoundException $exception) {
            $this->logger->error($exception->getLogMessage());
            return false;
        }
    }
}
