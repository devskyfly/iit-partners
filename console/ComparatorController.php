<?php
namespace devskyfly\yiiModuleIitPartners\console;

use devskyfly\php56\types\Vrbl;
use Yii;
use yii\console\Controller;
use yii\helpers\BaseConsole;
use yii\httpclient\Client;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ComparatorController extends Controller
{
    /**
     * Send request to Lk and print result to stdout.
     * 
     * @throws \Exception
     * @return number
     */
    public function actionCmp()
    {
        try {
            //#############################################################################################################
            $excluded_types=[
                
            ]; 
            $stocks=$this->getData('https://iitrust.lk/api/agent/points?format=json&stocks=15,22,25', $excluded_types);
            $stocks_ids=\array_column($stocks, 'id');
            
            $excluded_types=[

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
            $external=$this->getData('https://iitrust.lk/api/agent/points?format=json', $excluded_types);
            $external_ids=\array_column($external, 'id');

            //#############################################################################################################
            $excluded_types=[

            //'attendant',
            'recruit',
            //'operator',
            //'license',
            //'iit',
            'organization',
            'corporate',
            'medical',
            'group',
            'corp_without_upload'         
            ]; 
            
            //#############################################################################################################
            $internal=$this->getData('https://iitrust.lk/api/agent/points?format=json', $excluded_types);
            $internal_ids=\array_column($internal, 'id');

            $ids = \array_merge($stocks_ids, $external_ids, $internal_ids);
            $ids = \array_unique($ids);
            \sort($ids, SORT_ASC);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();    

            $sheet->setCellValue('A1', 'id');
            $sheet->setCellValue('B1', 'stocks');
            $sheet->setCellValue('C1', 'internal');
            $sheet->setCellValue('D1', 'external');

            $itr=1;
            foreach ($ids as $key => $id) {
                $itr++;
                $sheet->setCellValue('A'.$itr, $id);
                $content='';
                $item = $this->getItem($stocks,$id);
                if(!is_null($item)) $content=print_r($item, true);
                $sheet->setCellValue('B'.$itr, is_null($item)?'':$item['plain_title']);
                $item = $this->getItem($internal,$id);
                if(!is_null($item)) $content=print_r($item, true);
                $sheet->setCellValue('C'.$itr, is_null($item)?'':$item['plain_title']);
                $item = $this->getItem($external,$id);
                if(!is_null($item)) $content=print_r($item, true);
                $sheet->setCellValue('D'.$itr, is_null($item)?'':$item['plain_title']);
                $sheet->setCellValue('E'.$itr, $content);
            }
            
            $writer = new Xlsx($spreadsheet);
            $writer->save(__DIR__.'/cpm.xlsx');
            
        }catch(\Exception $e){
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            return -1;
        }catch(\Throwable $e){
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            return -1;
        }
        return 0;
    }

    protected function getItem($array,$id)
    {
        foreach ($array as $key => $item) {
            if($item['id']==$id) return $item;
        }
        return null;
    }

    protected function getData($url, $excluded_types)
    {
        $client=new Client();
            
        $request=$client
        ->createRequest()
        ->setMethod('GET')
        ->setHeaders([
            'Accept' => 'application/json;odata=verbose'
        ])
        ->addHeaders([
            'Authorization' => 'Basic ' . base64_encode('KozhevnikovA:8JxLkP4IQ2FV')
        ])->setUrl($url);
        $data=$request->send();
        $list=$data->getData();
        $lng=count($list);

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
        
       return array_values($list);
    }
}