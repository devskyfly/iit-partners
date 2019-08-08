## yii-module-iit-uc

[Докуметация](docs/api/index.html)

Модуль позволяет управлять [сущностями](docs/api/namespaces/devskyfly.yiiModuleIitUc.models.html):

* Регионы
* Населенные пункты
* Агенты

Модуль имеет свое rest [api](docs/api/namespaces/devskyfly.yiiModuleIitUc.controllers.rest.html).

### Консольные команды:

iit-partners/agents                              
iit-partners/agents/clear                      Delete agents items.
iit-partners/agents/reset-need-to-custom-flag
iit-partners/agents/update                     Update agents and add settlements if it needs.

iit-partners/lk                                  
iit-partners/lk/send-request-for-agents        Send request to Lk and print result to stdout.
iit-partners/lk/send-request-for-orgs

iit-partners/regions                             
iit-partners/regions/clear                     Clear regions.
iit-partners/regions/init                      Init region table from external file.

iit-partners/settlements                         
iit-partners/settlements/clear                 Delete Settlements items.

### Подключение модуля

Модуль надо подключить как к web так и console.

```php
'iit-partners'=>[
    'class'=>'devskyfly\yiiModuleIitPartners\Module',
    'lk_login'=>'KozhevnikovA',
    'lk_pass'=>'8JxLkP4IQ2FV',
    'lk_url'=>'https://iitrust.lk/api/agent/points/dump/?format=json',
]
```


### Настройки модуля

 * $lk_login="" - логин доступа к api LK
 * $lk_pass="" - пароль доступа к api LK
 * $lk_url="" - url метода api LK

### Применение миграций

./yii migrate --migrationPath=@app/vendor/devskyfly/yii-module-iit-agents-info/migrations