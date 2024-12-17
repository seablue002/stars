<?php
/**
 * HTTP响应消息类
 */

namespace app\common\lib;


class ResponseMessage
{
  protected $code;
  protected $status;
  protected $data;
  protected $message;
  
  /**
   * 返回成功消息
   * @param string $message 提示消息
   * @param $data 返回的数据
   * @return \think\response\Json
   */
  public function success($message = 'ok', $data = [])
  {
    $this->code = 200;
    $this->status = 200;
    $this->message = $message;
    $responseData = self::parseResponseData($this, $data);
    return json($responseData, $this->code);
  }
  
  /**
   * 返回失败消息
   * @param string $message 提示消息
   * @param $data 返回的数据
   * @return \think\response\Json
   */
  public function fail($message = 'fail', $data = [])
  {
    $this->code = 200;
    $this->status = 400;
    $this->message = $message;
    $responseData = self::parseResponseData($this, $data);
    return json($responseData, $this->code);
  }

  /**
   * 返回错误消息
   * @param string $message 提示消息
   * @param $data 返回的数据
   * @return \think\response\Json
   */
  public function error($message = 'error', $data = [])
  {
    $this->code = 200;
    $this->status = 400;
    $this->message = $message;
    $responseData = self::parseResponseData($this, $data);
    return json($responseData, $this->code);
  }
  
  /**
   * 合并整合返回的数据
   * @param $thisInstance ResponseMessage类实例
   * @param $data 返回的data数据
   * @return array
   */
  private static function parseResponseData($thisInstance, $data = null)
  {
    $tempResponseData =  [
      'status' => $thisInstance->status,
      'message' => $thisInstance->message
    ];
    if ($data !== null) {
      $thisInstance->data = $data;
      $tempResponseData['data'] = $thisInstance->data;
    }
    return $tempResponseData;
  }
}
