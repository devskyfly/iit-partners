<?php
namespace devskyfly\yiiModuleIitPartners\components;

use yii\base\BaseObject;
use devskyfly\php56\types\Str;
use devskyfly\yiiModuleIitPartners\models\Region;

class RegionsManager extends BaseObject
{
    public static function getList()
    {   
       
        $result=Region::find()->where(['active'=>'Y'])
        ->orderBy(['name'=>SORT_ASC])
        ->all();
        
        return $result;
    }
    
    /**
     * 
     * @param string $str_nmb
     * @throws \InvalidArgumentException
     * @return \devskyfly\yiiModuleIitPartners\models\Region
     */
    public static function getByStrNmb($str_nmb)
    {
        if(Str::isString()){
            throw new \InvalidArgumentException('Parameter $str_nmb is not string type.');
        }
        return Region::find()->where(['active'=>'Y','str_nmb'=>$str_nmb])->one();
    }
}