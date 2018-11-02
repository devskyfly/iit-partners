<?php

use devskyfly\yiiModuleAdminPanel\migrations\helpers\EntityMigrationHelper;

class m181102_085516_create_agent_table extends EntityMigrationHelper
{
    public $table="agent";
    
    public function up()
    {
        $fields=$this->getFieldsDefinition();
        $fields['lng']=$this->char(40);
        $fields['lat']=$this->char(40);
        
        $fields['lk_address']=$this->text();
        $fields['custom_address']=$this->text();
        
        $fields['phone']=$this->text();
        $fields['email']=$this->text();
        
        $this->createTable($this->table, $fields);
    }

    public function down()
    {
        echo "m181102_085516_create_agents_table cannot be reverted.\n";
        $this->dropTable($this->table);
        //return false;
    }
}
