<?php
use app\home\paginator\CustomPaginator;

// 容器Provider定义文件
return [
  'think\Paginator' => CustomPaginator::class,
];
