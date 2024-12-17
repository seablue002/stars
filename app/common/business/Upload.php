<?php
namespace app\common\business;
use think\facade\Filesystem;

class Upload
{
  public function uploadFile ($file, $path, $dir_name) {
    $save_name = Filesystem::disk('public')->putFile($dir_name, $file);
    return "{$path}{$save_name}";
  }
}
