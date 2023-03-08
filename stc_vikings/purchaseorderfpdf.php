<?php
require('../fpdf/fpdf.php');
include "../MCU/obdb.php";
global $tandc;
class ragnarget_poitems extends tesseract{
    function LoadData($poid){
        $data = array();
        $get_poitemsqry=mysqli_query($this->stc_dbs, "
            SELECT * FROM `stc_purchase_product_items`
            LEFT JOIN `stc_product`
            ON `stc_product_id`=`stc_purchase_product_items_product_id`
            LEFT JOIN `stc_sub_category`
            ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
            LEFT JOIN `stc_brand`
            ON `stc_product_brand_id`=`stc_brand_id`
            WHERE `stc_purchase_product_items_order_id`='".$poid."'
        ");
        foreach($get_poitemsqry as $line)
            $data[] = $line;

        $get_pot=mysqli_query($this->stc_dbs, "
            SELECT 
                `stc_purchase_product_notes` 
            FROM `stc_purchase_product`
            WHERE `stc_purchase_product_id` = '".$poid."'
        ");
        foreach($get_pot as $getrow){
            $GLOBALS['tandc']=$getrow['stc_purchase_product_notes'];
        }
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
        // Purchase order
        $this->Cell(55,10,'PURCHASE ORDER',0,0,'C');
        // Logo
        $this->Cell(90);
        $this->Image('images/stc_logo.png',170,12.5,30);
        // Line break
        $this->Ln(12);
        $this->SetFont('Arial','',8);
        $this->Ln(1);
        $this->Cell(90,5,'Rajmahal Apartment, D/304 3rd Floor, Block No 1,');
        $this->SetFont('Arial','',13);
        $this->Cell(0,5,'PO No : STC/'.substr("0000{$_GET['po_id']}", -5));
        $this->Ln(4);
        $this->SetFont('Arial','',8);
        $this->Cell(86.2,5,'Pardih, Jamshedpur, Jharkhand 832110');
        $this->SetFont('Arial','',13);
        $this->Cell(0,5,'PO Date : '.$_GET['pdate']);
        $this->Ln(5);
        $this->Cell(77.2);
        $this->SetFont('Arial','',10);
        $this->Cell(0,5,'Reference GTO No : '.$_GET['for']);
        // $this->Ln(5);
        $this->SetY(37);
        $this->SetX(91.5);
        $this->Cell(0,5,'Requisition No : '.$_GET['req']);
        $this->SetFont('Arial','',8);
        $this->Ln(2);
        $this->Cell(0,5,'Mobile No. : +91-8986811304');
        // $this->SetFont('Arial','',13);
        $this->Ln(2);
        $this->SetFont('Arial','',8);
        $this->Cell(0,5,'E-Mail : stc111213@gmail.com');
        $this->Ln(3);
        $this->Cell(0,5,'GSTIN : 20JCBPS6008G1ZT');
        $this->Ln(3);
        $this->SetFont('Arial','B',15);
        $this->Cell(0,0,'----------------------------------------------------------------------------------------------------------');
        $this->Ln();
        // Arial bold 15
        $this->SetFont('Arial','',7);
        // Move to the center
        $this->Cell(1);
        // Supplier
        $this->Cell(1,10,'Supplier :',0,0,'L');
        $this->Cell(100);
        // Bill to
        $this->Cell(1,10,'Bill To :',0,0,'L');
        $this->Ln(8);
        $this->SetFont('Arial','',12);
        $this->Cell(1);
        $this->Cell(1,5,$_GET['suppliername']);
        $this->Cell(100);
        $this->Cell(1,5,'STC ASSOCIATES');
        $this->Ln(5);
        $this->Cell(1);
        $this->SetFont('Arial','',7);
        $this->Cell(1);
        $this->MultiCell(100,3,'Address : '.$_GET['supplieraddress']);
        $this->SetY(62);
        $this->SetX(112);
        $this->MultiCell(85,3,'Address : Rajmahal Apartment, D/304 3rd Floor, Block No 1, Pardih, Jamshedpur, Jharkhand 832110');
        $this->Ln(0);
        $this->Cell(2);
        $this->Cell(0,5,'GSTIN : '.$_GET['suppliergstin']);
        $this->SetY(68.2);
        $this->SetX(112);
        $this->Cell(0,5,'GSTIN : 20JCBPS6008G1ZT');
        $this->Ln(4);
        $this->Cell(2);
        $this->Cell(0,5,'Email : '.$_GET['supplieremail']);
        $this->SetY(72.2);
        $this->SetX(112);
        $this->Cell(0,5,'Email : stc111213@gmail.com');
        $this->Ln(4);
        $this->Cell(2);
        $this->Cell(0,5,'Phone : '.$_GET['supplierphone']);
        $this->SetY(76.2);
        $this->SetX(112);
        $this->Cell(0,5,'Phone : +91-8986811304');
        $this->Ln(5);
        $this->SetFont('Arial','B',15);
        $this->Cell(0,0,'----------------------------------------------------------------------------------------------------------');
        $this->Ln(5);
        $this->SetFont('Arial','',12);
        $this->Cell(0,5,'Kind Attn: Mr/Miss. '.$_GET['suppliercontactperson']);
        $this->Ln(4);
        $this->SetFont('Arial','',8);
        $this->MultiCell(0,5,'Dear Sir/Madam,
                           It is a pleasure to associate with you through your following product in our progressive journey. Please arrange to deliver the same.');
        $this->Ln(1);
    }

    // Better table
    function ImprovedTable($header, $data){
        // Column widths
        $w = array(10, 65, 30, 15, 20, 25, 25);
        // Header
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,$header[$i],1,0,'C');
        $this->Ln();
        // Data
        $sl=0;
        $subtotal=0;
        $gsttotal=0;
        foreach($data as $row){
            $sl++;
            if($row['stc_sub_cat_name']=="OTHERS"){
              $mtype='';
            }else{
              $mtype=$row['stc_sub_cat_name'];
            }
            $brand='MAKE - ';
            if($row['stc_product_brand_id']==0){
              $brand='';
            }else{
              $brand.=$row['stc_brand_title'];
            }
            $pd_name=$mtype.' '.nl2br($row['stc_product_name']).' '.$brand;
            $pd_len=strlen($pd_name);
            if($pd_len>35){
                $val=$pd_len - 35;
                $pd_name=substr($pd_name, 0, -$val);
                $pd_name=$pd_name.'...';
            }
            $total=$row['stc_purchase_product_items_qty'] * $row['stc_purchase_product_items_rate'];
            $gst = ($total * $row["stc_product_gst"])/100;

            $this->Cell($w[0],6,$sl.'.','LR',0,'C');
            $this->Cell($w[1],6,$pd_name,'LR');
            $this->Cell($w[2],6,$row['stc_product_hsncode'],'LR',0,'C');
            $this->Cell($w[3],6,$row['stc_product_unit'],'LR',0, 'C');
            $this->Cell($w[4],6,number_format($row['stc_purchase_product_items_qty'], 2),'LR',0,'R');
            $this->Cell($w[5],6,number_format($row['stc_purchase_product_items_rate'], 2),'LR',0,'R');
            $this->Cell($w[6],6,number_format($total, 2),'LR',0,'R');
            $this->Ln();
            $subtotal+=$total;
            $gsttotal+=$gst;
        }
        $g_total=ceil($subtotal + $gsttotal);
        // Closing line
        $this->Cell(array_sum($w),0,'','T');
        $this->Ln();
        $this->Cell($w[0],6,'','LR', 0, 'R');
        $this->Cell($w[1],6,'','LR', 0, 'R');
        $this->Cell($w[2],6,'','LR', 0, 'R');
        $this->Cell($w[3],6,'','LR', 0, 'R');
        $this->Cell($w[4],6,'','LR', 0, 'R');
        $this->Cell($w[5],6,'SUBTOTAL :','LR', 0, 'R');
        $this->Cell($w[6],6,number_format($subtotal, 2),'LR', 0, 'R');
        $this->Ln();
        $this->Cell($w[0],6,'','LR', 0, 'R');
        $this->Cell($w[1],6,'','LR', 0, 'R');
        $this->Cell($w[2],6,'','LR', 0, 'R');
        $this->Cell($w[3],6,'','LR', 0, 'R');
        $this->Cell($w[4],6,'','LR', 0, 'R');
        $this->Cell($w[5],6,'CGST :','LR', 0, 'R');
        $this->Cell($w[6],6,number_format($gsttotal/2, 2),'LR', 0, 'R');
        $this->Ln();
        $this->Cell($w[0],6,'','LR', 0, 'R');
        $this->Cell($w[1],6,'','LR', 0, 'R');
        $this->Cell($w[2],6,'','LR', 0, 'R');
        $this->Cell($w[3],6,'','LR', 0, 'R');
        $this->Cell($w[4],6,'','LR', 0, 'R');
        $this->Cell($w[5],6,'SGST :','LR', 0, 'R');
        $this->Cell($w[6],6,number_format($gsttotal/2, 2),'LR', 0, 'R');
        $this->Ln();
        $this->Cell($w[0],6,'','LR', 0, 'R');
        $this->Cell($w[1],6,'','LR', 0, 'R');
        $this->Cell($w[2],6,'','LR', 0, 'R');
        $this->Cell($w[3],6,'','LR', 0, 'R');
        $this->Cell($w[4],6,'','LR', 0, 'R');
        $this->Cell($w[5],6,'GRAND TOTAL :','LR', 0, 'R');
        $this->Cell($w[6],6,number_format($g_total, 2),'LR', 0, 'R');
        $this->Ln();
        // Closing line
        $this->Cell(array_sum($w),0,'','T');
        $this->Ln(4);
        $this->SetFont('Arial','',8);
        $this->MultiCell(90,5,'Terms & Conditions :
'.$GLOBALS['tandc']
        );
        $this->Ln(4);
        $this->MultiCell(50,5,'For STC ASSOCIATES :


'.$_GET['createdbyuname'].'
+91-'.$_GET['createdbyuphone']
        );        
    }

    // Page footer
    function Footer(){
        // Position at 1.5 cm from bottom
        $this->SetY(-20);
        // Arial italic 8
        $this->SetFont('Arial','I',12);
        $this->MultiCell(0,5,'Office Add: Rajmahal Apartment, D/304 3rd Floor, Block No 1, Pardih, Jamshedpur, Jharkhand 832110, INDIA, Ph. No : 0567-123456, E-mail : stc111213@gmail.com.',0,'C');
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');
    }
}

if(isset($_GET['po_id'])){
    // Instanciation of inherited class
    $poid=$_GET['po_id'];

    $pdf = new PDF();
    $poitem = new ragnarget_poitems();

    $data = $poitem->LoadData($poid);
    $pdf->AliasNbPages();
    $header = array('#', 'ITEMS', 'HSN CODE', 'UNIT', 'QTY', 'RATE', 'AMOUNT');
    $pdf->SetFont('Arial','',8);
    $pdf->AddPage();
    $pdf->ImprovedTable($header,$data);
    $pdf->Output();
}
?>