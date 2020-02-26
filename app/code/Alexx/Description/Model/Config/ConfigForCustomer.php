<?php
declare(strict_types=1);

namespace Alexx\Description\Model\Config;

use Magento\Customer\Model\Session;
use Magento\Framework\App\State as ApplicationState;
use Magento\Framework\Exception\LocalizedException;

/**
 * Retreive data from session
 */
class ConfigForCustomer
{
    /**@var Session */
    private $customerSession;

    /**@var ApplicationState */
    private $applicationState;

    /**
     * @param Session $customerSession
     * @param ApplicationState $applicationState
     */
    public function __construct(Session $customerSession, ApplicationState $applicationState)
    {
        $this->customerSession = $customerSession;
        $this->applicationState = $applicationState;
    }

    /**
     * Check if current costomer allowed to add description to products
     *
     * @return bool
     */
    public function isDescriptionAddAllowed(): bool
    {
        return boolval($this->customerSession->getCustomer()->getAllowAddDescription());
    }

    /**
     * Retreive cuurent customer entity_id
     *
     * @return string
     */
    public function getCustomerId(): string
    {
        return (string)$this->customerSession->getCustomer()->getId();
    }

    /**
     * Check current session for customer is logged in
     *
     * @return bool
     */
    public function isCustomerLoggedIn(): bool
    {
        return $this->getCustomerId() !== null;
    }

    /**
     * Current area locator
     *
     * @return bool
     */
    public function isFront(): bool
    {
        try {
            $result = $this->applicationState->getAreaCode() == 'frontend';
        } catch (LocalizedException $exception) {
            $result = false;
        }
        return $result;
    }
}
