<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*---------------------------------------For Silent Product --------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class aetherCustomer extends tesseract{

	// go data to purchase challan table
	public function customer_process($silentcustomername, $silentcustomeraddress, $silentcustomercontactno, $silentcustomerpincode, $silentcustomercity, $silentcustomerstate, $silentcustomerremarks){
		$odin='';
		$lokicheckqry=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_groceries_merchant_title`
			FROM
			    `stc_groceries_merchant`
			WHERE
			    `stc_groceries_merchant_title` = '".mysqli_real_escape_string($this->stc_dbs, $silentcustomername)."' OR 
			    `stc_groceries_merchant_cont_no` = '".mysqli_real_escape_string($this->stc_dbs, $silentcustomercontactno)."'
		");
		if(mysqli_num_rows($lokicheckqry)>0){
			$odin .= "Hmmm!!! This Customer is already in record.";
		}else{
			$lokiinsertcustomer=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_groceries_merchant`(
					`stc_groceries_merchant_title`,
					`stc_groceries_merchant_address`,
					`stc_groceries_merchant_cont_no`,
					`stc_groceries_merchant_pincode`,
					`stc_groceries_merchant_city_id`,
					`stc_groceries_merchant_state_id`,
					`stc_groceries_merchant_remarks`,
					`stc_groceries_merchant_createdby`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $silentcustomername)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $silentcustomeraddress)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $silentcustomercontactno)."',
					'".mysqli_real_escape_string($this->stc_dbs, $silentcustomerpincode)."',
					'".mysqli_real_escape_string($this->stc_dbs, $silentcustomercity)."',
					'".mysqli_real_escape_string($this->stc_dbs, $silentcustomerstate)."',
					'".mysqli_real_escape_string($this->stc_dbs, $silentcustomerremarks)."',
					'".$_SESSION['stc_groceries_user_id']."'
				)
			");
			$odin='';
			if($lokiinsertcustomer){
				$odin .= "Customer Added Successfully!!!";
			}else{
				$odin .= "Hmm!!! Something went wrong. Please check customer name details or others field & try again!!!";
			}
		}
		return $odin;
	}	

	// stc call silent purchase
	public function stc_call_customer($search){
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_groceries_merchant_id`,
				`stc_groceries_merchant_title`,
				`stc_groceries_merchant_address`,
				`stc_groceries_merchant_cont_no`,
				`stc_groceries_merchant_pincode`,
				`stc_city_name`,
				`stc_state_name`,
				`stc_groceries_merchant_remarks`,
				`stc_groceries_user_name`
			FROM
				`stc_groceries_merchant`
			INNER JOIN `stc_city` 
			ON `stc_city_id` = `stc_groceries_merchant_city_id`
			INNER JOIN `stc_state` 
			ON `stc_state_id` = `stc_groceries_merchant_state_id`
			INNER JOIN `stc_groceries_user` 
			ON `stc_groceries_user_created_by` = `stc_groceries_merchant_createdby`
			WHERE 
				`stc_groceries_merchant_title` 	REGEXP 	'".mysqli_real_escape_string($this->stc_dbs, $search)."' OR
				`stc_groceries_merchant_address` 	REGEXP 	'".mysqli_real_escape_string($this->stc_dbs, $search)."' OR
				`stc_groceries_merchant_cont_no` 	REGEXP 	'".mysqli_real_escape_string($this->stc_dbs, $search)."' OR
				`stc_groceries_merchant_pincode` 	REGEXP 	'".mysqli_real_escape_string($this->stc_dbs, $search)."' OR
				`stc_groceries_merchant_remarks` 	REGEXP 	'".mysqli_real_escape_string($this->stc_dbs, $search)."' OR
				`stc_city_name` 				REGEXP 	'".mysqli_real_escape_string($this->stc_dbs, $search)."' OR
				`stc_state_name` 				REGEXP 	'".mysqli_real_escape_string($this->stc_dbs, $search)."' OR
				`stc_groceries_user_name` 		REGEXP 	'".mysqli_real_escape_string($this->stc_dbs, $search)."'
			ORDER BY 
				`stc_groceries_merchant_title` 
			DESC LIMIT 0,15
		");
		$odin='';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td colspan='7' align='center'>No Record Found!!!</td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$cust_type='';
				$odin.='
					<tr>
       					<td class="text-center">
       					  <p>'.$row["stc_groceries_merchant_title"].'</p>
       					</td>
       					<td class="text-left">
       					  <p>'.$row["stc_groceries_merchant_address"].'</p>
       					</td>
       					<td class="text-center">
       					  '.$row["stc_city_name"].'<br>
       					  '.$row["stc_state_name"].'<br>
       					  '.$row["stc_groceries_merchant_pincode"].'
       					</td>
       					<td class="text-center">
       					  <p>'.$row["stc_groceries_merchant_cont_no"].'</p>
       					</td>
       					<td class="text-center">
       					  <p>'.$cust_type.'</p>
       					</td>
       					<td class="text-center">
       					  <p>'.$row["stc_groceries_merchant_remarks"].'</p>
       					</td>
       					<td class="text-center">
       					  <p>'.$row["stc_groceries_user_name"].'</p>
       					</td>
       					<td class="text-center">
       					  <a 
       					  	href="#"
       					  	class="stc-tra-customer-edit-modal-req"
       					  	id="'.$row['stc_groceries_merchant_id'].'"
       					  ><i class="fa fa-edit"></i>
       					  </a>
       					</td>
            		</tr>         	
		    	';				
			}
			
		}
		return $odin;
	}

	// call customer details
	public function stc_call_customer_for_edit($cust_id){
		$odin = '';
		$title = '';
		$address = '';
		$cont_no = '';
		$odincustqry=mysqli_query($this->stc_dbs, "			
			SELECT DISTINCT
				`stc_groceries_merchant_title`,
				`stc_groceries_merchant_address`,
				`stc_groceries_merchant_cont_no`
			FROM
				`stc_groceries_merchant`
			WHERE 
				`stc_groceries_merchant_id` = '".mysqli_real_escape_string($this->stc_dbs, $cust_id)."'
			ORDER BY `stc_groceries_merchant_id` DESC LIMIT 0,1
		");

		foreach ($odincustqry as $value) {			
			$title = $value['stc_groceries_merchant_title'];
			$address = $value['stc_groceries_merchant_address'];
			$cont_no = $value['stc_groceries_merchant_cont_no'];
		}

		$odin=array(
			'cust_id' 	=> $cust_id,
			'title' 	=> $title,
			'address' 	=> $address,
			'cont_no' 	=> $cont_no
		);
		return $odin;
	}

	// save edited customer
	public function stc_save_customer_edit($cust_id, $cust_title, $cust_address, $cust_cont_no){
		$odin='';
		$odineditcustqry=mysqli_query($this->stc_dbs, "
			UPDATE
			    `stc_groceries_merchant`
			SET
				`stc_groceries_merchant_address`='".mysqli_real_escape_string($this->stc_dbs, $cust_address)."',
			    `stc_groceries_merchant_cont_no`='".mysqli_real_escape_string($this->stc_dbs, $cust_cont_no)."'
			WHERE
			    `stc_groceries_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $cust_id)."'
		");
		if($odineditcustqry){
			$odin='Merchant Edited Successfully!!!';
		}else{
			$odin='Hmmmm!!! Something Went Wrong Merchant Not Edited Successfully. Please Check & Try Again Later.';
		}
		return $odin;
	}
}

