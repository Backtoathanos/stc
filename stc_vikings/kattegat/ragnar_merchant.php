<?php
include "../../MCU/obdb.php";
class ragnarMerchants extends tesseract{
	private function stc_h($v){
		return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
	}

	private function stc_like_escape($v){
		return str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), $v);
	}

	public function stc_merchant_category_options(){
		return array('Manufacturer','Retailer','Wholesaler','Distributor','Dealer','Supplier','Trader','Service Provider','Others');
	}

	private function stc_merchant_search_where($name, $skf){
		$name = trim((string) $name);
		$skf = trim((string) $skf);
		$where = array('1=1');
		if($name !== ''){
			$esc = mysqli_real_escape_string($this->stc_dbs, $this->stc_like_escape($name));
			$where[] = "(
				`stc_merchant_name` LIKE '%".$esc."%'
				OR `stc_merchant_address` LIKE '%".$esc."%'
				OR `stc_merchant_gstin` LIKE '%".$esc."%'
				OR `stc_merchant_phone` LIKE '%".$esc."%'
				OR `stc_merchant_email` LIKE '%".$esc."%'
				OR `stc_merchant_contact_person` LIKE '%".$esc."%'
				OR `stc_merchant_category` LIKE '%".$esc."%'
			)";
		}
		if($skf !== ''){
			$esc = mysqli_real_escape_string($this->stc_dbs, $this->stc_like_escape($skf));
			$where[] = "`stc_merchant_specially_known_for` LIKE '%".$esc."%'";
		}
		return implode(' AND ', $where);
	}

	private function stc_num($v){
		return ($v === null || $v === '') ? 0.0 : (float) $v;
	}

	private function stc_dash($v){
		$v = trim((string) $v);
		return $v === '' ? '—' : $this->stc_h($v);
	}

	private function stc_merchant_initials($name){
		$parts = preg_split('/\s+/', strtoupper(trim((string) $name)));
		$skip = array('AND','&','THE','OF','PVT','LTD','LLP','CO','COMPANY');
		$letters = '';
		foreach($parts as $p){
			$p = preg_replace('/[^A-Z0-9]/', '', $p);
			if($p === '' || in_array($p, $skip, true)) continue;
			$letters .= $p[0];
			if(strlen($letters) >= 2) break;
		}
		return $letters !== '' ? $letters : 'M';
	}

	private function stc_merchant_avatar_color($id){
		$colors = array('#3f6ad8','#16aaff','#3ac47d','#f7b924','#d92550','#794c8a','#20c997','#e83e8c');
		return $colors[(int) $id % count($colors)];
	}

	private function stc_copy_btn($value, $label){
		$value = trim((string) $value);
		if($value === '') return '';
		return '<button type="button" class="stc-mer-copy" data-copy="'.$this->stc_h($value).'" data-label="'.$this->stc_h($label).'" title="Copy '.$this->stc_h($label).'"><i class="fa fa-clone"></i></button>';
	}

	private function stc_render_merchant_card($row){
		$id = (int) $row['stc_merchant_id'];
		$name = isset($row['stc_merchant_name']) ? trim((string) $row['stc_merchant_name']) : '';
		$city = isset($row['stc_city_name']) ? trim((string) $row['stc_city_name']) : '';
		$state = isset($row['stc_state_name']) ? trim((string) $row['stc_state_name']) : '';
		$place = trim($city.(($city && $state) ? ', ' : '').$state);
		$known = isset($row['stc_merchant_specially_known_for']) ? trim((string) $row['stc_merchant_specially_known_for']) : '';
		$category = isset($row['stc_merchant_category']) ? trim((string) $row['stc_merchant_category']) : '';
		$email = isset($row['stc_merchant_email']) ? trim((string) $row['stc_merchant_email']) : '';
		$phone = isset($row['stc_merchant_phone']) ? trim((string) $row['stc_merchant_phone']) : '';
		$gstin = isset($row['stc_merchant_gstin']) ? trim((string) $row['stc_merchant_gstin']) : '';
		$pan = isset($row['stc_merchant_pan']) ? trim((string) $row['stc_merchant_pan']) : '';
		$person = isset($row['stc_merchant_contact_person']) ? trim((string) $row['stc_merchant_contact_person']) : '';
		$address = isset($row['stc_merchant_address']) ? trim((string) $row['stc_merchant_address']) : '';
		$emailHtml = $email !== '' ? '<a href="mailto:'.$this->stc_h($email).'">'.$this->stc_h($email).'</a>' : '—';
		$phoneHtml = $phone !== '' ? '<a href="tel:'.$this->stc_h($phone).'">'.$this->stc_h($phone).'</a>' : '—';
		$knownHtml = $known !== '' ? '<span class="stc-mer-chip">'.$this->stc_h($known).'</span>' : '';
		$categoryHtml = ($category !== '' && strtoupper($category) !== 'NA') ? '<span class="stc-mer-chip stc-mer-chip-cat">'.$this->stc_h($category).'</span>' : '';
		$placeHtml = $place !== '' ? '<span class="stc-mer-loc"><i class="fa fa-map-marker"></i> '.$this->stc_h($place).'</span>' : '';
		return '
			<article class="stc-mer-card">
				<div class="stc-mer-card-top">
					<div class="stc-mer-avatar" style="background:'.$this->stc_merchant_avatar_color($id).'">'.$this->stc_h($this->stc_merchant_initials($name)).'</div>
					<div class="stc-mer-identity">
						<div class="stc-mer-name-row">
							<h5 class="stc-mer-name" title="'.$this->stc_h($name).'">'.$this->stc_h($name).'</h5>
							<span class="stc-mer-id">#'.$id.'</span>
						</div>
						<div class="stc-mer-tags">'.$categoryHtml.$knownHtml.$placeHtml.'</div>
					</div>
				</div>
				<p class="stc-mer-address"><i class="fa fa-building-o"></i> '.$this->stc_dash($address).'</p>
				<div class="stc-mer-facts">
					<div class="stc-mer-fact">
						<i class="fa fa-envelope-o"></i>
						<div><em>Email</em><b>'.$emailHtml.'</b></div>
						'.$this->stc_copy_btn($email, 'Email').'
					</div>
					<div class="stc-mer-fact">
						<i class="fa fa-phone"></i>
						<div><em>Contact</em><b>'.$phoneHtml.'</b></div>
						'.$this->stc_copy_btn($phone, 'Contact').'
					</div>
					<div class="stc-mer-fact">
						<i class="fa fa-id-card-o"></i>
						<div><em>GSTIN</em><b>'.$this->stc_dash($gstin).'</b></div>
						'.$this->stc_copy_btn($gstin, 'GSTIN').'
					</div>
					<div class="stc-mer-fact">
						<i class="fa fa-file-text-o"></i>
						<div><em>PAN</em><b>'.$this->stc_dash($pan).'</b></div>
						'.$this->stc_copy_btn($pan, 'PAN').'
					</div>
					<div class="stc-mer-fact stc-mer-fact-wide">
						<i class="fa fa-user"></i>
						<div><em>Contact person</em><b>'.$this->stc_dash($person).'</b></div>
						'.$this->stc_copy_btn($person, 'Contact person').'
					</div>
				</div>
			</article>
		';
	}

	private function stc_render_merchant_table_row($row){
		$id = (int) $row['stc_merchant_id'];
		$name = isset($row['stc_merchant_name']) ? trim((string) $row['stc_merchant_name']) : '';
		$city = isset($row['stc_city_name']) ? trim((string) $row['stc_city_name']) : '';
		$state = isset($row['stc_state_name']) ? trim((string) $row['stc_state_name']) : '';
		$place = trim($city.(($city && $state) ? ', ' : '').$state);
		$known = isset($row['stc_merchant_specially_known_for']) ? trim((string) $row['stc_merchant_specially_known_for']) : '';
		$category = isset($row['stc_merchant_category']) ? trim((string) $row['stc_merchant_category']) : '';
		$email = isset($row['stc_merchant_email']) ? trim((string) $row['stc_merchant_email']) : '';
		$phone = isset($row['stc_merchant_phone']) ? trim((string) $row['stc_merchant_phone']) : '';
		$gstin = isset($row['stc_merchant_gstin']) ? trim((string) $row['stc_merchant_gstin']) : '';
		$person = isset($row['stc_merchant_contact_person']) ? trim((string) $row['stc_merchant_contact_person']) : '';
		return '
			<tr>
				<td>
					<div class="stc-mer-td-name">
						<span class="stc-mer-avatar sm" style="background:'.$this->stc_merchant_avatar_color($id).'">'.$this->stc_h($this->stc_merchant_initials($name)).'</span>
						<div>
							<strong>'.$this->stc_h($name).'</strong>
							<small>#'.$id.($known !== '' ? ' · '.$this->stc_h($known) : '').'</small>
						</div>
					</div>
				</td>
				<td>'.$this->stc_dash($category).'</td>
				<td>'.$this->stc_dash($place).'</td>
				<td>
					<div class="stc-mer-td-stack">
						'.($email !== '' ? '<a href="mailto:'.$this->stc_h($email).'">'.$this->stc_h($email).'</a>' : '—').'
						<span>'.$this->stc_dash($phone).'</span>
					</div>
				</td>
				<td><code>'.$this->stc_dash($gstin).'</code></td>
				<td>'.$this->stc_dash($person).'</td>
			</tr>
		';
	}

	private function stc_render_merchant_empty(){
		return '
			<div class="stc-mer-empty">
				<div class="stc-mer-empty-icon"><i class="fa fa-users"></i></div>
				<h5>No merchants match this search</h5>
				<p>Try a different name, GSTIN, phone, or specialty.</p>
			</div>
		';
	}

	public function stc_search_merchant_paged($name, $skf, $page = 1, $per_page = 10){
		$where = $this->stc_merchant_search_where($name, $skf);
		$from = "
			FROM `stc_merchant`
			LEFT JOIN `stc_state` ON `stc_state_id`=`stc_merchant_state_id`
			LEFT JOIN `stc_city` ON `stc_city_id`=`stc_merchant_city_id`
			WHERE ".$where."
		";
		$total = 0;
		$cntq = mysqli_query($this->stc_dbs, "SELECT COUNT(DISTINCT `stc_merchant_id`) AS cnt ".$from);
		if($cntq && ($cr = mysqli_fetch_assoc($cntq))){
			$total = (int) $cr['cnt'];
		}
		$page = max(1, (int) $page);
		$per_page = max(1, min(50, (int) $per_page));
		$total_pages = $total > 0 ? (int) ceil($total / $per_page) : 1;
		if($page > $total_pages){
			$page = $total_pages;
		}
		$offset = ($page - 1) * $per_page;
		$html = '';
		$table = '';
		$qry = mysqli_query($this->stc_dbs, "
			SELECT DISTINCT `stc_merchant`.*, `stc_state_name`, `stc_city_name`
			".$from."
			ORDER BY `stc_merchant_name` ASC
			LIMIT ".(int) $offset.", ".(int) $per_page."
		");
		if($qry && mysqli_num_rows($qry) > 0){
			foreach($qry as $row){
				$html .= $this->stc_render_merchant_card($row);
				$table .= $this->stc_render_merchant_table_row($row);
			}
		}else{
			$html = $this->stc_render_merchant_empty();
		}
		$from_row = $total === 0 ? 0 : ($offset + 1);
		$to_row = min($total, $offset + $per_page);
		return array(
			'html' => $html,
			'table_html' => $table,
			'total' => $total,
			'total_pages' => $total_pages,
			'page' => $page,
			'per_page' => $per_page,
			'from' => $from_row,
			'to' => $to_row
		);
	}

	public function stc_search_merchant_byname($searchmebyname){
		$res = $this->stc_search_merchant_paged($searchmebyname, '', 1, 50);
		return $res['html'];
	}

	public function stc_search_merchant_byskf($searchmebyskf){
		$res = $this->stc_search_merchant_paged('', $searchmebyskf, 1, 50);
		return $res['html'];
	}

	// vendor add to table
	public function stc_vendor_hit($stcmername, $stcmeraddress, $stcmercity, $stcmerstate, $stcmercontperson, $stcmeremail, $stcmercontnumber, $stcmerskf, $stcmerpan, $stcmergst, $stcmerimages, $stcmercategory = ''){
		$stc_filter_add_merchant=mysqli_real_escape_string($this->stc_dbs, $stcmername);
		$stcmercategory=mysqli_real_escape_string($this->stc_dbs, $stcmercategory);
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_merchant` 
			WHERE `stc_merchant_name`='".mysqli_real_escape_string($this->stc_dbs, $stc_filter_add_merchant)."'
			OR `stc_merchant_pan`='".mysqli_real_escape_string($this->stc_dbs, $stcmerpan)."'
			OR `stc_merchant_gstin`='".mysqli_real_escape_string($this->stc_dbs, $stcmergst)."'
		");
		$count_loki_out=mysqli_num_rows($check_loki);
		if($count_loki_out == 0){
			$loki_query=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_merchant`(
					`stc_merchant_name`, 
					`stc_merchant_address`, 
					`stc_merchant_city_id`, 
					`stc_merchant_state_id`, 
					`stc_merchant_contact_person`, 
					`stc_merchant_email`, 
					`stc_merchant_phone`, 
					`stc_merchant_pan`, 
					`stc_merchant_gstin`, 
					`stc_merchant_specially_known_for`, 
					`stc_merchant_category`,
					`stc_merchant_image`
				) VALUES(
					'".$stc_filter_add_merchant."', 
					'".$stcmeraddress."', 
					'".$stcmercity."', 
					'".$stcmerstate."', 
					'".$stcmercontperson."', 
					'".$stcmeremail."', 
					'".$stcmercontnumber."', 
					'".$stcmerpan."', 
					'".$stcmergst."', 
					'".$stcmerskf."', 
					'".$stcmercategory."',
					'".$stcmerimages."'
				)
			");
			if($loki_query){
				$odin = "success";
			}else{
				$odin = "You Need to Try Again!!";
			}
		}else{
			$odin = "This Product Is Already In Record!!";
		}
		return $odin;
	}

	// call state
	public function call_state(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_state` ORDER BY `stc_state`.`stc_state_name` ASC");
		$odin='<option selected>Select State</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option selected>No State Found !!</option>";
		}else{
			foreach ($check_loki as $row) {
				if($row["stc_state_id"]==16){
					$odin.='
								<option selected value="'.$row["stc_state_id"].'">'.$row["stc_state_name"].'</option>		               	
			            	';		
		        }else{
		        		$odin.='
								<option value="'.$row["stc_state_id"].'">'.$row["stc_state_name"].'</option>		               	
			            	';	
		        }		
			}
			
		}
		return $odin;
	}

	// call city
	public function call_city(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_city` ORDER BY `stc_city`.`stc_city_name` ASC");
		$odin='<option selected>Select City</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option selected>No City Found !!</option>";
		}else{
			foreach ($check_loki as $row) {
		        if($row["stc_city_id"]==65){
					$odin.='
								<option selected value="'.$row["stc_city_id"].'">'.$row["stc_city_name"].'</option>		               	
			            	';		
		        }else{
		        		$odin.='
								<option value="'.$row["stc_city_id"].'">'.$row["stc_city_name"].'</option>		               	
			            	';		
		        }			
			}
			
		}
		return $odin;
	}

	public function stc_call_merchant_items($mer_id){
		$odin='';
		$odin_getqry=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
				`stc_product_id`,
				`stc_product_name`,
				`stc_cat_name`,
				`stc_sub_cat_name`   
			FROM
				`stc_product`
			INNER JOIN 
				`stc_category` 
			ON 
				`stc_cat_id`=`stc_product_cat_id` 
			INNER JOIN 
				`stc_sub_category` 
			ON 
				`stc_sub_cat_id`=`stc_product_sub_cat_id` 
			INNER JOIN 
				`stc_purchase_product_items` 
			ON 
				`stc_purchase_product_items_product_id`=`stc_product_id`
			INNER JOIN 
				`stc_purchase_product` 
			ON 
				`stc_purchase_product_id`=`stc_purchase_product_items_order_id`
			INNER JOIN 
				`stc_merchant` 
			ON 
				`stc_merchant_id`=`stc_purchase_product_merchant_id`
			WHERE 
				`stc_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $mer_id)."'
			ORDER BY `stc_product_name` ASC
		");
		if(mysqli_num_rows($odin_getqry)>0){
			foreach($odin_getqry as $odin_getrow){

				$sub_cat= ($odin_getrow['stc_sub_cat_name']=="OTHERS") ? "" : $odin_getrow['stc_sub_cat_name'];

				$odinallpoqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_purchase_product_items_qty`) as total_po_qty
					FROM
						`stc_purchase_product_items`
					WHERE
						`stc_purchase_product_items_product_id` ='".$odin_getrow['stc_product_id']."'
				");

				$total_po_qty= 0;
				foreach($odinallpoqry as $odinallporow){
					$total_po_qty=$this->stc_num($odinallporow['total_po_qty']);
				}

				$odinpoqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_purchase_product_items_qty`) as merchant_po_qty
					FROM
						`stc_purchase_product_items`
					INNER JOIN 
						`stc_purchase_product` 
					ON 
						`stc_purchase_product_id`=`stc_purchase_product_items_order_id`
					WHERE
						`stc_purchase_product_items_product_id` ='".$odin_getrow['stc_product_id']."'
					AND 
						`stc_purchase_product_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $mer_id)."'
				");

				$merchant_po_qty= 0;
				foreach($odinpoqry as $odiporow){
					$merchant_po_qty=$this->stc_num($odiporow['merchant_po_qty']);
				}

				$odinallgrnqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_product_grn_items_qty`) as allgrn_qty
					FROM
						`stc_product_grn_items`
					WHERE
						`stc_product_grn_items_product_id` ='".$odin_getrow['stc_product_id']."'
				");

				$allgrn_qty= 0;
				foreach($odinallgrnqry as $odinallgrnrow){
					$allgrn_qty=$this->stc_num($odinallgrnrow['allgrn_qty']);
				}

				$odingrnqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_product_grn_items_qty`) as grn_qty
					FROM
						`stc_product_grn_items`
					INNER JOIN 
						`stc_product_grn` 
					ON 
						`stc_product_grn_id`=`stc_product_grn_items_grn_order_id`
					WHERE
						`stc_product_grn_items_product_id` ='".$odin_getrow['stc_product_id']."'
					AND 
						`stc_product_grn_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $mer_id)."'
				");

				$grn_qty= 0;
				foreach($odingrnqry as $odingrnrow){
					$grn_qty=$this->stc_num($odingrnrow['grn_qty']);
				}

				$odininvqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_item_inventory_pd_qty`) as inv_qty
					FROM
						`stc_item_inventory`
					WHERE
						`stc_item_inventory_pd_id`='".$odin_getrow['stc_product_id']."'
				");

				$inv_qty= 0;
				foreach($odininvqry as $odininvrow){
					$inv_qty=$this->stc_num($odininvrow['inv_qty']);
				}

				$odinchallanqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_sale_product_items_product_qty`) as challan_qty
					FROM
						`stc_sale_product_items`
					WHERE
						`stc_sale_product_items_product_id`='".$odin_getrow['stc_product_id']."'
				");

				$challan_qty= 0;
				foreach($odinchallanqry as $odinchallanrow){
					$challan_qty=$this->stc_num($odinchallanrow['challan_qty']);
				}

				$odineinvqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_electronics_inventory_item_qty`) as einv_qty
					FROM
						`stc_electronics_inventory`
					WHERE
						`stc_electronics_inventory_item_id`='".$odin_getrow['stc_product_id']."'
				");

				$einv_qty= 0;
				foreach($odineinvqry as $odineinvrow){
					$einv_qty=$this->stc_num($odineinvrow['einv_qty']);
				}

				$odinechallanqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_sale_product_silent_challan_items_product_qty`) as echallan_qty
					FROM
						`stc_sale_product_silent_challan_items`
					WHERE
						`stc_sale_product_silent_challan_items_product_id`='".$odin_getrow['stc_product_id']."'
				");

				$echallan_qty= 0;
				foreach($odinechallanqry as $odinechallanrow){
					$echallan_qty=$this->stc_num($odinechallanrow['echallan_qty']);
				}

				$odin.='
					<tr>
						<td class="text-center">'.$odin_getrow['stc_product_id'].'</td>
						<td>'.$sub_cat.' '.$odin_getrow['stc_product_name'].' '.$odin_getrow['stc_product_name'].'</td>
						<td class="text-right">'.number_format($total_po_qty, 2).'</td>
						<td class="text-right">'.number_format($merchant_po_qty, 2).'</td>
						<td class="text-right">'.number_format($allgrn_qty, 2).'</td>
						<td class="text-right">'.number_format($grn_qty, 2).'</td>
						<td class="text-right">'.number_format($inv_qty, 2).'</td>
						<td class="text-right">'.number_format($challan_qty, 2).'</td>
						<td class="text-right">'.number_format($einv_qty, 2).'</td>
						<td class="text-right">'.number_format($echallan_qty, 2).'</td>
					</tr>
				';
			}
		}else{
			$odin.='
				<tr>
					<td>No Record Find!!</td>
				</tr>
			';
		}
		return $odin;
	}

	private $stc_mer_names_cache = null;

	private function stc_normalize_comparable_name($name){
		$n = strtoupper(trim((string) $name));
		$n = str_replace(array('&', '.', ',', '-', '/', '\\', "'", '"', '(', ')', '_'), ' ', $n);
		$n = preg_replace('/\b(PVT|PRIVATE|LIMITED|LTD|LLP|CO|COMPANY)\b/', ' ', $n);
		$n = preg_replace('/\s+/', ' ', $n);
		return trim((string) $n);
	}

	private function stc_merchant_names_list(){
		if($this->stc_mer_names_cache !== null){
			return $this->stc_mer_names_cache;
		}
		$out = array();
		$q = mysqli_query($this->stc_dbs, "
			SELECT TRIM(`stc_merchant_name`) AS n
			FROM `stc_merchant`
			WHERE `stc_merchant_name` IS NOT NULL
			  AND TRIM(`stc_merchant_name`) <> ''
		");
		if($q){
			while($row = mysqli_fetch_assoc($q)){
				$orig = trim((string) $row['n']);
				if($orig === '') continue;
				$out[] = array(
					'name' => $orig,
					'norm' => $this->stc_normalize_comparable_name($orig),
					'key' => strtoupper($orig)
				);
			}
		}
		$this->stc_mer_names_cache = $out;
		return $out;
	}

	private function stc_similar_merchant_matches($source, $limit = 2){
		$srcNorm = $this->stc_normalize_comparable_name($source);
		if($srcNorm === '' || strlen($srcNorm) < 4){
			return array();
		}
		$srcLen = strlen($srcNorm);
		$scored = array();
		foreach($this->stc_merchant_names_list() as $m){
			$merNorm = $m['norm'];
			if($merNorm === '') continue;
			$pct = 0.0;
			similar_text($srcNorm, $merNorm, $pct);
			$merLen = strlen($merNorm);
			$lev = ($srcLen <= 255 && $merLen <= 255) ? levenshtein($srcNorm, $merNorm) : null;
			$contains = (strpos($merNorm, $srcNorm) !== false || strpos($srcNorm, $merNorm) !== false);
			$maxLen = max($srcLen, $merLen);
			$relLev = ($lev !== null && $maxLen > 0) ? ($lev / $maxLen) : 1;
			$ok = $pct >= 72
				|| ($lev !== null && $lev <= 3 && abs($srcLen - $merLen) <= 8)
				|| ($contains && $pct >= 55 && min($srcLen, $merLen) >= 6)
				|| ($relLev <= 0.2 && $pct >= 60);
			if(!$ok) continue;
			$score = $pct;
			if($lev !== null) $score += max(0, 25 - $lev);
			if($contains) $score += 12;
			$scored[] = array('name' => $m['name'], 'pct' => (int) round($pct), 'score' => $score);
		}
		usort($scored, function($a, $b){
			if($a['score'] == $b['score']) return 0;
			return ($a['score'] > $b['score']) ? -1 : 1;
		});
		$top = array();
		$seen = array();
		foreach($scored as $row){
			$key = strtoupper(trim($row['name']));
			if(isset($seen[$key])) continue;
			$seen[$key] = true;
			$top[] = array('name' => $row['name'], 'pct' => $row['pct']);
			if(count($top) >= $limit) break;
		}
		return $top;
	}

	private function stc_adhoc_source_row_payload($name, $count){
		$matches = $this->stc_similar_merchant_matches($name, 2);
		$similar = array();
		foreach($matches as $m){
			$similar[] = $m['name'].' ('.$m['pct'].'%)';
		}
		return array(
			'source' => $name,
			'count' => (int) $count,
			'similar' => $matches,
			'similar_text' => $similar,
			'best' => isset($matches[0]['name']) ? $matches[0]['name'] : ''
		);
	}

	public function stc_adhoc_source_sync_list($search, $page, $per_page, $sort = 'source', $dir = 'asc'){
		$search = trim((string) $search);
		$page = max(1, (int) $page);
		$per_page = (int) $per_page;
		if($per_page < 10) $per_page = 25;
		if($per_page > 100) $per_page = 100;
		$allowedSort = array('source' => 'source_name', 'count' => 'usage_count', 'similar' => 'similar');
		if(!isset($allowedSort[$sort])) $sort = 'source';
		$dir = strtolower((string) $dir) === 'desc' ? 'desc' : 'asc';
		$offset = ($page - 1) * $per_page;

		$where = "
			A.`stc_purchase_product_adhoc_source` IS NOT NULL
			AND TRIM(A.`stc_purchase_product_adhoc_source`) <> ''
			AND NOT EXISTS (
				SELECT 1 FROM `stc_merchant` M
				WHERE UPPER(TRIM(M.`stc_merchant_name`)) = UPPER(TRIM(A.`stc_purchase_product_adhoc_source`))
			)
		";
		if($search !== ''){
			$esc = mysqli_real_escape_string($this->stc_dbs, $this->stc_like_escape($search));
			$where .= " AND TRIM(A.`stc_purchase_product_adhoc_source`) LIKE '%".$esc."%'";
		}

		$total = 0;
		$cntq = mysqli_query($this->stc_dbs, "
			SELECT COUNT(DISTINCT TRIM(A.`stc_purchase_product_adhoc_source`)) AS c
			FROM `stc_purchase_product_adhoc` A
			WHERE ".$where."
		");
		if($cntq && ($crow = mysqli_fetch_assoc($cntq))){
			$total = (int) $crow['c'];
		}
		$pages = max(1, (int) ceil($total / $per_page));
		if($page > $pages) $page = $pages;
		$offset = ($page - 1) * $per_page;

		$rows = array();
		if($sort === 'similar'){
			$q = mysqli_query($this->stc_dbs, "
				SELECT
					TRIM(A.`stc_purchase_product_adhoc_source`) AS source_name,
					COUNT(*) AS usage_count
				FROM `stc_purchase_product_adhoc` A
				WHERE ".$where."
				GROUP BY TRIM(A.`stc_purchase_product_adhoc_source`)
			");
			$all = array();
			if($q){
				while($r = mysqli_fetch_assoc($q)){
					$name = isset($r['source_name']) ? (string) $r['source_name'] : '';
					$all[] = $this->stc_adhoc_source_row_payload($name, $r['usage_count']);
				}
			}
			usort($all, function($a, $b) use ($dir){
				$av = strtoupper((string) $a['best']);
				$bv = strtoupper((string) $b['best']);
				$aEmpty = ($av === '');
				$bEmpty = ($bv === '');
				if($aEmpty !== $bEmpty){
					return $aEmpty ? 1 : -1;
				}
				$cmp = strcmp($av, $bv);
				if($cmp === 0) $cmp = strcasecmp((string) $a['source'], (string) $b['source']);
				return $dir === 'desc' ? -$cmp : $cmp;
			});
			$rows = array_slice($all, $offset, $per_page);
		} else {
			$orderCol = $allowedSort[$sort];
			$q = mysqli_query($this->stc_dbs, "
				SELECT
					TRIM(A.`stc_purchase_product_adhoc_source`) AS source_name,
					COUNT(*) AS usage_count
				FROM `stc_purchase_product_adhoc` A
				WHERE ".$where."
				GROUP BY TRIM(A.`stc_purchase_product_adhoc_source`)
				ORDER BY ".$orderCol." ".$dir.", source_name ASC
				LIMIT ".(int)$offset.", ".(int)$per_page."
			");
			if($q){
				while($r = mysqli_fetch_assoc($q)){
					$name = isset($r['source_name']) ? (string) $r['source_name'] : '';
					$rows[] = $this->stc_adhoc_source_row_payload($name, $r['usage_count']);
				}
			}
		}

		$from = $total === 0 ? 0 : (($page - 1) * $per_page) + 1;
		$to = min($total, $page * $per_page);
		return array(
			'ok' => true,
			'total' => $total,
			'page' => $page,
			'pages' => $pages,
			'from' => $from,
			'to' => $to,
			'sort' => $sort,
			'dir' => $dir,
			'rows' => $rows,
			'categories' => $this->stc_merchant_category_options()
		);
	}

	public function stc_adhoc_source_rename($olds, $new){
		$new = trim((string) $new);
		if($new === ''){
			return array('ok' => false, 'message' => 'New name cannot be empty.');
		}
		if(!is_array($olds)) $olds = array($olds);
		$unique = array();
		foreach($olds as $n){
			$n = trim((string) $n);
			if($n === '') continue;
			$unique[$n] = $n;
		}
		$olds = array_values($unique);
		if($olds === array()){
			return array('ok' => false, 'message' => 'Select at least one source.');
		}

		$merchantKeys = array();
		foreach($this->stc_merchant_names_list() as $m){
			$merchantKeys[$m['key']] = true;
		}

		$skipped = array();
		$toUpdate = array();
		foreach($olds as $name){
			$key = strtoupper($name);
			if(isset($merchantKeys[$key])){
				$skipped[] = $name;
				continue;
			}
			if($name === $new) continue;
			$toUpdate[] = $name;
		}
		if($toUpdate === array()){
			return array(
				'ok' => false,
				'message' => $skipped !== array()
					? 'Skipped — selected name(s) already exist in merchant master.'
					: 'New name is the same as the current name.'
			);
		}

		$updated = 0;
		foreach($toUpdate as $old){
			$escOld = mysqli_real_escape_string($this->stc_dbs, $old);
			$escNew = mysqli_real_escape_string($this->stc_dbs, $new);
			$uq = mysqli_query($this->stc_dbs, "
				UPDATE `stc_purchase_product_adhoc`
				SET `stc_purchase_product_adhoc_source`='".$escNew."'
				WHERE TRIM(`stc_purchase_product_adhoc_source`)='".$escOld."'
			");
			if($uq) $updated += mysqli_affected_rows($this->stc_dbs);
		}

		$fromLabel = count($toUpdate) === 1 ? '"'.$toUpdate[0].'"' : count($toUpdate).' source names';
		$msg = $updated.' record(s) renamed from '.$fromLabel.' to "'.$new.'".';
		if($skipped !== array()){
			$msg .= ' Skipped '.count($skipped).' name(s) already in merchant master.';
		}
		return array('ok' => true, 'message' => $msg, 'updated' => $updated, 'skipped' => $skipped);
	}

	public function stc_merchant_quick_add($name, $category){
		$name = strtoupper(trim((string) $name));
		$category = trim((string) $category);
		$allowed = $this->stc_merchant_category_options();
		if($name === ''){
			return array('ok' => false, 'message' => 'Merchant name is required.');
		}
		if(!in_array($category, $allowed, true)){
			return array('ok' => false, 'message' => 'Select a valid merchant category.');
		}
		$escName = mysqli_real_escape_string($this->stc_dbs, $name);
		$chk = mysqli_query($this->stc_dbs, "
			SELECT `stc_merchant_id` FROM `stc_merchant`
			WHERE UPPER(TRIM(`stc_merchant_name`))='".$escName."'
			LIMIT 1
		");
		if($chk && mysqli_num_rows($chk) > 0){
			return array('ok' => false, 'message' => 'This merchant name already exists.');
		}
		$escCat = mysqli_real_escape_string($this->stc_dbs, $category);
		$ins = mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_merchant`(
				`stc_merchant_name`,
				`stc_merchant_address`,
				`stc_merchant_city_id`,
				`stc_merchant_state_id`,
				`stc_merchant_contact_person`,
				`stc_merchant_email`,
				`stc_merchant_phone`,
				`stc_merchant_pan`,
				`stc_merchant_gstin`,
				`stc_merchant_specially_known_for`,
				`stc_merchant_category`,
				`stc_merchant_image`,
				`stc_merchant_found_by`
			) VALUES (
				'".$escName."',
				'',
				65,
				16,
				'',
				'',
				'',
				'',
				'',
				'',
				'".$escCat."',
				NULL,
				0
			)
		");
		if($ins){
			return array('ok' => true, 'message' => 'Merchant added.', 'id' => (int) mysqli_insert_id($this->stc_dbs));
		}
		return array('ok' => false, 'message' => 'Could not save merchant. Try again.');
	}
}

