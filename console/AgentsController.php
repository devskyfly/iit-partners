<?php
namespace devskyfly\yiiModuleIitPartners\console;

use devskyfly\yiiModuleIitPartners\models\Agent;
use devskyfly\yiiModuleIitPartners\tools\AgentUpdater;
use yii\console\Controller;
use yii\helpers\BaseConsole;

class AgentsController extends Controller
{
    /**
     * Update agents and add settlements if it needs.
     * 
     * @return number
     */
    public function actionUpdate()
    {
        try {
            $Updater=new AgentUpdater();
            $result=$Updater->update();
            $result=$Updater->clear();
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
    
    /**
     * Delete agents items.
     * 
     * @return number
     */
    public function actionClear()
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
}