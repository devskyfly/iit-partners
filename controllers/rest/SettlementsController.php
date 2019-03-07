<?php
namespace devskyfly\yiiModuleIitPartners\controllers\rest;

use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use devskyfly\php56\types\Arr;
use devskyfly\yiiModuleIitPartners\components\SettlementsManager;
use devskyfly\yiiModuleIitPartners\models\Agent;
use devskyfly\yiiModuleIitPartners\models\Region;
use devskyfly\yiiModuleIitPartners\models\Settlement;

class SettlementsController extends CommonController
{
    public function actionIndex($license=null)
    {
        $callback=function($item,$arr_item){
            $region_id=$item->_region__id;
            $region=Region::find()->where(['id'=>$region_id])->one();
            $arr_item['region_id']=$region->str_nmb;
            $arr_item['type']=Settlement::$hash_types[$item->type];
            return $arr_item;
        };
        
        $data=[];
        
        if(!in_array($license, ['Y','N',null])){
            throw new BadRequestHttpException('Query parameter $license is out of range.');
        }
        
        $query=SettlementsManager::getAll($license,'Y','N',false);
        
        $fields=[
            "id"=>"id",
            "name"=>"name"
        ];
        
        $data=$this->formData($query, $fields, $callback);
        $this->asJson($data);
    }
}