#<------------------------------------------------------------------------------------------------------>
#<--------------------------------------Object sections of Merchant class-------------------------------->
#<------------------------------------------------------------------------------------------------------>
if(isset($_POST['stc_search_merchant_paged'])){
	while(ob_get_level() > 0){
		ob_end_clean();
	}
	header('Content-Type: application/json; charset=utf-8');
	$objloki=new ragnarMerchants();
	$name=isset($_POST['stcmername']) ? $_POST['stcmername'] : '';
	$skf=isset($_POST['stcmerskf']) ? $_POST['stcmerskf'] : '';
	$page=isset($_POST['page']) ? (int) $_POST['page'] : 1;
	$per_page=isset($_POST['per_page']) ? (int) $_POST['per_page'] : 10;
	$payload=$objloki->stc_search_merchant_paged($name, $skf, $page, $per_page);
	$flags=JSON_UNESCAPED_UNICODE;
	if(defined('JSON_INVALID_UTF8_SUBSTITUTE')){
		$flags=$flags | JSON_INVALID_UTF8_SUBSTITUTE;
	}
	$json=json_encode($payload, $flags);
	if($json===false){
		$json=json_encode(array(
			'html' => '<div class="stc-mer-empty"><h5>Could not load merchants</h5><p>Invalid data encoding.</p></div>',
			'table_html' => '',
			'total' => 0,
			'total_pages' => 1,
			'page' => 1,
			'per_page' => $per_page,
			'from' => 0,
			'to' => 0
		));
	}
	echo $json;
	exit;
}

