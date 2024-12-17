<?php

namespace app\home\business;
use app\common\model\mysql\PublicTempVar AS PublicTempVarModel;

class TplVar
{
  private $tplVarModel = null;

  public function __construct()
  {
    $this->tplVarModel = new PublicTempVarModel;
  }

  public function getTplValList()
  {
    $list = $this->tplVarModel->getList();

    return $list;
  }

}
