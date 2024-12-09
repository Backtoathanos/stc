<?php
include "../../MCU/obdb.php";
class ragnarProduct extends tesseract{
	// data show in product section
	// call category
	public function call_category(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_category` ORDER BY `stc_cat_name` ASC");
		$odin='<option value="NA" selected>Select Category</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option value='NA' selected>No Category Found!!!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
							<option value="'.$row["stc_cat_id"].'">'.$row["stc_cat_name"].'</option>		               	
		            	';				
			}
			
		}
		return $odin;
	}

	// call sub category
	public function call_sub_category(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_sub_category`");
		$odin='<option value="NA" selected>Select Sub Category</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option value='NA' selected>No Category Found!!!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
							<option value="'.$row["stc_sub_cat_id"].'">'.$row["stc_sub_cat_name"].'</option>		               	
		            	';				
			}
			
		}
		return $odin;
	}

	// call racks
	public function call_rack(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_rack`");
		$odin='<option value="NA" selected>Select Rack</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option selected>No Rack Found!!!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
							<option value="'.$row["stc_rack_id"].'">'.$row["stc_rack_name"].'</option>		               	
		            	';				
			}
			
		}
		return $odin;
	}

	// call brands
	public function call_brand(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_brand`");
		$odin='<option value="0" selected>Select Brand</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option selected>No Brand Found!!!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
							<option value="'.$row["stc_brand_id"].'">'.$row["stc_brand_title"].'</option>		               	
		            	';				
			}
			
		}
		return $odin;
	}

	// add product to the database
	public function stc_product_hit($stcpdname, $stcpddsc, $stcpdcat, $stcpdsubcat, $stcpdhsn, $stcpdpercentage, $stcpdunit, $stcpdstatus, $stcpdgst, $stcpdimages, $stcpdbrand){
		$stc_filter_add_product=$stcpdname;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_product` 
			WHERE `stc_product_name`='".$stc_filter_add_product."' 
			AND `stc_product_cat_id`='".$stcpdcat."'
			AND `stc_product_sub_cat_id`='".$stcpdsubcat."'
			AND `stc_product_desc`='".$stcpddsc."'
			AND `stc_product_brand_id`='".$stcpdbrand."'
		");
		$count_loki_out=mysqli_num_rows($check_loki);
		if($count_loki_out == 0){
			$loki_query=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_product`(
					`stc_product_name`, 
					`stc_product_desc`, 
					`stc_product_cat_id`, 
					`stc_product_sub_cat_id`, 
					`stc_product_unit`, 
					`stc_product_hsncode`, 
					`stc_product_gst`, 
					`stc_product_image`,
					`stc_product_sale_percentage`,
					`stc_product_brand_id`,
					`stc_product_avail`
				) 
				VALUES(
					'".mysqli_real_escape_string($this->stc_dbs, $stc_filter_add_product)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $stcpddsc)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $stcpdcat)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $stcpdsubcat)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $stcpdunit)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $stcpdhsn)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $stcpdgst)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $stcpdimages)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcpdpercentage)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcpdbrand)."',
					'1'
				)
			");
			if($loki_query){
				$odin = "success";
			}else{
				$odin = "You Need To Try Again!!";
			}
		}else{
			$odin = "This Product Is Already In Record!!!";
		}
		return $odin;
	}

	/*---------------------------- Filter product s-----------------------------*/
	// filter product by all
	public function stc_getpd_by_multiple_inp($bjornefiltercatout, $bjornefiltersubcatout, $bjornefilternameout){
		$ivar='';
			
		$array = array(
			"bycat" => $bjornefiltercatout,
			"bysubcat" => $bjornefiltersubcatout,
			"byname" => $bjornefilternameout
		);
		$category='';
		$subcategory='';
		$productname='';
		foreach($array as $key => $value){
			if($array['bycat']!="NA"){
				$category="
					AND `stc_product_cat_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bycat'])."'
				";
			}

			if($array['bysubcat']!="NA"){
				$subcategory="
					AND `stc_product_sub_cat_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bysubcat'])."'
				";
			}

			if(!empty($array['byname'])){
				$productname="
					AND (`stc_product_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byname'])."'
					OR 
					`stc_product_desc` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byname'])."')
				";
			}
		}
		$endfilterqry='ORDER BY `stc_product_id` ASC LIMIT 0,30';

		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT * FROM `stc_product` 
			LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
			LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
			LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
			LEFT JOIN `stc_item_inventory` ON `stc_item_inventory_pd_id`=`stc_product_id`
			LEFT JOIN `stc_brand` ON `stc_brand_id`=`stc_product_brand_id`
			WHERE `stc_product_avail`='1' 
			".$category.$subcategory.$productname.$endfilterqry
		);

		if(mysqli_num_rows($ivarfilterquery)>0){
			foreach($ivarfilterquery as $filterrow){
				$correct_stcpdname=strlen($filterrow["stc_product_name"]);
				$stcqty=$filterrow["stc_item_inventory_pd_qty"];
				if($stcqty==0){
					$stcqty=0;
				}else{
					$stcqty;
				}
				$ivar.='
					<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
		        		<div class="row">
		        			<div class="el-wrapper">
		        				<div class="box-up">
		        					<img class="img" style="height: 100%; width:100%;" src="../stc_symbiote/stc_product_image/'.$filterrow["stc_product_image"].'" alt="">
		        					<div class="img-info">
		        						<div class="info-inner">
		        							<span class="p-name">'.$filterrow["stc_product_name"].'</span>
		        							<span class="p-company">
		        								'.$filterrow["stc_product_id"].' /
		        								'.$filterrow["stc_cat_name"].' / 
		        								'.$filterrow["stc_product_hsncode"].' / 
		        								'.$filterrow["stc_product_gst"].'%
		        							</span>
		        							<span class="p-qty" >'.$stcqty.' '.$filterrow["stc_product_unit"].'</span>
		        							<span>
												<a href="javascript:void(0)" id="'.$filterrow["stc_product_id"].'" class="btn btn-success view-purchase-mod-btn"  data-toggle="modal" data-target="#view-products-purchase-modal"><i class="fas fa-eye"></i> Purchase</a>
												<a href="javascript:void(0)" id="'.$filterrow["stc_product_id"].'" class="btn btn-success view-sale-mod-btn"  data-toggle="modal" data-target="#view-products-sale-modal"><i class="fas fa-eye"></i> Sale</a>
											</span>

		        						</div>
		        						<div class="a-size">Available on Rack : <span class="size">'.$filterrow["stc_rack_name"].'</span></div>
		        					</div>
		        				</div>

		        				<div class="box-down">
		        					<div class="h-bg">
		        						<div class="h-bg-inner"></div>
		        					</div>

		        					<a 
		        						class="cart viewedit" 
		        						id="'.$filterrow["stc_product_id"].'" 
		        						href="edit-product.php?pdid='.$filterrow["stc_product_id"].'">
			        						<span class="price">'.$filterrow["stc_sub_cat_name"].' / '.$filterrow["stc_brand_title"].'</span>
			        						<span class="add-to-cart">
			        						<span class="txt">Wanna Edit</span>
			        						</span>
		        					</a>
		        				</div>
		        			</div>
		        		</div>
		        	</div>
				';
			}
		}else{
			$ivar .= '
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
					<a 
				      href="#" 
				      class="btn btn-primary btn-block text-uppercase mb-3">
				      No Records Found!!!
				    </a>
				</div>
			';
		}
		return $ivar;
	}

	// by per line items id
	public function purchase_record_per_pd($pd_id){
		$odin='
			<table class="table table-hover ">
	        	<thead>
	        		<tr>
	        			<th scope="col">Product Desc</th>
	        			<th scope="col">Purchase Order Number</th>
	        			<th scope="col">Date</th>
	        			<th scope="col">Merchant</th> 
	        			<th scope="col">Quantity</th>
	        			<th scope="col">Rate</th>
	        		</tr>
	        	</thead>
	        	<tbody>  
		';
		$odinfetchbyidpoqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_purchase_product_id`,
				`stc_purchase_product_order_date`,
			    `stc_product_name`,
			    `stc_sub_cat_name`,
			    `stc_product_unit`,
			    `stc_merchant_name`,
			    `stc_purchase_product_items_qty`,
			    `stc_purchase_product_items_rate`
			FROM `stc_purchase_product_items` 
			INNER JOIN `stc_product` 
			ON `stc_product_id`=`stc_purchase_product_items_product_id`
			INNER JOIN `stc_sub_category` 
			ON `stc_sub_cat_id`=`stc_product_sub_cat_id`
			INNER JOIN `stc_purchase_product` 
			ON `stc_purchase_product_id`=`stc_purchase_product_items_order_id`
			INNER JOIN `stc_merchant` 
			ON `stc_merchant_id`=`stc_purchase_product_merchant_id`
			WHERE `stc_purchase_product_items_product_id`='".mysqli_real_escape_string($this->stc_dbs, $pd_id)."'
			ORDER BY `stc_purchase_product_id` DESC
		");
		$countfetchrow=mysqli_num_rows($odinfetchbyidpoqry);
		if($countfetchrow>0){
			foreach($odinfetchbyidpoqry as $fetchproduct){
				$subcat='';
				if($fetchproduct['stc_sub_cat_name']=="OTHERS"){
					$subcat='';
				}else{
					$subcat=$fetchproduct['stc_sub_cat_name'];
				}
				$odin.='
					<tr>
						<td width="20%">'.$subcat.' '.$fetchproduct['stc_product_name'].'</td>
						<td align="center">STC/'.substr("0000{$fetchproduct['stc_purchase_product_id']}", -5).'</td>
						<td align="center" width="12%">'.date('d-m-Y', strtotime($fetchproduct['stc_purchase_product_order_date'])).'</td>
						<td>'.$fetchproduct['stc_merchant_name'].'</td>
						<td align="right">'.number_format($fetchproduct['stc_purchase_product_items_qty'], 2).'<br>'.$fetchproduct['stc_product_unit'].'</td>
						<td align="right">'.number_format($fetchproduct['stc_purchase_product_items_rate'], 2).'</td>
		        	</tr>
				';
			}
		}else{
			$odin.='
				<tr>
					<td align="center" colspan="6">
						<a class="btn btn-primary btn-block text-uppercase">
                              No Record Found!!!
						</a>
                    </td>
				</tr>
			';
		}
		$odin.='
					<tr>
						<td align="center" colspan="6"><h2>Purchase Through Direct Challan</h2></td>
					</tr>
		';
		$odinfetchbyiddcqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_sale_product_id`,
				`stc_sale_product_date`,
			    `stc_product_name`,
			    `stc_sub_cat_name`,
			    `stc_product_unit`,
			    `stc_merchant_name`,
			    `stc_sale_product_dc_items_product_qty`,
			    `stc_sale_product_dc_items_product_rate`
			FROM `stc_sale_product_dc_items` 
			INNER JOIN `stc_product` 
			ON `stc_product_id`=`stc_sale_product_dc_items_product_id`
			INNER JOIN `stc_sub_category` 
			ON `stc_sub_cat_id`=`stc_product_sub_cat_id`
			INNER JOIN `stc_sale_product` 
			ON `stc_sale_product_id`=`stc_sale_product_dc_items_sale_product_id`
			INNER JOIN `stc_merchant` 
			ON `stc_merchant_id`=`stc_sale_product_dc_merchant`
			WHERE `stc_sale_product_dc_items_product_id`='".mysqli_real_escape_string($this->stc_dbs, $pd_id)."'
			ORDER BY `stc_sale_product_id` DESC
		");
		$countfetchrow2=mysqli_num_rows($odinfetchbyiddcqry);
		if($countfetchrow2>0){
			foreach($odinfetchbyiddcqry as $fetchproduct){
				$subcat='';
				if($fetchproduct['stc_sub_cat_name']=="OTHERS"){
					$subcat='';
				}else{
					$subcat=$fetchproduct['stc_sub_cat_name'];
				}
				$odin.='
					<tr>
						<td width="20%">'.$subcat.' '.$fetchproduct['stc_product_name'].'</td>
						<td align="center">STC/DC/'.substr("0000{$fetchproduct['stc_sale_product_id']}", -5).'</td>
						<td align="center" width="12%">'.date('d-m-Y', strtotime($fetchproduct['stc_sale_product_date'])).'</td>
						<td>'.$fetchproduct['stc_merchant_name'].'</td>
						<td align="right">'.number_format($fetchproduct['stc_sale_product_dc_items_product_qty'], 2).'<br>'.$fetchproduct['stc_product_unit'].'</td>
						<td align="right">'.number_format($fetchproduct['stc_sale_product_dc_items_product_rate'], 2).'</td>
		        	</tr>
				';
			}
		}else{
			$odin.='
				<tr>
					<td align="center" colspan="6">
						<a class="btn btn-primary btn-block text-uppercase">
                              No Record Found!!!
						</a>
                    </td>
				</tr>
			';
		}
		$odin.='
				</tbody>
			</table>
		';
		return $odin;
	}

	// by per line items id
	public function sale_record_per_pd($pd_id){
		$odin='
			<table class="table table-hover ">
	        	<thead>
	        		<tr>
	        			<th scope="col">Product Desc</th>
	        			<th scope="col">Sale Order Number</th>
	        			<th scope="col">Date</th>
	        			<th scope="col">Customer</th> 
	        			<th scope="col">Sitename</th> 
	        			<th scope="col">Quantity</th>
	        			<th scope="col">Rate</th>
	        		</tr>
	        	</thead>
	        	<tbody>  
		';
		$odinsaleqry1=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_sale_product_id`,
				`stc_sale_product_date`,
			    `stc_product_name`,
			    `stc_sub_cat_name`,
			    `stc_product_unit`,
			    `stc_customer_name`,
                `stc_sale_product_sitename`,
			    `stc_sale_product_items_product_qty`,
			    `stc_sale_product_items_product_sale_rate`
			FROM `stc_sale_product_items` 
			INNER JOIN `stc_product` 
			ON `stc_product_id`=`stc_sale_product_items_product_id`
			INNER JOIN `stc_sub_category` 
			ON `stc_sub_cat_id`=`stc_product_sub_cat_id`
			INNER JOIN `stc_sale_product` 
			ON `stc_sale_product_id`=`stc_sale_product_items_sale_product_id`
			INNER JOIN `stc_customer` 
			ON `stc_customer_id`=`stc_sale_product_cust_id`
			WHERE `stc_sale_product_items_product_id`='".mysqli_real_escape_string($this->stc_dbs, $pd_id)."'
			ORDER BY `stc_sale_product_id` DESC
		");

		$odinsaleqry2=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_sale_product_id`,
				`stc_sale_product_date`,
			    `stc_product_name`,
			    `stc_sub_cat_name`,
			    `stc_product_unit`,
			    `stc_customer_name`,
                `stc_sale_product_sitename`,
			    `stc_sale_product_dc_items_product_qty`,
			    `stc_sale_product_dc_items_product_sale_rate`
			FROM `stc_sale_product_dc_items` 
			INNER JOIN `stc_product` 
			ON `stc_product_id`=`stc_sale_product_dc_items_product_id`
			INNER JOIN `stc_sub_category` 
			ON `stc_sub_cat_id`=`stc_product_sub_cat_id`
			INNER JOIN `stc_sale_product` 
			ON `stc_sale_product_id`=`stc_sale_product_dc_items_sale_product_id`
			INNER JOIN `stc_customer` 
			ON `stc_customer_id`=`stc_sale_product_cust_id`
			WHERE `stc_sale_product_dc_items_product_id`='".mysqli_real_escape_string($this->stc_dbs, $pd_id)."'
			ORDER BY `stc_sale_product_id` DESC
		");

		$odinsaleqry3=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_sale_product_id`,
				`stc_sale_product_date`,
			    `stc_product_name`,
			    `stc_sub_cat_name`,
			    `stc_product_unit`,
			    `stc_customer_name`,
                `stc_sale_product_sitename`,
			    `stc_sale_product_vc_items_product_qty`,
			    `stc_sale_product_vc_items_product_sale_rate`
			FROM `stc_sale_product_vc` 
			INNER JOIN `stc_product` 
			ON `stc_product_id`=`stc_sale_product_vc_items_product_id`
			INNER JOIN `stc_sub_category` 
			ON `stc_sub_cat_id`=`stc_product_sub_cat_id`
			INNER JOIN `stc_sale_product` 
			ON `stc_sale_product_id`=`stc_sale_product_vc_items_sale_product_id`
			INNER JOIN `stc_customer` 
			ON `stc_customer_id`=`stc_sale_product_cust_id`
			WHERE `stc_sale_product_vc_items_product_id`='".mysqli_real_escape_string($this->stc_dbs, $pd_id)."'
			ORDER BY `stc_sale_product_id` DESC
		");
		$countsale_array=mysqli_num_rows($odinsaleqry1);
		if($countsale_array>0){
			foreach($odinsaleqry1 as $fetchproduct){
				$subcat='';
				if($fetchproduct['stc_sub_cat_name']=="OTHERS"){
					$subcat='';
				}else{
					$subcat=$fetchproduct['stc_sub_cat_name'];
				}
				$odin.='
					<tr>
						<td width="20%">'.$subcat.' '.$fetchproduct['stc_product_name'].'</td>
						<td align="center">STC/DC/'.substr("0000{$fetchproduct['stc_sale_product_id']}", -5).'</td>
						<td align="center" width="12%">'.date('d-m-Y', strtotime($fetchproduct['stc_sale_product_date'])).'</td>
						<td>'.$fetchproduct['stc_customer_name'].'</td>
						<td>'.$fetchproduct['stc_sale_product_sitename'].'</td>
						<td align="right">'.number_format($fetchproduct['stc_sale_product_items_product_qty'], 2).'<br>'.$fetchproduct['stc_product_unit'].'</td>
						<td align="right">'.number_format($fetchproduct['stc_sale_product_items_product_sale_rate'], 2).'</td>
		        	</tr>
				';
			}
		}else{
			$odin.='
				<tr>
					<td align="center" colspan="6">
						<a class="btn btn-primary btn-block text-uppercase">
                              No Record Found!!!
						</a>
                    </td>
				</tr>
			';
		}
		$odin.='
				<tr>
					<td align="center" colspan="6"><h2>Sale Through Direct Challan</h2></td>
				</tr>
		';
		$countsale_arrayagain=mysqli_num_rows($odinsaleqry2);
		if($countsale_arrayagain>0){
			foreach($odinsaleqry2 as $fetchproduct){
				$subcat='';
				if($fetchproduct['stc_sub_cat_name']=="OTHERS"){
					$subcat='';
				}else{
					$subcat=$fetchproduct['stc_sub_cat_name'];
				}
				$odin.='
					<tr>
						<td width="20%">'.$subcat.' '.$fetchproduct['stc_product_name'].'</td>
						<td align="center">STC/DC/'.substr("0000{$fetchproduct['stc_sale_product_id']}", -5).'</td>
						<td align="center" width="12%">'.date('d-m-Y', strtotime($fetchproduct['stc_sale_product_date'])).'</td>
						<td>'.$fetchproduct['stc_customer_name'].'</td>
						<td>'.$fetchproduct['stc_sale_product_sitename'].'</td>
						<td align="right">'.number_format($fetchproduct['stc_sale_product_dc_items_product_qty'], 2).'<br>'.$fetchproduct['stc_product_unit'].'</td>
						<td align="right">'.number_format($fetchproduct['stc_sale_product_dc_items_product_sale_rate'], 2).'</td>
		        	</tr>
				';
			}
		}else{
			$odin.='
				<tr>
					<td align="center" colspan="6">
						<a class="btn btn-primary btn-block text-uppercase">
                              No Record Found!!!
						</a>
                    </td>
				</tr>
			';
		}

		$odin.='
				<tr>
					<td align="center" colspan="6"><h2>Sale Through Virtual Challan</h2></td>
				</tr>
		';
		$countsale_arraylasttime=mysqli_num_rows($odinsaleqry3);
		if($countsale_arraylasttime>0){
			foreach($odinsaleqry2 as $fetchproduct){
				$subcat='';
				if($fetchproduct['stc_sub_cat_name']=="OTHERS"){
					$subcat='';
				}else{
					$subcat=$fetchproduct['stc_sub_cat_name'];
				}
				$odin.='
					<tr>
						<td width="20%">'.$subcat.' '.$fetchproduct['stc_product_name'].'</td>
						<td align="center">STC/DC/'.substr("0000{$fetchproduct['stc_sale_product_id']}", -5).'</td>
						<td align="center" width="12%">'.date('d-m-Y', strtotime($fetchproduct['stc_sale_product_date'])).'</td>
						<td>'.$fetchproduct['stc_customer_name'].'</td>
						<td>'.$fetchproduct['stc_sale_product_sitename'].'</td>
						<td align="right">'.number_format($fetchproduct['stc_sale_product_dc_items_product_qty'], 2).'<br>'.$fetchproduct['stc_product_unit'].'</td>
						<td align="right">'.number_format($fetchproduct['stc_sale_product_dc_items_product_sale_rate'], 2).'</td>
		        	</tr>
				';
			}
		}else{
			$odin.='
				<tr>
					<td align="center" colspan="6">
						<a class="btn btn-primary btn-block text-uppercase">
                              No Record Found!!!
						</a>
                    </td>
				</tr>
			';
		}
		$odin.='
				</tbody>
			</table>
		';
		return $odin;
	}

	/*---------------------------- Edit product page-----------------------------*/
	// edit product function
	public function stc_update_product($product_id, $stcpdname, $stcpddsc, $stcpdcat, $stcpdsubcat, $stcpdhsncode, $stcpdpercentage, $stcpdunit, $stcpdgst, $stcpdbrand){
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT `stc_product_name` FROM `stc_product` 
			WHERE `stc_product_desc`		=	'".mysqli_real_escape_string($this->stc_dbs, $stcpddsc				)."'
			AND `stc_product_cat_id`		=	'".mysqli_real_escape_string($this->stc_dbs, $stcpdcat				)."'
			AND `stc_product_sub_cat_id`	=	'".mysqli_real_escape_string($this->stc_dbs, $stcpdsubcat			)."'
			AND `stc_product_brand_id`		=	'".mysqli_real_escape_string($this->stc_dbs, $stcpdbrand			)."'
			AND `stc_product_id`			<>	'".mysqli_real_escape_string($this->stc_dbs, $product_id			)."'
		");
		$count_loki_out=mysqli_num_rows($check_loki);
		if($count_loki_out == 0){
			$check_loki=mysqli_query($this->stc_dbs, "
				UPDATE 
					`stc_product` 
				SET 
					`stc_product_name`				='".mysqli_real_escape_string($this->stc_dbs, $stcpdname		)."',
					`stc_product_desc`				='".mysqli_real_escape_string($this->stc_dbs, $stcpddsc			)."',
					`stc_product_cat_id`			='".mysqli_real_escape_string($this->stc_dbs, $stcpdcat			)."',
					`stc_product_sub_cat_id`		='".mysqli_real_escape_string($this->stc_dbs, $stcpdsubcat		)."', 
					`stc_product_unit`				='".mysqli_real_escape_string($this->stc_dbs, $stcpdunit		)."',
					`stc_product_hsncode`			='".mysqli_real_escape_string($this->stc_dbs, $stcpdhsncode		)."',
					`stc_product_gst`				='".mysqli_real_escape_string($this->stc_dbs, $stcpdgst			)."',
					`stc_product_sale_percentage`	='".mysqli_real_escape_string($this->stc_dbs, $stcpdpercentage	)."',
					`stc_product_brand_id`			='".mysqli_real_escape_string($this->stc_dbs, $stcpdbrand		)."' 
				WHERE 
					`stc_product_id`				='".mysqli_real_escape_string($this->stc_dbs, $product_id 		)."'
			");
			if($check_loki){
				$odin = "Product Updated Successfully!!!";
			}else{
				$odin = "Please Check Records & try Again!!!";
			}
		}else{
			$odin = "This product aready in records!!!";
		}
		return $odin;
	}

	/*---------------------------- Add product supports page-----------------------------*/
	// rack insert to table
	public function rack_in($rack_name){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_rack` WHERE `stc_rack_name`='".strtoupper($rack_name)."'");
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$date=date('Y-m-d H:i:s');
			$loki_query=mysqli_query($this->stc_dbs, "INSERT INTO `stc_rack`(`stc_rack_name`, `stc_rack_status`, `stc_rack_created_by`, `stc_rack_created_date`) VALUES('".strtoupper($rack_name)."', '1', '".$_SESSION['stc_empl_id']."', '".$date."')");
			if($loki_query){
				$odin = $rack_name." Added!!";
			}else{
				$odin = "You need to try again!!";
			}
		}else{
			$odin = "This rack is already in record!!";
		}
		return $odin;
	}
	
	public function remove_rack($id){
		$check_loki=mysqli_query($this->stc_dbs, "DELETE FROM `stc_rack` WHERE `stc_rack_id`='".strtoupper($id)."'");
		if($check_loki){
			$odin = "Rack deleted.";
		}else{
			$odin = "This rack is already in record!!";
		}
		return $odin;
	}

	// category insert tot table
	public function cat_in($category_name){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_category` WHERE `stc_cat_name`='".strtoupper($category_name)."'");
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$loki_query=mysqli_query($this->stc_dbs, "INSERT INTO `stc_category`(`stc_cat_name`) VALUES('".strtoupper($category_name)."')");
			if($loki_query){
				$odin = $category_name." Added!!";
			}else{
				$odin = "You Need To Try Again!!";
			}
		}else{
			$odin = "This Category Is Already In Record!!";
		}
		return $odin;
	}

	// sub category insert to table
	public function sub_cat_in($sub_category_name){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_sub_category` WHERE `stc_sub_cat_name`='".strtoupper($sub_category_name)."'");
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$loki_query=mysqli_query($this->stc_dbs, "INSERT INTO `stc_sub_category`(`stc_sub_cat_name`) VALUES('".strtoupper($sub_category_name)."')");
			if($loki_query){
				$odin = $sub_category_name." Added!!";
			}else{
				$odin = "You need to try again!!";
			}
		}else{
			$odin = "This Sub Category Is Already In Record!!";
		}
		return $odin;
	}

	// brand name insert to table
	public function brand_name_in($brand_name){
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_brand` 
			WHERE `stc_brand_title`='".strtoupper($brand_name)."'
		");
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$loki_query=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_brand`(
					`stc_brand_title`
				) VALUES(
					'".strtoupper($brand_name)."'
				)
			");
			if($loki_query){
				$odin = $brand_name." Added!!";
			}else{
				$odin = "You need to try again!!";
			}
		}else{
			$odin = "This Brand Is Already In Record!!";
		}
		return $odin;
	}

	/*---------------------------- Update product image page-----------------------------*/
	// alter & update product image 
	public function stc_alter_pdimage($pd_id, $pdname){
		$odin='';
		$lokiupdatepd=mysqli_query($this->stc_dbs, "
			UPDATE `stc_product` SET `stc_product_image`='".$pdname."' WHERE `stc_product_id`='".$pd_id."'
		");
		if($lokiupdatepd){
			$odin="success";
		}else{
			$odin="Something went wrong. Please try again Later!!!";
		}
		return $odin;
	}

	// filter product by all
	public function search_pd_with_img($bjornefiltercatout, $bjornefiltersubcatout, $bjornefilternameout){
		$ivar='
			<table class="table table-hover" align="centre">
				<thead>
				  	<tr>
	            	  	<th width="70%">Name</th>
						<th width="20%">Image</th>
						<th width="10%">Change</th>
	            	</tr>
				</thead>
				<tbody>
		';
		$array = array(
			"bycat" => $bjornefiltercatout,
			"bysubcat" => $bjornefiltersubcatout,
			"byname" => $bjornefilternameout
		);
		$category='';
		$subcategory='';
		$productname='';
		foreach($array as $key => $value){
			if($array['bycat']!="NA"){
				$category="
					AND `stc_product_cat_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bycat'])."'
				";
			}

			if($array['bysubcat']!="NA"){
				$subcategory="
					AND `stc_product_sub_cat_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bysubcat'])."'
				";
			}

			if(!empty($array['byname'])){
				$productname="
					AND (`stc_product_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byname'])."'
					OR 
					`stc_product_desc` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byname'])."')
				";
			}
		}
		$endfilterqry='ORDER BY `stc_product_name` ASC';

		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT 				
				`stc_product_name`, 
				`stc_sub_cat_name`, 
				`stc_product_image`, 
				`stc_product_id` 
			FROM `stc_product` 
			LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
			LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
			LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
			LEFT JOIN `stc_item_inventory` ON `stc_item_inventory_pd_id`=`stc_product_id`
			LEFT JOIN `stc_brand` ON `stc_brand_id`=`stc_product_brand_id`
			WHERE `stc_product_avail`='1' 
			".$category.$subcategory.$productname.$endfilterqry
		);

		if(mysqli_num_rows($ivarfilterquery)>0){
			$brandname='';
			$subcat='';
			foreach($ivarfilterquery as $filterrow){
				$correct_stcpdname=strlen($filterrow["stc_product_name"]);
				$ivar.='
					<tr>
						<td>'.$subcat.' '.$filterrow["stc_product_name"].' '.$brandname.' </td>
						<td>
							<img src="../stc_symbiote/stc_product_image/'.$filterrow["stc_product_image"].'" height="60" width="75" class="img-thumbnail" />
						</td>
						<td><button type="button" name="update" class="btn btn-warning bt-xs update" id="'.$filterrow["stc_product_id"].'">Change</button></td>
						
					</tr>
				';
			}
		}else{
			$ivar .= '
				<tr>
					<td>No Records Found!!!</td>					
				</tr>
			';
		}

		$ivar.='
			</table>
		';
		return $ivar;
	}
}

