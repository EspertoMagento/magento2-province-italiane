<?php
namespace EspertoMagento\ProvinceItaliane\Setup;

use Magento\Directory\Helper\Data;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;


class UpgradeData implements UpgradeDataInterface
{
    /**
     * Directory data
     *
     * @var Data
     */
    private $directoryData;

    /**
     * Init
     *
     * @param Data $directoryData
     */
    public function __construct(Data $directoryData)
    {
        $this->directoryData = $directoryData;
    }

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.1.0', '<')) {
            $province = [
                'SU' => 'Sud Sardegna',     
            ];

            foreach ($province as $code => $name) {
                $bind = ['country_id'   => 'IT', 'code' => $code, 'default_name' => $name];
                $setup->getConnection()->insert($setup->getTable('directory_country_region'), $bind);
                $regionId = $setup->getConnection()->lastInsertId($setup->getTable('directory_country_region'));

                $bind = ['locale'=> 'it_IT', 'region_id' => $regionId, 'name'=> $name];
                $setup->getConnection()->insert($setup->getTable('directory_country_region_name'), $bind);
            }

            $setup->endSetup();

        }
    }
}