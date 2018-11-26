<?php

use devskyfly\yiiModuleAdminPanel\migrations\helpers\contentPanel\ExtensionMigrationHelper;
use yii\helpers\Json;
use devskyfly\yiiModuleIitAgentsInfo\models\Region;

class m181102_085531_create_region_table extends ExtensionMigrationHelper
{
    public $table="region";
    
    public function up()
    {
        $fields=$this->getFieldsDefinition();
        $fields['str_nmb']=$this->char(2);
        $this->createTable($this->table, $fields);
        $this->initRegion();
    }

    public function down()
    {
        echo "m181102_085531_create_regions_table cannot be reverted.\n";
        $this->dropTable($this->table);
        //return false;
    }
    
    protected function initRegion()
    {
        $json_file_path=__DIR__.'/regions.json';
        $json=file_get_contents($json_file_path);
        
        $result=Json::decode($json,true);
        
        foreach ($result as $item){
            $region=new Region();
            $region->active='Y';
            $region->create_date_time=(new \DateTime())->format(\DateTime::ATOM);
            $region->change_date_time=(new \DateTime())->format(\DateTime::ATOM);
            $region->name=$item['name'];
            $region->str_nmb=$item['str_nmb'];
            $region->saveLikeItem();
        }
    }
}