#<------------------------------------------------------------------------------------------>
#<---------------------------------Silent customer section---------------------------------->
#<------------------------------------------------------------------------------------------>
// save customer
if(isset($_POST['save_customer_action'])){
	$out='';
	$silentcustomername			= $_POST['silentcustomername'];
	$silentcustomeraddress		= $_POST['silentcustomeraddress'];
	$silentcustomercontactno	= $_POST['silentcustomercontactno'];
	$silentcustomerpincode		= $_POST['silentcustomerpincode'];
	$silentcustomercity			= $_POST['silentcustomercity'];
	$silentcustomerstate		= $_POST['silentcustomerstate'];
	$silentcustomerremarks		= $_POST['silentcustomerremarks'];

	$fridaycustomer=new aetherCustomer();
	if(empty($silentcustomername) || empty($silentcustomeraddress) || empty($silentcustomercontactno)){
		$out= "Please Fill All Fields!!!";
	}else{
		$fridaycustomer=$fridaycustomer->customer_process($silentcustomername, $silentcustomeraddress, $silentcustomercontactno, $silentcustomerpincode, $silentcustomercity, $silentcustomerstate, $silentcustomerremarks);
		$out=$fridaycustomer;
	}
	echo $out;
}

// Show customer On customer Page
if(isset($_POST['stc_show_customer'])){
	$search=$_POST['search'];
	$objloki=new aetherCustomer();
	$objlokiout=$objloki->stc_call_customer($search);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// call customer records
if(isset($_POST['stc_tra_cust_call'])){
	$cust_id=$_POST['cust_id'];
	$objloki=new aetherCustomer();
	$objlokiout=$objloki->stc_call_customer_for_edit($cust_id);
	// echo $objlokiout;
	echo json_encode($objlokiout);
}

// save edit customer
if(isset($_POST['stc_tra_cust_save'])){
	$cust_id=$_POST['cust_id'];
	$cust_title=$_POST['cust_title'];
	$cust_address=$_POST['cust_address'];
	$cust_cont_no=$_POST['cust_cont_no'];
	$objloki=new aetherCustomer();
	$objlokiout=$objloki->stc_save_customer_edit($cust_id, $cust_title, $cust_address, $cust_cont_no);
	// echo $objlokiout;
	echo json_encode($objlokiout);
}
?>