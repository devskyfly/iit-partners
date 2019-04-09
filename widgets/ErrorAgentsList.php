<?php
namespace devskyfly\yiiModuleIitPartners\widgets;

use devskyfly\php56\types\Vrbl;
use devskyfly\yiiModuleIitPartners\models\Agent;
use yii\base\Widget;
use devskyfly\yiiModuleIitPartners\models\Settlement;

class ErrorAgentsList extends Widget
{
    public $data=[];
    
    public function init()
    {
        parent::init();
        $agents=Agent::find()->each();
        foreach ($agents as $agent){
            $result=false;
            
            if(Vrbl::isNull($agent['_settlement__id'])
                ||Vrbl::isEmpty($agent['_settlement__id'])){
                    $result=true;
            }
            
            $settlement=Settlement::find()->where(['id'=>$agent->_settlement__id])->one();
            if(!$settlement){
                $result=true;
            }
            
            if($result){
                $this->data[]=$agent;
            }
        }
    }
    
    public function run()
    {
        $data=$this->data;
        return $this->render('error-agents-list',compact("data"));
    }
}