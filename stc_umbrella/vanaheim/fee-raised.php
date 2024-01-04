<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*---------------------------------------For School Canteen --------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class Yggdrasil extends tesseract{
	// stc call request
	public function stc_save_school_fee($stcwhichschool, $stcschoolmonthlyfee, $stcschooladmissionfee, $stcschoolbooks, $stcschooltransporation, $stcschooldonation, $stcschooldayboarding, $stcschoolneatcoll, $stcschooldssalary, $stcschooltsalary, $stcschoolvfuel, $stcschoolvmaint, $stcschoolelectricity, $stcschoolcanteen, $stcschoolothercharges, $stcschoolcashback, $stcschoolexpenses, $stcschoolremarks){
		$odin='';
		$date=date("Y-m-d");
		$set_loki=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_school_fee`(
				`stc_school_fee_date`,
				`stc_school_fee_which_school`,
				`stc_school_fee_monthly_fee`,
				`stc_school_fee_admission_fee`,
				`stc_school_fee_book_charge`,
				`stc_school_fee_transportation`,
				`stc_school_fee_donation`,
				`stc_school_fee_dayboarding`,
				`stc_school_fee_neat`,
				`stc_school_fee_dstaffsal`,
				`stc_school_fee_teacherssal`,
				`stc_school_fee_vehiclefuel`,
				`stc_school_fee_vehiclemaintenance`,
				`stc_school_fee_electricity`,
				`stc_school_fee_canteen`,
				`stc_school_fee_others`,
				`stc_school_fee_cashback`,
				`stc_school_fee_expense`,
				`stc_school_fee_remarks`,
				`stc_school_fee_created_by`
			)VALUES(
				'".mysqli_real_escape_string($this->stc_dbs, $date)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcwhichschool)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmonthlyfee)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschooladmissionfee)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschoolbooks)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschooltransporation)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschooldonation)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschooldayboarding)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschoolneatcoll)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschooldssalary)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschooltsalary)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschoolvfuel)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschoolvmaint)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschoolelectricity)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschoolcanteen)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschoolothercharges)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschoolcashback)."',				
				'".mysqli_real_escape_string($this->stc_dbs, $stcschoolexpenses)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschoolremarks)."',
				'".$_SESSION['stc_school_user_id']."'
			)
		");
		if($set_loki){
			$odin = "Record saved successfully.";
		}else{
			$odin = "Something went wrong on saving record.";
		}
		return $odin;
	}

	// call request
	public function stc_call_fee(){
		$odin='
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th class="text-center"><b>School</b></th>
						<th class="text-center"><b>Date</b></th>
						<th class="text-center"><b>Monthly Fee</b></th>
						<th class="text-center"><b>Admission Fee</b></th>
						<th class="text-center"><b>Books</b></th>
						<th class="text-center"><b>Transportation</b></th>
						<th class="text-center"><b>Donation</b></th>
						<th class="text-center"><b>Day Boarding</b></th>
						<th class="text-center"><b>NEET</b></th>
						<th class="text-center"><b>Others</b></th>
						<th class="text-center"><b>Cashback</b></th>
						<th class="text-center"><b>D Staff Salary</b></th>
						<th class="text-center"><b>Teachers Salary</b></th>
						<th class="text-center"><b>Vehicle Fuels</b></th>
						<th class="text-center"><b>Vehicle Maintenance</b></th>
						<th class="text-center"><b>Electricity</b></th>
						<th class="text-center"><b>Canteen</b></th>
						<th class="text-center"><b>Other Expenses</b></th>
						<th class="text-center"><b>Total</b></th>
						<th class="text-center"><b>Remarks</b></th>
					</tr>
				</thead>
				<tbody>
		';
		$odin_get_req_qry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_school_fee_id`,
			    `stc_school_fee_which_school`,
			    `stc_school_fee_date`
			FROM
			    `stc_school_fee`
			WHERE 
				`stc_school_fee_created_by`='".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_school_user_id'])."'
			ORDER BY DATE(`stc_school_fee_date`) DESC LIMIT 0, 20
		");
		if(mysqli_num_rows($odin_get_req_qry)>0){
			$maxmonthfee=0;
			$maxadmfee=0;
			$maxbook=0;
			$maxtransport=0;
			$maxdonation=0;
			$maxdayboarding=0;
			$maxneat=0;
			$maxother=0;
			$maxcashback=0;
			$maxdsal=0;
			$maxssal=0;
			$maxvfuel=0;
			$maxvmaint=0;
			$maxelectricity=0;
			$maxcanteen=0;
			$maxexpense=0;
			$maxtotal=0;
			foreach($odin_get_req_qry as $req_row){
				$school='';
				$monthfee=0;
				$admmfee=0;
				$book=0;
				$transport=0;
				$donation=0;
				$dayboarding=0;
				$neat=0;
				$others=0;
				$cashback=0;
				$dsal=0;
				$ssal=0;
				$vfuel=0;
				$vmaint=0;
				$electricity=0;
				$canteen=0;
				$expense=0;
				$remarks=0;

				$odin_getstudentqry=mysqli_query($this->stc_dbs, "
					SELECT 
                        `stc_school_fee_which_school`,
                        `stc_school_fee_monthly_fee`,
                        `stc_school_fee_admission_fee`,
                        `stc_school_fee_book_charge`,
                        `stc_school_fee_transportation`,
                        `stc_school_fee_donation`,
                        `stc_school_fee_dayboarding`,
                        `stc_school_fee_neat`,
                        `stc_school_fee_others`,
                        `stc_school_fee_cashback`,
                        `stc_school_fee_dstaffsal`,
                        `stc_school_fee_teacherssal`,
                        `stc_school_fee_vehiclefuel`,
                        `stc_school_fee_vehiclemaintenance`,
                        `stc_school_fee_electricity`,
						`stc_school_fee_canteen`,
                        `stc_school_fee_expense`,
                        `stc_school_fee_remarks`
                    FROM
                        `stc_school_fee`
                    WHERE
                        `stc_school_fee_id`='".mysqli_real_escape_string($this->stc_dbs, $req_row['stc_school_fee_id'])."'
					AND 
						`stc_school_fee_which_school`='".mysqli_real_escape_string($this->stc_dbs, $req_row['stc_school_fee_which_school'])."'
					ORDER BY DATE(`stc_school_fee_date`) DESC
				");

				foreach($odin_getstudentqry as $odin_getstudentrow){
					$school=$odin_getstudentrow['stc_school_fee_which_school'];
					$monthfee=$odin_getstudentrow['stc_school_fee_monthly_fee'];
					$admmfee=$odin_getstudentrow['stc_school_fee_admission_fee'];
					$book=$odin_getstudentrow['stc_school_fee_book_charge'];
					$transport=$odin_getstudentrow['stc_school_fee_transportation'];
					$donation=$odin_getstudentrow['stc_school_fee_donation'];
					$dayboarding=$odin_getstudentrow['stc_school_fee_dayboarding'];
					$neat=$odin_getstudentrow['stc_school_fee_neat'];
					$dsal=$odin_getstudentrow['stc_school_fee_dstaffsal'];
					$ssal=$odin_getstudentrow['stc_school_fee_teacherssal'];
					$vfuel=$odin_getstudentrow['stc_school_fee_vehiclefuel'];
					$vmaint=$odin_getstudentrow['stc_school_fee_vehiclemaintenance'];
					$electricity=$odin_getstudentrow['stc_school_fee_electricity'];
					$canteen=$odin_getstudentrow['stc_school_fee_canteen'];
					$others=$odin_getstudentrow['stc_school_fee_others'];
					$cashback=$odin_getstudentrow['stc_school_fee_cashback'];
					$expense=$odin_getstudentrow['stc_school_fee_expense'];
					$remarks=$odin_getstudentrow['stc_school_fee_remarks'];
				}

				$total= $monthfee + $admmfee + $book + $transport + $donation + $dayboarding + $neat + $others + $cashback - ($expense + $dsal + $ssal + $vfuel + $electricity + $canteen + $vmaint);
				$maxmonthfee+=$monthfee;
				$maxadmfee+=$admmfee;
				$maxbook+=$book;
				$maxtransport+=$transport;
				$maxdonation+=$donation;
				$maxdayboarding+=$dayboarding;
				$maxneat+=$neat;
				$maxother+=$others;
				$maxcashback+=$cashback;
				$maxdsal+=$dsal;
				$maxssal+=$ssal;
				$maxvfuel+=$vfuel;
				$maxvmaint+=$vmaint;
				$maxelectricity+=$electricity;
				$maxcanteen+=$canteen;
				$maxexpense+=$expense;
				$odin.='
					<tr>
						<td class="text-center">'.$school.'</td>
						<td class="text-center">'.date('d-m-Y', strtotime($req_row['stc_school_fee_date'])).'</td>
						<td class="text-right">'.number_format($monthfee, 2).'</td>
						<td class="text-right">'.number_format($admmfee, 2).'</td>
						<td class="text-right">'.number_format($book, 2).'</td>
						<td class="text-right">'.number_format($transport, 2).'</td>
						<td class="text-right">'.number_format($donation, 2).'</td>
						<td class="text-right">'.number_format($dayboarding, 2).'</td>
						<td class="text-right">'.number_format($neat, 2).'</td>
						<td class="text-right">'.number_format($others, 2).'</td>
						<td class="text-right">'.number_format($cashback, 2).'</td>
						<td class="text-right">'.number_format($dsal, 2).'</td>
						<td class="text-right">'.number_format($ssal, 2).'</td>
						<td class="text-right">'.number_format($vfuel, 2).'</td>
						<td class="text-right">'.number_format($vmaint, 2).'</td>
						<td class="text-right">'.number_format($electricity, 2).'</td>
						<td class="text-right">'.number_format($canteen, 2).'</td>
						<td class="text-right">'.number_format($expense, 2).'</td>
						<td class="text-right">'.number_format($total, 2).'</td>
						<td class="text-right">'.$remarks.'</td>
					</tr>
				';
				$maxtotal+=$total;
			}
			$odin.='
				<tr style="font-size: 20px;font-weight: bold;">
					<td class="text-right" colspan="2">Total :</td>
					<td class="text-right">'.number_format($maxmonthfee, 2).'</td>
					<td class="text-right">'.number_format($maxadmfee, 2).'</td>
					<td class="text-right">'.number_format($maxbook, 2).'</td>
					<td class="text-right">'.number_format($maxtransport, 2).'</td>
					<td class="text-right">'.number_format($maxdonation, 2).'</td>
					<td class="text-right">'.number_format($maxdayboarding, 2).'</td>
					<td class="text-right">'.number_format($maxneat, 2).'</td>
					<td class="text-right">'.number_format($maxother, 2).'</td>
					<td class="text-right">'.number_format($maxcashback, 2).'</td>
					<td class="text-right">'.number_format($maxdsal, 2).'</td>
					<td class="text-right">'.number_format($maxssal, 2).'</td>
					<td class="text-right">'.number_format($maxvfuel, 2).'</td>
					<td class="text-right">'.number_format($maxvmaint, 2).'</td>
					<td class="text-right">'.number_format($maxelectricity, 2).'</td>
					<td class="text-right">'.number_format($maxcanteen, 2).'</td>
					<td class="text-right">'.number_format($maxexpense, 2).'</td>
					<td class="text-right">'.number_format($maxtotal, 2).'</td>
					<td class="text-right"></td>
				</tr>
			';
		}else{
			$odin.='
				<tr>
					<td colspan="17" class="text-center">No Records Found!!!</td>
				</tr>
			';
		}
		return $odin;
	}

	// search school fee
	public function stc_search_school_fee($search){
		$odin='
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th class="text-center"><b>School</b></th>
						<th class="text-center"><b>Date</b></th>
						<th class="text-center"><b>Monthly Fee</b></th>
						<th class="text-center"><b>Admission Fee</b></th>
						<th class="text-center"><b>Books</b></th>
						<th class="text-center"><b>Transportation</b></th>
						<th class="text-center"><b>Donation</b></th>
						<th class="text-center"><b>Day Boarding</b></th>
						<th class="text-center"><b>Neat</b></th>
						<th class="text-center"><b>Others</b></th>
						<th class="text-center"><b>Cashback</b></th>
						<th class="text-center"><b>D Staff Salary</b></th>
						<th class="text-center"><b>Teachers Salary</b></th>
						<th class="text-center"><b>Vehicle Fuels</b></th>
						<th class="text-center"><b>Vehicle Maintenance</b></th>
						<th class="text-center"><b>Electricity</b></th>
						<th class="text-center"><b>Canteen</b></th>
						<th class="text-center"><b>Other Expenses</b></th>
						<th class="text-center"><b>Total</b></th>
						<th class="text-center"><b>Remarks</b></th>
					</tr>
				</thead>
				<tbody>
		';
		$odin_get_req_qry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_school_fee_id`,
			    `stc_school_fee_which_school`,
			    `stc_school_fee_date`
			FROM
			    `stc_school_fee`
			WHERE 
				MONTH(`stc_school_fee_date`)='".mysqli_real_escape_string($this->stc_dbs, $search)."' 
			AND
				`stc_school_fee_created_by`='".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_school_user_id'])."'
			ORDER BY DATE(`stc_school_fee_date`) DESC
		");
		if(mysqli_num_rows($odin_get_req_qry)>0){
			$maxmonthfee=0;
			$maxadmfee=0;
			$maxbook=0;
			$maxtransport=0;
			$maxdonation=0;
			$maxdayboarding=0;
			$maxneat=0;
			$maxother=0;
			$maxcashback=0;
			$maxdsal=0;
			$maxssal=0;
			$maxvfuel=0;
			$maxvmaint=0;
			$maxelectricity=0;
			$maxcanteen=0;
			$maxexpense=0;
			$maxtotal=0;
			foreach($odin_get_req_qry as $req_row){
				$school='';
				$monthfee=0;
				$admmfee=0;
				$book=0;
				$transport=0;
				$donation=0;
				$dayboarding=0;
				$neat=0;
				$others=0;
				$cashback=0;
				$dsal=0;
				$ssal=0;
				$vfuel=0;
				$vmaint=0;
				$electricity=0;
				$canteen=0;
				$expense=0;
				$remarks=0;

				$odin_getstudentqry=mysqli_query($this->stc_dbs, "
					SELECT 
                        `stc_school_fee_which_school`,
                        `stc_school_fee_monthly_fee`,
                        `stc_school_fee_admission_fee`,
                        `stc_school_fee_book_charge`,
                        `stc_school_fee_transportation`,
                        `stc_school_fee_donation`,
                        `stc_school_fee_dayboarding`,
                        `stc_school_fee_neat`,
                        `stc_school_fee_others`,
                        `stc_school_fee_cashback`,
                        `stc_school_fee_dstaffsal`,
                        `stc_school_fee_teacherssal`,
                        `stc_school_fee_vehiclefuel`,
                        `stc_school_fee_vehiclemaintenance`,
                        `stc_school_fee_electricity`,
						`stc_school_fee_canteen`,
                        `stc_school_fee_expense`,
                        `stc_school_fee_remarks`
                    FROM
                        `stc_school_fee`
                    WHERE
                        `stc_school_fee_id`='".mysqli_real_escape_string($this->stc_dbs, $req_row['stc_school_fee_id'])."'
					AND 
						`stc_school_fee_which_school`='".mysqli_real_escape_string($this->stc_dbs, $req_row['stc_school_fee_which_school'])."'
					ORDER BY DATE(`stc_school_fee_date`) DESC
				");

				foreach($odin_getstudentqry as $odin_getstudentrow){
					$school=$odin_getstudentrow['stc_school_fee_which_school'];
					$monthfee=$odin_getstudentrow['stc_school_fee_monthly_fee'];
					$admmfee=$odin_getstudentrow['stc_school_fee_admission_fee'];
					$book=$odin_getstudentrow['stc_school_fee_book_charge'];
					$transport=$odin_getstudentrow['stc_school_fee_transportation'];
					$donation=$odin_getstudentrow['stc_school_fee_donation'];
					$dayboarding=$odin_getstudentrow['stc_school_fee_dayboarding'];
					$neat=$odin_getstudentrow['stc_school_fee_neat'];
					$dsal=$odin_getstudentrow['stc_school_fee_dstaffsal'];
					$ssal=$odin_getstudentrow['stc_school_fee_teacherssal'];
					$vfuel=$odin_getstudentrow['stc_school_fee_vehiclefuel'];
					$vmaint=$odin_getstudentrow['stc_school_fee_vehiclemaintenance'];
					$electricity=$odin_getstudentrow['stc_school_fee_electricity'];
					$canteen=$odin_getstudentrow['stc_school_fee_canteen'];
					$others=$odin_getstudentrow['stc_school_fee_others'];
					$cashback=$odin_getstudentrow['stc_school_fee_cashback'];
					$expense=$odin_getstudentrow['stc_school_fee_expense'];
					$remarks=$odin_getstudentrow['stc_school_fee_remarks'];
				}

				$total= $monthfee + $admmfee + $book + $transport + $donation + $dayboarding + $neat + $others + $cashback - ($expense + $dsal + $ssal + $vfuel + $electricity + $canteen + $vmaint);
				$maxmonthfee+=$monthfee;
				$maxadmfee+=$admmfee;
				$maxbook+=$book;
				$maxtransport+=$transport;
				$maxdonation+=$donation;
				$maxdayboarding+=$dayboarding;
				$maxneat+=$neat;
				$maxother+=$others;
				$maxcashback+=$cashback;
				$maxdsal+=$dsal;
				$maxssal+=$ssal;
				$maxvfuel+=$vfuel;
				$maxvmaint+=$vmaint;
				$maxelectricity+=$electricity;
				$maxcanteen+=$canteen;
				$maxexpense+=$expense;
				$odin.='
					<tr>
						<td class="text-center">'.$school.'</td>
						<td class="text-center">'.date('d-m-Y', strtotime($req_row['stc_school_fee_date'])).'</td>
						<td class="text-right">'.number_format($monthfee, 2).'</td>
						<td class="text-right">'.number_format($admmfee, 2).'</td>
						<td class="text-right">'.number_format($book, 2).'</td>
						<td class="text-right">'.number_format($transport, 2).'</td>
						<td class="text-right">'.number_format($donation, 2).'</td>
						<td class="text-right">'.number_format($dayboarding, 2).'</td>
						<td class="text-right">'.number_format($neat, 2).'</td>
						<td class="text-right">'.number_format($others, 2).'</td>
						<td class="text-right">'.number_format($cashback, 2).'</td>
						<td class="text-right">'.number_format($dsal, 2).'</td>
						<td class="text-right">'.number_format($ssal, 2).'</td>
						<td class="text-right">'.number_format($vfuel, 2).'</td>
						<td class="text-right">'.number_format($vmaint, 2).'</td>
						<td class="text-right">'.number_format($electricity, 2).'</td>
						<td class="text-right">'.number_format($canteen, 2).'</td>
						<td class="text-right">'.number_format($expense, 2).'</td>
						<td class="text-right">'.number_format($total, 2).'</td>
						<td class="text-right">'.$remarks.'</td>
					</tr>
				';
				$maxtotal+=$total;
			}
			$odin.='
				<tr style="font-size: 20px;font-weight: bold;">
					<td class="text-right" colspan="2">Total :</td>
					<td class="text-right">'.number_format($maxmonthfee, 2).'</td>
					<td class="text-right">'.number_format($maxadmfee, 2).'</td>
					<td class="text-right">'.number_format($maxbook, 2).'</td>
					<td class="text-right">'.number_format($maxtransport, 2).'</td>
					<td class="text-right">'.number_format($maxdonation, 2).'</td>
					<td class="text-right">'.number_format($maxdayboarding, 2).'</td>
					<td class="text-right">'.number_format($maxneat, 2).'</td>
					<td class="text-right">'.number_format($maxother, 2).'</td>
					<td class="text-right">'.number_format($maxcashback, 2).'</td>
					<td class="text-right">'.number_format($maxdsal, 2).'</td>
					<td class="text-right">'.number_format($maxssal, 2).'</td>
					<td class="text-right">'.number_format($maxvfuel, 2).'</td>
					<td class="text-right">'.number_format($maxvmaint, 2).'</td>
					<td class="text-right">'.number_format($maxelectricity, 2).'</td>
					<td class="text-right">'.number_format($maxcanteen, 2).'</td>
					<td class="text-right">'.number_format($maxexpense, 2).'</td>
					<td class="text-right">'.number_format($maxtotal, 2).'</td>
					<td class="text-right"></td>
				</tr>
			';
		}else{
			$odin.='
				<tr>
					<td colspan="17" class="text-center">No Records Found!!!</td>
				</tr>
			';
		}
		return $odin;
	}

	// save school income
	public function stc_save_school_income($schoolname, $income){
		$odin='';
		$date=date("Y-m-d");
		$year=date("Y");
		$month=date("m", strtotime($date));
		$odincheckqry=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_school_month_closing` 
			WHERE MONTH(`stc_school_month_closing_date`)='".$month."'
			AND YEAR(`stc_school_month_closing_date`)='".$year."' 
			AND `stc_school_fee_which_school`='".mysqli_real_escape_string($this->stc_dbs, $schoolname)."' 
			ORDER BY MONTH(`stc_school_month_closing_date`) DESC
		");
		if(mysqli_num_rows($odincheckqry)>0){
			$odin='Already added!!!';
		}else{
			$odinsetqry=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_school_month_closing`(
				    `stc_school_month_closing_date`,
				    `stc_school_fee_which_school`,
				    `stc_school_month_closing_value`,
				    `stc_school_month_closing_created_by`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $date)."',
					'".mysqli_real_escape_string($this->stc_dbs, $schoolname)."',
					'".mysqli_real_escape_string($this->stc_dbs, $income)."',
					'".$_SESSION['stc_school_user_id']."'
				)
			");
				if($odinsetqry){
					$odin='Income is saved.';
				}else{
					$odin='Something went wrong!!! Income is not saved.';
				}
		}
		return $odin;
	}

	// call income
	public function stc_call_school_income(){
		$odin='
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th class="text-center"><b>School</b></th>
						<th class="text-center"><b>Date</b></th>
						<th class="text-center"><b>Last Month Income</b></th>
					</tr>
				</thead>
				<tbody>
		';
		$odinqry=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_school_month_closing` WHERE `stc_school_month_closing_created_by`='".$_SESSION['stc_school_user_id']."' 
			ORDER BY TIMESTAMP(`stc_school_month_closing_date`) DESC
		");
		foreach($odinqry as $odinrow){
			$odin.='
					<tr>
						<td class="text-center">'.$odinrow['stc_school_fee_which_school'].'</td>
						<td class="text-center">'.date("d-m-Y", strtotime($odinrow['stc_school_month_closing_date'])).'</td>
						<td class="text-right">'.number_format($odinrow['stc_school_month_closing_value'], 2).'</td>
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
#<-----------------------------School School Obj Section----------------------------------->
#<------------------------------------------------------------------------------------------>
// save fee
if(isset($_POST['save_school_fee_action'])){
	$out='';
	$stcwhichschool 			= $_POST['stcwhichschool'];
	$stcschoolmonthlyfee 		= $_POST['stcschoolmonthlyfee'];
	$stcschooladmissionfee 		= $_POST['stcschooladmissionfee'];
	$stcschoolbooks 			= $_POST['stcschoolbooks'];
	$stcschooltransporation 	= $_POST['stcschooltransporation'];
	$stcschooldonation 			= $_POST['stcschooldonation'];
	$stcschooldayboarding 		= $_POST['stcschooldayboarding'];
	$stcschoolneatcoll 			= $_POST['stcschoolneatcoll'];
	$stcschooldssalary 			= $_POST['stcschooldssalary'];
	$stcschooltsalary 			= $_POST['stcschooltsalary'];
	$stcschoolvfuel 			= $_POST['stcschoolvfuel'];
	$stcschoolvmaint 			= $_POST['stcschoolvmaint'];
	$stcschoolelectricity 		= $_POST['stcschoolelectricity'];
	$stcschoolcanteen 			= $_POST['stcschoolcanteen'];
	$stcschoolothercharges 		= $_POST['stcschoolothercharges'];
	$stcschoolcashback 			= $_POST['stcschoolcashback'];
	$stcschoolexpenses 			= $_POST['stcschoolexpenses'];
	$stcschoolremarks 			= $_POST['stcschoolremarks'];

	$valkyrie=new Yggdrasil();
	if(empty($_SESSION['stc_school_user_id'])){
		header("Location:../index.html");
	}else{
		$lokiheck=$valkyrie->stc_save_school_fee($stcwhichschool, $stcschoolmonthlyfee, $stcschooladmissionfee, $stcschoolbooks, $stcschooltransporation, $stcschooldonation, $stcschooldayboarding, $stcschoolneatcoll, $stcschooldssalary, $stcschooltsalary, $stcschoolvfuel, $stcschoolvmaint, $stcschoolelectricity, $stcschoolcanteen, $stcschoolothercharges, $stcschoolcashback, $stcschoolexpenses, $stcschoolremarks);
		$out=$lokiheck;
	}
	echo $out;
}

// call request
if(isset($_POST['stc_call_fee'])){
	$valkyrie=new Yggdrasil();
	$lokiheck=$valkyrie->stc_call_fee();
	echo $lokiheck;
}

// search request
if(isset($_POST['stc_search_school_fee'])){
	$search=date('m', strtotime($_POST['search']));
	$valkyrie=new Yggdrasil();
	$lokiheck=$valkyrie->stc_search_school_fee($search);
	echo $lokiheck;
}

// save monthly income
if(isset($_POST['stc_save_school_income'])){
	$schoolname=$_POST['schoolname'];
	$income=$_POST['income'];
	$valkyrie=new Yggdrasil();
	$lokiheck=$valkyrie->stc_save_school_income($schoolname, $income);
	echo $lokiheck;
}

// call income
if(isset($_POST['stc_call_monthly_closer'])){
	$valkyrie=new Yggdrasil();
	$lokiheck=$valkyrie->stc_call_school_income();
	echo $lokiheck;
}
?>