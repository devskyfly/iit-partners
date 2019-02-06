<?php
namespace devskyfly\yiiModuleIitAgentsInfo\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $title='Модуль "Партнеры"';
        $list=[
            [
                'label'=>'',
                'sub_list'=>[
                    ['name'=>'Агенты','route'=>'/iit-agents-info/agents'],
                    ['name'=>'Регионы','route'=>'/iit-agents-info/regions'],
                    ['name'=>'Населенные пункты','route'=>'/iit-agents-info/settlements']
                ]
                
            ]
        ];
        return $this->render('index',compact("list","title"));
    }
}