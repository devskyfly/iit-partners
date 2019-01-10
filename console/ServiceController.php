<?php
namespace devskyfly\yiiModuleIitAgentsInfo\console;

use devskyfly\php56\types\Vrbl;
use devskyfly\yiiModuleIitAgentsInfo\models\Agent;
use devskyfly\yiiModuleIitAgentsInfo\models\Region;
use devskyfly\yiiModuleIitAgentsInfo\models\Settlement;
use devskyfly\yiiModuleIitAgentsInfo\tools\AgentUpdater;
use devskyfly\yiiModuleIitAgentsInfo\tools\Status;
use Yii;
use yii\console\Controller;
use yii\helpers\BaseConsole;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\console\ExitCode;

class ServiceController extends Controller
{
    /**
     * Send request to Lk and print result to stdout.
     * 
     * @throws \Exception
     * @return number
     */
    public function actionSendRequestToLk()
    {
        try {
            $this->module=Yii::$app->getModule('iit-agents-info');
            if(Vrbl::isNull($this->module))
            {
                throw new \Exception('Module "iit-agents-info" does not exist.');
            }
            
            $client=new Client();
            
            $request=$client
            ->createRequest()
            ->setMethod('GET')
            ->setHeaders([
                'Accept' => 'application/json;odata=verbose'
            ])
            ->addHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->module->lk_login . ':' . $this->module->lk_pass)
            ])->setUrl($this->module->lk_url);
            $data=$request->send();
            BaseConsole::stdout(print_r($data->getData(),true));
            
        }catch(\Exception $e){
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            return -1;
        }catch(\Throwable $e){
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            return -1;
        }
        return 0;
    }
    
    /**
     * Update agents and add settlements if it needs.
     * 
     * @return number
     */
    public function actionUpdateAgents()
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
     * Init region table from external file.
     * 
     * @return number
     */
    public function actionInitRegions()
    {
        $db=Yii::$app->db;
        $transaction=$db->beginTransaction();
        
        try{
            $json_file_path=__DIR__.'/../migrations/regions.json';
            $json=file_get_contents($json_file_path);
            
            $result=Json::decode($json,true);
            
            foreach ($result as $item){
                $region=new Region();
                $region->active='Y';
                $region->create_date_time=(new \DateTime())->format(\DateTime::ATOM);
                $region->change_date_time=(new \DateTime())->format(\DateTime::ATOM);
                $region->name=$item['name'];
                $region->str_nmb=$item['str_nmb'];
                $region->saveLikeItem();
            }
        }catch(\Throwable $e){
            $transaction->rollBack();
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            return ExitCode::UNSPECIFIED_ERROR;
        }
        catch(\Exception $e){
            $transaction->rollBack();
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            return ExitCode::UNSPECIFIED_ERROR;
        }
        $transaction->commit();
        return ExitCode::OK;
    }
    
    /**
     * Delete agents items.
     * 
     * @return number
     */
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