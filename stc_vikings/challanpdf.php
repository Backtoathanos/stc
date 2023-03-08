<?php
require('../fpdf/fpdf.php');
include "../MCU/obdb.php";
global $tandc;
class ragnarget_poitems extends tesseract{
    function LoadData($poid){
        $data = array();
        $get_poitemsqry=mysqli_query($this->stc_dbs, "
            SELECT * FROM `stc_sale_product_items`
            LEFT JOIN `stc_product`
            ON `stc_product_id`=`stc_sale_product_items_product_id`
            LEFT JOIN `stc_sub_category`
            ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
            LEFT JOIN `stc_rack`
            ON `stc_product_rack_id`=`stc_rack_id`
            LEFT JOIN `stc_brand`
            ON `stc_product_brand_id`=`stc_brand_id`
            WHERE `stc_sale_product_items_sale_product_id`='".$poid."'
        ");
        foreach($get_poitemsqry as $line)
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
        $this->Cell(55,10,'DELIVERY CHALLAN',0,0,'C');
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
        $this->Cell(1,5,'Challan No               : STC/DC/'.substr("0000{$_GET['challan_id']}", -5),0,0,'L');
        $this->Cell(100);
        // Bill to
        $this->SetY(48.3);
        $this->SetX(112);
        $this->Cell(1,10,'Transportation Mode    : Any Local Transport',0,0,'L');
        $this->SetY(54.6);
        $this->SetX(70);
        $this->Cell(1,8,'Challan Date : '.$_GET['cdate']);
        $this->SetY(56.2);
        $this->SetX(112.2);
        $this->Cell(1,5,'Way Bill No                  : NA');
        $this->SetY(62.2);
        $this->SetX(9.2);
        $this->SetFont('Arial','',7);
        $this->Cell(1);
        $this->MultiCell(100,3,'Reverse Charge (Y/N)  :');
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
                $pd_name=substr($pd_name, $val);
                $pd_name=$pd_name.'...';
            }
            $total=$row['stc_sale_product_items_product_qty'] * $row['stc_sale_product_items_product_sale_rate'];
            $gst = ($total * $row["stc_product_gst"])/100;

            $this->Cell($w[0],6,$sl.'.','LR',0,'C');
            $this->Cell($w[1],6,$pd_name,'LR');
            $this->Cell($w[2],6,$row['stc_product_unit'],'LR',0, 'C');
            $this->Cell($w[3],6,number_format($row['stc_sale_product_items_product_qty'], 2),'LR',0,'R');
            $this->Cell($w[4],6,'','LR',0,'C');
            $this->Cell($w[5],6,$row['stc_product_gst'].'%','LR',0,'C');
            $this->Cell($w[6],6,$row['stc_rack_name'],'LR',0,'C');
            $this->Ln();
            $subtotal+=$total;
            $gsttotal+=$gst;
        }
        // Closing line
        $this->Cell(array_sum($w),0,'','T');
        $this->Ln(4);
        $this->SetFont('Arial','',8);
        $this->Ln(4);
        $this->MultiCell(0,5,'
        For 
        STC ASSOCIATES :



        Authorised
        Signatory'
        );       
        $this->SetY(208.5);
        $this->SetX(150);
        $this->Cell(10);
        $this->MultiCell(0,5,'
        For 
        Customer Reciever :



        Reciever
        Signatory'
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

if(isset($_GET['challan_id'])){
    // Instanciation of inherited class
    $poid=$_GET['challan_id'];

    $pdf = new PDF();
    $poitem = new ragnarget_poitems();

    $data = $poitem->LoadData($poid);
    $pdf->AliasNbPages();
    $header = array('#', 'ITEMS', 'UNIT', 'QTY', 'RATE', 'GST', 'RACK');
    $pdf->SetFont('Arial','',8);
    $pdf->AddPage();
    $pdf->ImprovedTable($header,$data);
    $pdf->Output();
}
?>