<?php
include "../../MCU/obdb.php";
class sceptor extends tesseract{	
	public function stc_rack(){
		$result=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_rack`");
		$rackcount=mysqli_num_rows($result);
		return $rackcount;
	}

	public function stc_categories(){
		$result=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_category`");
		$catcount=mysqli_num_rows($result);
		return $catcount;
	}

	public function stc_sub_categories(){
		$result=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_sub_category`");
		$subcatcount=mysqli_num_rows($result);
		return $subcatcount;
	}

	public function stc_product(){
		$result=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_product`");
		$productcount=mysqli_num_rows($result);
		return $productcount;
	}

	public function stc_inventory(){
		$result=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_item_inventory` WHERE `stc_item_inventory_pd_qty`!=0");
		$productcount=mysqli_num_rows($result);
		return $productcount;
	}

	public function stc_brand(){
		$result=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_brand`");
		$brandcount=mysqli_num_rows($result);
		return $brandcount;
	}
}

class aether extends tesseract{	
	// insert rack category & sub category
	public function rack_in($rack_name){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_rack` WHERE `stc_rack_name`='".strtoupper($rack_name)."'");
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$loki_query=mysqli_query($this->stc_dbs, "INSERT INTO `stc_rack`(`stc_rack_name`) VALUES('".strtoupper($rack_name)."')");
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

	// data show in product section
	public function call_category(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_category` ORDER BY `stc_cat_name` ASC");
		$odin='<option value="NA" selected>Select Category</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option value='0' selected>No Category Found!!!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
							<option value="'.$row["stc_cat_id"].'">'.$row["stc_cat_name"].'</option>		               	
		            	';				
			}
			
		}
		return $odin;
	}

	public function call_sub_category(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_sub_category`");
		$odin='<option value="NA" selected>Select Sub Category</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option value='0' selected>No Category Found!!!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
							<option value="'.$row["stc_sub_cat_id"].'">'.$row["stc_sub_cat_name"].'</option>		               	
		            	';				
			}
			
		}
		return $odin;
	}

	public function call_rack(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_rack`");
		$odin='<option value="0" selected>Select Rack</option>';
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

	// add data from product section
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
					`stc_product_brand_id`
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
					'".mysqli_real_escape_string($this->stc_dbs, $stcpdbrand)."'
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

	public function call_pro_on_view(){
		$alagsequery="
					SELECT * FROM `stc_product` 
					LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
					LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
					LEFT JOIN `stc_item_inventory` ON `stc_item_inventory_pd_id`=`stc_product_id`
					LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` ORDER BY `stc_product_id` ASC
					";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';               
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$correct_stcpdname=strlen($row["stc_product_name"]);
				$stcqty=$row["stc_item_inventory_pd_qty"];
				if($stcqty==0){
					$stcqty=0;
				}else{
					$stcqty;
				}
				$odin.='	
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
		             	  <div class="row">
		             	    <div class="el-wrapper">
		             	      <div class="box-up">
		             	        <img class="img" style="height: 100%; width:100%;" src="stc_product_image/'.$row["stc_product_image"].'" alt="">
		             	        <div class="img-info">
		             	          <div class="info-inner">
		             	            <span class="p-name">'.$row["stc_product_name"].'</span>
		             	            <span class="p-company">'.$row["stc_cat_name"].'</span>
		             	            <span class="p-qty" >'.$stcqty.' '.$row["stc_product_unit"].'</span>
		             	          </div>
		             	          <div class="a-size">Available on Rack : <span class="size">'.$row["stc_rack_name"].'</span></div>
		             	        </div>
		             	      </div>

		             	      <div class="box-down">
		             	        <div class="h-bg">
		             	          <div class="h-bg-inner"></div>
		             	        </div>
                				<a class="cart viewedit" id="'.$row["stc_product_id"].'" href="edit-product.php?pdid='.$row["stc_product_id"].'">
		             	          <span class="price">'.$row["stc_sub_cat_name"].'</span>
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
			$odin .= '
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
						<a 
			              href="#" 
			              class="btn btn-primary btn-block text-uppercase mb-3">
			              No Records Found!!!
			            </a>
					</div>

					';
		}
		$odin.='';
		return $odin;
	}
	
	public function stc_search_product_name($searchme){
		$perfectsearchme=strtoupper($searchme);
		$alagsequery="
					SELECT DISTINCT * FROM `stc_product` 
					LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
					LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
					LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
					LEFT JOIN `stc_item_inventory` ON `stc_item_inventory_pd_id`=`stc_product_id`
					LEFT JOIN `stc_brand` ON `stc_brand_id`=`stc_product_brand_id`
					WHERE `stc_product_name` REGEXP '".$perfectsearchme."' 
					OR `stc_product_desc` REGEXP '".$perfectsearchme."'
					ORDER BY `stc_product_id` ASC
					LIMIT 0,50
					";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';               
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$correct_stcpdname=strlen($row["stc_product_name"]);
				$stcqty=$row["stc_item_inventory_pd_qty"];
				if($stcqty==0){
					$stcqty=0;
				}else{
					$stcqty;
				}
				$odin.='	
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
		             	  <div class="row">
		             	    <div class="el-wrapper">
		             	      <div class="box-up">
		             	        <img class="img" style="height: 100%; width:100%;" src="stc_product_image/'.$row["stc_product_image"].'" alt="">
		             	        <div class="img-info">
		             	          <div class="info-inner">
		             	            <span class="p-name">'.$row["stc_product_name"].'</span>
		             	            <span class="p-company">
		             	            	'.$row["stc_product_id"].' /
		             	            	'.$row["stc_cat_name"].' / 
		             	            	'.$row["stc_product_hsncode"].' / 
		             	            	'.$row["stc_product_gst"].'%
		             	            </span>
		             	            <span class="p-qty" >'.$stcqty.' '.$row["stc_product_unit"].'</span>
		             	          </div>
		             	          <div class="a-size">Available on Rack : <span class="size">'.$row["stc_rack_name"].'</span></div>
		             	        </div>
		             	      </div>

		             	      <div class="box-down">
		             	        <div class="h-bg">
		             	          <div class="h-bg-inner"></div>
		             	        </div>

		             	        <a class="cart viewedit" id="'.$row["stc_product_id"].'" href="edit-product.php?pdid='.$row["stc_product_id"].'">
		             	          <span class="price">'.$row["stc_sub_cat_name"].' / '.$row["stc_brand_title"].'</span>
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
			$odin .= '
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
						<a 
			              href="#" 
			              class="btn btn-primary btn-block text-uppercase mb-3">
			              No Records Found!!!
			            </a>
					</div>

					';
		}
		$odin.='';
		return $odin;
	}

	// filter data
	// by rack function
	public function stc_search_product_by_rack($filterbyrack){
		$alagsequery="
					SELECT DISTINCT * FROM `stc_product` 
					LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
					LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
					LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
					LEFT JOIN `stc_item_inventory` ON `stc_item_inventory_pd_id`=`stc_product_id`
					LEFT JOIN `stc_brand` ON `stc_brand_id`=`stc_product_brand_id`
					WHERE `stc_product_rack_id`='".$filterbyrack."' ORDER BY `stc_product_id` ASC
					";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';               
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$correct_stcpdname=strlen($row["stc_product_name"]);
				$stcqty=$row["stc_item_inventory_pd_qty"];
				if($stcqty==0){
					$stcqty=0;
				}else{
					$stcqty;
				}
				$odin.='	
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
		             	  <div class="row">
		             	    <div class="el-wrapper">
		             	      <div class="box-up">
		             	        <img class="img" style="height: 100%; width:100%;" src="stc_product_image/'.$row["stc_product_image"].'" alt="">
		             	        <div class="img-info">
		             	          <div class="info-inner">
		             	            <span class="p-name">'.$row["stc_product_name"].'</span>
		             	            <span class="p-company">
		             	            	'.$row["stc_product_id"].' /
		             	            	'.$row["stc_cat_name"].' / 
		             	            	'.$row["stc_product_hsncode"].' / 
		             	            	'.$row["stc_product_gst"].'%
		             	            </span>
		             	            <span class="p-qty" >'.$stcqty.' '.$row["stc_product_unit"].'</span>
		             	          </div>
		             	          <div class="a-size">Available on Rack : <span class="size">'.$row["stc_rack_name"].'</span></div>
		             	        </div>
		             	      </div>

		             	      <div class="box-down">
		             	        <div class="h-bg">
		             	          <div class="h-bg-inner"></div>
		             	        </div>

		             	        <a class="cart viewedit" id="'.$row["stc_product_id"].'" href="edit-product.php?pdid='.$row["stc_product_id"].'">
		             	          <span class="price">'.$row["stc_sub_cat_name"].' / '.$row["stc_brand_title"].'</span>
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
			$odin .= '
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
						<a 
			              href="#" 
			              class="btn btn-primary btn-block text-uppercase mb-3">
			              No Records Found!!!
			            </a>
					</div>

					';
		}
		$odin.='';
		return $odin;
	}

	// by cat function
	public function stc_search_product_by_cat($filterbycat){
		$alagsequery="
					SELECT DISTINCT * FROM `stc_product` 
					LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
					LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
					LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
					LEFT JOIN `stc_item_inventory` ON `stc_item_inventory_pd_id`=`stc_product_id`
					LEFT JOIN `stc_brand` ON `stc_brand_id`=`stc_product_brand_id`
					WHERE `stc_product_cat_id`='".$filterbycat."' ORDER BY `stc_product_id` ASC
					";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';               
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$correct_stcpdname=strlen($row["stc_product_name"]);
				$stcqty=$row["stc_item_inventory_pd_qty"];
				if($stcqty==0){
					$stcqty=0;
				}else{
					$stcqty;
				}
				$odin.='	
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
		             	  <div class="row">
		             	    <div class="el-wrapper">
		             	      <div class="box-up">
		             	        <img class="img" style="height: 100%; width:100%;" src="stc_product_image/'.$row["stc_product_image"].'" alt="">
		             	        <div class="img-info">
		             	          <div class="info-inner">
		             	            <span class="p-name">'.$row["stc_product_name"].'</span>
		             	            <span class="p-company">
		             	            	'.$row["stc_product_id"].' /
		             	            	'.$row["stc_cat_name"].' / 
		             	            	'.$row["stc_product_hsncode"].' / 
		             	            	'.$row["stc_product_gst"].'%
		             	            </span>
		             	            <span class="p-qty" >'.$stcqty.' '.$row["stc_product_unit"].'</span>
		             	          </div>
		             	          <div class="a-size">Available on Rack : <span class="size">'.$row["stc_rack_name"].'</span></div>
		             	        </div>
		             	      </div>

		             	      <div class="box-down">
		             	        <div class="h-bg">
		             	          <div class="h-bg-inner"></div>
		             	        </div>

		             	        <a class="cart viewedit" id="'.$row["stc_product_id"].'" href="edit-product.php?pdid='.$row["stc_product_id"].'">
		             	          <span class="price">'.$row["stc_sub_cat_name"].' / '.$row["stc_brand_title"].'</span>
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
			$odin .= '
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
						<a 
			              href="#" 
			              class="btn btn-primary btn-block text-uppercase mb-3">
			              No Records Found!!!
			            </a>
					</div>
					';
		}
		$odin.='';
		return $odin;
	}

	// by sub cat function
	public function stc_search_product_by_sub_cat($fiterbysubcat){
		$alagsequery="
					SELECT DISTINCT * FROM `stc_product` 
					LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
					LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
					LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
					LEFT JOIN `stc_item_inventory` ON `stc_item_inventory_pd_id`=`stc_product_id`
					LEFT JOIN `stc_brand` ON `stc_brand_id`=`stc_product_brand_id`
					WHERE `stc_product_sub_cat_id`='".$fiterbysubcat."' ORDER BY `stc_product_id` ASC
					";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';               
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$correct_stcpdname=strlen($row["stc_product_name"]);
				$stcqty=$row["stc_item_inventory_pd_qty"];
				if($stcqty==0){
					$stcqty=0;
				}else{
					$stcqty;
				}
				$odin.='	
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
		             	  <div class="row">
		             	    <div class="el-wrapper">
		             	      <div class="box-up">
		             	        <img class="img" style="height: 100%; width:100%;" src="stc_product_image/'.$row["stc_product_image"].'" alt="">
		             	        <div class="img-info">
		             	          <div class="info-inner">
		             	            <span class="p-name">'.$row["stc_product_name"].'</span>
		             	            <span class="p-company">
		             	            	'.$row["stc_product_id"].' /
		             	            	'.$row["stc_cat_name"].' / 
		             	            	'.$row["stc_product_hsncode"].' / 
		             	            	'.$row["stc_product_gst"].'%
		             	            </span>
		             	            <span class="p-qty" >'.$stcqty.' '.$row["stc_product_unit"].'</span>
		             	          </div>
		             	          <div class="a-size">Available on Rack : <span class="size">'.$row["stc_rack_name"].'</span></div>
		             	        </div>
		             	      </div>

		             	      <div class="box-down">
		             	        <div class="h-bg">
		             	          <div class="h-bg-inner"></div>
		             	        </div>

		             	        <a class="cart viewedit" id="'.$row["stc_product_id"].'" href="edit-product.php?pdid='.$row["stc_product_id"].'">
		             	          <span class="price">'.$row["stc_sub_cat_name"].' / '.$row["stc_brand_title"].'</span>
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
			$odin .= '
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
						<a 
			              href="#" 
			              class="btn btn-primary btn-block text-uppercase mb-3">
			              No Records Found!!!
			            </a>
					</div>
					';
		}
		$odin.='';
		return $odin;
	}

	// edit product function
	public function stc_update_product($product_id, $stcpdname, $stcpddsc, $stcpdcat, $stcpdsubcat, $stcpdhsncode, $stcpdpercentage, $stcpdunit, $stcpdgst, $stcpdbrand){
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_product` 
			WHERE `stc_product_name`		=	'".mysqli_real_escape_string($this->stc_dbs, $stcpdname				)."' 
			AND `stc_product_desc`			=	'".mysqli_real_escape_string($this->stc_dbs, $stcpddsc				)."'
			AND `stc_product_cat_id`		=	'".mysqli_real_escape_string($this->stc_dbs, $stcpdcat				)."'
			AND `stc_product_sub_cat_id`	=	'".mysqli_real_escape_string($this->stc_dbs, $stcpdsubcat			)."'
			AND `stc_product_brand_id`		=	'".mysqli_real_escape_string($this->stc_dbs, $stcpdbrand			)."'
			AND `stc_product_id`			=	'".mysqli_real_escape_string($this->stc_dbs, $product_id			)."'
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


	/*----------------------------------------------- merchant page functions -----------------------------------------*/
	public function stc_vendor_hit($stcmername, $stcmeraddress, $stcmercity, $stcmerstate, $stcmercontperson, $stcmeremail, $stcmercontnumber, $stcmerskf, $stcmerpan, $stcmergst, $stcmerimages){
		$stc_filter_add_merchant=mysqli_real_escape_string($this->stc_dbs, $stcmername);
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_merchant` WHERE `stc_merchant_name`='".$stc_filter_add_merchant."'");
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

	// by merchant name
	public function stc_search_merchant_byname($searchmebyname){
		$perfectsearchme=strtoupper($searchmebyname);
		$alagsequery="
					SELECT DISTINCT * FROM `stc_merchant` 
					LEFT JOIN `stc_state` ON `stc_state_id`=`stc_merchant_state_id` 
					LEFT JOIN `stc_city` ON `stc_city_id`=`stc_merchant_city_id` 
					WHERE `stc_merchant_name` REGEXP '".$searchmebyname."' ORDER BY `stc_merchant_id` ASC
					";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';               
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$correct_stcpdname=strlen($row["stc_merchant_name"]);
				$odin.='	
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
			             	<div style="padding: 15px; background-color: #fff;border-radius:20px;">
			             		<div class="row">
				             	  	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				             	  		<h5>Merchant Name - '.$row['stc_merchant_name'].'</h5>
				             	  	</div>
				             	  	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				             	  		<p>Address - '.$row['stc_merchant_address'].'</p>
				             	  	</div>
				             	  	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
				             	  		<p>Email - '.$row['stc_merchant_email'].'</p>
				             	  	</div>
				             	  	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
				             	  		<p>Contact - '.$row['stc_merchant_phone'].'</p>
				             	  	</div>
				             	  	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
				             	  		<p>GSTIN - '.$row['stc_merchant_gstin'].'</p>
				             	  	</div>
			             	 	</div>
			             	</div>
		             	</div>
							               	
		        ';				
			}
		}else{
			$odin .= '
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
						<a 
			              href="#" 
			              class="btn btn-primary btn-block text-uppercase mb-3">
			              No Records Found!!!
			            </a>
					</div>

					';
		}
		$odin.='';
		return $odin;
	}

	// by merchant skf
	public function stc_search_merchant_byskf($searchmebyskf){
		$perfectsearchme=strtoupper($searchmebyskf);
		$alagsequery="
					SELECT DISTINCT * FROM `stc_merchant` 
					LEFT JOIN `stc_state` ON `stc_state_id`=`stc_merchant_state_id` 
					LEFT JOIN `stc_city` ON `stc_city_id`=`stc_merchant_city_id` 
					WHERE `stc_merchant_specially_known_for` REGEXP '".$searchmebyskf."' ORDER BY `stc_merchant_id` ASC
					";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';               
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$correct_stcpdname=strlen($row["stc_merchant_name"]);
				$odin.='	
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
			             	<div style="padding: 15px; background-color: #fff;border-radius:20px;">
			             		<div class="row">
				             	  	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				             	  		<h5>Merchant Name - '.$row['stc_merchant_name'].'</h5>
				             	  	</div>
				             	  	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				             	  		<p>Address - '.$row['stc_merchant_address'].'</p>
				             	  	</div>
				             	  	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
				             	  		<p>Email - '.$row['stc_merchant_email'].'</p>
				             	  	</div>
				             	  	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
				             	  		<p>Contact - '.$row['stc_merchant_phone'].'</p>
				             	  	</div>
				             	  	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
				             	  		<p>GSTIN - '.$row['stc_merchant_gstin'].'</p>
				             	  	</div>
			             	 	</div>
			             	</div>
		             	</div>
							               	
		            	';				
			}
		}else{
			$odin .= '
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
						<a 
			              href="#" 
			              class="btn btn-primary btn-block text-uppercase mb-3">
			              No Records Found!!!
			            </a>
					</div>

					';
		}
		$odin.='';
		return $odin;
	}


	/*--------------------------------------------- Customer page functions -----------------------------------------*/
	public function stc_customer_hit($stccustname, $stccustaddress, $stccustcity, $stccuststate, $stccustcontperson, $stccustemail, $stccustcontnumber, $stccustskf, $stccustpan, $stccustgst, $stccustimages){
		$stc_filter_add_customer=mysqli_real_escape_string($this->stc_dbs, $stccustname);
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_customer` WHERE `stc_customer_name`='".$stc_filter_add_customer."'");
		$count_loki_out=mysqli_num_rows($check_loki);
		if($count_loki_out == 0){
			$loki_query=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_customer`(
					`stc_customer_name`, 
					`stc_customer_address`, 
					`stc_customer_city_id`, 
					`stc_customer_state_id`,
					`stc_customer_contact_person`, 
					`stc_customer_email`, 
					`stc_customer_phone`, 
					`stc_customer_pan`, 
					`stc_customer_gstin`, 
					`stc_customer_speciallyknownfor`, 
					`stc_customer_image`
				) 
				VALUES(
					'".$stc_filter_add_customer."', 
					'".mysqli_real_escape_string($this->stc_dbs, $stccustaddress)."', 
					'".$stccustcity."', 
					'".$stccuststate."', 
					'".mysqli_real_escape_string($this->stc_dbs, $stccustcontperson)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $stccustemail)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $stccustcontnumber)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $stccustpan)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $stccustgst)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $stccustskf)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $stccustimages)."'
				)
				");
			if($loki_query){
				$odin = "success";
			}else{
				$odin = "You need to try again!!";
			}
		}else{
			$odin = "This Product is Already in Record!!";
		}
		return $odin;
	}

	// by customer name
	public function stc_search_customer_byname($searchmebyname){
		$perfectsearchme=strtoupper($searchmebyname);
		$alagsequery="
					SELECT DISTINCT * FROM `stc_customer` 
					LEFT JOIN `stc_state` ON `stc_state_id`=`stc_customer_state_id` 
					LEFT JOIN `stc_city` ON `stc_city_id`=`stc_customer_city_id` 
					WHERE `stc_customer_name` REGEXP '".$searchmebyname."' ORDER BY `stc_customer_id` ASC
					";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';               
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$correct_stcpdname=strlen($row["stc_customer_name"]);
				$odin.='	
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
			             	<div style="padding: 15px; background-color: #fff;border-radius:20px;margin-top:15px;">
			             		<div class="row">
				             	  	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				             	  		<h5>Customer Name - '.$row['stc_customer_name'].'</h5>
				             	  	</div>
				             	  	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				             	  		<p>Address - '.$row['stc_customer_address'].'</p>
				             	  	</div>
				             	  	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
				             	  		<p>Email - '.$row['stc_customer_email'].'</p>
				             	  	</div>
				             	  	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
				             	  		<p>Contact - '.$row['stc_customer_phone'].'</p>
				             	  	</div>
				             	  	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
				             	  		<p>GSTIN - '.$row['stc_customer_gstin'].'</p>
				             	  	</div>
			             	 	</div>
			             	</div>
		             	</div>
							               	
		        ';				
			}
		}else{
			$odin .= '
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
						<a 
			              href="#" 
			              class="btn btn-primary btn-block text-uppercase mb-3">
			              No Records Found!!!
			            </a>
					</div>

					';
		}
		$odin.='';
		return $odin;
	}

	// by Customer skf
	public function stc_search_customer_byskf($searchmebyskf){
		$perfectsearchme=strtoupper($searchmebyskf);
		$alagsequery="
					SELECT DISTINCT * FROM `stc_customer` 
					LEFT JOIN `stc_state` ON `stc_state_id`=`stc_customer_state_id` 
					LEFT JOIN `stc_city` ON `stc_city_id`=`stc_customer_city_id` 
					WHERE `stc_customer_speciallyknownfor` REGEXP '".$searchmebyskf."' ORDER BY `stc_customer_id` ASC
					";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';               
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$correct_stcpdname=strlen($row["stc_merchant_name"]);
				$odin.='	
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
			             	<div style="padding: 15px; background-color: #fff;border-radius:20px;margin-top:8px;">
			             		<div class="row">
				             	  	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				             	  		<h5>Customer Name - '.$row['stc_customer_name'].'</h5>
				             	  	</div>
				             	  	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				             	  		<p>Address - '.$row['stc_customer_address'].'</p>
				             	  	</div>
				             	  	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
				             	  		<p>Email - '.$row['stc_customer_email'].'</p>
				             	  	</div>
				             	  	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
				             	  		<p>Contact - '.$row['stc_customer_phone'].'</p>
				             	  	</div>
				             	  	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
				             	  		<p>GSTIN - '.$row['stc_customer_gstin'].'</p>
				             	  	</div>
			             	 	</div>
			             	</div>
		             	</div>
							               	
		            	';				
			}
		}else{
			$odin .= '
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
						<a 
			              href="#" 
			              class="btn btn-primary btn-block text-uppercase mb-3">
			              No Records Found!!!
			            </a>
					</div>

					';
		}
		$odin.='';
		return $odin;
	}

	public function call_pd_with_img(){
		$slno=0;
		$subcat='';
		$rackname='';
		$result=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_product`
			LEFT JOIN `stc_sub_category`
			ON `stc_product_sub_cat_id`=`stc_sub_cat_id` ORDER BY`stc_product`.`stc_product_id` ASC
		");
		$productcount = '
			<table class="table table-hover" align="centre">
				<thead>
				  	<tr>
	            	  	<th width="70%">NAME</th>
						<th width="20%">Image</th>
						<th width="10%">Change</th>
	            	</tr>
				</thead>
				<tbody>
		';
		foreach($result as $row){
			if($row["stc_sub_cat_name"] == 'OTHERS'){
				$subcat='';
			}else{
				$subcat=$row["stc_sub_cat_name"];
			}
			$slno++;
			$productcount .= '
					<tr>
						<td>'.$subcat.' '.$row["stc_product_name"].'</td>
						<td>
							<img src="stc_product_image/'.$row["stc_product_image"].'" height="60" width="75" class="img-thumbnail" />
						</td>
						<td><button type="button" name="update" class="btn btn-warning bt-xs update" id="'.$row["stc_product_id"].'">Change</button></td>
						
					</tr>
			';
		}

		$productcount .= '
			</table>
		';
		return $productcount;
	}

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

	public function search_pd_with_img($productname){
		$slno=0;
		$subcat='';
		$rackname='';
		$result=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_product_name`, 
				`stc_sub_cat_name`, 
				`stc_product_image`, 
				`stc_product_id` 
			FROM `stc_product`
			LEFT JOIN `stc_sub_category`
			ON `stc_product_sub_cat_id`=`stc_sub_cat_id` 
			WHERE `stc_product_name` REGEXP '".$productname."' 
			OR `stc_product_desc` REGEXP '".$productname."'
			ORDER BY`stc_product`.`stc_product_id` ASC
		");
		$productcount = '
			<table class="table table-hover" align="centre">
				<thead>
				  	<tr>
	            	  	<th width="70%">NAME</th>
						<th width="20%">Image</th>
						<th width="10%">Change</th>
	            	</tr>
				</thead>
				<tbody>
		';
		if($count=mysqli_num_rows($result)==0){
			$productcount .= '
						<tr>
							<td>No Records Found!!!</td>
							
						</tr>
				';
		}else{
			foreach($result as $row){
				if($row["stc_sub_cat_name"] == 'OTHERS'){
					$subcat='';
				}else{
					$subcat=$row["stc_sub_cat_name"];
				}
				$slno++;
				$productcount .= '
						<tr>
							<td>'.$subcat.' '.$row["stc_product_name"].'</td>
							<td>
								<img src="stc_product_image/'.$row["stc_product_image"].'" height="60" width="75" class="img-thumbnail" />
							</td>
							<td><button type="button" name="update" class="btn btn-warning bt-xs update" id="'.$row["stc_product_id"].'">Change</button></td>
							
						</tr>
				';
			}
		}
		$productcount .= '
			</table>
		';
		return $productcount;
	}

	public function stc_customer_user_regitration($stccustusername, $stccustuseremail, $stccustusercontact, $stccustuserpincode, $stccustuseraddress, $stccustusercity, $stccustuserstate, $userid, $sup_password){
		$odin='';
		$lokigetuserqry=mysqli_query($this->stc_dbs,"
			SELECT `stc_agents_name` FROM `stc_agents`
			WHERE `stc_agents_email`='".mysqli_real_escape_string($this->stc_dbs, $stccustuseremail)."' OR 
				`stc_agents_contact`='".mysqli_real_escape_string($this->stc_dbs, $stccustusercontact)."'
		");
		if(mysqli_num_rows($lokigetuserqry)>0){
			$odin="User Already in Records!!!";
		}else{
			$lokigotocustuserquery=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_agents`(
					`stc_agents_name`, 
					`stc_agents_email`, 
					`stc_agents_contact`, 
					`stc_agents_address`, 
					`stc_agents_state_id`, 
					`stc_agents_city_id`, 
					`stc_agents_pincode`, 
					`stc_agents_userid`, 
					`stc_agents_pass`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $stccustusername)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stccustuseremail)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stccustusercontact)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stccustuseraddress)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stccustuserstate)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stccustusercity)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stccustuserpincode)."',
					'".mysqli_real_escape_string($this->stc_dbs, $userid)."',
					'".mysqli_real_escape_string($this->stc_dbs, $sup_password)."'
				)
			");
			if($lokigotocustuserquery){
				$odin="success";
			}else{
				$odin="not";
			}
		}
		return $odin;
	}

	public function search_uset_to_customer_set($stcuserid, $stccustid){
		$odin='';
		$lokicheck=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_agent_requested_customer` 
			WHERE `stc_agent_requested_customer_cust_id`='".$stccustid."'
			AND `stc_agent_requested_customer_agent_id`='".$stcuserid."'
		");
		if(mysqli_num_rows($lokicheck)>0){
			$odin="This Customer Already Set to User!!!";
		}else{
			$lokiletsgorequested=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_agent_requested_customer`(
					`stc_agent_requested_customer_cust_id`, 
					`stc_agent_requested_customer_agent_id`
				) VALUES (
					'".$stccustid."',
					'".$stcuserid."'
				)
			");
			if($lokiletsgorequested){
				$odin="Customer Is Set To This User:)";
			}else{
				$odin="Something Went Wrong!!!";
			}
		}
		return $odin;
	}
}

