<?php
// 全局中间件定义文件
return [
  \think\middleware\AllowCrossDomain::class,
  \app\common\middleware\LimitedRequestFrequency::class
];
