<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*---------------------------------------For Silent Invoice --------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class Yggdrasil extends tesseract{
	// stc call request
	public function stc_save_request_raised($custRequestpartyname, $custRequestcontname, $custRequestcontnumber, $custRequestaddress, $custRequestmaterialdetails, $custRequestorderby, $custRequestinvtype, $custRequestgstno, $custRequestremarks){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$set_loki=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_electronics_request_raised`(
			    `stc_electronics_request_raised_date`,
			    `stc_electronics_request_raised_custname`,
			    `stc_electronics_request_raised_cont_person`,
			    `stc_electronics_request_raised_cont_number`,
			    `stc_electronics_request_raised_address`,
			    `stc_electronics_request_raised_material_details`,
			    `stc_electronics_request_raised_order_by`,
			    `stc_electronics_request_raised_invoice_type`,
			    `stc_electronics_request_raised_gst_no`,
			    `stc_electronics_request_raised_remarks`,
			    `stc_electronics_request_raised_status`,
			    `stc_electronics_request_raised_created_by`
			)VALUES(
				'".mysqli_real_escape_string($this->stc_dbs, $date)."',
				'".mysqli_real_escape_string($this->stc_dbs, $custRequestpartyname)."',
				'".mysqli_real_escape_string($this->stc_dbs, $custRequestcontname)."',
				'".mysqli_real_escape_string($this->stc_dbs, $custRequestcontnumber)."',
				'".mysqli_real_escape_string($this->stc_dbs, $custRequestaddress)."',
				'".mysqli_real_escape_string($this->stc_dbs, $custRequestmaterialdetails)."',
				'".mysqli_real_escape_string($this->stc_dbs, $custRequestorderby)."',
				'".mysqli_real_escape_string($this->stc_dbs, $custRequestinvtype)."',
				'".mysqli_real_escape_string($this->stc_dbs, $custRequestgstno)."',
				'".mysqli_real_escape_string($this->stc_dbs, $custRequestremarks)."',
				'1',
				'".$_SESSION['stc_electro_user_id']."'
			)
		");
		if($set_loki){
			$odin = "Your Request Created Successfully.";
		}else{
			$odin = "Something went wrong on your Request creation.";
		}
		return $odin;
	}

	// call request
	public function stc_call_request(){
		$odin='
			<table class="table table-bordered table-responsive table-hover">
				<thead>
					<tr>
						<th>Request No <br> Request Date</th>
						<th>Customer/Party Name</th>
						<th>Contact Person <br> Contact Number</th>
						<th>Customer Address</th>
						<th>Material Details</th>
						<th>Order By</th>
						<th>Order Type</th>
						<th>GST No</th>
						<th>Remarks</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
		';
		$odin_get_req_qry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_electronics_request_raised_id`,
				`stc_electronics_request_raised_date`,
				`stc_electronics_request_raised_custname`,
				`stc_electronics_request_raised_cont_person`,
				`stc_electronics_request_raised_cont_number`,
				`stc_electronics_request_raised_address`,
				`stc_electronics_request_raised_material_details`,
				`stc_electronics_request_raised_order_by`,
				`stc_electronics_request_raised_invoice_type`,
				`stc_electronics_request_raised_gst_no`,
				`stc_electronics_request_raised_remarks`,
				`stc_electronics_request_raised_status`
			FROM
				`stc_electronics_request_raised`
			WHERE
				`stc_electronics_request_raised_created_by`='".$_SESSION['stc_electro_user_id']."'
			ORDER BY `stc_electronics_request_raised_id` DESC
			LIMIT 0,20
		");
		if(mysqli_num_rows($odin_get_req_qry)>0){
			foreach($odin_get_req_qry as $req_row){
				$status='Accepted';
				if($req_row['stc_electronics_request_raised_status']==1){
					$status='Requested';
				}

				$bgcolortr='style="background-color: #47a6a1;" title="Tax Invoice"';
				$invtype='Tax Invoice';
				if($req_row['stc_electronics_request_raised_invoice_type']==1){
					$invtype='Retail Invoice';
					$bgcolortr='title="Retail Invoice"';
				}
				$odin.='
					<tr '.$bgcolortr.'>
						<td>'.$req_row['stc_electronics_request_raised_id'].'</br>'.$req_row['stc_electronics_request_raised_date'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_custname'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_cont_person'].'</br>'.$req_row['stc_electronics_request_raised_cont_number'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_address'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_material_details'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_order_by'].'</td>
						<td>'.$invtype.'.</td>
						<td>'.$req_row['stc_electronics_request_raised_gst_no'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_remarks'].'</td>
						<td>'.$status.'.</td>
					</tr>
				';
			}
		}else{
			$odin.='
				<tr>
					<td>No Records found!!!</td>
				</tr>
			';
		}
		$odin.='
				</tbody>
			</table>
		';
		return $odin;
	}

	// search request
	public function stc_search_request($search){
		$odin='
			<table class="table table-bordered table-responsive table-hover">
				<thead>
					<tr>
						<th>Request No <br> Request Date</th>
						<th>Customer/Party Name</th>
						<th>Contact Person <br> Contact Number</th>
						<th>Customer Address</th>
						<th>Material Details</th>
						<th>Order By</th>
						<th>Order Type</th>
						<th>GST No</th>
						<th>Remarks</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
		';
		$odin_get_req_qry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_electronics_request_raised_id`,
				`stc_electronics_request_raised_date`,
				`stc_electronics_request_raised_custname`,
				`stc_electronics_request_raised_cont_person`,
				`stc_electronics_request_raised_cont_number`,
				`stc_electronics_request_raised_address`,
				`stc_electronics_request_raised_material_details`,
				`stc_electronics_request_raised_order_by`,
				`stc_electronics_request_raised_invoice_type`,
				`stc_electronics_request_raised_gst_no`,
				`stc_electronics_request_raised_remarks`,
				`stc_electronics_request_raised_status`
			FROM
				`stc_electronics_request_raised`
			WHERE
				`stc_electronics_request_raised_created_by`='".$_SESSION['stc_electro_user_id']."'
			AND (
				`stc_electronics_request_raised_custname` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR 
				`stc_electronics_request_raised_cont_person` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR 
				`stc_electronics_request_raised_cont_number` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR 
				`stc_electronics_request_raised_material_details` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR 
				`stc_electronics_request_raised_order_by` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR 
				`stc_electronics_request_raised_gst_no` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR 
				`stc_electronics_request_raised_address` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
			)
			ORDER BY `stc_electronics_request_raised_id` DESC
			LIMIT 0, 15
		");
		if(mysqli_num_rows($odin_get_req_qry)>0){
			foreach($odin_get_req_qry as $req_row){
				$status='Accepted';
				if($req_row['stc_electronics_request_raised_status']==1){
					$status='Requested';
				}

				$bgcolortr='style="background-color: #47a6a1;" title="Tax Invoice"';
				$invtype='Tax Invoice';
				if($req_row['stc_electronics_request_raised_invoice_type']==1){
					$invtype='Retail Invoice';
					$bgcolortr='title="Retail Invoice"';
				}
				$odin.='
					<tr '.$bgcolortr.'>
						<td>'.$req_row['stc_electronics_request_raised_id'].'</br>'.$req_row['stc_electronics_request_raised_date'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_custname'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_cont_person'].'</br>'.$req_row['stc_electronics_request_raised_cont_number'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_address'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_material_details'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_order_by'].'</td>
						<td>'.$invtype.'</td>
						<td>'.$req_row['stc_electronics_request_raised_gst_no'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_remarks'].'</td>
						<td>'.$status.'</td>
					</tr>
				';
			}
		}else{
			$odin.='
				<tr>
					<td>No Records found!!!</td>
				</tr>
			';
		}
		$odin.='
				</tbody>
			</table>
		';
		return $odin;
	}

	// call request raised
	public function stc_call_request_raised(){
		$odin='
			<table class="table table-bordered table-responsive table-hover">
				<thead>
					<tr>
						<th>Request No <br> Request Date</th>
						<th>Customer/Party Name</th>
						<th>Contact Person <br> Contact Number</th>
						<th>Customer Address</th>
						<th>Material Details</th>
						<th>Order By</th>
						<th>Order Type</th>
						<th>GST No</th>
						<th>Remarks</th>
						<th>Status</th>
						<th>By</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
		';
		$odin_get_req_qry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_electronics_request_raised_id`,
				`stc_electronics_request_raised_date`,
				`stc_electronics_request_raised_custname`,
				`stc_electronics_request_raised_cont_person`,
				`stc_electronics_request_raised_cont_number`,
				`stc_electronics_request_raised_address`,
				`stc_electronics_request_raised_material_details`,
				`stc_electronics_request_raised_order_by`,
				`stc_electronics_request_raised_invoice_type`,
				`stc_electronics_request_raised_gst_no`,
				`stc_electronics_request_raised_remarks`,
				`stc_electronics_request_raised_status`,
				`stc_electronics_user_fullName`
			FROM
				`stc_electronics_request_raised`
			INNER JOIN 
				`stc_electronics_user`
			ON 
				`stc_electronics_user_id`=`stc_electronics_request_raised_created_by`
			ORDER BY `stc_electronics_request_raised_id` DESC
			LIMIT 0,20
		");
		if(mysqli_num_rows($odin_get_req_qry)>0){
			foreach($odin_get_req_qry as $req_row){
				$status='Accepted';
				$action='#';
				if($req_row['stc_electronics_request_raised_status']==1){
					$status='Requested';
					$action='
						<a href="#" class="form-control btn btn-success text-center stc-e-request-action" id="'.$req_row['stc_electronics_request_raised_id'].'">
							<i class="fa fa-thumbs-up"></i>
						</a>
					';
				}

				$invtype='Tax Invoice';
				$bgcolortr='style="background-color: #47a6a1;" title="Tax Invoice"';
				if($req_row['stc_electronics_request_raised_invoice_type']==1){
					$invtype='Retail Invoice';
					$bgcolortr='title="Retail Invoice"';
				}
				$odin.='
					<tr '.$bgcolortr.'>
						<td>'.$req_row['stc_electronics_request_raised_id'].'</br>'.$req_row['stc_electronics_request_raised_date'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_custname'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_cont_person'].'</br>'.$req_row['stc_electronics_request_raised_cont_number'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_address'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_material_details'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_order_by'].'</td>
						<td>'.$invtype.'.</td>
						<td>'.$req_row['stc_electronics_request_raised_gst_no'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_remarks'].'</td>
						<td>'.$status.'.</td>
						<td>'.$req_row['stc_electronics_user_fullName'].'</td>
						<td class="text-center">'.$action.'</td>
					</tr>
				';
			}
		}else{
			$odin.='
				<tr>
					<td>No Records found!!!</td>
				</tr>
			';
		}
		$odin.='
				</tbody>
			</table>
		';
		return $odin;
	}

	// search request
	public function stc_search_request_raised($search){
		$odin='
			<table class="table table-bordered table-responsive table-hover">
				<thead>
					<tr>
						<th>Request No <br> Request Date</th>
						<th>Customer/Party Name</th>
						<th>Contact Person <br> Contact Number</th>
						<th>Customer Address</th>
						<th>Material Details</th>
						<th>Order By</th>
						<th>Order Type</th>
						<th>GST No</th>
						<th>Remarks</th>
						<th>Status</th>
						<th>By</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
		';
		$odin_get_req_qry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_electronics_request_raised_id`,
				`stc_electronics_request_raised_date`,
				`stc_electronics_request_raised_custname`,
				`stc_electronics_request_raised_cont_person`,
				`stc_electronics_request_raised_cont_number`,
				`stc_electronics_request_raised_address`,
				`stc_electronics_request_raised_material_details`,
				`stc_electronics_request_raised_order_by`,
				`stc_electronics_request_raised_invoice_type`,
				`stc_electronics_request_raised_gst_no`,
				`stc_electronics_request_raised_remarks`,
				`stc_electronics_request_raised_status`,
				`stc_electronics_user_fullName`
			FROM
				`stc_electronics_request_raised`
			INNER JOIN 
				`stc_electronics_user`
			ON 
				`stc_electronics_user_id`=`stc_electronics_request_raised_created_by`
			WHERE
				`stc_electronics_request_raised_custname` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR 
				`stc_electronics_request_raised_cont_person` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR 
				`stc_electronics_request_raised_cont_number` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR 
				`stc_electronics_request_raised_material_details` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR 
				`stc_electronics_request_raised_order_by` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR 
				`stc_electronics_request_raised_gst_no` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR 
				`stc_electronics_request_raised_address` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
			ORDER BY `stc_electronics_request_raised_id` DESC
			LIMIT 0,15
		");
		if(mysqli_num_rows($odin_get_req_qry)>0){
			foreach($odin_get_req_qry as $req_row){
				$status='Accepted';
				$action='#';
				if($req_row['stc_electronics_request_raised_status']==1){
					$status='Requested';
					$action='
						<a href="#" class="form-control btn btn-success text-center stc-e-request-action" id="'.$req_row['stc_electronics_request_raised_id'].'">
							<i class="fa fa-thumbs-up"></i>
						</a>
					';
				}

				$invtype='Tax Invoice';
				$bgcolortr='style="background-color: #47a6a1;" title="Tax Invoice"';
				if($req_row['stc_electronics_request_raised_invoice_type']==1){
					$invtype='Retail Invoice';
					$bgcolortr='title="Retail Invoice"';
				}
				$odin.='
					<tr '.$bgcolortr.'>
						<td>'.$req_row['stc_electronics_request_raised_id'].'</br>'.$req_row['stc_electronics_request_raised_date'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_custname'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_cont_person'].'</br>'.$req_row['stc_electronics_request_raised_cont_number'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_address'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_material_details'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_order_by'].'</td>
						<td>'.$invtype.'</td>
						<td>'.$req_row['stc_electronics_request_raised_gst_no'].'</td>
						<td>'.$req_row['stc_electronics_request_raised_remarks'].'</td>
						<td>'.$status.'</td>
						<td>'.$req_row['stc_electronics_user_fullName'].'</td>
						<td class="text-center">'.$action.'</td>
					</tr>
				';
			}
		}else{
			$odin.='
				<tr>
					<td>No Records found!!!</td>
				</tr>
			';
		}
		$odin.='
				</tbody>
			</table>
		';
		return $odin;
	}

	// change status
	public function stc_change_status($request_id){
		$odin='';
		$odinsetqry=mysqli_query($this->stc_dbs, "
			UPDATE
				`stc_electronics_request_raised`
			SET
				`stc_electronics_request_raised_status` = '2'
			WHERE
				`stc_electronics_request_raised_id` = '".mysqli_real_escape_string($this->stc_dbs, $request_id)."'
		");
		if($odinsetqry){
			$odin='Request Accepted. Status Changed';
		}else{
			$odin='Hmmm!!! Something went wrong on accepting request.';
		}
		return $odin;
	}
}

#<------------------------------------------------------------------------------------------>
#<---------------------------------Silent Request Section----------------------------------->
#<------------------------------------------------------------------------------------------>
// save request order
if(isset($_POST['save_Request_action'])){
	$out='';
	$custRequestpartyname=$_POST['custRequestpartyname'];
	$custRequestcontname=$_POST['custRequestcontname'];
	$custRequestcontnumber=$_POST['custRequestcontnumber'];
	$custRequestaddress=$_POST['custRequestaddress'];
	$custRequestmaterialdetails=$_POST['custRequestmaterialdetails'];
	$custRequestorderby=$_POST['custRequestorderby'];
	$custRequestinvtype=$_POST['custRequestinvtype'];
	$custRequestgstno=$_POST['custRequestgstno'];
	$custRequestremarks=$_POST['custRequestremarks'];

	$valkyrie=new Yggdrasil();
	if(empty($custRequestpartyname) || empty($custRequestcontname) || empty($custRequestcontnumber) || empty($custRequestaddress) || empty($custRequestmaterialdetails) || empty($custRequestorderby)){
		$out='Like i said above, Please do not let any field empty.';
	}elseif(($custRequestinvtype==2) && (empty($custRequestgstno) || strlen($custRequestgstno)<5)){
		$out='Please Provide Correct GST No Of Customer.';
	}elseif(empty($_SESSION['stc_electro_user_id'])){
		header("Location:../index.html");
	}else{
		$lokiheck=$valkyrie->stc_save_request_raised($custRequestpartyname, $custRequestcontname, $custRequestcontnumber, $custRequestaddress, $custRequestmaterialdetails, $custRequestorderby, $custRequestinvtype, $custRequestgstno, $custRequestremarks);
		$out=$lokiheck;
	}
	echo $out;
}

// call request
if(isset($_POST['stc_call_request'])){
	$valkyrie=new Yggdrasil();
	$lokiheck=$valkyrie->stc_call_request();
	echo $lokiheck;
}

// search request
if(isset($_POST['stc_search_request'])){
	$search=$_POST['search'];
	$valkyrie=new Yggdrasil();
	$lokiheck=$valkyrie->stc_search_request($search);
	echo $lokiheck;
}

// call request raised
if(isset($_POST['stc_call_request_raised'])){
	$valkyrie=new Yggdrasil();
	$lokiheck=$valkyrie->stc_call_request_raised();
	echo $lokiheck;
}

// search request raised
if(isset($_POST['stc_search_request_raised'])){
	$search=$_POST['search'];
	$valkyrie=new Yggdrasil();
	$lokiheck=$valkyrie->stc_search_request_raised($search);
	echo $lokiheck;
}

// change status
if(isset($_POST['stc_request_action'])){
	$request_id=$_POST['request_id'];
	$valkyrie=new Yggdrasil();
	$lokiheck=$valkyrie->stc_change_status($request_id);
	echo $lokiheck;
}
?>