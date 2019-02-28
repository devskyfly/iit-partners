<?php
namespace devskyfly\yiiModuleIitPartners\controllers;

use yii\web\Controller;
use devskyfly\yiiModuleIitPartners\models\common\ModuleNavigation;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $module_navigation=new ModuleNavigation();
        $list=[$module_navigation->getData()];
        return $this->render('index',compact("list","title"));
    }
}