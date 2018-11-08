<?php

use devskyfly\yiiModuleAdminPanel\migrations\helpers\EntityMigrationHelper;

class m181102_085600_create_settlement_table extends EntityMigrationHelper
{
    public $table="settlement";
    
    public function up()
    {
        $fields=$this->getFieldsDefinition();
        $fields['str_nmb']=$this->char(2);
        $fields['type']="ENUM('NOT_DEFINED','GOROD','STANICA','SELO','XUTOR','POS','PGT') NOT NULL";
        $this->createTable($this->table, $fields);
    }

    public function down()
    {
        echo "m181102_085600_create_settlements_table cannot be reverted.\n";
        $this->dropTable($this->table);
        //return false;
    }
}
