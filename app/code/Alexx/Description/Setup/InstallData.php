<?php
declare(strict_types=1);


namespace Alexx\Description\Setup;


use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Setup\CustomerSetupFactory;

class InstallData implements InstallDataInterface
{
    private $customerSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'allow_add_description', [
            'type' => 'int', // type of attribute
            'label' => 'Allow add product description',
            'input' => 'boolean', // input type
//            'source' => \Magento\Config\Model\Config\Source\Yesno::class,
            'required' => false, // if you want to required need to set true
            'visible' => true,
            'position' => 500, // position of attribute
            'system' => false,
            'backend' => \Magento\Customer\Model\Attribute\Backend\Data\Boolean::class,

        ]);

        /* Specify which place you want to display customer attribute */
        $attribute = $customerSetup->getEavConfig()->getAttribute(\Magento\Customer\Model\Customer::ENTITY, 'allow_add_description')
            ->addData(['used_in_forms' => [
                'adminhtml_customer',
//                'customer_account_create',
//                'customer_account_edit'
            ]
            ]);
        $attribute->save();
    }
}
