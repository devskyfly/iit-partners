<?php
namespace devskyfly\yiiModuleIitAgentsInfo\controllers\rest;

use devskyfly\yiiModuleIitAgentsInfo\models\Agent;
use devskyfly\yiiModuleIitAgentsInfo\models\Region;
use yii\rest\Controller;

class AgentsController extends CommonController
{
    public function actionIndex($license="N")
    {
       $data=[];
       $model_cls=Agent::class;
       $list=$model_cls::find()
       ->where(['active'=>'Y'])
       ->all();
       foreach ($list as $item){
           $data[]=[
               'id'=>$item->id,
               'name'=>$item->name,
           ];
       }
       
       
       $a=1;
       if($license=="Y"){
           $query=Agent::find()->where(['active'=>'Y','flag_is_public'=>'Y','flag_is_license'=>'Y']);
       }else{
           $query=Agent::find()->where(['active'=>'Y','flag_is_public'=>'Y']);
       }
       
       $fields=[
           "name"=>"title",
           "lk_guid"=>"guid",
           "flag_is_license"=>"license",
           "flag_is_own"=>"is_own",
           "lng"=>"longitude",
           "lat"=>"latitude",
           "email"=>"email",
           "phone"=>"phone",
       ];
       
       $callback=function($item,$arr_item){
           
           $region_id=$item->_region__id;
           $region=Region::find()->where(['id'=>$region_id])->one();
           $arr_item['region_id']=$region->str_nmb;
           return $arr_item;
       };
       
       $this->asJson($this->formData($query, $fields, $callback)); 
    }
}