<?php
declare(strict_types=1);

namespace Alexx\Description\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Setup\CustomerSetup;

/**
 * Script that configures boolean EAV attribure allow_add_description
 */
class InstallData implements InstallDataInterface
{
    /**@var CustomerSetupFactory*/
    private $customerSetupFactory;

    /**
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(CustomerSetupFactory $customerSetupFactory)
    {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * @inheritdoc
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        /**@var CustomerSetup $customerSetup*/
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'allow_add_description', [
            'type' => 'int',
            'label' => 'Allow add product description',
            'input' => 'boolean',
            'required' => false,
            'visible' => true,
            'position' => 500,
            'system' => false,
            'backend' => \Magento\Customer\Model\Attribute\Backend\Data\Boolean::class,
        ]);

        $attribute = $customerSetup->getEavConfig()
            ->getAttribute(\Magento\Customer\Model\Customer::ENTITY, 'allow_add_description')
            ->addData(['used_in_forms' => ['adminhtml_customer']]);
        $attribute->save();
    }
}
