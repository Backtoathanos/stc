<?php
include "../../MCU/obdb.php";
session_start();
// reports merchant ledger
class ragnarSummaryTBM extends tesseract{
   // safety
	public function stc_call_tbm($location, $month, $supervise_name){
		$optimusprime='';
		$month_arr = explode('-', date('m-Y', strtotime($month)));
		$month = $month_arr[0];
		$year = $month_arr[1];
		$location=" `stc_safetytbm_loc`='".mysqli_real_escape_string($this->stc_dbs, $location)."' AND";
		if($location=='NA'){
			$location='';
		}
		$supervise_rec="`stc_cust_pro_supervisor_fullname` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $supervise_name)."' AND";
		if($supervise_name==''){
			$supervise_rec='';
		}
		$query="
			SELECT * FROM `stc_safetytbm` 
			LEFT JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_pro_supervisor_id`=`stc_safetytbm_created_by`
			WHERE ".$supervise_rec.$location." (
				MONTH(`stc_safetytbm_date`) = '".mysqli_real_escape_string($this->stc_dbs, $month)."' AND
				YEAR(`stc_safetytbm_date`) = '".mysqli_real_escape_string($this->stc_dbs, $year)."'
			)
			ORDER BY DATE(`stc_safetytbm_date`) DESC
		";
		$optimusprimequery=mysqli_query($this->stc_dbs, $query);
		if(mysqli_num_rows($optimusprimequery)>0){
			$website=$_SERVER['SERVER_NAME'];
			$website = $website=="localhost" ? '../stc_agent47/' : 'https://stcassociate.com/stc_agent47/';
			foreach($optimusprimequery as $optimusprimerow){
				$optimusprimeimgqry=mysqli_query($this->stc_dbs, "
					SELECT `stc_safetytbm_img_location` FROM `stc_safetytbm_img` WHERE `stc_safetytbm_img_tbmid`='".$optimusprimerow['stc_safetytbm_id']."'
				");
				$img_path='';
				foreach($optimusprimeimgqry as $optimusprimeimgrow){
					$img_path=$optimusprimeimgrow['stc_safetytbm_img_location'];
				}

				
			    $safety_image='
			    		<a href="javascript:void(0)" class="btn btn-info stc-safety-tbm-image-show-btn" data-src="https://stcassociate.com/stc_sub_agent47/safety_img/'.$img_path.'">View</a>
			    ';
				if(trim($img_path)==""){
					$safety_image="";
				}

				$action_show='
			    	<a target="_blank" href="'.$website.'safety-tbm-print-preview.php?tbm_no='.$optimusprimerow['stc_safetytbm_id'].'" class="form-control btn btn-danger">Print</a>
			    ';

				$optimusprime.='
					<tr>
						<td>'.date('d-m-Y', strtotime($optimusprimerow['stc_safetytbm_date'])).'</td>
						<td>'.date('h:i A', strtotime($optimusprimerow['stc_safetytbm_time'])).'</td>
						<td>'.$optimusprimerow['stc_safetytbm_place'].'</td>
						<td>'.$safety_image.'</td>
						<td>'.$optimusprimerow['stc_cust_pro_supervisor_fullname'].'</td>
						<td>'.$action_show.'
						</td>
					</tr>
				';
			}
		}else{
			$optimusprime.='
				<tr>
					<td colspan="5">No data found</td>
				</tr>
			';
		}
		return $optimusprime;
	}
}
#<--------------------------------------------------------------------------------------------------------->
#<--------------------------------------Object sections of reports class----------------------------------->
#<--------------------------------------------------------------------------------------------------------->
// call tbm  safety
if(isset($_POST['stc_safety_calltbm'])){
	$location 			= 	$_POST['location'];
	$month 			= 	$_POST['month'];
	$supervise_name	= 	$_POST['supervise_name'];
	$objsearchreq=new ragnarSummaryTBM();
	$opobjsearchreq=$objsearchreq->stc_call_tbm($location, $month, $supervise_name);
	echo $opobjsearchreq;
}

?>