<?php
namespace devskyfly\yiiModuleIitAgentsInfo\models;

use devskyfly\yiiModuleAdminPanel\models\contentPanel\AbstractEntity;
use yii\helpers\ArrayHelper;

/**
 * 
 * @author devskyfly
 * 
 * @property string $type
 */
class Settlement extends AbstractEntity
{
    protected static function sectionCls()
    {
        return null;
    }
    
    public function extensions()
    {
        return [];
    }
    
    public function rules()
    {
        $parent_rules=parent::rules();
        $new_rules=[
            [['type'],'string']
        ];
        
        return ArrayHelper::merge($parent_rules, $new_rules);
    }
    
    public static function selectListRoute()
    {
        return "settlements/entity-select-list";
    }
}