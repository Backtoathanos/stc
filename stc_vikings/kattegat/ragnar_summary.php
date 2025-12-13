<?php
include "../../MCU/obdb.php";
session_start();
// reports merchant ledger
class ragnarSummary extends tesseract{
   // safety
	public function stc_call_tbm($location, $month, $supervise_name){
		$optimusprime='';
		$month_arr = explode('-', date('m-Y', strtotime($month)));
		$month = $month_arr[0];
		$year = $month_arr[1];
		$location_filter=" `stc_safetytbm_loc`='".mysqli_real_escape_string($this->stc_dbs, $location)."' AND";
		if($location=='NA'){
			$location_filter='';
		}
		$supervise_rec="`stc_cust_pro_supervisor_fullname` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $supervise_name)."' AND";
		if($supervise_name==''){
			$supervise_rec='';
		}
		
		// Get all departments for the location
		$dept_boxes = '';
		$dept_list = array();
		
		// First try to get departments from department table
		$location_for_dept = $location == 'NA' ? '' : $location;
		$dept_where = $location_for_dept != '' ? "WHERE `stc_status_down_list_department_location`='".mysqli_real_escape_string($this->stc_dbs, $location_for_dept)."' AND `stc_status_down_list_department_dept`<>''" : "WHERE `stc_status_down_list_department_dept`<>''";
		$dept_query = "SELECT DISTINCT `stc_status_down_list_department_dept` 
			FROM `stc_status_down_list_department` 
			".$dept_where."
			ORDER BY `stc_status_down_list_department_dept` ASC";
		$dept_result = mysqli_query($this->stc_dbs, $dept_query);
		
		if($dept_result && mysqli_num_rows($dept_result) > 0){
			foreach($dept_result as $dept_row){
				$dept_list[] = $dept_row['stc_status_down_list_department_dept'];
			}
		}
		
		// If no departments found, try to extract from TBM place field
		if(empty($dept_list)){
			$temp_where_conditions = array();
			if($supervise_rec != ''){
				$temp_where_conditions[] = rtrim($supervise_rec, ' AND');
			}
			if($location_filter != ''){
				$temp_where_conditions[] = rtrim($location_filter, ' AND');
			}
			$temp_where_conditions[] = "MONTH(`stc_safetytbm_date`) = '".mysqli_real_escape_string($this->stc_dbs, $month)."'";
			$temp_where_conditions[] = "YEAR(`stc_safetytbm_date`) = '".mysqli_real_escape_string($this->stc_dbs, $year)."'";
			$temp_where_clause = "WHERE " . implode(" AND ", $temp_where_conditions);
			
			$temp_tbm_query = "SELECT DISTINCT `stc_safetytbm_place` FROM `stc_safetytbm` ".$temp_where_clause;
			$temp_tbm_result = mysqli_query($this->stc_dbs, $temp_tbm_query);
			if($temp_tbm_result && mysqli_num_rows($temp_tbm_result) > 0){
				foreach($temp_tbm_result as $temp_row){
					$place = trim($temp_row['stc_safetytbm_place']);
					if($place != ''){
						// Try to extract department code (e.g., "LD#1", "SP#1", "MK#7")
						if(preg_match('/([A-Z]+)#?\d*/', $place, $matches)){
							$dept_code = $matches[1];
							if(!in_array($dept_code, $dept_list)){
								$dept_list[] = $dept_code;
							}
						}
					}
				}
			}
		}
		
		// Build WHERE clause properly
		$where_conditions = array();
		if($supervise_rec != ''){
			$where_conditions[] = rtrim($supervise_rec, ' AND');
		}
		if($location_filter != ''){
			$where_conditions[] = rtrim($location_filter, ' AND');
		}
		$where_conditions[] = "MONTH(`stc_safetytbm_date`) = '".mysqli_real_escape_string($this->stc_dbs, $month)."'";
		$where_conditions[] = "YEAR(`stc_safetytbm_date`) = '".mysqli_real_escape_string($this->stc_dbs, $year)."'";
		
		$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";
		
		$query="
			SELECT *, `stc_status_down_list_department_dept` as dept_name, DATE(`stc_safetytbm_date`) as tbm_date FROM `stc_safetytbm` 
			LEFT JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_pro_supervisor_id`=`stc_safetytbm_created_by`
			LEFT JOIN `stc_status_down_list`
			ON `stc_safetytbm_std_id`=`stc_status_down_list_id`
			LEFT JOIN `stc_status_down_list_department`
			ON `stc_status_down_list_department_loc_id`=`stc_status_down_list_location`
			".$where_clause."
			ORDER BY DATE(`stc_safetytbm_date`) DESC
		";
		$optimusprimequery=mysqli_query($this->stc_dbs, $query);
		
		// Check for query errors
		if(!$optimusprimequery){
			$optimusprime .= '<tr><td colspan="6">Query Error: '.mysqli_error($this->stc_dbs).'</td></tr>';
			return $optimusprime;
		}
		
		if(mysqli_num_rows($optimusprimequery)>0){
			$website=$_SERVER['SERVER_NAME'];
			$website = $website=="localhost" ? '../stc_agent47/' : 'https://stcassociate.com/stc_agent47/';
			
			// Group records by date
			$records_by_date = array();
			foreach($optimusprimequery as $optimusprimerow){
				$tbm_date = date('Y-m-d', strtotime($optimusprimerow['stc_safetytbm_date']));
				if(!isset($records_by_date[$tbm_date])){
					$records_by_date[$tbm_date] = array();
				}
				$records_by_date[$tbm_date][] = $optimusprimerow;
			}
			
			// Process each date
			foreach($records_by_date as $tbm_date => $date_records){
				// Generate department boxes for this specific date
				$dept_boxes = '';
				if(!empty($dept_list)){
					$dept_boxes = '<div class="stc-dept-box-container" style="position: relative; display: flex; flex-wrap: wrap; gap: 6px; padding: 8px;">';
					$dept_boxes .= '<span class="stc-clear-filter" data-date="'.$tbm_date.'" title="Clear Filter">×</span>';
					foreach($dept_list as $dept_name){
						
						// Count TBMs for this department for this specific date
						$count_where_conditions = array();
						if($supervise_rec != ''){
							$count_where_conditions[] = rtrim($supervise_rec, ' AND');
						}
						if($location_filter != ''){
							$count_where_conditions[] = rtrim($location_filter, ' AND');
						}
						$count_where_conditions[] = "DATE(`stc_safetytbm_date`) = '".mysqli_real_escape_string($this->stc_dbs, $tbm_date)."'";
						$count_where_conditions[] = "(
							`stc_safetytbm_place` LIKE '%".mysqli_real_escape_string($this->stc_dbs, $dept_name)."%' OR
							`stc_status_down_list_department_dept`='".mysqli_real_escape_string($this->stc_dbs, $dept_name)."'
						)";
						
						$count_where_clause = "WHERE " . implode(" AND ", $count_where_conditions);
						
						$tbm_count_query = "
							SELECT COUNT(*) as tbm_count FROM `stc_safetytbm` 
							LEFT JOIN `stc_cust_pro_supervisor`
							ON `stc_cust_pro_supervisor_id`=`stc_safetytbm_created_by`
							LEFT JOIN `stc_status_down_list`
							ON `stc_safetytbm_std_id`=`stc_status_down_list_id`
							LEFT JOIN `stc_status_down_list_department`
							ON `stc_status_down_list_department_loc_id`=`stc_status_down_list_location`
							".$count_where_clause."
						";
						$tbm_count_result = mysqli_query($this->stc_dbs, $tbm_count_query);
						$tbm_count = 0;
						if($tbm_count_result){
							$count_row = mysqli_fetch_assoc($tbm_count_result);
							$tbm_count = $count_row['tbm_count'];
						} else {
							// Fallback: simple count without joins - only check place field
							$simple_where_conditions = array();
							if($supervise_rec != ''){
								$simple_where_conditions[] = rtrim($supervise_rec, ' AND');
							}
							if($location_filter != ''){
								$simple_where_conditions[] = rtrim($location_filter, ' AND');
							}
							$simple_where_conditions[] = "DATE(`stc_safetytbm_date`) = '".mysqli_real_escape_string($this->stc_dbs, $tbm_date)."'";
							$simple_where_conditions[] = "`stc_safetytbm_place` LIKE '%".mysqli_real_escape_string($this->stc_dbs, $dept_name)."%'";
							$simple_where_clause = "WHERE " . implode(" AND ", $simple_where_conditions);
							
							$simple_count_query = "SELECT COUNT(*) as tbm_count FROM `stc_safetytbm` ".$simple_where_clause;
							$simple_count_result = mysqli_query($this->stc_dbs, $simple_count_query);
							if($simple_count_result){
								$count_row = mysqli_fetch_assoc($simple_count_result);
								$tbm_count = $count_row['tbm_count'];
							}
						}
						
						// Determine color: green if count > 0, red if count = 0
						$box_color = $tbm_count > 0 ? '#28a745' : '#dc3545';
						$box_bg = $tbm_count > 0 ? '#d4edda' : '#f8d7da';
						
						$dept_boxes .= '
							<div class="stc-dept-box" 
								data-dept="'.htmlspecialchars($dept_name).'" 
								data-date="'.$tbm_date.'"
								style="cursor: pointer; padding: 6px 10px; border: 1px solid '.$box_color.'; background-color: '.$box_bg.'; border-radius: 3px; text-align: center; min-width: 70px; transition: all 0.3s;">
								<div style="font-weight: bold; color: '.$box_color.'; font-size: 11px; line-height: 1.2;">'.htmlspecialchars($dept_name).'</div>
								<div style="font-size: 10px; color: #333; margin-top: 2px; line-height: 1.2;">'.$tbm_count.'</div>
							</div>
						';
					}
					$dept_boxes .= '</div>';
					
					// Add date header and department boxes row
					$optimusprime .= '<tr><td colspan="6" style="background-color: #f0f0f0; font-weight: bold; padding: 10px;">Date: '.date('d-m-Y', strtotime($tbm_date)).'</td></tr>';
					$optimusprime .= '<tr><td colspan="6">'.$dept_boxes.'</td></tr>';
				}
				
				// Add rows for this date
				foreach($date_records as $optimusprimerow){
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
				
				// Get department name - try from join first, then check if place contains department name
				$dept_name_attr = '';
				if(isset($optimusprimerow['dept_name']) && $optimusprimerow['dept_name'] != ''){
					$dept_name_attr = htmlspecialchars($optimusprimerow['dept_name']);
				} else {
					// Try to match department from place field
					$place = $optimusprimerow['stc_safetytbm_place'];
					if($place != ''){
						$dept_match_query = "SELECT `stc_status_down_list_department_dept` 
							FROM `stc_status_down_list_department` 
							WHERE `stc_status_down_list_department_dept`<>'' 
							AND '".mysqli_real_escape_string($this->stc_dbs, $place)."' LIKE CONCAT('%', `stc_status_down_list_department_dept`, '%')
							LIMIT 1";
						$dept_match_result = mysqli_query($this->stc_dbs, $dept_match_query);
						if($dept_match_result && mysqli_num_rows($dept_match_result) > 0){
							$dept_match_row = mysqli_fetch_assoc($dept_match_result);
							$dept_name_attr = htmlspecialchars($dept_match_row['stc_status_down_list_department_dept']);
						}
					}
				}

					$row_date = date('Y-m-d', strtotime($optimusprimerow['stc_safetytbm_date']));
					$optimusprime.='
						<tr class="stc-tbm-row" data-dept="'.$dept_name_attr.'" data-date="'.$row_date.'">
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
			}
		}else{
			$optimusprime.='
				<tr>
					<td colspan="6">No data found</td>
				</tr>
			';
		}
		return $optimusprime;
	}

	// call mrd location
   public function stc_mrd_location_call($customer_id, $tojob){
      $odin='<option value="NA">Select</option>';
      $odin_get_locqry=mysqli_query($this->stc_dbs, "
         SELECT DISTINCT `stc_status_down_list_department_location` 
         FROM `stc_status_down_list_department` 
         INNER JOIN `stc_cust_project` 
         ON `stc_status_down_list_department_loc_id`=`stc_cust_project_id` 
         WHERE `stc_cust_project_cust_id`='".mysqli_real_escape_string($this->stc_dbs, $customer_id)."'
         AND `stc_status_down_list_department_location`<>''
         ORDER BY `stc_status_down_list_department_location` ASC
      ");
	  if($tojob==1){
		$odin_get_locqry=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT `stc_cust_project_id`, `stc_cust_project_title` 
			FROM `stc_cust_project` 
			WHERE `stc_cust_project_cust_id`='".mysqli_real_escape_string($this->stc_dbs, $customer_id)."'
			ORDER BY `stc_cust_project_title` ASC
		");
	  }
      if(mysqli_num_rows($odin_get_locqry)>0){
         foreach($odin_get_locqry as $odin_get_locrow){
			if($tojob==2){
				$odin.='
				<option>'.$odin_get_locrow['stc_status_down_list_department_location'].'</option>
				';
			}else{
				$odin.='
				<option value="'.$odin_get_locrow['stc_cust_project_id'].'">'.$odin_get_locrow['stc_cust_project_title'].'</option>
				';
			}
         }
      }else{
         $odin.='
            <option value="NA">Location not found.</option>
         ';
      }
      return $odin;
   }

   // call mrd
   public function stc_mrd_find($from, $to, $tojob, $customer, $location, $dept, $pro_id, $tomaterial, $page, $limit){  
      $offset = ($page - 1) * $limit;     
      $odin='';
      $filter_query='';
      if(empty($from)){
         $filter_query.='';
      }
      if($tojob=="2"){
         $filter_query.=" AND R.stc_cust_super_requisition_list_sdlid<>0";
      }
      if($customer!="NA"){
         $filter_query.=" AND stc_cust_project_cust_id='".mysqli_real_escape_string($this->stc_dbs, $customer)."'";
      }
      $sdl_joiner='';
      if(($tojob=="1" || $tojob=="NA") && $dept!="Select" && $dept!="NA" && $pro_id!="NA"){
         $filter_query.=" AND P.`stc_cust_project_id`='".mysqli_real_escape_string($this->stc_dbs, $pro_id)."'";
      }else if($tojob=="2" && $dept!="NA" && $pro_id!="NA"){
         if($location!="NA"){
            $sdl_joiner='            
               LEFT JOIN `stc_status_down_list`
               ON R.`stc_cust_super_requisition_list_sdlid`=`stc_status_down_list_id`
            ';
            $filter_query.=" AND stc_status_down_list_plocation='".mysqli_real_escape_string($this->stc_dbs, $location)."'";
         }
         $filter_query.=" AND stc_status_down_list_sub_location='".mysqli_real_escape_string($this->stc_dbs, $dept)."'";
      }
      if($tomaterial!="NA"){
         $filter_query.=" AND I.stc_cust_super_requisition_items_type='".mysqli_real_escape_string($this->stc_dbs, $tomaterial)."'";
      }
      $query= "
         SELECT 
            R.`stc_cust_super_requisition_list_id` as req_id,
            I.`stc_cust_super_requisition_list_id` as req_item_id,
            R.`stc_cust_super_requisition_list_date`,
            R.`stc_cust_super_requisition_list_sdlid`,
            I.`stc_cust_super_requisition_list_items_title`, 
            I.`stc_cust_super_requisition_list_items_unit`,
            I.`stc_cust_super_requisition_list_items_reqqty`,
            I.`stc_cust_super_requisition_list_items_approved_qty`,
            I.`stc_cust_super_requisition_items_finalqty`,
            I.`stc_cust_super_requisition_items_type`,
            I.`stc_cust_super_requisition_items_priority`,
            P.`stc_cust_project_cust_id`,
            P.`stc_cust_project_title`,
            S.`stc_cust_pro_supervisor_fullname`,
            S.`stc_cust_pro_supervisor_contact`  
         FROM `stc_cust_super_requisition_list_items` I
         LEFT JOIN `stc_cust_super_requisition_list` R
         ON R.`stc_cust_super_requisition_list_id`=I.`stc_cust_super_requisition_list_items_req_id`
         LEFT JOIN `stc_cust_project` P
         ON `stc_cust_project_id`=`stc_cust_super_requisition_list_project_id`
         LEFT JOIN `stc_cust_pro_supervisor` S
         ON S.`stc_cust_pro_supervisor_id`=R.`stc_cust_super_requisition_list_super_id`
         ".$sdl_joiner."
         WHERE DATE(`stc_cust_super_requisition_list_date`) BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $from)."'
         AND '".mysqli_real_escape_string($this->stc_dbs, $to)."' ".$filter_query."
         ORDER BY DATE(R.`stc_cust_super_requisition_list_date`) DESC, R.`stc_cust_super_requisition_list_sdlid` DESC         
      ";

	  if($tojob==1 && $pro_id=='NA'){
		$filter_query.=" AND stc_cust_project_id='".mysqli_real_escape_string($this->stc_dbs, $location)."'";
		$query= "
			SELECT 
				R.`stc_cust_super_requisition_list_id` as req_id,
				I.`stc_cust_super_requisition_list_id` as req_item_id,
				R.`stc_cust_super_requisition_list_date`,
				R.`stc_cust_super_requisition_list_sdlid`,
				I.`stc_cust_super_requisition_list_items_title`, 
				I.`stc_cust_super_requisition_list_items_unit`,
				I.`stc_cust_super_requisition_list_items_reqqty`,
				I.`stc_cust_super_requisition_list_items_approved_qty`,
				I.`stc_cust_super_requisition_items_finalqty`,
				I.`stc_cust_super_requisition_items_type`,
				I.`stc_cust_super_requisition_items_priority`,
				P.`stc_cust_project_cust_id`,
				P.`stc_cust_project_title`,
				S.`stc_cust_pro_supervisor_fullname`,
				S.`stc_cust_pro_supervisor_contact`  
			FROM `stc_cust_super_requisition_list_items` I
			LEFT JOIN `stc_cust_super_requisition_list` R
			ON R.`stc_cust_super_requisition_list_id`=I.`stc_cust_super_requisition_list_items_req_id`
			LEFT JOIN `stc_cust_project` P
			ON `stc_cust_project_id`=`stc_cust_super_requisition_list_project_id`
			LEFT JOIN `stc_cust_pro_supervisor` S
			ON S.`stc_cust_pro_supervisor_id`=R.`stc_cust_super_requisition_list_super_id`
			WHERE DATE(`stc_cust_super_requisition_list_date`) BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $from)."'
			AND '".mysqli_real_escape_string($this->stc_dbs, $to)."' ".$filter_query."
			ORDER BY DATE(R.`stc_cust_super_requisition_list_date`) DESC, R.`stc_cust_super_requisition_list_sdlid` DESC         
		";
	  }

      $total_result = mysqli_query($this->stc_dbs, $query);
      $total_record = mysqli_num_rows($total_result);

      $query.="LIMIT $limit OFFSET $offset";
      $odin_get_mrdqry=mysqli_query($this->stc_dbs, $query);
      if(mysqli_num_rows($odin_get_mrdqry)>0){
         $prev_pno='';
         $prev_check=0;
         foreach($odin_get_mrdqry as $odin_get_mrdrow){
            $pno='';
            if($odin_get_mrdrow['stc_cust_super_requisition_list_sdlid']>0){
               $pno=$odin_get_mrdrow['stc_cust_super_requisition_list_sdlid'];
               // $prev_pno=$odin_get_mrdrow['stc_cust_super_requisition_list_sdlid']!=$prev_pno ? $odin_get_mrdrow['stc_cust_super_requisition_list_sdlid'] : $prev_pno;
               if($prev_check==$pno && $pno!=0){
                  $prev_pno++;
               }else{
                  $prev_check=$pno;
                  $prev_pno=10;
               }
            }
            $prev_pno=$prev_pno==0 ? "" : $prev_pno;
            $stcdispatchedqty=0;
            $stcdecqtyqry=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_cust_super_requisition_list_items_rec_recqty`
					FROM `stc_cust_super_requisition_list_items_rec` 
					WHERE 
						(
                     `stc_cust_super_requisition_list_items_rec_list_id`='".$odin_get_mrdrow['req_id']."' 
					   AND `stc_cust_super_requisition_list_items_rec_list_item_id`='".$odin_get_mrdrow['req_item_id']."'  
               )
            ");
				foreach($stcdecqtyqry as $dispatchedrow){
					$stcdispatchedqty+=$dispatchedrow['stc_cust_super_requisition_list_items_rec_recqty'];
				}

            $stcrecqry=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_cust_super_requisition_rec_items_fr_supervisor_date`,
						`stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty`
					FROM `stc_cust_super_requisition_rec_items_fr_supervisor` 
					WHERE `stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid`='".$odin_get_mrdrow['req_item_id']."'  
				");
            
            $stcrecieveddate='';
            $stcrecievedqty=0;
            if(mysqli_num_rows($stcrecqry)>0){
               foreach($stcrecqry as $recievedrow){
                  $stcrecieveddate=date('d-m-Y', strtotime($recievedrow['stc_cust_super_requisition_rec_items_fr_supervisor_date']));
                  $stcrecievedqty+=$recievedrow['stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty'];
               }
            }

            $stcconsumedqty=0;
				$stcconsrecqtyqry=mysqli_query($this->stc_dbs, "
					SELECT 
						SUM(`stc_cust_super_list_items_consumption_items_qty`) AS consumable_qty
					FROM `stc_cust_super_list_items_consumption_items` 
					WHERE `stc_cust_super_list_items_consumption_items_name`='".mysqli_real_escape_string($this->stc_dbs, $odin_get_mrdrow['req_item_id'])."'  
				");
				foreach($stcconsrecqtyqry as $consumedrow){
					$stcconsumedqty+=$consumedrow['consumable_qty']==null ? 0 : $consumedrow['consumable_qty'];
				}
            $stcpendingqty=$odin_get_mrdrow['stc_cust_super_requisition_items_finalqty'] - $stcdispatchedqty;
            $stockqty=$stcrecievedqty - $stcconsumedqty;
            $bgcolor=$odin_get_mrdrow['stc_cust_super_requisition_items_priority']==2 ? 'style="background:#ffa5a5;color:black"' : "";
            $materialpriority=$odin_get_mrdrow['stc_cust_super_requisition_items_priority']==2 ? "Urgent" : "Normal";            

            $odin.='
               <tr data-toggle="modal" data-target=".stc-mrdmodal-res" class="showmrd-details" reqnumber="'.$odin_get_mrdrow['req_id'].'" reqdate="'.date('d-m-Y', strtotime($odin_get_mrdrow['stc_cust_super_requisition_list_date'])).'" reqraisedby="'.$odin_get_mrdrow['stc_cust_pro_supervisor_fullname'].' - '.$odin_get_mrdrow['stc_cust_pro_supervisor_contact'].'" reqraisedfrom="'.$odin_get_mrdrow['stc_cust_project_title'].'" itemdesc="'.$odin_get_mrdrow['stc_cust_super_requisition_list_items_title'].'" itemqty="'.number_format($odin_get_mrdrow['stc_cust_super_requisition_list_items_reqqty'], 2).'" itemunit="'.$odin_get_mrdrow['stc_cust_super_requisition_list_items_unit'].'" itempriority="'.$materialpriority.'" >
                  <td class="text-left">'.$odin_get_mrdrow['stc_cust_project_title'].'</td>
                  <td class="text-center">'.$odin_get_mrdrow['req_id'].'</td>
                  <td class="text-center">'.date('d-m-Y', strtotime($odin_get_mrdrow['stc_cust_super_requisition_list_date'])).'</td>
                  <td>'.$odin_get_mrdrow['stc_cust_super_requisition_list_items_title'].'</td>
                  <td class="text-center">'.$odin_get_mrdrow['stc_cust_super_requisition_list_items_unit'].'</td>
                  <td class="text-right">'.number_format($odin_get_mrdrow['stc_cust_super_requisition_list_items_reqqty'], 2).'</td>
                  <td class="text-right">'.number_format($odin_get_mrdrow['stc_cust_super_requisition_list_items_approved_qty'], 2).'</td>
                  <td class="text-right">'.number_format($odin_get_mrdrow['stc_cust_super_requisition_items_finalqty'], 2).'</td>
                  <td class="text-center" '.$bgcolor.'>'.$materialpriority.'</td>
               </tr>
            ';
         }
      } else {
         $odin .= '<tr><td colspan="15">No record found.</td></tr>';
     }
 
     // Add pagination controls
     $pages = ceil($total_record / $limit);
     $pagination = '<tr><td colspan="15"><div class="pagination">';
     for ($i = 1; $i <= $pages; $i++) {
         $active = ($i == $page) ? 'active' : '';
         $activestyle = ($i == $page) ? "style='background-color: #afaeff; font-weight: bold;padding: 5px;'" : "style='font-weight: bold;padding: 5px;'";
         $pagination .= "<a href='javascript:void(0)' class='stc-mrd-page $active' $activestyle data-page='$i'>$i</a>";
     }
     $pagination .= '</div></td></tr>';
 
     return $odin . $pagination;
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
	$objsearchreq=new ragnarSummary();
	$opobjsearchreq=$objsearchreq->stc_call_tbm($location, $month, $supervise_name);
	echo $opobjsearchreq;
}

// call mrd location
if(isset($_POST['stc_mrd_call_location'])){
   $out='';
   $customer_id=$_POST['customer_id'];
   $tojob=$_POST['tojob'];

   $bjorneschoolfee=new ragnarSummary();

   $out=$bjorneschoolfee->stc_mrd_location_call($customer_id, $tojob);
   echo $out;
}

if (isset($_POST['stc_mrd_call_mrd'])) {
   $out = '';
   $from = date('Y-m-d', strtotime($_POST['from']));
   $to = date('Y-m-d', strtotime($_POST['to']));
   $tojob = $_POST['tojob'];
   $customer = $_POST['customer'];
   $location = $_POST['location'];
   $dept = $_POST['dept'];
   $pro_id = $_POST['pro_id'];
   $tomaterial = $_POST['tomaterial'];
   $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
   $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 10;

   $bjorneschoolfee = new ragnarSummary();
   $out = $bjorneschoolfee->stc_mrd_find($from, $to, $tojob, $customer, $location, $dept, $pro_id, $tomaterial, $page, $limit);
   echo json_encode($out);
}
?>