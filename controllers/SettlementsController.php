<?php
namespace devskyfly\yiiModuleIitAgentsInfo\controllers;

use devskyfly\yiiModuleAdminPanel\controllers\AbstractContentPanelController;
use devskyfly\yiiModuleAdminPanel\widgets\contentPanel\ItemSelector;

use devskyfly\yiiModuleIitAgentsInfo\models\Settlement;

class SettlementsController extends AbstractContentPanelController
{
    /**
     *
     * {@inheritDoc}
     * @see \devskyfly\yiiModuleAdminPanel\controllers\AbstractContentPanelController::sectionItem()
     */
    public static function sectionCls()
    {
        //Если иерархичность не требуется, то вместо названия класса можно передать null
        return null;
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \devskyfly\yiiModuleAdminPanel\controllers\AbstractContentPanelController::entityItem()
     */
    public static function entityCls()
    {
        return Settlement::class;
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \devskyfly\yiiModuleAdminPanel\controllers\AbstractContentPanelController::entityEditorViews()
     */
    public function entityEditorViews()
    {
        return function($form,$item)
        {
            return [
                [
                    "label"=>"main",
                    "content"=>
                     $form->field($item,'name')
                    .$form->field($item,'create_date_time')
                    .$form->field($item,'change_date_time')
                    .$form->field($item,'active')->checkbox(['value'=>'Y','uncheckValue'=>'N','checked'=>$item->active=='Y'?true:false])
                    .$form->field($item,'type')->dropDownList([
                        'NOT_DEFINED'=>'Не определено',
                        'STAN'=>'Станица',
                        'CITY'=>'Город',
                        'COUNTRY'=>'Деревня',
                        'SETTLEMENT'=>'Поселок',
                        'SETTLEMENT_TOWN'=>'Поселок гор. типа'])
                ],
                
            ];
        };
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \devskyfly\yiiModuleAdminPanel\controllers\AbstractContentPanelController::sectionEditorItems()
     */
    public function sectionEditorViews()
    {
        return null;
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \devskyfly\yiiModuleAdminPanel\controllers\AbstractContentPanelController::itemLabel()
     */
    public function itemLabel()
    {
        return "Населенные пункты";
    }
}