// by merchant name
if(isset($_POST['search_mer_byname_var_in'])){
	$out='';
	$searchmebyname=$_POST['search_mer_byname_var_in'];
	if(strlen($searchmebyname)<=3){
		$out='
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				<a 
			      href="#" 
			      class="btn btn-primary btn-block text-uppercase mb-3">
			      Enter At least a word of 3 characters!!!
			    </a>
			</div>
		';
	}else{
		$objloki=new ragnarMerchants();
		$objlokiout=$objloki->stc_search_merchant_byname($searchmebyname);
		$out=$objlokiout;
	}
	echo json_encode($out);
}

// by merchant specially known for
if(isset($_POST['search_mer_var_byskf_var_in'])){
	$out='';
	$searchmebyskf=$_POST['search_mer_var_byskf_var_in'];
	if(strlen($searchmebyskf)<=3){
		$out='
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				<a 
			      href="#" 
			      class="btn btn-primary btn-block text-uppercase mb-3">
			      Enter At least a word of 3 characters!!!
			    </a>
			</div>
		';
	}else{
		$objloki=new ragnarMerchants();
		$objlokiout=$objloki->stc_search_merchant_byskf($searchmebyskf);
		$out=$objlokiout;
	}
	echo json_encode($out);
}

// input merchant
if(isset($_POST['stc_add_merchant_hit'])){
	$stcmername=strtoupper($_POST['stcmername']);
	$stcmeraddress=strtoupper($_POST['stcmeraddress']);
	$stcmercity=$_POST['stcmercity'];
	$stcmerstate=$_POST['stcmerstate'];
	$stcmercontperson=$_POST['stcmercontperson'];
	$stcmeremail=$_POST['stcmeremail'];
	$stcmercontnumber=$_POST['stcmercontnumber'];
	$stcmerskf=$_POST['stcmerskf'];
	$stcmercategory=isset($_POST['stcmercategory']) ? $_POST['stcmercategory'] : 'NA';
	$stcmerpan=strtoupper($_POST['stcmerpan']);
	$stcmergst=strtoupper($_POST['stcmergstin']);
	$stcmerimages='NA';

	$adago=new ragnarMerchants();
	$allowedCats=$adago->stc_merchant_category_options();

	if(empty($stcmername) || empty($stcmeraddress) || empty($stcmeremail) || empty($stcmercontnumber) || empty($stcmerpan) || empty($stcmergst) || $stcmercategory==='NA' || !in_array($stcmercategory, $allowedCats, true)){
		echo "Please Fill All Fields!!!";
	}else{
		// function calling
		$objadago=$adago->stc_vendor_hit($stcmername, $stcmeraddress, $stcmercity, $stcmerstate, $stcmercontperson, $stcmeremail, $stcmercontnumber, $stcmerskf, $stcmerpan, $stcmergst, $stcmerimages, $stcmercategory);

		if($objadago == "success"){	
			echo "Merchant added!!";
		}else{
			echo $objadago;
		}
	}
}

