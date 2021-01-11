<?php

namespace Inchoo\EAVCustomAttribute\Setup\Patch\Data;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class InchooAddCustomSelectAttribute implements DataPatchInterface
{
    protected $moduleDataSetup;
    protected $eavSetupFactory;

    public function __construct(ModuleDataSetupInterface $moduleDataSetup, EavSetupFactory $eavSetupFactory)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
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
        $attributeGroupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, $groupName);

        $eavSetup->addAttribute(
            $entityTypeId,
            'custom_select_field',
            [
                'type' => 'int',
                'input' => 'select',
                'label' => 'My Custom Select Field',
                'required' => false,
                'user_defined' => 1,
                'sort_order' => 101,
                'option' => [
                    'values' => [
                        1 => 'Select one',
                        2 => 'Select two',
                        3 => 'Select three'
                    ]
                ]
            ]
        );

        $attributeId = $eavSetup->getAttributeId($entityTypeId, 'custom_select_field');
        $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeId, 101);
    }
}
