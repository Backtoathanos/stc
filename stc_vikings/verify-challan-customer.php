<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
require_once 'kattegat/auth_helper.php';
STCAuthHelper::checkAuth();

include "../MCU/db.php";

function stc_challan_last_bracket($text){
  $text = trim((string) $text);
  if($text === '' || substr($text, -1) !== ')') return '';
  $depth = 0;
  $len = strlen($text);
  for($i = $len - 1; $i >= 0; $i--){
    $ch = $text[$i];
    if($ch === ')') $depth++;
    elseif($ch === '('){
      $depth--;
      if($depth === 0){
        return trim(substr($text, $i + 1, $len - $i - 2));
      }
    }
  }
  return '';
}

function stc_challan_is_wo_code($s){
  $s = trim((string) $s);
  if($s === '' || strpos($s, '/') === false) return false;
  return !preg_match('/[A-Za-z]{3,}(?:\s+[A-Za-z0-9#.\-]{2,})+/', $s);
}

function stc_challan_site_label($sitename, $prLocation){
  $sitename = trim(preg_replace('/\s+/', ' ', (string) $sitename));
  $prLocation = trim(preg_replace('/\s+/', ' ', (string) $prLocation));
  if($sitename === ''){
    return ($prLocation === '' || $prLocation === '-') ? '' : $prLocation;
  }
  if($prLocation === '' || $prLocation === '-' || strcasecmp($sitename, $prLocation) === 0){
    return $sitename;
  }
  if(stripos($sitename, $prLocation) !== false){
    return $sitename;
  }
  return $sitename.' ('.$prLocation.')';
}

function stc_challan_combination_label($sitename, $prLocation){
  $sitename = trim(preg_replace('/\s+/', ' ', (string) $sitename));
  $prLocation = trim(preg_replace('/\s+/', ' ', (string) $prLocation));
  $source = ($prLocation !== '' && $prLocation !== '-') ? $prLocation : $sitename;
  if($source === '') return '';
  $last = stc_challan_last_bracket($source);
  if($last !== '' && !stc_challan_is_wo_code($last)){
    return $last;
  }
  if($prLocation === '' || $prLocation === '-'){
    $fromSite = stc_challan_last_bracket($sitename);
    if($fromSite !== '' && !stc_challan_is_wo_code($fromSite)){
      return $fromSite;
    }
  }
  return $source;
}

function stc_challan_slot($value, $width){
  $value = trim((string) $value);
  if($value === '' || $value === '—' || $value === '-'){
    $value = '';
  }
  if(strlen($value) > $width){
    $value = substr($value, 0, $width);
  }
  return '<span class="gas-slot" style="width:'.$width.'ch">'.htmlspecialchars($value).'</span>';
}

function stc_challan_is_tata_steel_amc($site_label, $to_site, $to_lines = array()){
  $checks = array($site_label, $to_site);
  foreach((array) $to_lines as $line){
    $checks[] = $line;
  }
  foreach($checks as $val){
    if(strcasecmp(trim((string) $val), 'TATA STEEL AMC') === 0){
      return true;
    }
  }
  return false;
}

function stc_challan_row_sitename($row){
  $site = trim((string) ($row['sitename'] ?? ''));
  if($site === ''){
    $site = trim((string) ($row['display_site'] ?? ''));
  }
  if($site === '') return '';
  // e.g. "SP#1 (PWOG/00192/25-26) (TATA STEEL AMC)" -> "SP#1"
  if(preg_match('/^([^(]+)/', $site, $m)){
    return trim($m[1]);
  }
  return $site;
}

function stc_challan_data_uri($path){
  if(!is_file($path)) return '';
  $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
  $mime = ($ext === 'png') ? 'image/png' : 'image/jpeg';
  return 'data:'.$mime.';base64,'.base64_encode(file_get_contents($path));
}

function stc_challan_export_filename($challan_no, $date){
  $base = trim($challan_no) !== '' ? $challan_no : 'GAS-'.$date;
  return preg_replace('/[^A-Za-z0-9._-]+/', '-', $base).'-customer-challan';
}

