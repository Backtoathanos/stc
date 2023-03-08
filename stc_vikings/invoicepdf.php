<?php
session_start();
require('../fpdf/fpdf.php');
include "../MCU/obdb.php";
global $basic;
global $pandf;
global $fc;
global $cgst;
global $sgst;
global $igst;
global $total;

global $value5;
global $value12;
global $value18;
global $value28;
global $gstval5;
global $gstval12;
global $gstval18;
global $gstval28;

global $igstval5;
global $igstval12;
global $igstval18;
global $igstval28;

global $Year;

global $amtword;

    $yearchange=date('Y', strtotime($_GET['cdate']));
    $monthchange=date('m', strtotime($_GET['cdate']));
    $yearchangevalue='';
    if($yearchange=="2020" && $monthchange>3){
      $yearchangevalue="20-21";
    }elseif($yearchange=="2020" && $monthchange<=3){
      $yearchangevalue="19-20";
    }elseif($yearchange=="2021" && $monthchange>3){
      $yearchangevalue="21-22";
    }elseif($yearchange=="2021" && $monthchange<=3){
      $yearchangevalue="20-21";
    }elseif($yearchange=="2022" && $monthchange>3){
      $yearchangevalue="22-23";
    }elseif($yearchange=="2022" && $monthchange<=3){
      $yearchangevalue="21-22";
    }elseif($yearchange=="2023" && $monthchange>3){
      $yearchangevalue="23-24";
    }
    $GLOBALS['Year']=$yearchangevalue;




