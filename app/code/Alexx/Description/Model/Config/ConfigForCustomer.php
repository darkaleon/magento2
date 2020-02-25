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
     */
    public function isDescriptionAddAllowed()
    {
        return boolval($this->customerSession->getCustomer()->getAllowAddDescription());
    }

    /**
     * Retreive cuurent customer entity_id
     */
    public function getCustomerId()
    {
        return $this->customerSession->getCustomer()->getId();
    }

    /**
     * Check current session for customer is logged in
     */
    public function isCustomerLoggedIn()
    {
        return $this->getCustomerId() !== null;
    }

    /**
     * Current area locator
     */
    public function isFront()
    {
        try {
            $result = $this->applicationState->getAreaCode() == 'frontend';
        } catch (LocalizedException $exception) {
            $result = false;
        }
        return $result;
    }
}
