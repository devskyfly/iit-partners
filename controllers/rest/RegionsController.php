<?php
namespace devskyfly\yiiModuleIitPartners\controllers\rest;

use yii\web\BadRequestHttpException;
use yii\helpers\ArrayHelper;
use devskyfly\yiiModuleIitPartners\models\Agent;
use devskyfly\yiiModuleIitPartners\components\RegionsManager;
use devskyfly\yiiModuleIitPartners\models\Region;

class RegionsController extends CommonController
{
    public function actionIndex($license=null)
    {
        $data=[];
        $head=[];
        
        if(!in_array($license, ['Y','N',null])){
            throw new BadRequestHttpException('Query parameter $license is out of range.');
        }
        
        $query=RegionsManager::getAll($license,'Y',null,false);
        
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

    public function actionAll()
    {
        $result = [];
        $regions = Region::find()->orderBy(['str_nmb'=>SORT_ASC])->all();
        foreach ($regions as $region) {
            $result[] =["name"=>$region->name,"code"=>$region['str_nmb']];
        }
        $this->asJson($result);
    }
}

