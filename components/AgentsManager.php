<?php
namespace devskyfly\yiiModuleIitPartners\components;

use devskyfly\php56\types\Nmbr;
use devskyfly\php56\types\Vrbl;
use devskyfly\yiiModuleIitPartners\models\Agent;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class AgentsManager extends BaseObject
{
    public static function getAll($license=null,$public=null,$flag_is_need_to_custom=null,$get_query_result=true)
    {
        if(!in_array($license, ['Y','N',null])){
            throw new \InvalidArgumentException('Parameter $license is out of range.');
        }
        
        if(!in_array($public, ['Y','N',null])){
            throw new \InvalidArgumentException('Parameter $public is out of range.');
        }
        
        if(!in_array($flag_is_need_to_custom, ['Y','N',null])){
            throw new \InvalidArgumentException('Parameter $flag_is_need_to_custom is out of range.');
        }

        $query=Agent::find()->where(['active'=>'Y']);
        
        if(!Vrbl::isNull($license)){
            $query=$query->andWhere(['flag_is_license'=>$license]);
        }
        
        if(!Vrbl::isNull($public)){
            $query=$query->andWhere(['flag_is_public'=>$public]);
        }
        
        if(!Vrbl::isNull($flag_is_need_to_custom)){
            $query=$query->andWhere(['flag_is_need_to_custom'=>$flag_is_need_to_custom]);
        }
        
        if($get_query_result){
            $query->all();
        }else{
            return $query;
        }
    }
    
    /**
     * 
     * @param number $lng
     * @param number $lat
     * @param string $license
     * @throws \InvalidArgumentException
     * @return \devskyfly\yiiModuleIitPartners\models\Agent|null
     */
    public static function getNearest($lng, $lat, $license=null, $flag_is_need_to_custom=null, $public=null, $arr=true)
    {
        if(!in_array($license, ['Y','N',null])){
            throw new \InvalidArgumentException('Parameter $license is out of range.');
        }
        
        if(!in_array($public, ['Y','N',null])){
            throw new \InvalidArgumentException('Parameter $public is out of range.');
        }
        
        if(!in_array($flag_is_need_to_custom, ['Y','N',null])){
            throw new \InvalidArgumentException('Parameter $flag_is_need_to_custom is out of range.');
        }
        
        $lng=Nmbr::toDoubleStrict($lng);
        $lat=Nmbr::toDoubleStrict($lat);
        
        /* $agents=Agent::find()
        ->where(['active'=>'Y','flag_is_public'=>'Y','flag_is_license'=>$license])
        ->all(); */
        
        $query=Agent::find()->where(['active'=>'Y']);
        
        if(!Vrbl::isNUll($license)){
            $query->andWhere(['flag_is_license'=>$license]);
        }
        if(!Vrbl::isNUll($public)){
            $query->andWhere(['flag_is_public'=>$public]);
        }
        if(!Vrbl::isNull($flag_is_need_to_custom)){
            $query=$query->andWhere(['flag_is_need_to_custom'=>$flag_is_need_to_custom]);
        }
        
        $agents=$query->all();
        $sort_fn=function($a, $b)
        {
            if ($a['del'] == $b['del']) {
                return 0;
            }
            return ($a['del'] < $b['del']) ? -1 : 1;
        };

        $arr=[];
        foreach ($agents as $agent){
            if((!Nmbr::isNumeric($agent['lng']))
            ||(!Nmbr::isNumeric($agent['lat'])))
            $arr[]=[
                'link'=>$agent,
                'lng'=>$agent->lng,
                'lat'=>$agent->lat,
                'del'=>sqrt(pow($lng-$agent->lng,2)+pow($lat-$agent->lat,2))
            ];
        }
        
        usort($arr, $sort_fn);
        
        if ($arr) {
            return $arr;
        } else {
            if(isset($arr[0])){
                return $arr[0];
            }
        }

        return null;
    }

    
}