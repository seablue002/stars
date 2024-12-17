<?php


namespace app\home\controller;

use think\App;
use app\common\business\SystemConfig AS SystemConfigBusiness;
use think\Request;
use think\Template;
use app\home\business\Column as ColumnBusiness;
use app\home\business\TplVar as TplVarBusiness;
use app\common\lib\Utils;

class Index extends IndexBase
{
  private $template = null;
  private $columnBusiness = null;
  private $tplVarBusiness = null;
  private $utils = null;
  private $systemConfigBusiness = null;

  public function __construct(App $app)
  {
    parent::__construct($app);
    $this->template = new Template();
    $this->columnBusiness = new ColumnBusiness;
    $this->tplVarBusiness = new TplVarBusiness;
    $this->utils = new Utils;
    $this->systemConfigBusiness = new SystemConfigBusiness;
  }

  public function index(Request $request)
  {
    $route_params = explode('/', $request->pathinfo());
    $route_params_len = count($route_params);

    // 提取最后一个pathinfo参数
    $route_params_last = $route_params[$route_params_len - 1];
    // 提取?get请求参数
    $search_params = [
      // 标签
      'label' => input('get.label'),
      // 页码
      'page' => input('get.page')
    ];

    // 获取请求参数
    $tpl_catch_file_name = implode('/', $route_params);

    if (preg_match("/^\d+\.html$/", $route_params_last)) {
      $dir_path = implode('/', array_slice($route_params, 0, $route_params_len - 1));
    } else {
      $dir_path = implode('/', $route_params);
      $dir_path = preg_replace('/_\d+\.html$/', '.html', $dir_path);
      $dir_path = preg_replace('/\.html$/', '', $dir_path);
    }

    $where = "CONCAT(parent_dir_path, column_dir_path) = :path";
    $column = $this->columnBusiness->getColumnDetail($where, ['path' => $dir_path]);
    if (empty($column)) {
      die('栏目不存在');
    }
    $catche_tpl_name = root_path() . 'public\\tpl_catche\\home\\' . $tpl_catch_file_name;

    if (preg_match("/^\d+\.html$/", $route_params_last) || ($column->is_last === 2)) {
      if (preg_match('/.html$/', $catche_tpl_name) === 0) {
        $catche_tpl_name .= '.html';
      }
    } else {
      // 列表页带分页码
      if (preg_match('/.html$/', $catche_tpl_name) === 0) {
        if (!isset($search_params['page'])) {
          // 列表页无页码参数默认第一页
          $catche_tpl_name .= '.html';
        } else {
          $catche_tpl_name .= '_' . $search_params['page'] . '.html';
        }
      }
    }


    // 判断模板缓存时长是否过期, 在有效期内，则直接读取缓存tpl模板显示
    // 获取系统配置
    $system_config = $this->systemConfigBusiness->getConfigValue(['tpl_cache_expire']);
    if (is_file($catche_tpl_name) && (time() - filemtime($catche_tpl_name) < $system_config['tpl_cache_expire']['value'])) {
      $this->template->fetch($catche_tpl_name);
      die;
    }

    // 根据请求参数区分是详情访问，还是列表或者单页访问
    if (preg_match("/^\d+\.html$/", $route_params_last)) {
      // 访问详情
      // 提取id参数值
      $id = explode('.', $route_params_last)[0];

      // 获取详情所属的栏目信息
      $dir_path = implode('/', array_slice($route_params, 0, $route_params_len - 1));
      $nt_cms_model = $this->getColumnModelTplInfo($dir_path);

      $model_tb_name = $nt_cms_model['model_tb_name'];
      // 组装拼接内容对应的数据表模型路径
      $model_str = '\app\common\model\mysql\\' . $model_tb_name;
      // 实例化数据表模型
      $model = new $model_str();
      // 使用id获取详情内容数据
      $detail = $model->getOne(['id' => $id]);

      $tpl = $nt_cms_model['tpl'];
      $tpl['content'] = preg_replace('/{\$detail\[\'content\'\]}/', '{$detail[\'content\'] | raw}', $tpl['content']);

      $search_params['id'] = $id;
      $tpl_var_data = [
        'detail' => $detail
      ];
    } else {
      // 访问列表或单页
      $mode = 'listOrSingle';
      /* 组装栏目访问目录路径 上层栏目目录(上层栏目目录可以是多个dir1/dir2/...
      , 可以二级、三级及以上更多层级)/本栏目目录 */
      $dir_path = implode('/', $route_params);
      $dir_path = preg_replace('/_\d+\.html$/', '.html', $dir_path);
      $dir_path = preg_replace('/\.html$/', '', $dir_path);
      $nt_cms_model = $this->getColumnModelTplInfo($dir_path, $mode);

      $model_tb_name = $nt_cms_model['model_tb_name'];
      // 组装拼接内容对应的数据表模型路径
      $model_str = '\app\common\model\mysql\\' . $model_tb_name;
      // 实例化数据表模型
      $model = new $model_str();

      $column = $nt_cms_model['column'];
      $tpl = $nt_cms_model['tpl'];

      if (in_array($column['is_last'], [0, 1])) {
        // 列表
        // 使用column_id获取列表数据

        $where = ['column_id' => $column['id']];

        $where_str = '';

        if (!empty($search_params['label'])) {
          // 如果有label标签参数，使用label进行筛选
          $label = explode(',', $search_params['label']);

          $where_str = '';
          foreach ($label as $lab) {
            $where_str .= " FIND_IN_SET({$lab}, label_ids) OR";
          }
          $where_str = rtrim($where_str, ' OR');
        }

        $list = $model->getPageListWithPageHtml($where, $where_str, '*', 10);

        $tpl_var_data = [
          'tpl_list_item_var' => (function () use ($tpl) {
            return function ($vo) use ($tpl) {
              $this->template->display($tpl['item_var'], ['vo' => $vo]);
//              $this->template->__set('vo', $vo);
//              $a = $tpl['item_var'];
//              $this->template->parse($a);
//              echo $a;
            };
          })(),
          'list' => $list
        ];
      } else if ($column['is_last'] === 2) {
        // 单页
        $tpl_var_data = [];
      }
    }

    /*公共通用数据组装*/
    // 1、头部导航数据列表
    $tpl_var_data['nav_list'] = $this->columnBusiness->getColumnNormalInfoList();
    $temp_column_list = [];
    $this->utils->getChildrenCategoryTree($temp_column_list, $tpl_var_data['nav_list'], 0);
    $tpl_var_data['nav_list'] = $temp_column_list;

    // 2、栏目信息
    $tpl_var_data['column'] = $column;

    // 3、$request
    $tpl_var_data['request'] = $request;

    // 4、请求参数
    $tpl_var_data['search_params'] = $search_params;

    // 查询数据库提取所有可用状态的公共模板变量数据
    $tpl_var_list = $this->tplVarBusiness->getTplValList();
    // 变量公共模板变量，对模板内容$tpl['content']进行变量字符标识替换
    foreach ($tpl_var_list as $tpl_var) {
      $var_mark = '[!--temp_var.' . $tpl_var['var_key'] . '--!]';
      $tpl['content'] = str_replace($var_mark, $tpl_var['var_value'], $tpl['content']);
    }

    ob_start();
    $this->template->display($tpl['content'], $tpl_var_data);
    $tpl_html = ob_get_contents();
    $fp = fopen($catche_tpl_name, 'w');
    if ($fp) {
      fwrite($fp, $tpl_html);
      fclose($fp);
    }
  }

