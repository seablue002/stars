<?php


namespace app\common\lib;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use app\common\exception\ApiException;

class ExportExcel
{
  // 表格名称
  public $excelTableTitle = null;
  // excel表格头部标题
  public $headerTitle = null;
  // 数据列名称(字段名)
  public $dataColumn = null;
  // 需要导出excel的数据列表
  public $data = null;

  public function __construct($excelTableTitle, $headerTitle, $dataColumn, $data)
  {
    $this->excelTableTitle = $excelTableTitle;
    $this->headerTitle = $headerTitle;
    $this->dataColumn = $dataColumn;
    $this->data = $data;
  }

  public function doExport ($mode = '', $dir = '') {
    $spreadsheet = new Spreadsheet();
    $worksheet = $spreadsheet->getActiveSheet();
    $worksheet->setTitle($this->excelTableTitle);
    //表头
    //设置单元格内容
    foreach ($this->headerTitle as $index => $header_title) {
      $worksheet->setCellValueByColumnAndRow($index + 1, 1, $header_title);
    }

    $len = count($this->data);
    $j = 0;
    for ($i=0; $i < $len; $i++) {
      $j = $i + 2; //从表格第2行开始
      foreach ($this->dataColumn as $index => $column_name) {
        $worksheet->setCellValueByColumnAndRow($index + 1, $j, $this->data[$i][$column_name]);
      }
    }

    $styleArrayBody = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          'color' => ['argb' => '666666'],
        ],
      ],
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      ],
    ];
    $total_rows = $len + 2;
    //添加所有边框/居中
    $worksheet->getStyle('A1:K'.$total_rows)->applyFromArray($styleArrayBody);

    $filename = $this->excelTableTitle . date('Y-m-d-H-i-s') . '.xlsx';
    if (empty($mode)) {
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="'.$filename.'"');
      header('Cache-Control: max-age=0');

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save('php://output');
    } else if ($mode === 'file-url') {
      $dirname = __DIR__ . "/../../../public/storage/{$dir}";
      if (!is_dir($dirname)) {
        try {
          mkdir($dirname, 0775, true);
        } catch (\Throwable $e) {
          throw new ApiException('导出数据失败');
        }
      }
      $writer = new Xlsx($spreadsheet);
      $writer->save(__DIR__ . "/../../../public/storage/{$dir}/{$filename}");
    }
    return $filename;
  }
}