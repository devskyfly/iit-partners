<?php
namespace devskyfly\yiiModuleIitAgentsInfo\console;

use devskyfly\yiiModuleIitAgentsInfo\tools\AgentUpdater;
use Yii;
use yii\console\Controller;
use yii\helpers\BaseConsole;
use yii\helpers\Json;

class ServiceController extends Controller
{
    public function actionUpdateAgents()
    {
        try {
        $Updater=new AgentUpdater();
        $result=$Updater->update();
        }catch(\Exception $e){
            BaseConsole::stdout($e->getMessage().PHP_EOL);
            return -1;
        }catch (\Throwable $e){
            BaseConsole::stdout($e->getMessage().PHP_EOL);
            return -1;
        }        
        return 0;
        BaseConsole::stdout($result);
    }
}