#<------------------------------------------------------------------------------------------------------>
#<--------------------------------------Object sections of product class-------------------------------->
#<------------------------------------------------------------------------------------------------------>
// call for the product page
if(isset($_POST['friday'])){
	$fridaycat=new ragnarProduct();
	$fridaysubcat=new ragnarProduct();
	$fridayrack=new ragnarProduct();
	$fridaybrand=new ragnarProduct();

	$objfridaycat=$fridaycat->call_category();
	$objfridaysubcat=$fridaysubcat->call_sub_category();
	$objfridayrack=$fridayrack->call_rack();
	$objfridaybrand=$fridaybrand->call_brand();

	$mount=array($objfridaycat, $objfridaysubcat, $objfridayrack, $objfridaybrand);
	echo json_encode($mount);
}

// sudoop product to product table
if(isset($_POST['stc_add_product_hit'])){
	$stcpdname=strtoupper($_POST['stcpdname']);
	$stcpddsc=strtoupper($_POST['stcpddesc']);
	$stcpdcat=$_POST['stcpdcategory'];
	$stcpdsubcat=$_POST['stcpdsubcategory'];
	$stcpdhsn=$_POST['stcpdhsncode'];
	$stcpdpercentage=$_POST['stcpdpercentage'];
	$stcpdunit=strtoupper($_POST['stcpdunit']);
	$stcpdbrand=$_POST['stcpdbrand'];
	$stcpdstatus=1;
	$stcpdgst=$_POST['stcpdgst'];
	$stcpdimages=$_FILES['stcpdimage']['name'];
	$stcpdtmpname=$_FILES['stcpdimage']['tmp_name'];

	$adago=new ragnarProduct();

	// function calling
	if($stcpdcat=="NA" || $stcpdsubcat=="NA" || $stcpdunit=="NA" || $stcpdgst=="0"){
		echo "Sab field bhar pahle!!!";
	}else{
		$objadago=$adago->stc_product_hit($stcpdname, $stcpddsc, $stcpdcat, $stcpdsubcat, $stcpdhsn, $stcpdpercentage, $stcpdunit, $stcpdstatus, $stcpdgst, $stcpdimages, $stcpdbrand);

		if($objadago == "success"){	
			$stcassemble=move_uploaded_file($stcpdtmpname, "../../stc_symbiote/stc_product_image/".$stcpdimages);
			if($stcassemble){
				echo "Product's added!!";
			}else{
				echo "Product Uploaded Successfully but Except Images!!!";
			}
		}else{
			echo $objadago;
		}
	}
}

