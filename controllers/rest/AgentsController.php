<?php
namespace devskyfly\yiiModuleIitPartners\controllers\rest;

use devskyfly\php56\types\Str;
use devskyfly\php56\types\Vrbl;
use devskyfly\yiiModuleIitPartners\models\Region;
use devskyfly\yiiModuleIitPartners\models\Settlement;
use devskyfly\yiiModuleIitPartners\components\AgentsManager;
use yii\web\BadRequestHttpException;

class AgentsController extends CommonController
{
    public function actionIndex($license=null)
    {
       if(!in_array($license, ['Y','N',null])){
            throw new BadRequestHttpException('Query parameter $license is out of range.');
       }
       
       $callback=function($item,$arr_item){
           
           $settlement=Settlement::getById($item['_settlement__id']);
           if(Vrbl::isNull($settlement)){
               throw new \InvalidArgumentException('Parameter $settlment is null.');
           }
           
           $region_id=$settlement['_region__id'];
           $region=Region::find()
           ->where(['id'=>$region_id])
           ->one();
           
           $arr_item['region_id']=$region->str_nmb;
           $arr_item['settlement_id']=Str::toString($item['_settlement__id']);
           return $arr_item;
       };
       
       $query=AgentsManager::getAll($license,'Y',false);
       
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
       
       $this->asJson($this->formData($query, $fields, $callback)); 
    }
    
    public function actionGetNearest($lng,$lat,$license=null)
    {
        if(!in_array($license, ['Y','N',null])){
            throw new BadRequestHttpException('Query parameter $license is out of range.');
        }
        
        $nearest=AgentsManager::getNearest($lng, $lat, $license);
        
        if(Vrbl::isNull($nearest)){
            throw NotFoundHttpException();
        }      
        $item=$nearest;
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