<?php
namespace devskyfly\yiiModuleIitAgentsInfo\console;

use devskyfly\yiiModuleIitAgentsInfo\models\Agent;
use devskyfly\yiiModuleIitAgentsInfo\models\Settlement;
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
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            return -1;
        }catch (\Throwable $e){
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            return -1;
        }        
        
        BaseConsole::stdout($result['status']);
        return 0;
    }
    
    public function actionClearAgents()
    {
       $result='';
       try {
           $result=Agent::truncateLikeItems();
       }catch(\Exception $e){
           BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
           return -1;
       }catch (\Throwable $e){
           BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
           return -1;
       }
       BaseConsole::stdout('Удалено: '.$result.' строк.'.PHP_EOL);
       return 0;
    }
    
    public function actionClearSettlements()
    {
        $result='';
        try {
            $result=Settlement::truncateLikeItems();
        }catch(\Exception $e){
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            return -1;
        }catch (\Throwable $e){
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            return -1;
        }
        BaseConsole::stdout('Удалено: '.$result.' строк.'.PHP_EOL);
        return 0;
    }
}