// search product by name by category by sub category by brand
if(isset($_POST['search_alo_in'])){
	$category=$_POST['stcpdcategory'];
	$subcategory=$_POST['stcpdsubcategory'];
	$pdname=$_POST['stcsearchpdname'];
	$out='';
	if($category=="NA" || $subcategory=="NA" || empty($pdname)){
		$out='
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				<a 
			      href="#" 
			      class="btn btn-primary btn-block text-uppercase mb-3">
			      Do not let any field empty!!!
			    </a>
			</div>
		';
	}elseif(strlen($pdname)<=3){
		$out='
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				<a 
			      href="#" 
			      class="btn btn-primary btn-block text-uppercase mb-3">
			      Enter At least a word!!!
			    </a>
			</div>
		';
	}else{
		$objloki=new ragnarProduct();
		$objlokiout=$objloki->stc_search_product_name($pdname, $category, $subcategory);
		$out.=$objlokiout;
	}
	// echo json_encode($out);
	echo $out;
}

#<--------------------------------------Filterations------------------------------------------>

// search by same
if(isset($_POST['stcaction'])){
	$bjornefiltercatout=$_POST['phpfiltercatout'];
	$bjornefiltersubcatout=$_POST['phpfiltersubcatout'];
	$bjornefilternameout=$_POST['phpfilternameout'];
	$out='';
	$objpdres=new ragnarProduct();	
	$opobjpdres=$objpdres->stc_getpd_by_multiple_inp($bjornefiltercatout, $bjornefiltersubcatout, $bjornefilternameout);
	$out=$opobjpdres;
	echo $out;
}

