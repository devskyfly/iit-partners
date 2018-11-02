<?php

use devskyfly\yiiModuleAdminPanel\migrations\helpers\EntityMigrationHelper;

class m181102_085531_create_region_table extends EntityMigrationHelper
{
    public $table="region";
    
    public function up()
    {
        $fields=$this->getFieldsDefinition();
        $fields['str_nmb']=$this->char(2);
        $this->createTable($this->table, $fields);
    }

    public function down()
    {
        echo "m181102_085531_create_regions_table cannot be reverted.\n";
        $this->dropTable($this->table);
        //return false;
    }
}
