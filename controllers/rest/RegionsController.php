<?php
namespace devskyfly\yiiModuleIitAgentsInfo\controllers\rest;

use devskyfly\php56\types\Arr;
use devskyfly\yiiModuleIitAgentsInfo\models\Agent;
use devskyfly\yiiModuleIitAgentsInfo\models\Region;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\helpers\ArrayHelper;

class RegionsController extends CommonController
{
    public function actionIndex($license="N")
    {
        $data=[];
        $head=[];
        
        if(!in_array($license, ['Y','N'])){
            throw new BadRequestHttpException('Query parameter $license is out of range.');
        }
        
        if($license=="Y"){
            $query=Agent::find()->where(['active'=>'Y','flag_is_public'=>'Y','flag_is_license'=>'Y']);
        }else{
            $query=Agent::find()->where(['active'=>'Y']);
        }
        
        $agents=$query->asArray()->all();
        $region_ids=Arr::getColumn($agents,'_region__id');
        $region_ids=array_unique($region_ids);
        
        $query=Region::find()->where(['active'=>'Y','id'=>$region_ids])->orderBy(['name'=>SORT_ASC]);;
        $fields=[
            "id"=>"id",
            "name"=>"name",
            "str_nmb"=>"code"
        ];
        $data=$this->formData($query, $fields);
        
        $head=[];
        $head_codes=[77,78];
        
        foreach($data as $key=>$item){
            if(in_array($item['code'], $head_codes)){
                $head[]=$item;
                unset($data[$key]);
            }
        }
        
        $data=ArrayHelper::merge($head,$data);
        $this->asJson($data);
    }
}

