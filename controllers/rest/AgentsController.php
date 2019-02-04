<?php
namespace devskyfly\yiiModuleIitAgentsInfo\controllers\rest;

use devskyfly\yiiModuleIitAgentsInfo\models\Agent;
use devskyfly\yiiModuleIitAgentsInfo\models\Region;
use yii\rest\Controller;
use devskyfly\php56\types\Nmbr;
use devskyfly\php56\types\Str;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;

class AgentsController extends CommonController
{
    public function actionIndex($license="N")
    {
       if(!in_array($license, ['Y','N'])){
            throw new BadRequestHttpException('Query parameter $license is out of range.');
       }
       
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
           "custom_address"=>"address",
           "_settlement__id"=>"settlement_id"
       ];
       
       $callback=function($item,$arr_item){
           
           $region_id=$item->_region__id;
           $region=Region::find()->where(['id'=>$region_id])->one();
           $arr_item['region_id']=$region->str_nmb;
           $arr_item['settlement_id']=Str::toString($item['_settlement__id']);
           return $arr_item;
       };
       
       $this->asJson($this->formData($query, $fields, $callback)); 
    }
    
    public function actionGetNearest($lng,$lat,$license="N")
    {
        if(!in_array($license, ['Y','N'])){
            throw new BadRequestHttpException('Query parameter $license is out of range.');
        }
        $lng=Nmbr::toDoubleStrict($lng);
        $lat=Nmbr::toDoubleStrict($lat);
        
        $agents=Agent::find()
        ->where(['active'=>'Y','flag_is_public'=>'Y','flag_is_license'=>$license])
        ->all();
        
        $sort_fn=function($a, $b)
        {
            if ($a['del'] == $b['del']) {
                return 0;
            }
            return ($a['del'] < $b['del']) ? -1 : 1;
        };
        
        $arr=[];
        foreach ($agents as $agent){
            $arr[]=[
                'link'=>$agent,
                'lng'=>$agent->lng,
                'lat'=>$agent->lat,
                'del'=>sqrt(pow($lng-$agent->lng,2)+pow($lat-$agent->lat,2))
            ];
        }
        
        usort($arr, $sort_fn);
        
        if(!isset($arr[0])){
            throw NotFoundHttpException();
        }      
        $item=$arr[0]['link'];
        $result=[
            "title"=>$item->name,
            "guid"=>$item->lk_guid,
            "license"=>$item->flag_is_license,
            "is_own"=>$item->flag_is_own,
            "longitude"=>$item->lng,
            "latitude"=>$item->lat,
            "email"=>$item->email,
            "phone"=>$item->phone,
            "address"=>$item->custom_address
        ];
        $this->asJson($result);
    }
}