class eyeofagamotto extends tesseract{
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
}
#<--------------------------------------------------------------------->
#<----------------------------Object Section--------------------------->
#<--------------------------------------------------------------------->

if(isset($_POST["mask"])){	
	$objrack=new sceptor();
	$objcat=new sceptor();
	$objsubcat=new sceptor();
	$objproduct=new sceptor();
	$objinventory=new sceptor();
	$objbrand=new sceptor();

	$opobjrack=$objrack->stc_rack();
	$opobjcat=$objcat->stc_categories();
	$opobjsubcat=$objsubcat->stc_sub_categories();
	$opobjproduct=$objproduct->stc_product();
	$opobjinentory=$objinventory->stc_inventory();
	$opobjbrand=$objbrand->stc_brand();

	$cursedyouout=array($opobjrack, $opobjcat, $opobjsubcat, $opobjproduct, $opobjinentory, $opobjbrand);

	echo json_encode($cursedyouout);
}

// show on the badge
// rack upload
if(isset($_POST['rack_hit'])){
	$rack_name=$_POST['rack_name'];
	$objloki=new aether();
	$objlokiout=$objloki->rack_in($rack_name);
	echo json_encode($objlokiout);
}

// category upload
if(isset($_POST['category_hit'])){
	$category_name=$_POST['category_name'];
	$objloki=new aether();
	$objlokiout=$objloki->cat_in($category_name);
	echo json_encode($objlokiout);
}

