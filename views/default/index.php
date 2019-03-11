<?php
/* $this yii/web/view */
/* $list []*/
/* $title string */
use devskyfly\yiiModuleAdminPanel\widgets\common\NavigationMenu;

?>
<?
$this->title=$title;
?>

<div class="col-xs-3">
<?=NavigationMenu::widget(['list'=>$list])?>
</div>