<?php
namespace devskyfly\yiiModuleIitPartners\console;

use devskyfly\php56\types\Vrbl;
use Yii;
use yii\console\Controller;
use yii\helpers\BaseConsole;
use yii\httpclient\Client;

class LkController extends Controller
{
    /**
     * Send request to Lk and print result to stdout.
     * 
     * @throws \Exception
     * @return number
     */
    public function actionSendRequestForAgents()
    {
        try {
            $this->module=Yii::$app->getModule('iit-partners');
            if(Vrbl::isNull($this->module))
            {
                throw new \Exception('Module "iit-partners" does not exist.');
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
            $list=$data->getData();
            
            
            $itr=0;
            $lng=count($list);
            
            $excluded_types=[
                //'',
            //'attendant',
            'recruit',
            'operator',
            //'license',
            //'iit',
            'organization',
            'corporate',
            'medical',
            'group',
            'corp_without_upload'         
            ]; 

            for($itr=0;$itr<$lng;$itr++){
                $item=$list[$itr];
                if($item['point_type']==16){
                    unset($list[$itr]);
                }

                if($item['blocked']){
                    unset($list[$itr]);
                }

                if($item['point_is_technical']==1){
                    unset($list[$itr]);
                }
                if(in_array($item['parent']['agent_type'],$excluded_types)){
                    unset($list[$itr]);
                }
            }
            
            BaseConsole::stdout(print_r(array_values($list),true));
            
        }catch(\Exception $e){
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            return -1;
        }catch(\Throwable $e){
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            return -1;
        }
        return 0;
    }
    
    public function actionSendRequestForOrgs()
    {
        try {
            $this->module=Yii::$app->getModule('iit-partners');
            if(Vrbl::isNull($this->module))
            {
                throw new \Exception('Module "iit-partners" does not exist.');
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
            ])->setUrl($this->module->lk_org_url);
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
}