function stc_customer_challan_document_html($meta, $opts = array()){
  $headerSrc = isset($opts['header_src']) ? $opts['header_src'] : stc_challan_data_uri(__DIR__.'/images/gas-header.jpg');
  $wmSrc = isset($opts['wm_src']) ? $opts['wm_src'] : stc_challan_data_uri(__DIR__.'/images/gas-watermark.png');
  $forWord = !empty($opts['for_word']);
  $challanDigits = preg_replace('/^GAS\s*/i', '', $meta['challan_no']);
  $toHtml = $meta['to_lines'] ? htmlspecialchars(implode("\n", $meta['to_lines'])) : '—';
  $showSitename = !empty($meta['show_sitename']);
  $colCount = $showSitename ? 5 : 4;
  $rowsHtml = '';
  $sl = 0;
  foreach($meta['rows'] as $row){
    $sl++;
    $siteCell = $showSitename ? '<td style="border:1px solid #111;padding:3px 6px;font-size:12px;">'.htmlspecialchars(stc_challan_row_sitename($row)).'</td>' : '';
    $rowsHtml .= '<tr>'
      .'<td class="sl" style="border:1px solid #111;padding:3px 6px;font-size:12px;text-align:center;width:55px;">'.$sl.'</td>'
      .$siteCell
      .'<td style="border:1px solid #111;padding:3px 6px;font-size:12px;">'.nl2br(htmlspecialchars(trim((string)$row['item_desc']))).'</td>'
      .'<td class="c" style="border:1px solid #111;padding:3px 6px;font-size:12px;text-align:center;width:80px;">'.number_format((float)$row['accepted_qty'], 2).'</td>'
      .'<td class="c" style="border:1px solid #111;padding:3px 6px;font-size:12px;text-align:center;width:70px;">'.htmlspecialchars($row['unit']).'</td>'
      .'</tr>';
  }
  $blank = max(0, (int)$meta['blank_rows']);
  for($i = 0; $i < $blank; $i++){
    $blankSite = $showSitename ? '<td style="border:1px solid #111;padding:3px 6px;height:18px;">&nbsp;</td>' : '';
    $rowsHtml .= '<tr><td class="sl" style="border:1px solid #111;padding:3px 6px;height:18px;">&nbsp;</td>'.$blankSite.'<td style="border:1px solid #111;padding:3px 6px;">&nbsp;</td><td style="border:1px solid #111;padding:3px 6px;">&nbsp;</td><td style="border:1px solid #111;padding:3px 6px;">&nbsp;</td></tr>';
  }
  if($rowsHtml === ''){
    $rowsHtml = '<tr><td colspan="'.$colCount.'" class="c" style="border:1px solid #111;padding:6px;text-align:center;">No accepted items found for this date.</td></tr>';
  }
  $thead = $showSitename
    ? '<tr>'
      .'<th style="border:1px solid #111;padding:3px 6px;font-size:12px;text-align:center;width:55px;">SL NO</th>'
      .'<th style="border:1px solid #111;padding:3px 6px;font-size:12px;text-align:center;width:90px;">SITENAME</th>'
      .'<th style="border:1px solid #111;padding:3px 6px;font-size:12px;text-align:center;">MATERIAL DESCRIPTION</th>'
      .'<th style="border:1px solid #111;padding:3px 6px;font-size:12px;text-align:center;width:80px;">QUANTITY</th>'
      .'<th style="border:1px solid #111;padding:3px 6px;font-size:12px;text-align:center;width:70px;">UNIT</th>'
      .'</tr>'
    : '<tr>'
      .'<th style="border:1px solid #111;padding:3px 6px;font-size:12px;text-align:center;width:55px;">SL NO</th>'
      .'<th style="border:1px solid #111;padding:3px 6px;font-size:12px;text-align:center;">MATERIAL DESCRIPTION</th>'
      .'<th style="border:1px solid #111;padding:3px 6px;font-size:12px;text-align:center;width:80px;">QUANTITY</th>'
      .'<th style="border:1px solid #111;padding:3px 6px;font-size:12px;text-align:center;width:70px;">UNIT</th>'
      .'</tr>';

  $headerHtml = '';
  if($headerSrc !== ''){
    if($forWord){
      $headerHtml = '<p style="margin:0;padding:0;text-align:left;">'
        .'<img src="'.$headerSrc.'" width="680" height="110" alt="Global AC System" style="width:680px;height:110px;border:0;display:block;">'
        .'</p>';
    }else{
      $headerHtml = '<img class="hdr" src="'.$headerSrc.'" alt="Global AC System">';
    }
  }

  $wmHtml = '';
  if($wmSrc !== ''){
    if($forWord){
      $wmHtml = '<!--[if gte vml 1]>'
        .'<v:shape id="Watermark" o:preferrelative="t" o:spt="75" type="#_x0000_t75" '
        .'style="position:absolute;margin-left:90pt;margin-top:120pt;width:320pt;height:320pt;z-index:-1;visibility:visible;" filled="f" stroked="f">'
        .'<v:imagedata src="'.$wmSrc.'" o:title="watermark"/>'
        .'<w:wrap type="none"/>'
        .'<w:anchorlock/>'
        .'</v:shape>'
        .'<![endif]-->';
    }else{
      $wmHtml = '<img class="wm" src="'.$wmSrc.'" alt="">';
    }
  }

  $pageCss = $forWord
    ? '@page Section1 { size: 595.3pt 841.9pt; margin: 28pt 36pt 36pt 36pt; }
       div.Section1 { page: Section1; }
       body { margin: 0; padding: 0; font-family: "Times New Roman", Times, serif; color: #111; }
       img { border: 0; }
       table { border-collapse: collapse; }
       .title { text-align: center; font-weight: 700; font-size: 15pt; margin: 8pt 0 10pt; line-height: 1.25; }
       .meta { width: 100%; margin-bottom: 8pt; }
       .meta td { vertical-align: top; font-weight: 700; font-size: 12pt; }
       .meta .right { text-align: right; white-space: nowrap; width: 48%; }
       .items { width: 100%; }
       .sign { text-align: right; font-weight: 700; font-size: 12pt; margin-top: 22pt; }
       .footer { margin-top: 18pt; text-align: center; font-family: Arial, Helvetica, sans-serif; font-size: 8pt; line-height: 1.4; }'
    : '@page { margin: 0; size: A4 portrait; }
       * { box-sizing: border-box; }
       body { margin: 0; padding: 0; font-family: "Times New Roman", Times, serif; color: #111; }
       .sheet { position: relative; width: 210mm; min-height: 297mm; }
       .hdr { width: 210mm; display: block; }
       .wm { position: absolute; left: 16%; top: 90mm; width: 68%; opacity: 0.45; z-index: 0; }
       .body { position: relative; z-index: 1; padding: 6mm 14mm 18mm; }
       .title { text-align: center; font-weight: 700; font-size: 15px; margin: 6px 0 12px; line-height: 1.3; }
       .meta { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
       .meta td { vertical-align: top; font-weight: 700; font-size: 13px; }
       .meta .right { text-align: right; white-space: nowrap; width: 46%; }
       .to .lbl { margin-bottom: 3px; }
       .items { width: 100%; border-collapse: collapse; }
       .items th, .items td { border: 1px solid #111; padding: 3px 6px; font-size: 12px; }
       .items th { text-align: center; }
       .items td.sl, .items td.c { text-align: center; }
       .sign { text-align: right; font-weight: 700; font-size: 13px; margin-top: 22px; }
       .footer { position: absolute; left: 10mm; right: 10mm; bottom: 6mm; text-align: center; font-family: Arial, Helvetica, sans-serif; font-size: 9px; line-height: 1.45; }
       .footer a { color: #3b3dc4; text-decoration: underline; }';

  $openWrap = $forWord ? '<div class="Section1">' : '<div class="sheet">';
  $closeWrap = '</div>';
  $bodyOpen = $forWord ? '<div class="body" style="position:relative;">' : '<div class="body">';

  return '<!DOCTYPE html><html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns:v="urn:schemas-microsoft-com:vml" xmlns="http://www.w3.org/TR/REC-html40"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <!--[if gte mso 9]><xml>
   <o:OfficeDocumentSettings><o:AllowPNG/></o:OfficeDocumentSettings>
   <w:WordDocument><w:View>Print</w:View><w:Zoom>100</w:Zoom><w:DoNotOptimizeForBrowser/></w:WordDocument>
  </xml><![endif]-->
  <style>
    '.$pageCss.'
  </style></head><body>
  '.$openWrap.'
    '.$headerHtml.'
    '.$wmHtml.'
    '.$bodyOpen.'
      <div class="title">2 COPY ENTRY CHALLAN<br>CONSUMABLE MATERIALS</div>
      <table class="meta" width="100%" cellspacing="0" cellpadding="0"><tr>
        <td class="to" valign="top"><div class="lbl">To,</div>'.nl2br($toHtml).'</td>
        <td class="right" valign="top">
          CHALLAN NO : GAS '.htmlspecialchars($challanDigits).'<br>
          DATE : '.htmlspecialchars($meta['challan_date']).'<br>
          ORDER NO : '.htmlspecialchars($meta['order_no'] !== '' ? $meta['order_no'] : '').'<br>
          ORDER DATE : '.htmlspecialchars($meta['order_date']).'<br>
          VEHICLE NO. – '.htmlspecialchars($meta['vehicle_no']).'
        </td>
      </tr></table>
      <table class="items" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
        <thead>'.$thead.'</thead>
        <tbody>'.$rowsHtml.'</tbody>
      </table>
      <div class="sign">FOR GLOBAL AC SYSTEM JSR PVT LTD</div>
    </div>
    <div class="footer">
      Registered Office: 502/A, Jawahar Nagar, Road No.:17, PO – Azad Nagar, Mango, Jamshedpur – 832110, Jharkhand, INDIA Website:
      www.globalacsystem.com, E-Mail: globalacsystem@yahoo.com,
      Mobile No.: 9471129415 / 9471127774, Ph No.: 06572230808<br>
      Branch Office Add: C/o. Majesty 79, A Block, Dhatkidih, PO – Bistupur, Jamshedpur – 831001, Jharkhand, INDIA
    </div>
  '.$closeWrap.'
  </body></html>';
}

function stc_customer_challan_export_pdf($meta){
  $autoload = dirname(__DIR__).'/vendor/autoload.php';
  if(!is_file($autoload)){
    header('HTTP/1.1 500 Internal Server Error');
    echo 'PDF library is not installed.';
    exit;
  }
  require_once $autoload;

  $html = stc_customer_challan_document_html($meta);
  $options = new \Dompdf\Options();
  $options->set('isRemoteEnabled', true);
  $options->set('isHtml5ParserEnabled', true);
  $options->setChroot([realpath(__DIR__), realpath(dirname(__DIR__))]);
  $dompdf = new \Dompdf\Dompdf($options);
  $dompdf->setPaper('A4', 'portrait');
  $dompdf->loadHtml($html);
  $dompdf->render();
  $dompdf->stream(stc_challan_export_filename($meta['challan_no'], $meta['date']).'.pdf', array('Attachment' => true));
  exit;
}

function stc_customer_challan_export_word($meta){
  $filename = stc_challan_export_filename($meta['challan_no'], $meta['date']).'.doc';
  $boundary = '----=_NextPart_STC_'.md5(uniqid('', true));
  $headerPath = __DIR__.'/images/gas-header.jpg';
  $wmPath = __DIR__.'/images/gas-watermark.png';

  $html = stc_customer_challan_document_html($meta, array(
    'for_word' => true,
    'header_src' => is_file($headerPath) ? 'cid:gas-header.jpg' : '',
    'wm_src' => is_file($wmPath) ? 'cid:gas-watermark.png' : '',
  ));

  $doc = "MIME-Version: 1.0\r\n";
  $doc .= 'Content-Type: multipart/related; boundary="'.$boundary.'"'."\r\n\r\n";
  $doc .= '--'.$boundary."\r\n";
  $doc .= "Content-Type: text/html; charset=\"utf-8\"\r\n";
  $doc .= "Content-Transfer-Encoding: quoted-printable\r\n";
  $doc .= "Content-Location: challan.htm\r\n\r\n";
  $doc .= quoted_printable_encode($html)."\r\n";

  if(is_file($headerPath)){
    $doc .= '--'.$boundary."\r\n";
    $doc .= "Content-Type: image/jpeg\r\n";
    $doc .= "Content-Transfer-Encoding: base64\r\n";
    $doc .= "Content-ID: <gas-header.jpg>\r\n";
    $doc .= "Content-Location: gas-header.jpg\r\n\r\n";
    $doc .= chunk_split(base64_encode(file_get_contents($headerPath)))."\r\n";
  }
  if(is_file($wmPath)){
    $doc .= '--'.$boundary."\r\n";
    $doc .= "Content-Type: image/png\r\n";
    $doc .= "Content-Transfer-Encoding: base64\r\n";
    $doc .= "Content-ID: <gas-watermark.png>\r\n";
    $doc .= "Content-Location: gas-watermark.png\r\n\r\n";
    $doc .= chunk_split(base64_encode(file_get_contents($wmPath)))."\r\n";
  }
  $doc .= '--'.$boundary."--\r\n";

  header('Content-Type: application/msword; charset=UTF-8');
  header('Content-Disposition: attachment; filename="'.$filename.'"');
  header('Cache-Control: max-age=0');
  header('Content-Length: '.strlen($doc));
  echo $doc;
  exit;
}

function stc_customer_challan_export_excel($meta){
  $autoload = dirname(__DIR__).'/vendor/autoload.php';
  if(!is_file($autoload)){
    header('HTTP/1.1 500 Internal Server Error');
    echo 'Excel library is not installed.';
    exit;
  }
  require_once $autoload;

  $ss = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
  $sheet = $ss->getActiveSheet();
  $sheet->setTitle('Challan');
  $showSitename = !empty($meta['show_sitename']);
  $lastCol = $showSitename ? 'E' : 'D';
  $sheet->getColumnDimension('A')->setWidth(10);
  if($showSitename){
    $sheet->getColumnDimension('B')->setWidth(28);
    $sheet->getColumnDimension('C')->setWidth(45);
    $sheet->getColumnDimension('D')->setWidth(12);
    $sheet->getColumnDimension('E')->setWidth(10);
  }else{
    $sheet->getColumnDimension('B')->setWidth(55);
    $sheet->getColumnDimension('C')->setWidth(16);
    $sheet->getColumnDimension('D')->setWidth(12);
  }

  $headerPath = __DIR__.'/images/gas-header.jpg';
  $row = 1;
  if(is_file($headerPath)){
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('Header');
    $drawing->setPath($headerPath);
    $drawing->setCoordinates('A1');
    $drawing->setWidth(720);
    $drawing->setWorksheet($sheet);
    $sheet->mergeCells('A1:'.$lastCol.'1');
    $sheet->getRowDimension(1)->setRowHeight(52);
    $row = 3;
  }

  $sheet->mergeCells('A'.$row.':'.$lastCol.$row);
  $sheet->setCellValue('A'.$row, '2 COPY ENTRY CHALLAN');
  $sheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(14);
  $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
  $row++;
  $sheet->mergeCells('A'.$row.':'.$lastCol.$row);
  $sheet->setCellValue('A'.$row, 'CONSUMABLE MATERIALS');
  $sheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
  $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
  $row += 2;

  $toStart = $row;
  $sheet->setCellValue('A'.$row, 'To,');
  $sheet->getStyle('A'.$row)->getFont()->setBold(true);
  $toLines = $meta['to_lines'] ? $meta['to_lines'] : array('—');
  foreach($toLines as $line){
    $row++;
    $sheet->mergeCells('A'.$row.':B'.$row);
    $sheet->setCellValue('A'.$row, $line);
    $sheet->getStyle('A'.$row)->getFont()->setBold(true);
  }
  $metaRow = $toStart;
  $challanDigits = preg_replace('/^GAS\s*/i', '', $meta['challan_no']);
  $metaPairs = array(
    array('CHALLAN NO :', 'GAS '.$challanDigits),
    array('DATE :', $meta['challan_date']),
    array('ORDER NO :', $meta['order_no']),
    array('ORDER DATE :', $meta['order_date']),
    array('VEHICLE NO. –', $meta['vehicle_no']),
  );
  $metaLabelCol = $showSitename ? 'D' : 'C';
  $metaValueCol = $showSitename ? 'E' : 'D';
  foreach($metaPairs as $pair){
    $sheet->setCellValue($metaLabelCol.$metaRow, $pair[0]);
    $sheet->setCellValue($metaValueCol.$metaRow, $pair[1]);
    $sheet->getStyle($metaLabelCol.$metaRow.':'.$metaValueCol.$metaRow)->getFont()->setBold(true);
    $sheet->getStyle($metaLabelCol.$metaRow.':'.$metaValueCol.$metaRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    $metaRow++;
  }
  $row = max($row, $metaRow) + 1;

  $headRow = $row;
  $headers = $showSitename
    ? array('SL NO', 'SITENAME', 'MATERIAL DESCRIPTION', 'QUANTITY', 'UNIT')
    : array('SL NO', 'MATERIAL DESCRIPTION', 'QUANTITY', 'UNIT');
  $sheet->fromArray($headers, null, 'A'.$headRow);
  $sheet->getStyle('A'.$headRow.':'.$lastCol.$headRow)->getFont()->setBold(true);
  $sheet->getStyle('A'.$headRow.':'.$lastCol.$headRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
  $row++;
  $sl = 0;
  foreach($meta['rows'] as $item){
    $sl++;
    $sheet->setCellValue('A'.$row, $sl);
    if($showSitename){
      $sheet->setCellValue('B'.$row, stc_challan_row_sitename($item));
      $sheet->setCellValue('C'.$row, trim((string)$item['item_desc']));
      $sheet->setCellValue('D'.$row, (float)$item['accepted_qty']);
      $sheet->setCellValue('E'.$row, $item['unit']);
      $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $sheet->getStyle('D'.$row.':E'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $sheet->getStyle('D'.$row)->getNumberFormat()->setFormatCode('0.00');
      $sheet->getStyle('B'.$row.':C'.$row)->getAlignment()->setWrapText(true);
    }else{
      $sheet->setCellValue('B'.$row, trim((string)$item['item_desc']));
      $sheet->setCellValue('C'.$row, (float)$item['accepted_qty']);
      $sheet->setCellValue('D'.$row, $item['unit']);
      $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $sheet->getStyle('C'.$row.':D'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $sheet->getStyle('C'.$row)->getNumberFormat()->setFormatCode('0.00');
      $sheet->getStyle('B'.$row)->getAlignment()->setWrapText(true);
    }
    $row++;
  }
  if($sl === 0){
    $sheet->mergeCells('A'.$row.':'.$lastCol.$row);
    $sheet->setCellValue('A'.$row, 'No accepted items found for this date.');
    $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $row++;
  }
  $lastTable = $row - 1;
  $sheet->getStyle('A'.$headRow.':'.$lastCol.$lastTable)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
  $row += 2;
  $sheet->mergeCells('A'.$row.':'.$lastCol.$row);
  $sheet->setCellValue('A'.$row, 'FOR GLOBAL AC SYSTEM JSR PVT LTD');
  $sheet->getStyle('A'.$row)->getFont()->setBold(true);
  $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
  $row += 2;
  $sheet->mergeCells('A'.$row.':'.$lastCol.$row);
  $sheet->setCellValue('A'.$row, 'Registered Office: 502/A, Jawahar Nagar, Road No.:17, PO – Azad Nagar, Mango, Jamshedpur – 832110, Jharkhand, INDIA');
  $sheet->getStyle('A'.$row)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
  $sheet->getStyle('A'.$row)->getFont()->setSize(9);
  $row++;
  $sheet->mergeCells('A'.$row.':'.$lastCol.$row);
  $sheet->setCellValue('A'.$row, 'Website: www.globalacsystem.com, E-Mail: globalacsystem@yahoo.com, Mobile No.: 9471129415 / 9471127774, Ph No.: 06572230808');
  $sheet->getStyle('A'.$row)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
  $sheet->getStyle('A'.$row)->getFont()->setSize(9);
  $row++;
  $sheet->mergeCells('A'.$row.':'.$lastCol.$row);
  $sheet->setCellValue('A'.$row, 'Branch Office Add: C/o. Majesty 79, A Block, Dhatkidih, PO – Bistupur, Jamshedpur – 831001, Jharkhand, INDIA');
  $sheet->getStyle('A'.$row)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
  $sheet->getStyle('A'.$row)->getFont()->setSize(9);
  $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
  $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
  $sheet->getPageSetup()->setFitToPage(true)->setFitToWidth(1)->setFitToHeight(1);

  $filename = stc_challan_export_filename($meta['challan_no'], $meta['date']).'.xlsx';
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment; filename="'.$filename.'"');
  header('Cache-Control: max-age=0');
  $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($ss);
  $writer->save('php://output');
  $ss->disconnectWorksheets();
  unset($ss);
  exit;
}

$date = '';
if(isset($_GET['date']) && $_GET['date'] != ''){
  $date = date('Y-m-d', strtotime($_GET['date']));
} else if(isset($_GET['begdate']) && $_GET['begdate'] != ''){
  $date = date('Y-m-d', strtotime($_GET['begdate']));
} else {
  $date = date('Y-m-d');
}

$order_number = isset($_GET['order_number']) ? trim((string) $_GET['order_number']) : '';
$site_label = isset($_GET['site']) ? trim((string) $_GET['site']) : '';
$date_esc = mysqli_real_escape_string($con, $date);

$challanFrom = "
  FROM `stc_verify_dispatch_accept` VA
  INNER JOIN `stc_cust_super_requisition_list_items` I
    ON I.`stc_cust_super_requisition_list_id` = VA.`item_id`
  INNER JOIN `stc_cust_super_requisition_list` L
    ON L.`stc_cust_super_requisition_list_id` = I.`stc_cust_super_requisition_list_items_req_id`
  LEFT JOIN `stc_cust_project` P
    ON P.`stc_cust_project_id` = L.`stc_cust_super_requisition_list_project_id`
  LEFT JOIN `stc_customer` CU
    ON CU.`stc_customer_id` = P.`stc_cust_project_cust_id`
  LEFT JOIN `stc_cust_pro_supervisor` S
    ON S.`stc_cust_pro_supervisor_id` = L.`stc_cust_super_requisition_list_super_id`
  LEFT JOIN `stc_requisition_combiner_req` CR
    ON CR.`stc_requisition_combiner_req_requisition_id` = L.`stc_cust_super_requisition_list_id`
  LEFT JOIN `stc_requisition_combiner` C
    ON C.`stc_requisition_combiner_id` = CR.`stc_requisition_combiner_req_comb_id`
  WHERE DATE(VA.`created_date`) = '".$date_esc."'
";

$filter_sql = '';
if($order_number !== ''){
  $filter_sql .= " AND L.`stc_cust_super_requisition_list_order_number` = '".mysqli_real_escape_string($con, $order_number)."'";
}

$rows = array();
$to_customer = '';
$to_site = $site_label;
$to_address = '';
$meta_order = $order_number;
$order_date_from = '';
$order_date_to = '';

$sql = mysqli_query($con, "
  SELECT
    VA.`qty` AS accepted_qty,
    I.`stc_cust_super_requisition_list_items_title` AS item_desc,
    I.`stc_cust_super_requisition_list_items_unit` AS unit,
    I.`stc_cust_super_requisition_list_id` AS item_id,
    P.`stc_cust_project_title` AS sitename,
    P.`stc_cust_project_address` AS project_address,
    CU.`stc_customer_name` AS customer_name,
    C.`stc_requisition_combiner_date` AS pr_date,
    C.`stc_requisition_combiner_refrence` AS pr_location,
    L.`stc_cust_super_requisition_list_order_number` AS order_number
  ".$challanFrom."
  ".$filter_sql."
  ORDER BY TIMESTAMP(VA.`created_date`) DESC, VA.`id` DESC
");

if($sql && mysqli_num_rows($sql) > 0){
  while($row = mysqli_fetch_assoc($sql)){
    $combinationName = stc_challan_combination_label($row['sitename'], $row['pr_location']);
    if($site_label !== '' && strcasecmp($combinationName, $site_label) !== 0){
      continue;
    }
    $displaySite = stc_challan_site_label($row['sitename'], $row['pr_location']);
    $row['display_site'] = $displaySite;
    $row['combination_name'] = $combinationName;
    $rows[] = $row;
    if($to_customer === '' && trim((string) ($row['customer_name'] ?? '')) !== ''){
      $to_customer = trim($row['customer_name']);
    }
    if($to_site === '' && $combinationName !== ''){
      $to_site = $combinationName;
    }
    if($to_address === '' && trim((string) ($row['project_address'] ?? '')) !== ''){
      $to_address = trim($row['project_address']);
    }
    if($meta_order === '' && trim((string) ($row['order_number'] ?? '')) !== ''){
      $meta_order = trim($row['order_number']);
    }
    $prDate = trim((string) ($row['pr_date'] ?? ''));
    if($prDate !== '' && $prDate !== '0000-00-00' && $prDate !== '0000-00-00 00:00:00'){
      $prDay = date('Y-m-d', strtotime($prDate));
      if($order_date_from === '' || $prDay < $order_date_from) $order_date_from = $prDay;
      if($order_date_to === '' || $prDay > $order_date_to) $order_date_to = $prDay;
    }
  }
}

$challan_no = 'GAS '.date('dmy', strtotime($date));
$challan_date = date('d/m/Y', strtotime($date));
$order_date_text = '—';
if($order_date_from !== '' && $order_date_to !== '' && $order_date_from !== $order_date_to){
  $order_date_text = date('d/m/Y', strtotime($order_date_from)).' TO '.date('d/m/Y', strtotime($order_date_to));
}elseif($order_date_from !== ''){
  $order_date_text = date('d/m/Y', strtotime($order_date_from));
}else{
  $order_date_text = $challan_date;
}
$blank_rows = max(0, 22 - count($rows));
$embed = isset($_GET['embed']) && $_GET['embed'] !== '0' && $_GET['embed'] !== '';

$toLines = array();
if($to_customer !== '') $toLines[] = $to_customer;
if($to_site !== '' && strcasecmp($to_site, $to_customer) !== 0) $toLines[] = $to_site;
if($to_address !== '') $toLines[] = $to_address;
if(!$toLines) $toLines[] = '—';

$show_sitename = stc_challan_is_tata_steel_amc($site_label, $to_site, $toLines);

if($show_sitename){
  $toLines = array(
    'The Head Security Work',
    'TATA STEEL LTD JSR',
    'JMD GATE',
  );
}

if($show_sitename && count($rows) > 1){
  usort($rows, function($a, $b){
    $cmp = strcasecmp(stc_challan_row_sitename($a), stc_challan_row_sitename($b));
    if($cmp !== 0) return $cmp;
    return strcasecmp(trim((string)($a['item_desc'] ?? '')), trim((string)($b['item_desc'] ?? '')));
  });
}

$export = isset($_GET['export']) ? strtolower(trim((string) $_GET['export'])) : '';
if($export === 'pdf' || $export === 'excel' || $export === 'xlsx' || $export === 'word' || $export === 'doc'){
  $exportMeta = array(
    'date' => $date,
    'challan_no' => $challan_no,
    'challan_date' => $challan_date,
    'order_no' => $meta_order,
    'order_date' => $order_date_text,
    'vehicle_no' => '',
    'to_lines' => $toLines,
    'rows' => $rows,
    'blank_rows' => $blank_rows,
    'show_sitename' => $show_sitename,
  );
  if($export === 'pdf'){
    stc_customer_challan_export_pdf($exportMeta);
  }elseif($export === 'word' || $export === 'doc'){
    stc_customer_challan_export_word($exportMeta);
  }else{
    stc_customer_challan_export_excel($exportMeta);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Customer Challan - <?php echo htmlspecialchars($challan_no); ?></title>
    <link rel="stylesheet" href="../stc_symbiote/css/bootstrap.min.css" />
    <style>
      * { box-sizing: border-box; }
      html, body {
        margin: 0;
        padding: 0;
      }
      body {
        background: #d8dde3;
        color: #111;
        font-family: "Times New Roman", Times, serif;
      }
      .hidden-print { margin: 12px; text-align: right; }
      .gas-sheet {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 210mm;
        height: 297mm;
        min-height: 297mm;
        max-height: 297mm;
        margin: 12px auto 24px;
        background: #fff;
        box-shadow: 0 2px 14px rgba(0,0,0,.12);
        overflow: hidden;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      .gas-header {
        display: block;
        width: 100%;
        height: auto;
        flex: 0 0 auto;
        border: 0;
      }
      .gas-body {
        position: relative;
        flex: 1 1 auto;
        padding: 6mm 14mm 16mm;
        display: flex;
        flex-direction: column;
        min-height: 0;
      }
      .gas-content {
        position: relative;
        z-index: 1;
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        min-height: 0;
      }
      .gas-table-wrap {
        position: relative;
        flex: 1 1 auto;
        min-height: 0;
      }
      .gas-watermark {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 72%;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
      }
      .gas-title {
        text-align: center;
        margin: 2mm 0 7mm;
        font-weight: 700;
        font-size: 15px;
        letter-spacing: .2px;
        line-height: 1.3;
      }
      .gas-meta {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 6mm;
        font-size: 13px;
      }
      .gas-to { max-width: 54%; }
      .gas-to .lbl { font-weight: 700; margin-bottom: 3px; }
      .gas-to .val { font-weight: 700; line-height: 1.4; }
      .gas-right {
        text-align: right;
        line-height: 1.55;
        font-weight: 700;
        white-space: nowrap;
      }
      .gas-slot {
        display: inline-block;
        font-family: "Courier New", Courier, monospace;
        font-weight: 700;
        white-space: pre;
        overflow: hidden;
        vertical-align: baseline;
        border-bottom: 1px solid #111;
        min-height: 1em;
        text-align: left;
      }
      .gas-table {
        position: relative;
        z-index: 1;
        width: 100%;
        height: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        background: transparent;
      }
      .gas-table th, .gas-table td {
        border: 1px solid #111;
        padding: 2px 6px;
        font-size: 12px;
        color: #111;
        background: transparent;
        height: 18px;
      }
      .gas-table th {
        text-align: center;
        font-weight: 700;
        font-size: 12px;
      }
      .gas-table td.sl { width: 70px; text-align: center; }
      .gas-table td.site { width: 18%; word-wrap: break-word; font-size: 11px; }
      .gas-table td.qty, .gas-table td.unit { text-align: center; }
      .gas-table td.desc { word-wrap: break-word; }
      .gas-sign {
        margin-top: auto;
        padding-top: 10mm;
        text-align: right;
        font-weight: 700;
        font-size: 13px;
        letter-spacing: .2px;
      }
      .gas-footer {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        padding: 3mm 10mm 5mm;
        text-align: center;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 9px;
        color: #222;
        line-height: 1.45;
        background: #fff;
      }
      .gas-footer a { color: #3b3dc4; text-decoration: underline; }
      body.gas-embed .hidden-print { display: none !important; }
      body.gas-embed .gas-sheet { margin: 8px auto 12px; }
      @page {
        size: A4 portrait;
        margin: 0;
      }
      @media print {
        html, body {
          width: 210mm !important;
          height: 297mm !important;
          margin: 0 !important;
          padding: 0 !important;
          background: #fff !important;
        }
        .hidden-print { display: none !important; }
        .gas-sheet {
          width: 210mm !important;
          height: 297mm !important;
          min-height: 297mm !important;
          max-height: 297mm !important;
          margin: 0 !important;
          box-shadow: none !important;
          page-break-inside: avoid;
          break-inside: avoid;
        }
        .gas-header, .gas-watermark, .gas-sheet, .gas-footer {
          -webkit-print-color-adjust: exact;
          print-color-adjust: exact;
        }
      }
    </style>
  </head>
  <body<?php echo $embed ? ' class="gas-embed"' : ''; ?>>
    <div class="hidden-print">
      <a class="btn btn-secondary" href="<?php echo htmlspecialchars('verify-challan.php?date='.urlencode($date).($order_number !== '' ? '&order_number='.urlencode($order_number) : '').($site_label !== '' ? '&site='.urlencode($site_label) : '')); ?>">Back</a>
      <button type="button" id="printInvoice" class="btn btn-info"><i class="fas fa-print"></i> Print</button>
    </div>

    <div class="gas-sheet">
      <img class="gas-header" src="images/gas-header.jpg" alt="Global AC System">
      <div class="gas-body">
        <div class="gas-content">
          <div class="gas-title">
            2 COPY ENTRY CHALLAN<br>
            CONSUMABLE MATERIALS
          </div>

          <div class="gas-meta">
            <div class="gas-to">
              <div class="lbl">To,</div>
              <div class="val">
                <?php echo nl2br(htmlspecialchars(implode("\n", $toLines))); ?>
              </div>
            </div>
            <div class="gas-right">
              CHALLAN NO : GAS <?php echo stc_challan_slot(preg_replace('/^GAS\s*/i', '', $challan_no), 10); ?><br>
              DATE : <?php echo htmlspecialchars($challan_date); ?><br>
              ORDER NO : <?php echo stc_challan_slot($meta_order, 14); ?><br>
              ORDER DATE : <?php echo htmlspecialchars($order_date_text); ?><br>
              VEHICLE NO. – <?php echo stc_challan_slot('', 10); ?>
            </div>
          </div>

          <div class="gas-table-wrap">
            <img class="gas-watermark" src="images/gas-watermark.png" alt="">
          <table class="gas-table">
            <thead>
              <tr>
                <th style="width:8%;">SL NO</th>
                <th>MATERIAL DESCRIPTION</th>
                <th style="width:14%;">QUANTITY</th>
                <th style="width:12%;">UNIT</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sl = 0;
              foreach($rows as $row){
                $sl++;
                $desc = trim((string) $row['item_desc']);
              ?>
                <tr>
                  <td class="sl"><?php echo $sl; ?></td>
                  <td class="desc"><?php echo nl2br(htmlspecialchars($desc)); ?></td>
                  <td class="qty"><?php echo number_format((float)$row['accepted_qty'], 2); ?></td>
                  <td class="unit"><?php echo htmlspecialchars($row['unit']); ?></td>
                </tr>
              <?php
              }
              for($i = 0; $i < $blank_rows; $i++){
                echo '<tr><td class="sl">&nbsp;</td>';
                echo '<td class="desc">&nbsp;</td><td class="qty">&nbsp;</td><td class="unit">&nbsp;</td></tr>';
              }
              ?>
            </tbody>
          </table>
          </div>

          <div class="gas-sign">FOR GLOBAL AC SYSTEM JSR PVT LTD</div>
        </div>
      </div>
      <div class="gas-footer">
        Registered Office: 502/A, Jawahar Nagar, Road No.:17, PO – Azad Nagar, Mango, Jamshedpur – 832110, Jharkhand, INDIA Website:
        <a href="http://www.globalacsystem.com" target="_blank">www.globalacsystem.com</a>,
        E-Mail: <a href="mailto:globalacsystem@yahoo.com">globalacsystem@yahoo.com</a>,
        Mobile No.: 9471129415 / 9471127774, Ph No.: 06572230808<br>
        Branch Office Add: C/o. Majesty 79, A Block, Dhatkidih, PO – Bistupur, Jamshedpur – 831001, Jharkhand, INDIA
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script>
      $('#printInvoice').on('click', function(){ window.print(); });
    </script>
  </body>
</html>
