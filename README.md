# 根据客户端ip进行投票限制

### 安装
```shell
composer require oliver-garfield/vote
```

### 使用
```php
<?php

use OliverGarfield\Vote\VoteFacade;

include_once "vendor/autoload.php";

$config = [
    "ip_nums"=>5 //每个ip可投票次数
];

try
{
    $vote = new VoteFacade($config);
}
catch (Exception $e)
{
}
if(VoteFacade::checkCan())
{
    VoteFacade::setNum();
    // 成功
}
else
{
    // 失败，投票次数已经用尽
}

```