// search single item purchase
if(isset($_POST['jsfilterprobypoidout'])){
	$pd_id=$_POST['jsfilterprobyid'];
	$objpdres=new ragnarProduct();
	$outobjpdres=$objpdres->purchase_record_per_pd($pd_id);
	// echo $outobjpdres;
	echo json_encode($outobjpdres);
}

// search single item sale
if(isset($_POST['jsfilterprobysoidout'])){
	$pd_id=$_POST['jsfilterprobyid'];
	$objpdres=new ragnarProduct();
	$outobjpdres=$objpdres->sale_record_per_pd($pd_id);
	// echo $outobjpdres;
	echo json_encode($outobjpdres);
}

#<--------------------------------------Edited Product objects------------------------------------->

// edit product page
if(isset($_POST['stc_edit_product_hit'])){
	$out='';
	$product_id=$_POST['stc_set_product_id'];
	$stcpdname=strtoupper($_POST['stcpdname']);
	$stcpddsc=strtoupper($_POST['stcpddesc']);
	$stcpdcat=$_POST['stcpdcategory'];
	$stcpdsubcat=$_POST['stcpdsubcategory'];
	$stcpdhsncode=$_POST['stcpdhsncode'];
	$stcpdpercentage=$_POST['stcpdpercentage'];
	$stcpdunit=strtoupper($_POST['stcpdunit']);
	$stcpdbrand=strtoupper($_POST['stcpdbrand']);
	$stcpdgst=$_POST['stcpdgst'];
	$objthor=new ragnarProduct();
	if($stcpdcat=="NA" || $stcpdsubcat=="NA" || empty($stcpdname) || empty($stcpddsc)){
		$out="Kid!!! Don't let any field empty otherwise i will cursed you out.";
	}else{
		$objthorout=$objthor->stc_update_product($product_id, $stcpdname, $stcpddsc, $stcpdcat, $stcpdsubcat, $stcpdhsncode, $stcpdpercentage, $stcpdunit, $stcpdgst, $stcpdbrand);
		$out=$objthorout;
	}
	echo json_encode($objthorout);
}

