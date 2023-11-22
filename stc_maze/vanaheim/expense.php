<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*---------------------------------------For Silent Invoice --------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class Yggdrasil extends tesseract{
	// stc call request
	public function stc_save_expense($silentexpensdedate, $silentexpenseamount, $silentexpensemessage, $company){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$set_loki=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_expenses`(
			    `stc_expenses_date`,
			    `stc_expenses_amount`,
			    `stc_expenses_message`,
			    `stc_expenses_company`,
			    `stc_expenses_created_by`
			)VALUES(
				'".mysqli_real_escape_string($this->stc_dbs, $silentexpensdedate)."',
				'".mysqli_real_escape_string($this->stc_dbs, $silentexpenseamount)."',
				'".mysqli_real_escape_string($this->stc_dbs, $silentexpensemessage)."',
				'".mysqli_real_escape_string($this->stc_dbs, $company)."',
				'".$_SESSION['stc_electro_user_id']."'
			)
		");
		if($set_loki){
			$odin = "Your Expense Created Successfully.";
		}else{
			$odin = "Something went wrong on your expense creation.";
		}
		return $odin;
	}

	// call request raised
	public function stc_call_expense(){
		$odin='
			<table class="table table-bordered table-responsive table-hover">
				<thead>
					<tr>
						<th class="text-center">Expense Number</th>
						<th class="text-center">Expense Date</th>
						<th class="text-center">Expense Message</th>
						<th class="text-center">Expense Amount</th>
						<th class="text-center">Created By</th>
					</tr>
				</thead>
				<tbody>
		';
		$odin_get_req_qry=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_expenses_id`,
			    `stc_expenses_date`,
			    `stc_expenses_amount`,
			    `stc_expenses_message`,
			    `stc_electronics_user_fullName`
			FROM
			    `stc_expenses`
			INNER JOIN 
				`stc_electronics_user` 
			ON
				`stc_electronics_user_id`=`stc_expenses_created_by`
			WHERE 
				`stc_expenses_created_by`='".$_SESSION['stc_electro_user_id']."'
			AND 
				`stc_expenses_company`=2
			ORDER BY DATE(`stc_expenses_date`) DESC
		");
		if(mysqli_num_rows($odin_get_req_qry)>0){
			foreach($odin_get_req_qry as $req_row){
				$odin.='
					<tr>
						<td class="text-right">STC/E/E/'.$req_row['stc_expenses_id'].'</td>
						<td class="text-center">'.date('d-m-Y', strtotime($req_row['stc_expenses_date'])).'</td>
						<td class="text-left">'.$req_row['stc_expenses_message'].'</td>
						<td class="text-right">'.number_format($req_row['stc_expenses_amount'], 2).'</td>
						<td class="text-center">'.$req_row['stc_electronics_user_fullName'].'</td>
					</tr>
				';
			}
			$odin.='
				<tr>
					<td colspan="3" class="text-right">Total : </td>
					<td class="text-right">'.number_format($req_row['stc_expenses_amount'], 2).'</td>
					<td class="text-right"></td>
				</tr>
			';
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
}

#<------------------------------------------------------------------------------------------>
#<---------------------------------Silent Request Section----------------------------------->
#<------------------------------------------------------------------------------------------>
// save request order
if(isset($_POST['save_expense_action'])){
	$out='';
	$silentexpensdedate=$_POST['silentexpensdedate'];
	$silentexpenseamount=$_POST['silentexpenseamount'];
	$silentexpensemessage=$_POST['silentexpensemessage'];
	$company=$_POST['company'];

	$valkyrie=new Yggdrasil();
	if(empty($silentexpensdedate) || empty($silentexpenseamount) || empty($silentexpensemessage)){
		$out='Like i said above, Please do not let any field empty.';
	}elseif(empty($_SESSION['stc_electro_user_id'])){
		header("Location:../index.html");
	}else{
		$lokiheck=$valkyrie->stc_save_expense($silentexpensdedate, $silentexpenseamount, $silentexpensemessage, $company);
		$out=$lokiheck;
	}
	echo $out;
}

// call request
if(isset($_POST['stc_call_expense'])){
	$valkyrie=new Yggdrasil();
	$lokiheck=$valkyrie->stc_call_expense();
	echo $lokiheck;
}
?>