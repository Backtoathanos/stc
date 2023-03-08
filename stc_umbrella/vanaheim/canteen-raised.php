<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*---------------------------------------For School Canteen --------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class Yggdrasil extends tesseract{
	// stc call request
	public function stc_save_school_canteen($stcschoolcanteenserdate, $stcschoolcanteensertype, $stcschoolcanteensertime, $stcschoolcanteenserqty, $stcschoolcanteenserremarks){
		$odin='';
		$set_loki=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_school_canteen`(
			    `stc_school_canteen_date`,
			    `stc_school_canteen_serve_type`,
			    `stc_school_canteen_serve_time`,
			    `stc_school_canteen_serve_quantity`,
			    `stc_school_canteen_remarks`,
			    `stc_school_canteen_created_by`
			)VALUES(
				'".mysqli_real_escape_string($this->stc_dbs, $stcschoolcanteenserdate)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschoolcanteensertype)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschoolcanteensertime)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschoolcanteenserqty)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcschoolcanteenserremarks)."',
				'".$_SESSION['stc_school_user_id']."'
			)
		");
		if($set_loki){
			$odin = "Record saved successfully.";
		}else{
			$odin = "Something went wrong on your record save.";
		}
		return $odin;
	}

	// call request
	public function stc_call_canteen(){
		$odin='
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th class="text-center">Date</th>
						<th class="text-center">Student</th>
						<th class="text-center">Teacher</th>
						<th class="text-center">Staff</th>
						<th class="text-center">Guest</th>
						<th class="text-center">Total</th>
					</tr>
				</thead>
				<tbody>
		';
		$odin_get_req_qry=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
			    `stc_school_canteen_date`
			FROM
			    `stc_school_canteen`
			ORDER BY DATE(`stc_school_canteen_date`) DESC
			LIMIT 0,20
		");
		if(mysqli_num_rows($odin_get_req_qry)>0){
			$maxtotal=0;
			$maxstudent=0;
			$maxteacher=0;
			$maxstaff=0;
			$maxguest=0;
			foreach($odin_get_req_qry as $req_row){
				$student=0;
				$teacher=0;
				$staff=0;
				$guest=0;
				$total=0;

				$odin_getstudentqry=mysqli_query($this->stc_dbs, "
					SELECT
					    `stc_school_canteen_serve_quantity`
					FROM
					    `stc_school_canteen`
					WHERE 
						DATE(`stc_school_canteen_date`)='".mysqli_real_escape_string($this->stc_dbs, $req_row['stc_school_canteen_date'])."'
					AND 
						`stc_school_canteen_serve_type`='student'
					ORDER BY DATE(`stc_school_canteen_date`) DESC
				");

				foreach($odin_getstudentqry as $odin_getstudentrow){
					$student+=$odin_getstudentrow['stc_school_canteen_serve_quantity'];
				}

				$odin_getteacherqry=mysqli_query($this->stc_dbs, "
					SELECT
					    `stc_school_canteen_serve_quantity`
					FROM
					    `stc_school_canteen`
					WHERE 
						DATE(`stc_school_canteen_date`)='".mysqli_real_escape_string($this->stc_dbs, $req_row['stc_school_canteen_date'])."'
					AND 
						`stc_school_canteen_serve_type`='teacher'
					ORDER BY DATE(`stc_school_canteen_date`) DESC
				");

				foreach($odin_getteacherqry as $odin_getteacherrow){
					$teacher+=$odin_getteacherrow['stc_school_canteen_serve_quantity'];
				}

				$odin_getstaffqry=mysqli_query($this->stc_dbs, "
					SELECT
					    `stc_school_canteen_serve_quantity`
					FROM
					    `stc_school_canteen`
					WHERE 
						DATE(`stc_school_canteen_date`)='".mysqli_real_escape_string($this->stc_dbs, $req_row['stc_school_canteen_date'])."'
					AND 
						`stc_school_canteen_serve_type`='staff'
					ORDER BY DATE(`stc_school_canteen_date`) DESC
				");

				foreach($odin_getstaffqry as $odin_getstaffrow){
					$staff+=$odin_getstaffrow['stc_school_canteen_serve_quantity'];
				}

				$odin_getguestqry=mysqli_query($this->stc_dbs, "
					SELECT
					    `stc_school_canteen_serve_quantity`
					FROM
					    `stc_school_canteen`
					WHERE 
						DATE(`stc_school_canteen_date`)='".mysqli_real_escape_string($this->stc_dbs, $req_row['stc_school_canteen_date'])."'
					AND 
						`stc_school_canteen_serve_type`='guest'
					ORDER BY DATE(`stc_school_canteen_date`) DESC
				");

				foreach($odin_getguestqry as $odin_getguestrow){
					$guest+=$odin_getguestrow['stc_school_canteen_serve_quantity'];
				}

				$total= $student + $teacher + $staff + $guest;
				$maxtotal+=$total;
				$maxstudent+=$student;
				$maxteacher+=$teacher;
				$maxstaff+=$staff;
				$maxguest+=$guest;
				$odin.='
					<tr>
						<td class="text-center">'.date('d-m-Y', strtotime($req_row['stc_school_canteen_date'])).'</td>
						<td class="text-right">'.number_format($student, 2).'</td>
						<td class="text-right">'.number_format($teacher, 2).'</td>
						<td class="text-right">'.number_format($staff, 2).'</td>
						<td class="text-right">'.number_format($guest, 2).'</td>
						<td class="text-right"><a href="#" class="stc-school-showdeep-req" id="'.$req_row['stc_school_canteen_date'].'">'.number_format($total, 2).'</a></td>
					</tr>
				';
			}
			$odin.='
				<tr style="font-size: 20px;font-weight: bold;">
					<td class="text-center">Total</td>
					<td class="text-right">'.number_format($maxstudent, 2).' NOS</td>
					<td class="text-right">'.number_format($maxteacher, 2).' NOS</td>
					<td class="text-right">'.number_format($maxstaff, 2).' NOS</td>
					<td class="text-right">'.number_format($maxguest, 2).' NOS</td>
					<td class="text-right">'.number_format($maxtotal, 2).' NOS</td>
				</tr>
			';
		}else{
			$odin.='
				<tr>
					<td colspan="6" class="text-center">No Records Found!!!</td>
				</tr>
			';
		}
		return $odin;
	}

	// search request
	public function stc_search_canteen($search){
		$odin='
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th class="text-center">Date</th>
						<th class="text-center">Student</th>
						<th class="text-center">Teacher</th>
						<th class="text-center">Staff</th>
						<th class="text-center">Guest</th>
						<th class="text-center">Total</th>
					</tr>
				</thead>
				<tbody>
		';
		$odin_get_req_qry=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
			    `stc_school_canteen_date`
			FROM
			    `stc_school_canteen`
			WHERE 
				MONTH(`stc_school_canteen_date`)='".mysqli_real_escape_string($this->stc_dbs, $search)."'
			ORDER BY DATE(`stc_school_canteen_date`) DESC
		");
		if(mysqli_num_rows($odin_get_req_qry)>0){
			$maxtotal=0;
			$maxstudent=0;
			$maxteacher=0;
			$maxstaff=0;
			$maxguest=0;
			foreach($odin_get_req_qry as $req_row){
				$student=0;
				$teacher=0;
				$staff=0;
				$guest=0;
				$total=0;

				$odin_getstudentqry=mysqli_query($this->stc_dbs, "
					SELECT
					    `stc_school_canteen_serve_quantity`
					FROM
					    `stc_school_canteen`
					WHERE 
						DATE(`stc_school_canteen_date`)='".mysqli_real_escape_string($this->stc_dbs, $req_row['stc_school_canteen_date'])."'
					AND 
						`stc_school_canteen_serve_type`='student'
					ORDER BY DATE(`stc_school_canteen_date`) DESC
				");

				foreach($odin_getstudentqry as $odin_getstudentrow){
					$student+=$odin_getstudentrow['stc_school_canteen_serve_quantity'];
				}

				$odin_getteacherqry=mysqli_query($this->stc_dbs, "
					SELECT
					    `stc_school_canteen_serve_quantity`
					FROM
					    `stc_school_canteen`
					WHERE 
						DATE(`stc_school_canteen_date`)='".mysqli_real_escape_string($this->stc_dbs, $req_row['stc_school_canteen_date'])."'
					AND 
						`stc_school_canteen_serve_type`='teacher'
					ORDER BY DATE(`stc_school_canteen_date`) DESC
				");

				foreach($odin_getteacherqry as $odin_getteacherrow){
					$teacher+=$odin_getteacherrow['stc_school_canteen_serve_quantity'];
				}

				$odin_getstaffqry=mysqli_query($this->stc_dbs, "
					SELECT
					    `stc_school_canteen_serve_quantity`
					FROM
					    `stc_school_canteen`
					WHERE 
						DATE(`stc_school_canteen_date`)='".mysqli_real_escape_string($this->stc_dbs, $req_row['stc_school_canteen_date'])."'
					AND 
						`stc_school_canteen_serve_type`='staff'
					ORDER BY DATE(`stc_school_canteen_date`) DESC
				");

				foreach($odin_getstaffqry as $odin_getstaffrow){
					$staff+=$odin_getstaffrow['stc_school_canteen_serve_quantity'];
				}

				$odin_getguestqry=mysqli_query($this->stc_dbs, "
					SELECT
					    `stc_school_canteen_serve_quantity`
					FROM
					    `stc_school_canteen`
					WHERE 
						DATE(`stc_school_canteen_date`)='".mysqli_real_escape_string($this->stc_dbs, $req_row['stc_school_canteen_date'])."'
					AND 
						`stc_school_canteen_serve_type`='guest'
					ORDER BY DATE(`stc_school_canteen_date`) DESC
				");

				foreach($odin_getguestqry as $odin_getguestrow){
					$guest+=$odin_getguestrow['stc_school_canteen_serve_quantity'];
				}

				$total= $student + $teacher + $staff + $guest;
				$maxtotal+=$total;
				$maxstudent+=$student;
				$maxteacher+=$teacher;
				$maxstaff+=$staff;
				$maxguest+=$guest;
				$odin.='
					<tr>
						<td class="text-center">'.date('d-m-Y', strtotime($req_row['stc_school_canteen_date'])).'</td>
						<td class="text-right">'.number_format($student, 2).'</td>
						<td class="text-right">'.number_format($teacher, 2).'</td>
						<td class="text-right">'.number_format($staff, 2).'</td>
						<td class="text-right">'.number_format($guest, 2).'</td>
						<td class="text-right"><a href="#" class="stc-school-showdeep-req" id="'.$req_row['stc_school_canteen_date'].'">'.number_format($total, 2).'</a></td>
					</tr>
				';
			}
			$odin.='
				<tr style="font-size: 20px;font-weight: bold;">
					<td class="text-center">Total</td>
					<td class="text-right">'.number_format($maxstudent, 2).' NOS</td>
					<td class="text-right">'.number_format($maxteacher, 2).' NOS</td>
					<td class="text-right">'.number_format($maxstaff, 2).' NOS</td>
					<td class="text-right">'.number_format($maxguest, 2).' NOS</td>
					<td class="text-right">'.number_format($maxtotal, 2).' NOS</td>
				</tr>
			';
		}else{
			$odin.='
				<tr>
					<td colspan="6" class="text-center">No Records Found!!!</td>
				</tr>
			';
		}
		return $odin;
	}

	// search by date
	public function stc_search_by_date($search){
		$odin='';
		$stbf=0;
		$stlunch=0;
		$stes=0;
		$stdinner=0;

		$tebf=0;
		$telunch=0;
		$tees=0;
		$tedinner=0;

		$sfbf=0;
		$sflunch=0;
		$sfes=0;
		$sfdinner=0;

		$gsbf=0;
		$gslunch=0;
		$gses=0;
		$gsdinner=0;

		$odin_getqry=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_school_canteen_serve_type`,
			    `stc_school_canteen_serve_time`,
			    `stc_school_canteen_serve_quantity`
			FROM
			    `stc_school_canteen`
			WHERE
			    DATE(`stc_school_canteen_date`)='".mysqli_real_escape_string($this->stc_dbs, $search)."'
		");
		foreach($odin_getqry as $odin_getrow){
			if($odin_getrow['stc_school_canteen_serve_type']=="student" && $odin_getrow['stc_school_canteen_serve_time']=="breakfast"){
				$stbf+=$odin_getrow['stc_school_canteen_serve_quantity'];
			}elseif($odin_getrow['stc_school_canteen_serve_type']=="student" && $odin_getrow['stc_school_canteen_serve_time']=="lunch"){
				$stlunch+=$odin_getrow['stc_school_canteen_serve_quantity'];
			}elseif($odin_getrow['stc_school_canteen_serve_type']=="student" && $odin_getrow['stc_school_canteen_serve_time']=="evening snacks"){
				$stes+=$odin_getrow['stc_school_canteen_serve_quantity'];
			}elseif($odin_getrow['stc_school_canteen_serve_type']=="student" && $odin_getrow['stc_school_canteen_serve_time']=="dinner"){
				$stdinner+=$odin_getrow['stc_school_canteen_serve_quantity'];
			}elseif($odin_getrow['stc_school_canteen_serve_type']=="teacher" && $odin_getrow['stc_school_canteen_serve_time']=="breakfast"){
				$tebf+=$odin_getrow['stc_school_canteen_serve_quantity'];
			}elseif($odin_getrow['stc_school_canteen_serve_type']=="teacher" && $odin_getrow['stc_school_canteen_serve_time']=="lunch"){
				$telunch+=$odin_getrow['stc_school_canteen_serve_quantity'];
			}elseif($odin_getrow['stc_school_canteen_serve_type']=="teacher" && $odin_getrow['stc_school_canteen_serve_time']=="evening snacks"){
				$tees+=$odin_getrow['stc_school_canteen_serve_quantity'];
			}elseif($odin_getrow['stc_school_canteen_serve_type']=="teacher" && $odin_getrow['stc_school_canteen_serve_time']=="dinner"){
				$tedinner+=$odin_getrow['stc_school_canteen_serve_quantity'];
			}elseif($odin_getrow['stc_school_canteen_serve_type']=="staff" && $odin_getrow['stc_school_canteen_serve_time']=="breakfast"){
				$sfbf+=$odin_getrow['stc_school_canteen_serve_quantity'];
			}elseif($odin_getrow['stc_school_canteen_serve_type']=="staff" && $odin_getrow['stc_school_canteen_serve_time']=="lunch"){
				$sflunch+=$odin_getrow['stc_school_canteen_serve_quantity'];
			}elseif($odin_getrow['stc_school_canteen_serve_type']=="staff" && $odin_getrow['stc_school_canteen_serve_time']=="evening snacks"){
				$sfes+=$odin_getrow['stc_school_canteen_serve_quantity'];
			}elseif($odin_getrow['stc_school_canteen_serve_type']=="staff" && $odin_getrow['stc_school_canteen_serve_time']=="dinner"){
				$sfdinner+=$odin_getrow['stc_school_canteen_serve_quantity'];
			}elseif($odin_getrow['stc_school_canteen_serve_type']=="guest" && $odin_getrow['stc_school_canteen_serve_time']=="breakfast"){
				$gsbf+=$odin_getrow['stc_school_canteen_serve_quantity'];
			}elseif($odin_getrow['stc_school_canteen_serve_type']=="guest" && $odin_getrow['stc_school_canteen_serve_time']=="lunch"){
				$gslunch+=$odin_getrow['stc_school_canteen_serve_quantity'];
			}elseif($odin_getrow['stc_school_canteen_serve_type']=="guest" && $odin_getrow['stc_school_canteen_serve_time']=="evening snacks"){
				$gses+=$odin_getrow['stc_school_canteen_serve_quantity'];
			}elseif($odin_getrow['stc_school_canteen_serve_type']=="guest" && $odin_getrow['stc_school_canteen_serve_time']=="dinner"){
				$gsdinner+=$odin_getrow['stc_school_canteen_serve_quantity'];
			}
		}
		$odin='
				<tr>
                    <td class="text-center">Breakfast</td>
                    <td class="text-right">'.number_format($stbf, 2).'</td>
                    <td class="text-right">'.number_format($tebf, 2).'</td>
                    <td class="text-right">'.number_format($sfbf, 2).'</td>
                    <td class="text-right">'.number_format($gsbf, 2).'</td>
                </tr>
                <tr>
                    <td class="text-center">Lunch</td>
                    <td class="text-right">'.number_format($stlunch, 2).'</td>
                    <td class="text-right">'.number_format($telunch, 2).'</td>
                    <td class="text-right">'.number_format($sflunch, 2).'</td>
                    <td class="text-right">'.number_format($gslunch, 2).'</td>
                </tr>
                <tr>
                    <td class="text-center">Evening Snacks</td>
                    <td class="text-right">'.number_format($stes, 2).'</td>
                    <td class="text-right">'.number_format($tees, 2).'</td>
                    <td class="text-right">'.number_format($sfes, 2).'</td>
                    <td class="text-right">'.number_format($gses, 2).'</td>
                </tr>
                <tr>
                    <td class="text-center">Dinner</td>
                    <td class="text-right">'.number_format($stdinner, 2).'</td>
                    <td class="text-right">'.number_format($tedinner, 2).'</td>
                    <td class="text-right">'.number_format($sfdinner, 2).'</td>
                    <td class="text-right">'.number_format($gsdinner, 2).'</td>
                </tr>
                <tr>
                    <td class="text-center">Total</td>
                    <td class="text-right">'.number_format(($stbf + $stlunch + $stes + $stdinner), 2).' NOS</td>
                    <td class="text-right">'.number_format(($tebf + $telunch + $tees + $tedinner), 2).' NOS</td>
                    <td class="text-right">'.number_format(($sfbf + $sflunch + $sfes + $sfdinner), 2).' NOS</td>
                    <td class="text-right">'.number_format(($gsbf + $gslunch + $gses + $gsdinner), 2).' NOS</td>
                </tr>
		';
		return $odin;
	}
}

