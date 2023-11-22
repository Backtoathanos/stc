<?php
include "../../MCU/obdb.php";
class worm extends tesseract{
	public function goto_enduser($pdid, $username, $useremail, $userphone, $userqty, $usernotes){
		$laufey='';

		function getRandom($length){
	       
	        $str = 'abcdefghijklmnopqrstuvwzyz';
	        $str1= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	        $str2= '0123456789';
	        $shuffled = str_shuffle($str);
	        $shuffled1 = str_shuffle($str1);
	        $shuffled2 = str_shuffle($str2);
	        $total = $shuffled.$shuffled1.$shuffled2;
	        $shuffled3 = str_shuffle($total);
	        $result= substr($shuffled3, 0, $length);
	        return $result;
	    }
		$trackkey = getRandom(8);

		$laufeyqry=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_euser_querybox`(
				`stc_euser_querybox_trckid`, 
				`stc_euser_querybox_uname`, 
				`stc_euser_querybox_email`, 
				`stc_euser_queryboxphone`, 
				`stc_euser_queryboxnotes`, 
				`stc_euser_querybox_pdid`, 
				`stc_euser_querybox_pdqty`
			) VALUES (
				'".$trackkey."',
				'".$username."',
				'".$useremail."',
				'".$userphone."',
				'".$usernotes."',
				'".$pdid."',
				'".$userqty."'
			)
		");
		if($laufeyqry){
			$laufey="saved";
		}else{
			$laufey="Hmm!!! Something went wrong. Please try again later!!!";
		}
		return $laufey;
	}

	public function get_product($pdid){
		$laufey='';
		$laufeyqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_product_name`, 
				`stc_product_image`, 
				`stc_product_hsncode`,
				`stc_sub_cat_name` 
			FROM `stc_product`
			INNER JOIN `stc_sub_category`
			ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
			WHERE `stc_product_id`='".$pdid."'
		");
		$laufey=mysqli_fetch_assoc($laufeyqry);
		return $laufey;
	}
}

if(isset($_GET["thuderstrike"])){	
	$pdid=$_GET['pdid'];
	$username=$_GET['username'];
	$useremail=$_GET['useremail'];
	$userphone=$_GET['userphone'];
	$userqty=$_GET['userqty'];
	$usernotes=$_GET['usernotes'];
	$out='';
	$lokiinworm=new worm();
	$lokiinwormpd=new worm();
	if(empty($username) || empty($useremail) || empty($userphone) || empty($userqty) || empty($usernotes)){
		$out="empty";
	}else{
		$firstout=$lokiinworm->goto_enduser($pdid, $username, $useremail, $userphone, $userqty, $usernotes);
		$secout=$lokiinwormpd->get_product($pdid);
		if($firstout=="saved"){
		    $to = 'stc111213@gmail.com'; 
			$from = 'info@stcassociate.com'; 
			$fromName = 'STC Associate'; 
			 
			$subject = "Requirements from end users ".$username; 
			 
			$htmlContent = ' 
			    <html> 
			    <head> 
			        <title>Requirements for '.$secout['stc_sub_cat_name'].' '.$secout['stc_product_name'].'</title> 
			    </head> 
			    <body> 
			        <h1>Please response to Mr/Mrs '.$username.'!</h1> 
			        <table cellspacing="0" style="border: 2px dashed #FB4314; width: 100%;"> 
			            <tr> 
			                <th>Product:</th><td>'.$secout['stc_sub_cat_name'].' '.$secout['stc_product_name'].'</td> 
			            </tr>
			            <tr> 
			                <th>Name:</th><td>STC Associate</td> 
			            </tr> 
			            <tr style="background-color: #e0e0e0;"> 
			                <th>Email:</th><td>'.$useremail.'</td> 
			            </tr>
			            <tr style="background-color: #e0e0e0;"> 
			                <th>Contact:</th><td>'.$userphone.'</td> 
			            </tr>
			            <tr> 
			                <th>Website:</th><td><a href="http://www.stcassociate.com">www.stcassociate.com</a></td> 
			            </tr> 
			        </table> 
			    </body> 
			    </html>
			'; 
			 
			// Set content-type header for sending HTML email 
			$headers = "MIME-Version: 1.0" . "\r\n"; 
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 
			 
			// Additional headers 
			$headers .= 'From: '.$fromName.'<'.$from.'>' . "\r\n"; 
			 
			// Send email 
			if(mail($to, $subject, $htmlContent, $headers)){ 
			    $out='Email has sent successfully.'; 
			}else{ 
			   $out='Email sending failed.'; 
			}
		}else{
			$out="fail";
		}
	}
	echo $out;
}

?>