  // 获取栏目、数据表对应模型、模板
  protected function getColumnModelTplInfo ($dir_path, $mode = 'detail') {
    $where = "CONCAT(parent_dir_path, column_dir_path) = :path";
    $column = $this->columnBusiness->getColumnDetail($where, ['path' => $dir_path]);

    if (empty($column)) {
      die('栏目不存在');
    }

    if ($mode === 'detail') {
      if ($column['is_last'] !== 1) {
        die('所属栏目非终极栏目，请调整栏目');
      }
    }

    // 从栏目数据中整理出对应的数据表模型
    $model_tb_name_words = explode('_', $column['model_tb_name']);
    $model_tb_name_words = array_map(function ($name_words) {
      return ucfirst($name_words);
    }, $model_tb_name_words);
    $model_tb_name = implode('', $model_tb_name_words);

    if ($mode === 'detail') {
      // 获取内容所属栏目对应的详情模板
      // 组装拼接详情模板对应的数据表模型路径
      $model_tpl_str = '\app\common\model\mysql\\' . $model_tb_name . 'Temp';
      // 实例化数据表模型
      $model_tpl = new $model_tpl_str();
      // 详情模式，获取对应详情模板
      $tpl = $model_tpl->getOne(['id' => $column['detail_temp_id']]);
    }
    else if ($mode === 'listOrSingle') {
      if (in_array($column['is_last'], [0, 1])) {
        // 获取内容所属栏目对应的列表模板
        // 组装拼接列表模板对应的数据表模型路径
        $model_tpl_str = '\app\common\model\mysql\\' . $model_tb_name . 'ListTemp';
        // 实例化数据表模型
        $model_tpl = new $model_tpl_str();

        // 列表模式，获取对应列表模板
        $tpl = $model_tpl->getOne(['id' => $column['list_temp_id']]);
      } else if ($column['is_last'] === 2) {
        // 获取单页对应的模板
        // 组装拼接单页详情模板对应的数据表模型路径
        $model_tpl_str = '\app\common\model\mysql\\' . $model_tb_name . 'Temp';
        // 实例化数据表模型
        $model_tpl = new $model_tpl_str();
        // 详情模式，获取对应详情模板
        $tpl = $model_tpl->getOne(['id' => $column['detail_temp_id']]);
      }
    }

    if (empty($tpl->toArray())) {
      die('模板获取失败，请设置栏目对应模板');
    }

    return [
      'column' => $column,
      'model_tb_name' => $model_tb_name,
      'tpl' => $tpl
    ];
  }
}
