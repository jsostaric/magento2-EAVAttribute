<?php

namespace Inchoo\EAVCustomAttribute\Setup\Patch\Data;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class InchooAddCustomInputAttribute implements DataPatchInterface
{
    protected $eavSetupFactory;
    protected $moduleDataSetup;

    /**
     * InchooAddCustomSelectAttribute constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(EavSetupFactory $eavSetupFactory, ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public function apply()
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $groupName = 'My custom Group';

        $entityTypeId = $eavSetup->getEntityTypeId('catalog_product');
        $attributeSetId = $eavSetup->getDefaultAttributeSetId($entityTypeId);
        $eavSetup->addAttributeGroup($entityTypeId, $attributeSetId, $groupName, 100);
        $attributeGroupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, $groupName);

        $eavSetup->addAttribute(
            $entityTypeId,
            'custom_input_field',
            [
                'type' => 'varchar',
                'input' => 'text',
                'label' => 'Custom Input Field',
                'required' => false,
                'user_defined' => 1
            ]
        );

        $attributeId = $eavSetup->getAttributeId($entityTypeId, 'custom_input_field');
        $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeId, 100);
    }
}