#<--------------------------------------Add Product support objects-------------------------------->
// rack hit
if(isset($_POST['rack_hit'])){
	$rack_name=$_POST['rack_name'];
	$objloki=new ragnarProduct();
	$objlokiout=$objloki->rack_in($rack_name);
	echo json_encode($objlokiout);
}

// rack hit
if(isset($_POST['remove_rack'])){
	$id=$_POST['id'];
	$objloki=new ragnarProduct();
	$objlokiout=$objloki->remove_rack($id);
	echo json_encode($objlokiout);
}

// category hit
if(isset($_POST['category_hit'])){
	$category_name=$_POST['category_name'];
	$objloki=new ragnarProduct();
	$objlokiout=$objloki->cat_in($category_name);
	echo json_encode($objlokiout);
}

// sub category hit
if(isset($_POST['sub_category_hit'])){
	$sub_category_name=$_POST['sub_category_name'];

	$objloki=new ragnarProduct();
	$objlokiout=$objloki->sub_cat_in($sub_category_name);
	echo json_encode($objlokiout);
}

// brand hit
if(isset($_POST['brand_hit'])){
	$brand_name=$_POST['brand_name'];

	$objloki=new ragnarProduct();
	$objlokiout=$objloki->brand_name_in($brand_name);
	echo json_encode($objlokiout);
}

#<--------------------------------------Update product image objects------------------------------>
// update product image
if(isset($_POST['image_id'])){
	$pd_id=$_POST['image_id'];
	$pdname=$_FILES['image']['name'];
	$pdtmp_name=$_FILES['image']['tmp_name'];
	$adago=new ragnarProduct();
	$objadago=$adago->stc_alter_pdimage($pd_id, $pdname);

	if($objadago == "success"){	
		$stcassemble=move_uploaded_file($pdtmp_name, "../../stc_symbiote/stc_product_image/".$pdname);
		if($stcassemble){
			echo "Product image updated!!!";
		}else{
			echo "Please check and try again!!!";
		}
	}else{
		echo $objadago;
	}
}

// search by same
if(isset($_POST['pdimg_sear'])){
	$bjornefiltercatout=$_POST['phpfiltercatout'];
	$bjornefiltersubcatout=$_POST['phpfiltersubcatout'];
	$bjornefilternameout=$_POST['phpfilternameout'];
	$out='';
	$objpdres=new ragnarProduct();	
	$opobjpdres=$objpdres->search_pd_with_img($bjornefiltercatout, $bjornefiltersubcatout, $bjornefilternameout);
	$out=$opobjpdres;
	echo $out;
}
?>