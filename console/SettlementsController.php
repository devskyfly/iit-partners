<?php
namespace devskyfly\yiiModuleIitPartners\console;

use devskyfly\php56\types\Vrbl;
use devskyfly\yiiModuleIitPartners\models\Agent;
use devskyfly\yiiModuleIitPartners\models\Region;
use devskyfly\yiiModuleIitPartners\models\Settlement;
use devskyfly\yiiModuleIitPartners\tools\AgentUpdater;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\BaseConsole;
use yii\helpers\Json;
use yii\httpclient\Client;

class AgentsController extends Controller
{
    /**
     * Delete Settlements items.
     * 
     * @return number
     */
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