<?php
/* $this yii/web/view */
/* $list []*/
/* $title string */
use app\widgets\NavigationMenu;
?>

<?
$this->title=$title;
?>
<div class="row">
	<div class="col-xs-12">
		<h2><?=$title?></h2>	
	</div>
</div>
<div class="col-xs-3">
<?=NavigationMenu::widget(['list'=>$list])?>
</div>