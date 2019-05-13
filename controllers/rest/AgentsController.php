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
           
           $settlement=Settlement::find()->where(['id'=>$item['_settlement__id']])->one();
           
           $arr_item['region_id']=$region->str_nmb;
           $arr_item['settlement_id']=Str::toString($item['_settlement__id']);
           $arr_item['locality_name']=Str::toString($settlement->name);
           $arr_item['locality_type']=Settlement::$hash_types[$settlement['type']];
           return $arr_item;
       };
       
       $query=AgentsManager::getAll($license,'Y','N',false);
       
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
           "_settlement__id"=>"settlement_id",
           "locality_name"=>"locality_name",
           "locality_type"=>"locality_type",
           "comment"=>"comment",
           "open"=>"open_hours",
           "close"=>"closed_time"
       ];
       
       $this->asJson($this->formData($query, $fields, $callback)); 
    }
    
    public function actionGetNearest($lng,$lat,$license=null)
    {
        if(!in_array($license, ['Y','N',null])){
            throw new BadRequestHttpException('Query parameter $license is out of range.');
        }
     
        $result=[];
        $nearest=AgentsManager::getNearest($lng, $lat, $license, null, 'Y', true);
        
        if(Vrbl::isNull($nearest)){
            throw NotFoundHttpException();
        }      
        
        foreach ($nearest as $nearestItm) {
            $item=$nearestItm['link'];
            if ($nearestItm['del'] < 6) {
                $result[]=[
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
            }
        }
        $this->asJson($result);
    }
}