#<------------------------------------------------------------------------------------------>
#<-----------------------------School Canteen Obj Section----------------------------------->
#<------------------------------------------------------------------------------------------>
// save request order
if(isset($_POST['save_canteen_action'])){
	$out='';
	$stcschoolcanteenserdate=$_POST['stcschoolcanteenserdate'];
	$stcschoolcanteensertype=$_POST['stcschoolcanteensertype'];
	$stcschoolcanteensertime=$_POST['stcschoolcanteensertime'];
	$stcschoolcanteenserqty=$_POST['stcschoolcanteenserqty'];
	$stcschoolcanteenserremarks=$_POST['stcschoolcanteenserremarks'];

	$valkyrie=new Yggdrasil();
	if(empty($stcschoolcanteenserqty)){
		$out='Please Enter Serve Quantity.';
	}elseif(empty($_SESSION['stc_school_user_id'])){
		header("Location:../index.html");
	}else{
		$lokiheck=$valkyrie->stc_save_school_canteen($stcschoolcanteenserdate, $stcschoolcanteensertype, $stcschoolcanteensertime, $stcschoolcanteenserqty, $stcschoolcanteenserremarks);
		$out=$lokiheck;
	}
	echo $out;
}

// call request
if(isset($_POST['stc_call_canteen'])){
	$valkyrie=new Yggdrasil();
	$lokiheck=$valkyrie->stc_call_canteen();
	echo $lokiheck;
}

// search request
if(isset($_POST['stc_search_canteen'])){
	$search=date('m', strtotime($_POST['search']));
	$valkyrie=new Yggdrasil();
	$lokiheck=$valkyrie->stc_search_canteen($search);
	echo $lokiheck;
}

// call by date
if(isset($_POST['stc_call_by_date'])){
	$search=$_POST['req_date'];
	$valkyrie=new Yggdrasil();
	$lokiheck=$valkyrie->stc_search_by_date($search);
	echo $lokiheck;
}
?>