class ragnarget_poitems extends tesseract{
    function LoadData($poid){
        $sepvalqry=mysqli_query($this->stc_dbs, "
            SELECT 
              `stc_sale_product_id`,
              `stc_sale_product_dc_keys`
            FROM `stc_sale_product_bill` 
            INNER JOIN `stc_sale_product_bill_no` 
            ON `stc_sale_product_bill_no_bill_id`=`stc_sale_product_bill_id`
            INNER JOIN `stc_sale_product` 
            ON `stc_sale_product_id`=`stc_sale_product_bill_no_bill_challan_id`
            WHERE `stc_sale_product_bill_id`='".$_GET['invoice_id']."'
            AND `stc_sale_product_bill_no_bill_series`='1'
        ");

        foreach($sepvalqry as $comrow){
            if($comrow['stc_sale_product_dc_keys']=="directchallaned"){
              $nestsepqry=mysqli_query($this->stc_dbs, "
                SELECT 
                  `stc_product_gst`,
                  `stc_sale_product_dc_items_sale_product_id`,
                  `stc_sale_product_dc_items_product_qty`,
                  `stc_sale_product_dc_items_product_sale_rate`
                FROM `stc_sale_product_dc_items`
                INNER JOIN `stc_product` 
                ON `stc_product_id`=`stc_sale_product_dc_items_product_id`
                WHERE `stc_sale_product_dc_items_sale_product_id`='".$comrow['stc_sale_product_id']."'
              ");
              foreach($nestsepqry as $nestrow){
                $value=0;
                if($nestrow['stc_product_gst']==5){
                  $value=round($nestrow['stc_sale_product_dc_items_product_qty'], 2) * round($nestrow['stc_sale_product_dc_items_product_sale_rate'], 2);
                  $GLOBALS['value5']+=$value;
                  $GLOBALS['gstval5']+=(round($value, 2) * 5)/100;
                }elseif($nestrow['stc_product_gst']==12){
                  $value=round($nestrow['stc_sale_product_dc_items_product_qty'], 2) * round($nestrow['stc_sale_product_dc_items_product_sale_rate'], 2);
                  $GLOBALS['value12']+=$value;
                  $GLOBALS['gstval12']+=(round($value, 2) * 12)/100;
                }elseif($nestrow['stc_product_gst']==18){
                  $value=round($nestrow['stc_sale_product_dc_items_product_qty'], 2) * round($nestrow['stc_sale_product_dc_items_product_sale_rate'], 2);
                  $GLOBALS['value18']+=$value;
                  $GLOBALS['gstval18']+=(round($value, 2) * 18)/100;
                }else{
                  $value=round($nestrow['stc_sale_product_dc_items_product_qty'], 2) * round($nestrow['stc_sale_product_dc_items_product_sale_rate'], 2);
                  $GLOBALS['value28']+=$value;
                  $GLOBALS['gstval28']+=(round($value, 2) * 28)/100;
                }
              }
            }elseif($comrow['stc_sale_product_dc_keys']=="virtualchallaned"){
              $nestsepqry=mysqli_query($this->stc_dbs, "
                SELECT 
                  `stc_product_gst`,
                  `stc_sale_product_vc_items_sale_product_id`,
                  `stc_sale_product_vc_items_product_qty`,
                  `stc_sale_product_vc_items_product_sale_rate`
                FROM `stc_sale_product_vc`
                INNER JOIN `stc_product` 
                ON `stc_product_id`=`stc_sale_product_vc_items_product_id` 
                WHERE `stc_sale_product_vc_items_sale_product_id`='".$comrow['stc_sale_product_id']."'
              ");
              foreach($nestsepqry as $nestrow){
                $value=0;
                if($nestrow['stc_product_gst']==5){
                  $value=round($nestrow['stc_sale_product_vc_items_product_qty'],2) * round($nestrow['stc_sale_product_vc_items_product_sale_rate'],2);
                  $GLOBALS['value5']+=$value;
                  $GLOBALS['gstval5']+=(round($value, 2) * 5)/100;
                }elseif($nestrow['stc_product_gst']==12){
                  $value=round($nestrow['stc_sale_product_vc_items_product_qty'],2) * round($nestrow['stc_sale_product_vc_items_product_sale_rate'],2);
                  $GLOBALS['value12']+=$value;
                  $GLOBALS['gstval12']+=(round($value, 2) * 12)/100;
                }elseif($nestrow['stc_product_gst']==18){
                  $value=round($nestrow['stc_sale_product_vc_items_product_qty'],2) * round($nestrow['stc_sale_product_vc_items_product_sale_rate'],2);
                  $GLOBALS['value18']+=$value;
                  $GLOBALS['gstval18']+=(round($value, 2) * 18)/100;
                }else{
                  $value=round($nestrow['stc_sale_product_vc_items_product_qty'],2) * round($nestrow['stc_sale_product_vc_items_product_sale_rate'],2);
                  $GLOBALS['value28']+=$value;
                  $GLOBALS['gstval28']+=(round($value, 2) * 28)/100;
                }
              }
            }else{
              $nestsepqry=mysqli_query($this->stc_dbs, "
                SELECT 
                  `stc_product_gst`,
                  `stc_sale_product_items_sale_product_id`,
                  `stc_sale_product_items_product_qty`,
                  `stc_sale_product_items_product_sale_rate`
                FROM `stc_sale_product_items`
                INNER JOIN `stc_product` 
                ON `stc_product_id`=`stc_sale_product_items_product_id` 
                WHERE `stc_sale_product_items_sale_product_id`='".$comrow['stc_sale_product_id']."'
              ");
              foreach($nestsepqry as $nestrow){
                $value=0;
                if($nestrow['stc_product_gst']==5){
                  $value=round($nestrow['stc_sale_product_items_product_qty'],2) * round($nestrow['stc_sale_product_items_product_sale_rate'],2);
                  $GLOBALS['value5']+=$value;
                  $GLOBALS['gstval5']+=(round($value, 2) * 5)/100;
                }elseif($nestrow['stc_product_gst']==12){
                  $value=round($nestrow['stc_sale_product_items_product_qty'],2) * round($nestrow['stc_sale_product_items_product_sale_rate'],2);
                  $GLOBALS['value12']+=$value;
                  $GLOBALS['gstval12']+=(round($value, 2) * 12)/100;
                }elseif($nestrow['stc_product_gst']==18){
                  $value=round($nestrow['stc_sale_product_items_product_qty'], 2) * round($nestrow['stc_sale_product_items_product_sale_rate'], 2);
                  $GLOBALS['value18']+=$value;
                  $GLOBALS['gstval18']+=(round($value, 2) * 18)/100;
                }else{
                  $value=round($nestrow['stc_sale_product_items_product_qty'],2) * round($nestrow['stc_sale_product_items_product_sale_rate'],2);
                  $GLOBALS['value28']+=$value;
                  $GLOBALS['gstval28']+=(round($value, 2) * 28)/100;
                }
              }
            }
        }

        if($_GET['cust_id']==16){
            $GLOBALS['igstval5']=0;
            $GLOBALS['igstval12']=0;
            $GLOBALS['igstval18']=0;
            $GLOBALS['igstval28']=0;                    
        }else{
            $GLOBALS['igstval5']=$GLOBALS['gstval5'];
            $GLOBALS['igstval12']=$GLOBALS['gstval12'];
            $GLOBALS['igstval18']=$GLOBALS['gstval18'];
            $GLOBALS['igstval28']=$GLOBALS['gstval28'];
            $GLOBALS['gstval5']=0;
            $GLOBALS['gstval12']=0;
            $GLOBALS['gstval18']=0;
            $GLOBALS['gstval28']=0;  
        }

        $data = array();
        $get_invoiceqry=mysqli_query($this->stc_dbs, "
            SELECT * FROM `stc_sale_product_bill`
            LEFT JOIN `stc_sale_product_bill_no`
            ON `stc_sale_product_bill_id`=`stc_sale_product_bill_no_bill_id`
            LEFT JOIN `stc_sale_product`
            ON `stc_sale_product_bill_no_bill_challan_id`=`stc_sale_product_id`
            LEFT JOIN `stc_customer`
            ON `stc_sale_product_cust_id`=`stc_customer_id`
            WHERE `stc_sale_product_bill_id`='".$poid."'
            AND `stc_sale_product_bill_no_bill_series`='1'
        ");
        foreach($get_invoiceqry as $line)
            $data[] = $line;
        return $data;
    }
}

class PDF extends FPDF{

    // Page header
    function Header(){
        $this->Image('../stc_symbiote/img/stc-header.png',10,12,40);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the center
        $this->Cell(70);
        // Delevery Challan
        $this->Cell(55,10,'TAX INVOICE',0,0,'C');
        // Logo
        $this->Cell(90);
        $this->Image('images/stc_logo.png',170,12.5,30);
        // Line break
        $this->Ln(12);
        $this->SetFont('Arial','',8);
        $this->Ln(1);
        $this->Cell(90,5,'Rajmahal Apartment, D/304 3rd Floor, Block No 1,');
        $this->Ln(4);
        $this->SetFont('Arial','',8);
        $this->Cell(86.2,5,'Pardih, Jamshedpur, Jharkhand 832110');
        $this->Ln(5);
        $this->Cell(77.2);
        $this->SetFont('Arial','',8);
        $this->Ln(4);
        $this->Cell(0,5,'Mobile No. : +91-8986811304');
        $this->SetFont('Arial','',13);
        $this->Ln(4);
        $this->SetFont('Arial','',8);
        $this->Cell(0,5,'E-Mail : stc111213@gmail.com');
        $this->Ln(7);
        $this->SetFont('Arial','B',15);
        $this->Cell(0,0,'----------------------------------------------------------------------------------------------------------');
        $this->Ln(4);
        $this->SetFont('Arial','',7);
        $this->Cell(0,5,'GSTIN                      : 20JCBPS6008G1ZT');
        $this->Ln(5);
        // Supplier
        $this->Cell(1,5,'Invoice No               : STC/'.substr("0000{$_GET['invo_no']}", -5).'/'.$GLOBALS['Year'],0,0,'L');
        $this->Cell(100);
        // Bill to
        $this->SetY(48.3);
        $this->SetX(112);
        $this->Cell(1,10,'Transportation Mode    : Any Local Transport',0,0,'L');
        $this->SetY(54.6);
        $this->SetX(70);
        $this->Cell(1,8,'Invoice Date : '.$_GET['cdate']);
        $this->SetY(56.2);
        $this->SetX(112.2);
        $this->Cell(1,5,'Way Bill No                  : NA');
        $this->SetY(62.2);
        $this->SetX(9.2);
        $this->SetFont('Arial','',7);
        $this->Cell(1);
        $this->MultiCell(100,3,'Reverse Charge (Y/N)  : No');
        $this->SetY(62.2);
        $this->SetX(112.5);
        $this->MultiCell(85,3,'LR No                          : NA');
        $this->SetY(66);
        $this->SetX(10);
        $this->Cell(0,5,'State                        : JHARKHAND');
        $this->SetY(66);
        $this->SetX(112);
        $this->Cell(0,5,'Date of Supply             : '.$_GET['dos']);
        $this->SetY(71.2);
        $this->SetX(10);
        $this->Cell(0,5,'Customer PO No     : '.$_GET['cust_po_no']);
        $this->SetY(71.2);
        $this->SetX(60);
        $this->Cell(0,5,'Customer PO Date     : '.$_GET['cust_po_date']);
        $this->SetY(71.2);
        $this->SetX(112);
        $this->Cell(0,5,'Place of Supply            : '.$_GET['pos']);
        $this->SetY(76.2);
        $this->SetX(10);
        $this->Cell(0,5,'Reference                : '.$_GET['refrence']);
        $this->SetY(76.2);
        $this->SetX(112);
        $this->Cell(0,5,'Site Name                    : '.$_GET['sitename']);
        $this->Ln(5);
        $this->SetFont('Arial','B',15);
        $this->Cell(0,0,'----------------------------------------------------------------------------------------------------------');
        $this->Ln(5);
        $this->SetY(82.2);
        $this->SetX(10);
        // Arial bold 15
        $this->SetFont('Arial','',7);
        // Move to the center
        $this->Cell(1);
        // Supplier
        $this->Cell(1,10,'Bill To Party :',0,0,'L');
        $this->Cell(100);
        // Bill to
        $this->SetY(82.2);
        $this->SetX(112);
        $this->Cell(1,10,'Ship To Party [Delivery Address] :',0,0,'L');
        $this->Ln(8);
        $this->SetFont('Arial','',12);
        $this->SetY(89.2);
        $this->SetX(10);
        $this->Cell(1,5,$_GET['cust_name']);
        $this->SetY(89.2);
        $this->SetX(112);
        $this->Cell(1,5,$_GET['cust_name']);
        $this->SetY(94.2);
        $this->SetX(10);
        $this->SetFont('Arial','',7);
        $this->SetY(94.2);
        $this->SetX(10);
        $this->MultiCell(100,3, 'Address : '.$_GET['cust_address']);
        $this->SetY(94.2);
        $this->SetX(112);
        $this->MultiCell(85,3, 'Address : '.$_GET['cust_site_addres']);
        $this->SetY(99.2);
        $this->SetX(10);
        $this->Cell(0,5,'Contact Person : '.$_GET['cust_cont_person']);
        $this->SetY(99.2);
        $this->SetX(112);
        $this->Cell(0,5,'Contact Person : '.$_GET['cust_site_cont_person']);
        $this->SetY(103.2);
        $this->SetX(10);
        $this->Cell(0,5,'Contact No : '.$_GET['cust_cont_no']);
        $this->SetY(103.2);
        $this->SetX(112);
        $this->Cell(0,5,'Contact No : '.$_GET['cust_site_cont_no']);
        $this->SetY(107.2);
        $this->SetX(10);
        $this->Cell(0,5,'Email  : '.$_GET['cust_email']);
        $this->SetY(107.2);
        $this->SetX(112);
        $this->Cell(0,5,'Email  : '.$_GET['cust_email']);
        $this->SetY(112.2);
        $this->SetX(10);
        $this->Cell(0,5,'GSTIN : '.$_GET['cust_gst']);
        $this->SetY(112.2);
        $this->SetX(112);
        $this->Cell(0,5,'GSTIN : '.$_GET['cust_gst']);
        $this->SetY(117.2);
        $this->SetX(10);
        $this->Cell(0,5,'State   : JHARKHAND');
        $this->SetY(117.2);
        $this->SetX(112);
        $this->Cell(0,5,'State   : JHARKHAND');
        $this->Ln(5);
        $this->SetFont('Arial','B',15);
        $this->Cell(0,0,'----------------------------------------------------------------------------------------------------------');
        $this->Ln(5);
    }

    // Better table
    function ImprovedTable($header, $data){
        // Column widths
        $w = array(10, 65, 20, 10, 15, 20, 20, 10, 20);
        // Header
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,$header[$i],1,0,'C');
        $this->Ln();
        // Data
        $sl=0;
        $subtotal=0;
        $gsttotal=0;
        foreach($_SESSION["stc_print_prevew"] as $row){
            $sl++;
            // if($row['stc_sub_cat_name']=="OTHERS"){
              $mtype='';
            // }else{
            //   $mtype=$row['stc_sub_cat_name'];
            // }
            $brand='';
            // if($row['stc_product_brand_id']==0){
            //   $brand='';
            // }else{
            //   $brand.='';
            // }
            $pd_name=$mtype.' '.nl2br($row['product_name']).' '.$brand;
            $pd_len=strlen($pd_name);
            if($pd_len>40){
                $val=$pd_len - 40;
                $val=$pd_len - $val;
                $pd_name=substr($pd_name, -40);
                $pd_name=$pd_name.'...';
            }
            $total=$row['product_quantity'] * $row['product_price'];
            $gst = ($total * $row["product_gst"])/100;
            $itemtotal=$total + $gst;
            $this->Cell($w[0],6,$sl.'.','LR',0,'C');
            $this->Cell($w[1],6,$pd_name,'LR');
            $this->Cell($w[2],6,$row['product_hsncode'],'LR',0, 'C');
            $this->Cell($w[3],6,$row['product_unit'],'LR',0, 'C');
            $this->Cell($w[4],6,number_format($row['product_quantity'], 2),'LR',0,'R');
            $this->Cell($w[5],6,number_format($row['product_price'], 2),'LR',0,'R');
            $this->Cell($w[6],6,number_format($total, 2),'LR',0,'R');
            $this->Cell($w[7],6,$row['product_gst'].'%','LR',0,'C');
            $this->Cell($w[8],6,number_format($itemtotal, 2),'LR',0,'R');
            $this->Ln();
            $subtotal+=$total;
            $gsttotal+=$gst;
            if($sl==12 || $sl==24 || $sl==36 || $sl==48){
                for($i=0;$i<=10;$i++){
                    $this->Cell($w[0],0,'','LR',0,'C');
                    $this->Cell($w[1],0,'','LR');
                    $this->Cell($w[2],0,'','LR',0,'C');
                    $this->Cell($w[3],0,'','LR',0,'C');
                    $this->Cell($w[4],0,'','LR',0,'R');
                    $this->Cell($w[5],0,'','LR',0,'R');
                    $this->Cell($w[6],0,'','LR',0,'R');
                    $this->Cell($w[7],0,'','LR',0,'C');
                    $this->Cell($w[8],6,'','LR',0,'R');
                    $this->Ln();
                }
                for($i=0;$i<count($header);$i++)
                    $this->Cell($w[$i],7,$header[$i],1,0,'C');
                $this->Ln();
            }
        }
        // Closing line
        $this->Cell(array_sum($w),0,'','T');
        $this->Ln(5);  
        $this->SetFont('Arial','B',15);
        $this->Cell(0,0,'----------------------------------------------------------------------------------------------------------');
        $this->Ln(5);  
        $GLOBALS['basic']=$subtotal;
        if($_GET['cust_id']==16){
            $GLOBALS['cgst']=$gsttotal/2;
            $GLOBALS['sgst']=$gsttotal/2;
            $GLOBALS['igst']=0;
        }else{
            $GLOBALS['cgst']=0;
            $GLOBALS['sgst']=0;
            $GLOBALS['igst']=$gsttotal;
        }
        $GLOBALS['total']=$subtotal + $gsttotal;
    }

    // Page footer
    function Footer(){

        // Position at 1.5 cm from bottom
        $this->SetFont('Arial','',7);
        $this->SetY(208);
        $this->SetX(10);
        $this->Cell(0,0,'TAX NAME');
        $this->SetY(208);
        $this->SetX(40);
        $this->Cell(0,0,'BASIC');
        $this->SetY(208);
        $this->SetX(60);
        $this->Cell(0,0,'CGST');
        $this->SetY(208);
        $this->SetX(80);
        $this->Cell(0,0,'SGST');
        $this->SetY(208);
        $this->SetX(100);
        $this->Cell(0,0,'IGST');
        $this->SetY(208);
        $this->SetX(160);
        $this->Cell(0,0,'SUBTOTAL :');
        $this->SetY(208);
        $this->SetX(187);
        $this->Cell(0,0,number_format($GLOBALS['basic'], 2),0,0,'R');
        $this->Ln(5);  
        // 5% tax line

        $this->SetY(211);
        $this->SetFont('Arial','',7);
        $this->Cell(0,0,'5%');
        $this->SetY(211);
        $this->SetX(40);
        $this->Cell(0,0,number_format($GLOBALS['value5'], 2));
        $this->SetY(211);
        $this->SetX(60);
        $this->Cell(0,0,number_format(($GLOBALS['gstval5']/2), 2));
        $this->SetY(211);
        $this->SetX(80);
        $this->Cell(0,0,number_format(($GLOBALS['gstval5']/2), 2));
        $this->SetY(211);
        $this->SetX(100);
        $this->Cell(0,0,number_format($GLOBALS['igstval5'], 2));
        $this->SetY(211);
        $this->SetX(141.8);
        $this->Cell(0,0,'PACKING & FORWARDING :');
        $this->SetY(211);
        $this->SetX(187);
        $this->Cell(0,0,number_format($GLOBALS['pandf'], 2),0,0,'R');
        $this->Ln(5);  
        // 12% tax line

        $this->SetY(214);
        $this->SetFont('Arial','',7);
        $this->Cell(0,0,'12%');
        $this->SetY(214);
        $this->SetX(40);
        $this->Cell(0,0,number_format($GLOBALS['value12'], 2));

        $this->SetY(214);
        $this->SetX(60);
        $this->Cell(0,0,number_format(($GLOBALS['gstval12']/2), 2));

        $this->SetY(214);
        $this->SetX(80);
        $this->Cell(0,0,number_format(($GLOBALS['gstval12']/2), 2));

        $this->SetY(214);
        $this->SetX(100);
        $this->Cell(0,0,number_format($GLOBALS['igstval12'], 2));

        $this->SetY(214);
        $this->SetX(149.3);
        $this->Cell(0,0,'FREIGHT CHARGES :');

        $this->SetY(214);
        $this->SetX(187);
        $this->Cell(0,0,number_format($GLOBALS['fc'], 2),0,0,'R');
        $this->Ln(5);  
        // 18% tax line 

        $this->SetY(217);
        $this->SetFont('Arial','',7);
        $this->Cell(0,0,'18%');
        $this->SetY(217);
        $this->SetX(40);
        $this->Cell(0,0,number_format($GLOBALS['value18'], 2));
        $this->SetY(217);
        $this->SetX(60);
        $this->Cell(0,0,number_format(($GLOBALS['gstval18']/2), 2));
        $this->SetY(217);
        $this->SetX(80);
        $this->Cell(0,0,number_format(($GLOBALS['gstval18']/2), 2));
        $this->SetY(217);
        $this->SetX(100);
        $this->Cell(0,0,number_format($GLOBALS['igstval18'], 2));
        $this->SetY(217);
        $this->SetX(166.3);
        $this->Cell(0,0,'CGST :');
        $this->SetY(217);
        $this->SetX(187);
        $this->Cell(0,0,number_format($GLOBALS['cgst'], 2),0,0,'R');
        $this->Ln(5);  
        // 28% tax line

        $this->SetY(220);
        $this->SetFont('Arial','',7);
        $this->Cell(0,0,'28%');
        $this->SetY(220);
        $this->SetX(40);
        $this->Cell(0,0,number_format($GLOBALS['value28'], 2));
        $this->SetY(220);
        $this->SetX(60);
        $this->Cell(0,0,number_format(($GLOBALS['gstval28']/2), 2));
        $this->SetY(220);
        $this->SetX(80);
        $this->Cell(0,0,number_format(($GLOBALS['gstval28']/2), 2));
        $this->SetY(220);
        $this->SetX(100);
        $this->Cell(0,0,number_format($GLOBALS['igstval28'], 2));
        $this->SetY(220);
        $this->SetX(166.5);
        $this->Cell(0,0,'SGST :');
        $this->SetY(220);
        $this->SetX(167.4);
        $this->Cell(0,0,number_format($GLOBALS['sgst'], 2),0,0,'R');
        $this->Ln(5);
        $this->SetY(223);
        $this->SetX(167.4);
        $this->Cell(0,0,'IGST :');
        $this->SetY(223);
        $this->SetX(167.4);
        $this->Cell(0,0,number_format($GLOBALS['igst'], 2),0,0,'R');
        $this->Ln(5);
        $this->SetY(226);
        $this->SetX(158.8);
        $this->Cell(0,0,'TCS @0.075 :');
        $this->SetY(226);
        $this->SetX(187);
        $this->Cell(0,0,'0.00',0,0,'R');
        $this->Ln(5);
        $this->SetY(229);
        $this->SetX(155.6);
        $this->Cell(0,0,'GRAND TOTAL :');
        $this->SetY(229);
        $this->SetX(188);
        $this->Cell(0,0,number_format($GLOBALS['total'], 2),0,0,'R');
        $this->Ln(5);
        $this->SetY(238);
        $this->SetFont('Arial','B',15);
        $this->Cell(0,0,'----------------------------------------------------------------------------------------------------------');
        $this->Ln(0);  
        $this->SetFont('Arial','',8);
        $this->SetY(240);
        $this->SetX(10);
        $this->Cell(0,5,'Bank Name         : AXIS BANK');
        $this->SetY(244);
        $this->SetX(10);
        $this->Cell(0,5,'Branch Name      : AZADNAGAR');
        $this->SetY(248);
        $this->SetX(10);
        $this->Cell(0,5,'A/c No                 : 920020051082641');
        $this->SetY(252);
        $this->SetX(10);
        $this->Cell(0,5,'IFSC Code          : UTIB0003459');
        $this->Ln(0);
        $this->SetFont('Arial','B',10);
        $this->SetY(240);
        $this->SetX(110);

        $amount=$GLOBALS['total'];
        // $get_amount= AmountInWords($amt_words);
        $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
        // Check if there is any number after decimal
        $amt_hundred = null;
        $count_length = strlen($num);
        $x = 0;
        $string = array();
        $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
          3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
          7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
          10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
          13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
          16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
          19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
          40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
          70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
         $here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
         while( $x < $count_length ) {
           $get_divider = ($x == 2) ? 10 : 100;
           $amount = floor($num % $get_divider);
           $num = floor($num / $get_divider);
           $x += $get_divider == 10 ? 1 : 2;
           if ($amount) {
            $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
            $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
            $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' 
            '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' 
            '.$here_digits[$counter].$add_plural.' '.$amt_hundred;
             }
        else $string[] = null;
        }
        $implode_to_Rupees = implode('', array_reverse($string));
        $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
        " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
        $get_amount= ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;

        $this->Cell(0,5,"Invoice Amount (In Words) :");
        $this->Ln(0);
        $this->SetFont('Arial','',6);
        $this->SetY(244);
        $this->SetX(110);
        $this->Cell(0,5,preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "\n", $get_amount)));
        $this->Ln(0);
        $this->SetY(-40);
        $this->SetFont('Arial','B',15);
        $this->Cell(0,0,'----------------------------------------------------------------------------------------------------------');
        $this->Ln(0);  
        $this->SetY(-38);
        // $this->SetX(10);
        $this->Cell(0,5,"Terms & Conditions");
        $this->Ln(0);  
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        $this->MultiCell(0,5,'
            For STC ASSOCIATES


            Authorised Signatory',0,'R');
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');
    }
}

if(isset($_GET['invoice_id'])){
    // Instanciation of inherited class
    $poid=$_GET['invoice_id'];

    $pdf = new PDF();
    $poitem = new ragnarget_poitems();

    $data = $poitem->LoadData($poid);
    $pdf->AliasNbPages();
    $header = array('#', 'ITEMS', 'HSN CODE', 'UNIT', 'QTY', 'RATE', 'AMOUNT', 'GST', 'TOTAL');
    $pdf->SetFont('Arial','',8);
    $pdf->AddPage();
    $pdf->ImprovedTable($header,$data);
    $pdf->Output();
}
?>