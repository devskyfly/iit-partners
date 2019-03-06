<?php
namespace devskyfly\yiiModuleIitPartners\components;

use yii\base\BaseObject;
use devskyfly\php56\types\Obj;
use devskyfly\yiiModuleIitPartners\models\Region;
use devskyfly\yiiModuleIitPartners\models\Settlement;

class SettlementsManager extends BaseObject
{
    public static function getList()
    {
        
        $result=Settlement::find()->where(['active'=>'Y'])
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
   /*  public static function getByRegion($region)
    {
        if(Obj::isA($region,Region::class)){
            throw new \InvalidArgumentException('Parameter $region is not string type.');
        }
        return Settlement::find()->where(['active'=>'Y','str_nmb'=>$str_nmb])->one();
    } */
}