// sub category upload
if(isset($_POST['sub_category_hit'])){
	$sub_category_name=$_POST['sub_category_name'];

	$objloki=new aether();
	$objlokiout=$objloki->sub_cat_in($sub_category_name);
	echo json_encode($objlokiout);
}

// brand hit
if(isset($_POST['brand_hit'])){
	$brand_name=$_POST['brand_name'];

	$objloki=new aether();
	$objlokiout=$objloki->brand_name_in($brand_name);
	echo json_encode($objlokiout);
}

// call to the product page
if(isset($_POST['friday'])){
	$fridaycat=new aether();
	$fridaysubcat=new aether();
	$fridayrack=new aether();
	$fridaybrand=new aether();

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

	$adago=new aether();

	// function calling
	if($stcpdcat=="NA" || $stcpdsubcat=="NA" || $stcpdunit=="NA" || $stcpdgst=="0"){
		echo "Sab field bhar pahle!!!";
	}else{
		$objadago=$adago->stc_product_hit($stcpdname, $stcpddsc, $stcpdcat, $stcpdsubcat, $stcpdhsn, $stcpdpercentage, $stcpdunit, $stcpdstatus, $stcpdgst, $stcpdimages, $stcpdbrand);

		if($objadago == "success"){	
			$stcassemble=move_uploaded_file($stcpdtmpname, "../stc_product_image/".$stcpdimages);
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

// call product on product page
if(isset($_POST['fridaycallproonview'])){
	$objfriday=new aether();
	$objfridayout=$objfriday->call_pro_on_view();
	echo json_encode($objfridayout);
}

/*------------------------------Filter Product objets-----------------------------*/
// by name
if(isset($_POST['search_alo_in'])){
	$out='';
	$searchme=$_POST['search_alo_in'];
	if(strlen($searchme)>=3){
		$objloki=new aether();
		$objlokiout=$objloki->stc_search_product_name($searchme);
		$out.=$objlokiout;
	}else{
		$out='
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				<a 
			      href="#" 
			      class="btn btn-primary btn-block text-uppercase mb-3">
			      Enter At least 3 key!!!
			    </a>
			</div>
		';
	}
	echo json_encode($out);
}

// by rack
if(isset($_POST['jsfilterrackout'])){
	$filterbyrack=$_POST['jsfilterrackout'];
	$objloki=new aether();
	$objlokiout=$objloki->stc_search_product_by_rack($filterbyrack);
	echo $objlokiout;
}

// by cat
if(isset($_POST['jsfiltercatout'])){
	$filterbycat=$_POST['jsfiltercatout'];
	$objloki=new aether();
	$objlokiout=$objloki->stc_search_product_by_cat($filterbycat);
	echo $objlokiout;
}

// by subcat
if(isset($_POST['jsfiltersubcatout'])){
	$filterbysubcat=$_POST['jsfiltersubcatout'];
	$objloki=new aether();
	$objlokiout=$objloki->stc_search_product_by_sub_cat($filterbysubcat);
	echo $objlokiout;
}

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
	$objthor=new aether();
	if($stcpdcat=="NA" || $stcpdsubcat=="NA" || empty($stcpdname) || empty($stcpddsc)){
		$out="Kid!!! Don't let any field empty otherwise i will cursed you out.";
	}else{
		$objthorout=$objthor->stc_update_product($product_id, $stcpdname, $stcpddsc, $stcpdcat, $stcpdsubcat, $stcpdhsncode, $stcpdpercentage, $stcpdunit, $stcpdgst, $stcpdbrand);
		$out=$objthorout;
	}
	echo json_encode($objthorout);
}

/*------------------------------Merchant Page-----------------------------*/
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
	$stcmerpan=strtoupper($_POST['stcmerpan']);
	$stcmergst=strtoupper($_POST['stcmergstin']);
	$stcmerimages=$_FILES['stcmerimage']['name'];
	$stcmertmpname=$_FILES['stcmerimage']['tmp_name'];

	// echo $stcmername.'> '.$stcmeraddress.'> '.$stcmercity.'> '.$stcmerstate.'> '.$stcmeremail.'> '.$stcmercontnumber.'> '.$stcmerskf.'> '.$stcmerpan.'> '.$stcmergst.'> '.$stcperimages.'> '.$stcmertmpname;

	$adago=new aether();

	if(empty($stcmername) || empty($stcmeraddress) || empty($stcmeremail) || empty($stcmercontnumber) || empty($stcmerpan) || empty($stcmergst)){
		echo "Please Fill All Column!!!";
	}else{
		// function calling
		$objadago=$adago->stc_vendor_hit($stcmername, $stcmeraddress, $stcmercity, $stcmerstate, $stcmercontperson, $stcmeremail, $stcmercontnumber, $stcmerskf, $stcmerpan, $stcmergst, $stcmerimages);

		if($objadago == "success"){	
			$stcassemble=move_uploaded_file($stcmertmpname, "../stc_merchant_image/".$stcmerimages);
			if($stcassemble){
				echo "Merchant added!!";
			}else{
				echo "Merchant Uploaded Successfully Except Merchant Images!!!";
			}
		}else{
			echo $objadago;
		}
	}
}

// call state on merchants
// call to the product page
if(isset($_POST['indialocation'])){
	$indialocationstate=new aether();
	$indialocationcity=new aether();

	$outindialocationstate=$indialocationstate->call_state();
	$outindialocationcity=$indialocationcity->call_city();

	$mount=array($outindialocationcity, $outindialocationstate);
	echo json_encode($mount);
}

// by merchant name
if(isset($_POST['search_mer_byname_var_in'])){
	$searchmebyname=$_POST['search_mer_byname_var_in'];
	$objloki=new aether();
	$objlokiout=$objloki->stc_search_merchant_byname($searchmebyname);
	echo json_encode($objlokiout);
}

// by merchant specially known for
if(isset($_POST['search_mer_var_byskf_var_in'])){
	$searchmebyskf=$_POST['search_mer_var_byskf_var_in'];
	$objloki=new aether();
	$objlokiout=$objloki->stc_search_merchant_byskf($searchmebyskf);
	echo json_encode($objlokiout);
}

/*------------------------------Customer Page-----------------------------*/
// input customer
if(isset($_POST['stc_add_customer_hit'])){
	$stccustname=strtoupper($_POST['stccustname']);
	$stccustaddress=strtoupper($_POST['stccustaddress']);
	$stccustcity=$_POST['stcmercity'];
	$stccuststate=$_POST['stccuststate'];
	$stccustcontperson=$_POST['stccustcontperson'];
	$stccustemail=$_POST['stccustemail'];
	$stccustcontnumber=$_POST['stccustcontnumber'];
	$stccustskf=$_POST['stccustskf'];
	$stccustpan=strtoupper($_POST['stccustpan']);
	$stccustgst=strtoupper($_POST['stccustgstin']);
	$stccustimages=$_FILES['stccustimage']['name'];
	$stccusttmpname=$_FILES['stccustimage']['tmp_name'];

	$adago=new aether();

	if(empty($stccustname) || empty($stccustaddress) || empty($stccustemail) || empty($stccustcontnumber) || empty($stccustpan) || empty($stccustgst)){
		echo "Please Fill All Column!!!";
	}else{
		// function calling
		$objadago=$adago->stc_customer_hit($stccustname, $stccustaddress, $stccustcity, $stccuststate, $stccustcontperson, $stccustemail, $stccustcontnumber, $stccustskf, $stccustpan, $stccustgst, $stccustimages);

		if($objadago == "success"){	
			$stcassemble=move_uploaded_file($stccusttmpname, "../stc_customer_image/".$stccustimages);
			if($stcassemble){
				echo "Customer added!!!";
			}else{
				echo "Customer Details Uploaded Successfully Except Customer Image!!!";
			}
		}else{
			echo $objadago;
		}
	}
}

// by customer name
if(isset($_POST['search_cust_byname_var_in'])){
	$searchmebyname=$_POST['search_cust_byname_var_in'];
	$objloki=new aether();
	$objlokiout=$objloki->stc_search_customer_byname($searchmebyname);
	echo json_encode($objlokiout);
}

// by customer specially known for
if(isset($_POST['search_cust_var_byskf_var_in'])){
	$searchmebyskf=$_POST['search_cust_var_byskf_var_in'];
	$objloki=new aether();
	$objlokiout=$objloki->stc_search_customer_byskf($searchmebyskf);
	echo json_encode($objlokiout);
}

if(isset($_POST['stc_add_user_hit'])){
	$out='';
	$stccustusername 		=	$_POST['stccustusername'];
	$stccustuseremail 		=	$_POST['stccustuseremail'];
	$stccustusercontact 	=	$_POST['stccustusercontact'];
	$stccustuserpincode 	=	$_POST['stccustuserpincode'];
	$stccustuseraddress 	=	$_POST['stccustuseraddress'];
	$stccustusercity 		=	$_POST['stccustusercity'];
	$stccustuserstate 		=	$_POST['stccustuserstate'];
	function randomPassword() {
		$alphabet = "abcdefghijklmnopqrstuwvxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
		    $n = rand(0, $alphaLength);
		    $pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}
	$userid=str_replace(' ', '', $stccustusername);
	$sup_password=randomPassword();
	if(empty($stccustusername) || empty($stccustuseremail) || empty($stccustusercontact)){
		$out="Do not let any field empty!!!";
	}else{
		$objcustuser=new aether();
		$outobjcustuser=$objcustuser->stc_customer_user_regitration($stccustusername, $stccustuseremail, $stccustusercontact, $stccustuserpincode, $stccustuseraddress, $stccustusercity, $stccustuserstate, $userid, $sup_password);
		if($outobjcustuser=="success"){
			$out="User registered successfully.";
			$from="info@stcassociate.com";
			$headers = "MIME-Version: 1.0" . "\r\n"; 
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 
			$headers .= 'From: '.$from.'>' . "\r\n";
			$maildesc= '
				<!DOCTYPE html>
		        <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
		        <head>
		          <meta charset="utf-8">
		          <meta name="viewport" content="width=device-width,initial-scale=1">
		          <meta name="x-apple-disable-message-reformatting">
		          <title></title>
		          <!--[if mso]>
		          <style>
		            table {border-collapse:collapse;border-spacing:0;border:none;margin:0;}
		            div, td {padding:0;}
		            div {margin:0 !important;}
		          </style>
		          <noscript>
		            <xml>
		              <o:OfficeDocumentSettings>
		                <o:PixelsPerInch>96</o:PixelsPerInch>
		              </o:OfficeDocumentSettings>
		            </xml>
		          </noscript>
		          <![endif]-->
		          <style>
		            table, td, div, h1, p {
		              font-family: Arial, sans-serif;
		            }
		            @media screen and (max-width: 530px) {
		              .unsub {
		                display: block;
		                padding: 8px;
		                margin-top: 14px;
		                border-radius: 6px;
		                background-color: #555555;
		                text-decoration: none !important;
		                font-weight: bold;
		              }
		              .col-lge {
		                max-width: 100% !important;
		              }
		            }
		            @media screen and (min-width: 531px) {
		              .col-sml {
		                max-width: 27% !important;
		              }
		              .col-lge {
		                max-width: 73% !important;
		              }
		            }
		          </style>
		        </head>
		        <body style="margin:0;padding:0;word-spacing:normal;background-color:#939297;">
		          <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#939297;">
		            <table role="presentation" style="width:100%;border:none;border-spacing:0;">
		              <tr>
		                <td align="center" style="padding:0;">
		                  <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
		                    <tr>
		                      <td style="padding:40px 30px 30px 30px;text-align:center;font-size:24px;font-weight:bold;">
		                        <a href="http://www.example.com/" style="text-decoration:none;"><img src="http://stcassociate.com/stc_symbiote/img/stc_logo.png" width="165" alt="Logo" style="border-radius: 50%;width:80%;max-width:165px;height:auto;border:none;text-decoration:none;color:#ffffff;"></a>
		                      </td>
		                    </tr>
		                    <tr>
		                      <td style="padding:30px;background-color:#ffffff;">
		                        <h1 style="margin-top:0;margin-bottom:16px;font-size:26px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">Welcome to STC Associates</h1>
		                        <p style="margin:0;">Please find the your ID & password below provided by STC Associates.
		                            Here is a link of your platform <a href="http://stcassociate.com/stc_agent47/" style="color:#e50d70;text-decoration:underline;">Click me</a> wth your<br>
		                            User id - <b>'.$userid.'</b><br>
		                            Password - <b>'.$sup_password.'</b>
		                        </p>
		                      </td>
		                    </tr>
		                    <tr>
		                      <td style="padding:0;font-size:24px;line-height:28px;font-weight:bold;">
		                        <a href="#" style="text-decoration:none;"><img src="https://library.kissclipart.com/20180904/vzw/kissclipart-supervisor-dibujo-clipart-construction-laborer-cli-c856a67bc49abb0e.jpg" width="600" alt="" style="width:100%;height:auto;display:block;border:none;text-decoration:none;color:#363636;"></a>
		                      </td>
		                    </tr>
		                    <tr>
		                        <td style="padding:30px;text-align:center;font-size:12px;background-color:#404040;color:#cccccc;">
		                          <p style="margin:0;font-size:14px;line-height:20px;">&reg; STC Associates, Jamshedpur 2021<br><a class="unsub" href="#" style="color:#cccccc;text-decoration:underline;">This is System generated mail. Please do not reply.</a></p>
		                        </td>
		                    </tr>
		                  </table>
		                  <!--[if mso]>
		                  </td>
		                  </tr>
		                  <tr>
		                    <td style="padding:30px;text-align:center;font-size:12px;background-color:#404040;color:#cccccc;">
		                      <p style="margin:0;font-size:14px;line-height:20px;">&reg; STC Associates, Jamshedpur 2021<br><a class="unsub" href="#" style="color:#cccccc;text-decoration:underline;">This is System generated mail. Please do not reply.</a></p>
		                    </td>
		                   </tr>
		                  </table>
		                  <![endif]-->
		                </td>
		              </tr>
		            </table>
		          </div>
		        </body>
		        </html> 	
			';
			mail($stccustuseremail, "Welcome to STC Associates", $maildesc, $headers);
		}elseif($outobjcustuser=="not"){
			$out="Something Went Wrong, Please Try Again Later!!!";
		}else{
			$out="User Existed!!!";
		}
	}
	echo json_encode($out);
	// echo $out;
}

if(isset($_POST['stc_set_user_customer_hit'])){
	$stcuserid=$_POST['userid'];
	$stccustid=$_POST['custid'];
	$objuserset=new aether();
	$outobjuserset=$objuserset->search_uset_to_customer_set($stcuserid, $stccustid);
	// echo json_encode($outobjuserset);
	echo $outobjuserset;
}
/*------------------------------Product image Page-----------------------------*/
// product image object sections
if(isset($_POST['action'])){
	$objpdimge=new aether();
	$outobjpdimge=$objpdimge->call_pd_with_img();
	echo $outobjpdimge;
}

if(isset($_POST['image_id'])){
	$pd_id=$_POST['image_id'];
	$pdname=$_FILES['image']['name'];
	$pdtmp_name=$_FILES['image']['tmp_name'];
	$adago=new aether();
	$objadago=$adago->stc_alter_pdimage($pd_id, $pdname);

	if($objadago == "success"){	
		$stcassemble=move_uploaded_file($pdtmp_name, "../stc_product_image/".$pdname);
		if($stcassemble){
			echo "Product image updated!!!";
		}else{
			echo "Please check and try again!!!";
		}
	}else{
		echo $objadago;
	}
}

// search
if(isset($_POST['pdimg_sear'])){
	$productname=$_POST['productname'];
	$objpdimge=new aether();
	$outobjpdimge=$objpdimge->search_pd_with_img($productname);
	echo json_encode($outobjpdimge);
}


/*------------------------------Product single phase-----------------------------*/
if(isset($_POST['jsfilterprobypoidout'])){
	$pd_id=$_POST['jsfilterprobyid'];
	$objpdres=new eyeofagamotto();
	$outobjpdres=$objpdres->purchase_record_per_pd($pd_id);
	// echo $outobjpdres;
	echo json_encode($outobjpdres);
}

if(isset($_POST['jsfilterprobysoidout'])){
	$pd_id=$_POST['jsfilterprobyid'];
	$objpdres=new eyeofagamotto();
	$outobjpdres=$objpdres->sale_record_per_pd($pd_id);
	// echo $outobjpdres;
	echo json_encode($outobjpdres);
}
?>