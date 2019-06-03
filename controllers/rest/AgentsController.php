<?php
namespace devskyfly\yiiModuleIitPartners\controllers\rest;

use devskyfly\php56\types\Str;
use devskyfly\php56\types\Vrbl;
use devskyfly\php56\types\Nmbr;
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
           $arr_item['settlement_id']=Nmbr::toIntegerStrict($item['_settlement__id']);

           $arr_item['locality_name']=Str::toString($settlement->name);
           $arr_item['locality_type']=Settlement::$hash_types[$settlement['type']];
           return $arr_item;
       };
       
       $query=AgentsManager::getAll($license,'Y',null,false);
       
       $fields=[
           "name"=>"title",
           "lk_guid"=>"guid",
           "flag_is_license"=>"license",
           "flag_is_own"=>"is_own",
           "flag_is_fast_release"=>"fast_release",
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

       $data=$this->formData($query, $fields, $callback);

       /*$data=array_map(function($item){
            $item['settlement_id']=Nmbr::toIntegerStrict($item['settlement_id']);
            return $item;
       },$data);*/

       $this->asJson($data); 
    }
    
    public function actionGetNearest($lng,$lat,$license=null)
    {
        $resultFormFct = function ($nearest,$del=0)
        {
            $result=[];
            foreach ($nearest as $nearestItm) {
                $item=$nearestItm['link'];

                if (($nearestItm['del'] < $del)
                || ($del == 0)) {
                    if(empty($item->_settlement__id)) continue;
                    $settlement_id=Nmbr::toIntegerStrict($item->_settlement__id);
                    $settlement=Settlement::getById($settlement_id);
                    if(empty($settlement->_region__id)) continue;
                    $region=Region::find()
                    ->where(['id'=>$settlement->_region__id])
                    ->one();
                    $region_id=$region->str_nmb;
                        $result[]=[
                        "title"=>$item->name,
                        "guid"=>$item->lk_guid,
                        "license"=>$item->flag_is_license,
                        "is_own"=>$item->flag_is_own,
                        "longitude"=>$item->lng,
                        "latitude"=>$item->lat,
                        "email"=>$item->email,
                        "phone"=>$item->phone,
                        "address"=>$item->custom_address,
                        "settlement_id"=>$settlement_id,
                        "region_id"=>$region_id,
                        "del" => $nearestItm['del']
                    ];
               }
            }
            return $result;
        };

        if(!in_array($license, ['Y','N',null])){
            throw new BadRequestHttpException('Query parameter $license is out of range.');
        }
     
        $result=[];
        $nearest=AgentsManager::getNearest($lng, $lat, $license, null, 'Y', true);




        if (Vrbl::isNull($nearest)) {
            throw NotFoundHttpException();
        }

        $result = $resultFormFct($nearest, 6);

        if (empty($result)) {
            $result = $resultFormFct($nearest);
            $result = array_splice($result, 0, 15);
        } else {
            if (count($result) > 10) {
                $result = array_splice($result, 0, 15);
            }
        }

        $result = static::sortByOwn($result, 3);

        $this->asJson($result);
    }
   
    protected function sortByOwn($arr,$del=0)
    {
        if(!is_array($arr)){
            throw new \InvalidArgumentException('Param $arr is not array type.');
        }

        if(!Nmbr::isNumeric($del)){
            throw new \InvalidArgumentException('Param $del is not numeric type.');
        }

        $own = [];
        $size = count($arr);
        for ($i = 0; $i < $size; $i++) {
            $itm = $arr[$i];

            if ($del == 0) {
                if ($itm['is_own'] == 'Y') {
                    $own[] = $itm;
                    unset($arr[$i]);
                }
            } else {
                if (($itm['is_own'] == 'Y')
                    && ($itm['del'] == $del)
                ) {
                    $own[] = $itm;
                    unset($arr[$i]);
                }
            }
        }

        $arr = ArrayHelper::merge($own, $arr);
        $arr = array_values($arr);

        return $arr;
    }
}