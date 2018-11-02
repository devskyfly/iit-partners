<?php
namespace devskyfly\yiiModuleIitAgentsInfo\models;

use devskyfly\yiiModuleAdminPanel\models\contentPanel\AbstractEntity;

class Agent extends AbstractEntity
{
    protected static function sectionCls()
    {
        return null;
    }

    public function extensions()
    {
        return [];
    }  
}

