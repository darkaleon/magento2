<?php
declare(strict_types=1);

namespace Alexx\Description\Model\Config;

use Magento\Customer\Model\Session;
use Magento\Framework\App\State as ApplicationState;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Retreive data from session
 */
class CustomerAccessManagerToDescription
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
        if (!$this->isCustomerLoggedIn()) {
            $result = false;
        } else {
            try {
                $customer = $this->customerSession->getCustomerData();
                $result = boolval(
                    $customer->getExtensionAttributes()->getAllowAddDescription()->getCustomerAllowAddDescription()
                );
            } catch (NoSuchEntityException | LocalizedException $exception) {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * Retreive current customer entity_id
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
        return !empty($this->getCustomerId());
    }

    /**
     * Current area locator
     *
     * @return bool
     */
    public function isStorefront(): bool
    {
        try {
            $result = $this->applicationState->getAreaCode() == 'frontend';
        } catch (LocalizedException $exception) {
            $result = false;
        }
        return $result;
    }
}
