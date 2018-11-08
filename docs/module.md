## Module devskyfly/yii-module-iit-agents-info

### Подключение модуля

Модуль надо подключить как к web так и console.

```php
'iit-agents-info'=>[
    'class'=>'devskyfly\yiiModuleIitAgentsInfo\Module',
    'lk_login'=>'KozhevnikovA',
    'lk_pass'=>'8JxLkP4IQ2FV',
    'lk_url'=>'https://iitrust.lk/api/agent/points/dump/?format=json',
]
```


### Настройки модуля

 * $upload_public_agents - boolean указывает какие типы агентов загружать
 * $lk_login="" - логин доступа к api LK
 * $lk_pass="" - пароль доступа к api LK
 * $lk_url="" - url метода api LK

### Применение миграций

./yii migrate --migrationPath=@app/vendor/devskyfly/yii-module-iit-agents-info/migrations