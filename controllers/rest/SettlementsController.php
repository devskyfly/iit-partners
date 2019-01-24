<?php
namespace devskyfly\yiiModuleIitAgentsInfo\controllers\rest;

use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use devskyfly\php56\types\Arr;
use devskyfly\yiiModuleIitAgentsInfo\models\Agent;
use devskyfly\yiiModuleIitAgentsInfo\models\Region;
use devskyfly\yiiModuleIitAgentsInfo\models\Settlement;

class SettlementsController extends CommonController
{
    public function actionIndex($license="N")
    {
        $data=[];
        
        if(!in_array($license, ['Y','N'])){
            throw new BadRequestHttpException('Query parameter $license is out of range.');
        }
        
        if($license=="Y"){
            $query=Agent::find()->where(['active'=>'Y','flag_is_public'=>'Y','flag_is_license'=>'Y']);
        }else{
            $query=Agent::find()->where(['active'=>'Y']);
        }
        $agents=$query->asArray()->all();
        $region_ids=Arr::getColumn($agents,'_settlement__id');
        $region_ids=array_unique($region_ids);
        
        $query=Settlement::find()->where(['active'=>'Y','id'=>$region_ids])->orderBy(['name'=>SORT_ASC]);
        
        $fields=[
            "id"=>"id",
            "name"=>"name"
        ];
        
        $callback=function($item,$arr_item){
            
            $region_id=$item->_region__id;
            $region=Region::find()->where(['id'=>$region_id])->one();
            $arr_item['region_id']=$region->str_nmb;
            return $arr_item;
        };
        
        $data=$this->formData($query, $fields, $callback);
        
        $this->asJson($data);
    }
}

