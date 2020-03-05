<?php
declare(strict_types=1);

namespace Alexx\Description\Block\Adminhtml;

use Magento\Backend\Block\Template\Context;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Controller\RegistryConstants;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Ui\Component\Layout\Tabs\TabInterface;

/**
 * Customer account form block
 */
class CustomerFormAllowAddDescriptionTab extends \Magento\Backend\Block\Widget\Form implements TabInterface
{
    /** @var \Magento\Framework\Data\FormFactory*/
    private $formFactory;

    /** @var \Magento\Framework\Registry*/
    private $coreRegistry;

    /** @var string*/
    protected $_template = 'Alexx_Description::customer/allow_add_description_form.phtml';

    /** @var CustomerRepositoryInterface */
    private $customerRepository;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        CustomerRepositoryInterface $customerRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->coreRegistry = $registry;
        $this->formFactory = $formFactory;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @inheritDoc
     */
    public function getTabLabel()
    {
        return __('Product description');
    }

    /**
     * @inheritDoc
     */
    public function getTabTitle()
    {
        return __('Product description');
    }

    /**
     * @inheritDoc
     */
    public function getTabClass()
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getTabUrl()
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function isAjaxLoaded()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function canShowTab()
    {
        return (bool)$this->getCurrentCustomerId();
    }

    /**
     * @inheritDoc
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    protected function _prepareForm()
    {
        if (!$this->canShowTab()) {
            return $this;
        }
        /**@var \Magento\Framework\Data\Form $form */
        $form = $this->formFactory->create();
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('Product description information'),
                'class' => 'customer-allow-add-description-fieldset',
            ]
        );
        $checkboxElement = $fieldset->addField(
            'customer-allow-add-description',
            'checkbox',
            [
                'label' => __('Allow add product description'),
                'name' => 'allow_add_description',
                'data-form-part' => $this->getData('target_form'),
                'value' => true,
                'onchange' => 'this.value = this.checked;',
            ]
        );
        $customer = $this->getCurrentCustomer();
        $checkboxElement->setIsChecked(
            $customer->getExtensionAttributes()->getAllowAddDescription()->getCustomerAllowAddDescription()
        );
        return $this;
    }

    /**
     * Get current customer id
     *
     * @return int
     */
    private function getCurrentCustomerId(): int
    {
        return (int)$this->coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
    }

    /**
     * Get current customer model
     *
     * @return CustomerInterface|null
     */
    private function getCurrentCustomer(): ?CustomerInterface
    {
        $customerId = $this->getCurrentCustomerId();
        try {
            $customer = $this->customerRepository->getById($customerId);
        } catch (NoSuchEntityException | LocalizedException $e) {
            return null;
        }
        return $customer;
    }
}
