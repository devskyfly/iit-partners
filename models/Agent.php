<?php
namespace devskyfly\yiiModuleIitAgentsInfo\models;

use devskyfly\yiiModuleAdminPanel\models\contentPanel\AbstractEntity;
use yii\helpers\ArrayHelper;

/**
 * 
 * @author devskyfly
 *
 * @property string $info
 * @property string $lk_guid
 * 
 * @property string $lng
 * @property string $lat
 * 
 * @property string $lk_address
 * @property string $custom_address
 * @property string $phone
 * @property string $email
 * 
 * @property string $flag_is_license
 * @property string $flag_is_own
 * @property string $flag_is_public
 * @property string $flag_is_need_to_custom
 * 
 * @property string $manager_in_charge
 */
class Agent extends AbstractEntity
{
    /**********************************************************************/
    /** Implementation **/
    /**********************************************************************/
    
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
            [['info','lk_guid'],'string'],
            [['lng','lat'],'string'],
            [['lk_address','custom_address'],'string'],
            [['manager_in_charge'],'string'],
            [['phone','email'],'string'],
            [['_region__id','_settlement__id'],'string'],
            [['flag_is_license','flag_is_own','flag_is_public','flag_is_need_to_custom'],'string']
        ];
        
        return ArrayHelper::merge($parent_rules, $new_rules);
    }
    
    /**********************************************************************/
    /** Extension **/
    /**********************************************************************/
    
    /**
     * Returm agent record by guid
     *
     * @param string $guid
     *
     * @return AbstractEntity | null
     */
    public static function findByGuid($guid)
    {
        return static::find()->where(['lk_guid'=>$guid])->one();
    }
}