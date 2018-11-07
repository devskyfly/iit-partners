<?php
namespace devskyfly\yiiModuleIitAgentsInfo\tools;

use devskyfly\php56\types\Vrbl;
use devskyfly\yiiModuleIitAgentsInfo\models\Agent;
use Yii;
use yii\base\BaseObject;
use yii\httpclient\Client;
use yii\helpers\Json;
use yii\helpers\BaseConsole;

class AgentUpdater extends BaseObject
{
    /**
     * 
     * @var \yii\base\Module
     */
    protected $module;
    
    /**
     * 
     * @var \yii\httpclient\Client
     */
    protected $client;
    
    public function init()
    {
        $this->module=Yii::$app->getModule('iit-agents-info');
        
        if(Vrbl::isNull($this->module))
        {
            throw new \Exception('Module "iit-agents-info" does not exist.');
        }
        
        $this->initClient();
    }
    
    public function update()
    {
        $request=$this->createRequest();
        $response=$request->send();
        $agents=$response->getData();
        $db=Yii::$app->db;
        $transaction=$db->beginTransaction();
        
        try{
            foreach ($agents as $agent_item){
                $agent=Agent::findByGuid($agent_item['guid']);
                if(Vrbl::isNull($agent)){
                    if(!$this->addAgent($agent_item)){
                        throw new \Exception('Can\'t save '.$agent_item['title'].'-'.$agent_item['guid']);
                    }
                }else{
                    if(!$this->addUpdate($agent_item)){
                        throw new \Exception('Can\'t update '.$agent_item['title'].'-'.$agent_item['guid']);
                    }
                }
            }
        }catch(\Exception $e){
            $transaction->rollBack();
            BaseConsole::stdout($e->getMessage().PHP_EOL);
            return -1;
        }catch (\Throwable $e){
            $transaction->rollBack();
            BaseConsole::stdout($e->getMessage().PHP_EOL);
            return -1;
        }
        $transaction->commit();
        return 0;
    }
    
    /**
     * 
     * @param [] $data
     * @return bool
     */
    protected function addAgent($data)
    {
        $model=new Agent();
        $model->active='Y';
        $model->create_date_time=(new \DateTime())->format(\DateTime::ATOM);
        $model->change_date_time=(new \DateTime())->format(\DateTime::ATOM);
        $model->name=$data['title'];
        
        $model->manager_in_charge=$data['manager_in_charge'];
        
        $model->custom_address=$data['address'];
        $model->lk_address=$data['address'];
        $model->phone=$data['phone'];
        $model->email=$data['email'];
        
        $model->lng=$data['longitude'];
        $model->lat=$data['latitude'];
        
        $model->flag_is_need_to_custom='Y';
        if(mb_ereg_match('^[ \s\S]*?инфотекс[ \s\S]*?$',$data['title'],'i')===true){
            $model->flag_is_own='Y';
        }else{
            $model->flag_is_own='N';
        }
        $model->flag_is_license=$data['point_licensee_type']==16?'Y':'N';
        $model->flag_is_public=$data['point_type']==8?'Y':'N';
        
        return $model->saveLikeItem();
    }
    
    /**
     * 
     * @param Agent $omdel
     * @param [] $data
     */
    protected function updateAgent($model,$data)
    {
        
    }
    
    protected function createRequest()
    {
        $request=$this->client
        ->createRequest()
        ->setMethod('GET')
        ->setHeaders([
            'Accept' => 'application/json;odata=verbose'
        ])
        ->addHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->module->lk_login . ':' . $this->module->lk_pass)
        ])->setUrl($this->module->lk_url);
        return $request;
    }
    
    protected function initClient()
    {
        $this->client=new Client();
    }
    
    
}