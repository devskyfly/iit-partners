<?php
namespace devskyfly\yiiModuleIitAgentsInfo;

use devskyfly\php56\types\Lgc;
use devskyfly\php56\types\Str;
use devskyfly\php56\types\Vrbl;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class Module extends \yii\base\Module
{
    
    /**
     * 
     * @var string
     */
     public $lk_login="";
     /**
      * 
      * @var string
      */
     public $lk_pass="";
     
     /**
      * 
      * @var string
      */
     public $lk_url="";
     
     public function init()
     {
         parent::init();
         $this->checkProperties();
         if(Yii::$app instanceof \yii\console\Application){
             $this->controllerNamespace='devskyfly\yiiModuleIitAgentsInfo\console';
         }
     }
     
     public function behaviors()
     {
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 'except'=>[
                     'rest/*/*',
                 ],
                 'rules' => [
                      [
                         'allow' => true,
                         'roles' => ['@'],
                     ], 
                 ],
             ]
         ];
     }
     
     protected function checkProperties()
     {
         if(Vrbl::isEmpty($this->lk_login)){
             throw new \InvalidArgumentException('Property $lk_login is empty.');
         }
         
         if(!Str::isString($this->lk_login)){
             throw new \InvalidArgumentException('Property $lk_login is is not string type.');
         }
         
         if(Vrbl::isEmpty($this->lk_pass)){
             throw new \InvalidArgumentException('Property $lk_pass is empty.');
         }
         
         if(!Str::isString($this->lk_pass)){
             throw new \InvalidArgumentException('Property $lk_login is is not string type.');
         }
         
         if(Vrbl::isEmpty($this->lk_url)){
             throw new \InvalidArgumentException('Property $lk_url is empty.');
         }
         
         if(!Str::isString($this->lk_pass)){
             throw new \InvalidArgumentException('Property $lk_url is not string type.');
         }
     }
}