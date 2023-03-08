<?php
include "../../MCU/obdb.php";
class ragnarCustomers extends tesseract{
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

	// Add customer details to table
	public function stc_customer_hit($stccustname, $stccustaddress, $stccustcity, $stccuststate, $stccustcontperson, $stccustemail, $stccustcontnumber, $stccustskf, $stccustpan, $stccustgst, $stccustimages){
		$stc_filter_add_customer=mysqli_real_escape_string($this->stc_dbs, $stccustname);
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_customer` 
			WHERE `stc_customer_name`='".$stc_filter_add_customer."'
		");
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

	public function stc_customer_user_regitration($stccustusername, $stccustuseremail, $stccustusercontact, $stccustuserpincode, $stccustuseraddress, $stccustusercity, $stccustuserstate, $userid, $sup_password, $stccustuserrole){
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
					`stc_agents_pass`, 
					`stc_agents_role`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $stccustusername)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stccustuseremail)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stccustusercontact)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stccustuseraddress)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stccustuserstate)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stccustusercity)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stccustuserpincode)."',
					'".mysqli_real_escape_string($this->stc_dbs, $userid)."',
					'".mysqli_real_escape_string($this->stc_dbs, $sup_password)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stccustuserrole)."'
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

	// customer set user
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

#<------------------------------------------------------------------------------------------------------>
#<--------------------------------------Object sections of Merchant class------------------------------->
#<------------------------------------------------------------------------------------------------------>

// call to the customer page
if(isset($_POST['indialocation'])){
	$indialocationstate=new ragnarCustomers();
	$indialocationcity=new ragnarCustomers();

	$outindialocationstate=$indialocationstate->call_state();
	$outindialocationcity=$indialocationcity->call_city();

	$mount=array($outindialocationcity, $outindialocationstate);
	echo json_encode($mount);
}

// input Customer
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
	$stccustimages='NA';

	$adago=new ragnarCustomers();

	if(empty($stccustname) || empty($stccustaddress) || empty($stccustemail) || empty($stccustcontnumber) || empty($stccustpan) || empty($stccustgst)){
		echo "Please Fill All Fields!!!";
	}else{
		// function calling
		$objadago=$adago->stc_customer_hit($stccustname, $stccustaddress, $stccustcity, $stccuststate, $stccustcontperson, $stccustemail, $stccustcontnumber, $stccustskf, $stccustpan, $stccustgst, $stccustimages);

		if($objadago == "success"){	
			echo "Customer added!!!";
		}else{
			echo $objadago;
		}
	}
}

// by customer name
if(isset($_POST['search_cust_byname_var_in'])){
	$out='';
	$searchmebyname=$_POST['search_cust_byname_var_in'];
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
		$objloki=new ragnarCustomers();
		$objlokiout=$objloki->stc_search_customer_byname($searchmebyname);
		$out=$objlokiout;
	}
	echo json_encode($out);
}

// by customer specially known for
if(isset($_POST['search_cust_var_byskf_var_in'])){
	$out='';
	$searchmebyskf=$_POST['search_cust_var_byskf_var_in'];
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
		$objloki=new ragnarCustomers();
		$objlokiout=$objloki->stc_search_customer_byskf($searchmebyskf);
		$out=$objlokiout;
	}
	echo json_encode($out);
}

// save customer user object
if(isset($_POST['stc_add_user_hit'])){
	$out='';
	$stccustusername 		=	$_POST['stccustusername'];
	$stccustuseremail 		=	$_POST['stccustuseremail'];
	$stccustusercontact 	=	$_POST['stccustusercontact'];
	$stccustuserpincode 	=	$_POST['stccustuserpincode'];
	$stccustuseraddress 	=	$_POST['stccustuseraddress'];
	$stccustusercity 		=	$_POST['stccustusercity'];
	$stccustuserstate 		=	$_POST['stccustuserstate'];
	$stccustuserrole 		=	$_POST['stccustuserrole'];
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
		$objcustuser=new ragnarCustomers();
		$outobjcustuser=$objcustuser->stc_customer_user_regitration($stccustusername, $stccustuseremail, $stccustusercontact, $stccustuserpincode, $stccustuseraddress, $stccustusercity, $stccustuserstate, $userid, $sup_password, $stccustuserrole);
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

// set user to the customers
if(isset($_POST['stc_set_user_customer_hit'])){
	$stcuserid=$_POST['userid'];
	$stccustid=$_POST['custid'];
	$objuserset=new ragnarCustomers();
	$outobjuserset=$objuserset->search_uset_to_customer_set($stcuserid, $stccustid);
	// echo json_encode($outobjuserset);
	echo $outobjuserset;
}
?>