// call state on merchants
// call to the product page
if(isset($_POST['indialocation'])){
	$indialocationstate=new ragnarMerchants();
	$indialocationcity=new ragnarMerchants();

	$outindialocationstate=$indialocationstate->call_state();
	$outindialocationcity=$indialocationcity->call_city();

	$mount=array($outindialocationcity, $outindialocationstate);
	echo json_encode($mount);
}

// call merchant
if(isset($_POST['stc_get_merchant_item'])){
	$out='';
	$mer_id=$_POST['mer_id'];
	if($mer_id=="NA"){
		$out='
			<tr>
				<td>
				</td>
			</tr>
		';
	}else{
		$objloki=new ragnarMerchants();
		$objlokiout=$objloki->stc_call_merchant_items($mer_id);
		$out=$objlokiout;
	}
	echo $out;
}

function stc_merchant_json_flags(){
	$flags = JSON_UNESCAPED_UNICODE;
	if(defined('JSON_INVALID_UTF8_SUBSTITUTE')){
		$flags = $flags | JSON_INVALID_UTF8_SUBSTITUTE;
	}
	return $flags;
}

if(isset($_POST['stc_adhoc_source_sync_list'])){
	while(ob_get_level() > 0){
		ob_end_clean();
	}
	header('Content-Type: application/json; charset=utf-8');
	$obj = new ragnarMerchants();
	$search = isset($_POST['search']) ? $_POST['search'] : '';
	$page = isset($_POST['page']) ? $_POST['page'] : 1;
	$per_page = isset($_POST['per_page']) ? $_POST['per_page'] : 25;
	$sort = isset($_POST['sort']) ? $_POST['sort'] : 'source';
	$dir = isset($_POST['dir']) ? $_POST['dir'] : 'asc';
	echo json_encode($obj->stc_adhoc_source_sync_list($search, $page, $per_page, $sort, $dir), stc_merchant_json_flags());
	exit;
}

if(isset($_POST['stc_adhoc_source_rename'])){
	while(ob_get_level() > 0){
		ob_end_clean();
	}
	header('Content-Type: application/json; charset=utf-8');
	$obj = new ragnarMerchants();
	$olds = array();
	if(isset($_POST['old_sources']) && is_array($_POST['old_sources'])){
		$olds = $_POST['old_sources'];
	}elseif(isset($_POST['old_source'])){
		$olds = array($_POST['old_source']);
	}
	$new = isset($_POST['new_source']) ? $_POST['new_source'] : '';
	echo json_encode($obj->stc_adhoc_source_rename($olds, $new), stc_merchant_json_flags());
	exit;
}

if(isset($_POST['stc_merchant_quick_add'])){
	while(ob_get_level() > 0){
		ob_end_clean();
	}
	header('Content-Type: application/json; charset=utf-8');
	$obj = new ragnarMerchants();
	$name = isset($_POST['name']) ? $_POST['name'] : '';
	$category = isset($_POST['category']) ? $_POST['category'] : '';
	echo json_encode($obj->stc_merchant_quick_add($name, $category), stc_merchant_json_flags());
	exit;
}
?>