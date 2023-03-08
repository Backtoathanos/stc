<!DOCTYPE html>
<html lang="en">
<head>
	<title>STC Associate a Ecommerce Online Shopping Category Bootstrap Responsive Website Template | Single Page :: STC Associate</title>
	<!-- for-mobile-apps -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="STC Associate Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
		SmartPhone Compatible web template, free web designs for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
	<!-- //for-mobile-apps -->
	<!-- Custom Theme files -->
	<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
	<link href="css/style.css" rel="stylesheet" type="text/css" media="all" /> 
	<link href="css/fasthover.css" rel="stylesheet" type="text/css" media="all" />
	<!-- //Custom Theme files -->
	<!-- font-awesome icons -->
	<link href="css/font-awesome.css" rel="stylesheet"> 
	<!-- //font-awesome icons -->
	<!-- js -->
	<script src="js/jquery.min.js"></script> 
	<!-- //js -->  
	<!-- web fonts --> 
	<link href='//fonts.googleapis.com/css?family=Glegoo:400,700' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
	<!-- //web fonts --> 
	<!-- for bootstrap working -->
	<script type="text/javascript" src="js/bootstrap-3.1.1.min.js"></script>
	<!-- //for bootstrap working -->
	<!-- start-smooth-scrolling -->
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(".scroll").click(function(event){		
				event.preventDefault();
				$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
			});
		});
	</script>
	<!-- //end-smooth-scrolling --> 
</head> 
<body> 
	<!-- mail route modal -->
	<div class="modal" tabindex="-1" role="dialog" id="routmailmodal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Contact us</h5>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-6 col-md-6">
							<label for="name">Your name : </label>
						</div>
						<div class="col-sm-6 col-md-6">
							<input type="text" class="form-control routeformailname" placeholder="Please enter your name" required>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 col-md-6">
							<label for="email">Your email : </label>
						</div>
						<div class="col-sm-6 col-md-6">
							<input type="email" class="form-control routeformailemail" placeholder="Please enter your email" required>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 col-md-6">
							<label for="email">Your phone no : </label>
						</div>
						<div class="col-sm-6 col-md-6">
							<input type="email" class="form-control routeformailphone" placeholder="Please enter your phone number" required>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 col-md-6">
							<label for="quantity">Product quantity/unit : </label>
						</div>
						<div class="col-sm-6 col-md-6">
							<input type="number" class="form-control routeformailqty" placeholder="Please enter product quantity" required>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 col-md-6">
							<label for="onotes">Other notes : </label>
						</div>
						<div class="col-sm-6 col-md-6">
							<textarea class="form-control routeformailnotes" placeholder="Please enter other notes" required></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" class="modpdidmailroute">
				 	<button type="button" class="btn btn-primary routeformailsubmit">Submit</button>
			  	</div>
			</div>
		</div>
	</div>

	<!-- header modal -->
	<!-- <div class="modal fade" id="myModal88" tabindex="-1" role="dialog" aria-labelledby="myModal88"
		aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;</button>
					<h4 class="modal-title" id="myModalLabel">Don't Wait, Login now!</h4>
				</div>
				<div class="modal-body modal-body-sub">
					<div class="row">
						<div class="col-md-8 modal_body_left modal_body_left1" style="border-right: 1px dotted #C2C2C2;padding-right:3em;">
							<div class="sap_tabs">	
								<div id="horizontalTab" style="display: block; width: 100%; margin: 0px;">
									<ul>
										<li class="resp-tab-item" aria-controls="tab_item-0"><span>Sign in</span></li>
										<li class="resp-tab-item" aria-controls="tab_item-1"><span>Sign up</span></li>
									</ul>		
									<div class="tab-1 resp-tab-content" aria-labelledby="tab_item-0">
										<div class="facts">
											<div class="register">
												<form action="#" method="post">			
													<input name="Email" placeholder="Email Address" type="text" required="">						
													<input name="Password" placeholder="Password" type="password" required="">										
													<div class="sign-up">
														<input type="submit" value="Sign in"/>
													</div>
												</form>
											</div>
										</div> 
									</div>	 
									<div class="tab-2 resp-tab-content" aria-labelledby="tab_item-1">
										<div class="facts">
											<div class="register">
												<form action="#" method="post">			
													<input placeholder="Name" name="Name" type="text" required="">
													<input placeholder="Email Address" name="Email" type="email" required="">	
													<input placeholder="Password" name="Password" type="password" required="">	
													<input placeholder="Confirm Password" name="Password" type="password" required="">
													<div class="sign-up">
														<input type="submit" value="Create Account"/>
													</div>
												</form>
											</div>
										</div>
									</div> 			        					            	      
								</div>	
							</div>
							<script src="js/easyResponsiveTabs.js" type="text/javascript"></script>
							<script type="text/javascript">
								$(document).ready(function () {
									$('#horizontalTab').easyResponsiveTabs({
										type: 'default', //Types: default, vertical, accordion           
										width: 'auto', //auto or any width like 600px
										fit: true   // 100% fit in a container
									});
								});
							</script>
							<div id="OR" class="hidden-xs">OR</div>
						</div>
						<div class="col-md-4 modal_body_right modal_body_right1">
							<div class="row text-center sign-with">
								<div class="col-md-12">
									<h3 class="other-nw">Sign in with</h3>
								</div>
								<div class="col-md-12">
									<ul class="social">
										<li class="social_facebook"><a href="#" class="entypo-facebook"></a></li>
										<li class="social_dribbble"><a href="#" class="entypo-dribbble"></a></li>
										<li class="social_twitter"><a href="#" class="entypo-twitter"></a></li>
										<li class="social_behance"><a href="#" class="entypo-behance"></a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>  -->
	<!-- header modal -->
	<!-- header -->
	<div class="header" id="home1">
		<div class="container">
			<!-- <div class="w3l_login">
				<a href="#" data-toggle="modal" data-target="#myModal88"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>
			</div> -->
			<div class="w3l_logo">
				<h1><a href="../index.html">STC Associate<span>Your stores. Your place.</span></a></h1>
			</div>
			<div class="search">
				<input class="search_box" type="checkbox" id="search_box">
				<label class="icon-search" for="search_box"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></label>
				<div class="search_form">
					<form action="#" method="post">
						<input type="text" name="Search" placeholder="Search...">
						<input type="submit" value="Send">
					</form>
				</div>
			</div>
			<!-- <div class="cart cart box_1"> 
				<form action="#" method="post" class="last"> 
					<input type="hidden" name="cmd" value="_cart" />
					<input type="hidden" name="display" value="1" />
					<button class="w3view-cart" type="submit" name="submit" value=""><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></button>
				</form>   
			</div>   -->
		</div>
	</div>
	<!-- //header -->
	<!-- navigation -->
	<div class="navigation">
		<div class="container">
			<nav class="navbar navbar-default">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header nav_2">
					<button type="button" class="navbar-toggle collapsed navbar-toggle1" data-toggle="collapse" data-target="#bs-megadropdown-tabs">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div> 
				<div class="collapse navbar-collapse" id="bs-megadropdown-tabs">
					<ul class="nav navbar-nav">
						<li><a href="../index.html" class="act">Home</a></li>	
						<!-- Mega Menu -->
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Products <b class="caret"></b></a>
							<ul class="dropdown-menu multi-column columns-3">
								<div class="row">
									<div class="col-sm-3">
										<ul class="multi-column-dropdown">
											<h6>Electronics & Electricals</h6>
											<li><a href="products.php?product=tv">TV</a></li>
											<li><a href="products.php?product=Fridge">Fridge</a></li> 
											<li><a href="products.php?product=Washing Machines">Washing Machines</a></li>
											<li><a href="products.php?product=AC">AC</a></li>
											<li><a href="products.php">Others<span>New</span></a></li>
										</ul>
									</div>
									<div class="col-sm-3">
										<ul class="multi-column-dropdown">
											<h6>Industrial Plants & Machinery</h6>
											<li><a href="products.php?product=AC">Grinding M/C</a></li>
											<li><a href="products.php?product=AC">Cutting M/C</a></li>
											<li><a href="products.php?product=AC">Welding M/C</a></li>
										</ul>
									</div>
									<div class="col-sm-3">
										<ul class="multi-column-dropdown">
											<h6>Building & Construction</h6>
											<li><a href="products.php?product=Cements">Cements</a></li>
											<li><a href="products.php?product=Putty">Putty</a></li>
											<li><a href="products.php?product=Paints & Primers">Paints & Primers</a></li>
											<li><a href="stc_online/products2.php?product=TMT Bars">TMT Bars</a></li>
										</ul>
									</div>
									<div class="col-sm-3">
										<ul class="multi-column-dropdown">
											<h6>Others</h6>
											<li><a href="products.php?product=AC">Tv</a></li>
											<li><a href="products.php?product=AC">Camera</a></li>
											<li><a href="products.php?product=AC">AC</a></li>
											<li><a href="products.php?product=AC">Grinders</a></li>
										</ul>
									</div>
									<div class="clearfix"></div>
								</div>
							</ul>
						</li>
						<li><a href="about.html">About Us</a></li> 
						<li class="w3pages"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Franchise <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="stc_online/stc-electronics.php">STC Electronics</a></li>
								<li><a href="stc_online/stc-tradings.php">STC Trading</a></li>     
							</ul>
						</li>  
						<li><a href="stc_online/mail.html">Mail Us</a></li>
					</ul>
				</div>
			</nav>
		</div>
	</div>
	<!-- //navigation -->
	<!-- banner -->
	<!-- <div class="banner banner10">
		<div class="container">
			<h2>Single Page</h2>
		</div>
	</div> -->
	<!-- //banner -->   
	<!-- breadcrumbs -->
	<div class="breadcrumb_dress">
		<div class="container">
			<ul>
				<li><a href="../index.html"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Home</a> <i>/</i></li>
				<li>Single Pieces <i>/</i></li>
				<li><?php echo substr($_GET['productname'], 0, 40);?> <i>
			</ul>
		</div>
	</div>
	<!-- //breadcrumbs -->  
	<!-- single -->
	<div class="single">
		<div class="container">
		<?php 
			include_once("../MCU/db.php");
			if(isset($_GET['pdid']) && !empty($_GET['pdid'])){				
				$stconpdqry=mysqli_query($con, "
					SELECT * FROM `stc_product` WHERE `stc_product_id`='".$_GET['pdid']."'
				");
				$callonlinepro=mysqli_fetch_assoc($stconpdqry);
				$checkrow=mysqli_num_rows($stconpdqry);
				if($checkrow>0){
					echo '
						<div class="col-md-4 single-left">
							<div class="flexslider">
								<ul class="slides">
									<li data-thumb="images/a.jpg">
										<div class="thumb-image"> <img src="../stc_symbiote/stc_product_image/'.$callonlinepro['stc_product_image'].'" data-imagezoom="true" class="img-responsive" alt=""> </div>
									</li>
									<!-- <li data-thumb="images/b.jpg">
										 <div class="thumb-image"> <img src="images/b.jpg" data-imagezoom="true" class="img-responsive" alt=""> </div>
									</li>
									<li data-thumb="images/c.jpg">
									   <div class="thumb-image"> <img src="images/c.jpg" data-imagezoom="true" class="img-responsive" alt=""> </div>
									</li>  -->
								</ul>
							</div>
							<!-- flexslider -->
								<script defer src="js/jquery.flexslider.js"></script>
								<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />
								<script>
								// Can also be used with $(document).ready()
								$(window).load(function() {
								  $(".flexslider").flexslider({
									animation: "slide",
									controlNav: "thumbnails"
								  });
								});
								</script>
							<!-- flexslider -->
							<!-- zooming-effect -->
								<script src="js/imagezoom.js"></script>
							<!-- //zooming-effect -->
						</div>
						<div class="col-md-8 single-right">
							<h3>'.$callonlinepro['stc_product_name'].'</h3>
							<div class="description">
								<h5><i>Description</i></h5>
								<p>'.$callonlinepro['stc_product_name'].'</p>
							</div>
							<div class="">
								<form action="#" method="post">  
									<a href="#" id="'.$callonlinepro['stc_product_id'].'" class="w3ls-cart">Get for qoute</a>
								</form>
							</div> 
						</div>
					';
				}else{
					echo '
						<div class="col-md-12 single-left">
							<h4>No product found!!!</h4>
						</div>
					';
				}
			}
		?>
			<div class="clearfix"> </div>
		</div>
	</div> 
	<!-- <div class="additional_info">
		<div class="container">
			<div class="sap_tabs">	
				<div id="horizontalTab1" style="display: block; width: 100%; margin: 0px;">
					<ul>
						<li class="resp-tab-item" aria-controls="tab_item-0" role="tab"><span>Product Information</span></li>
						<li class="resp-tab-item" aria-controls="tab_item-1" role="tab"><span>Reviews</span></li>
					</ul>		
					<div class="tab-1 resp-tab-content additional_info_grid" aria-labelledby="tab_item-0">
						<h3>The Best 3GB RAM Mobile Phone</h3>
						<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore 
							eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident.
							Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut 
							odit aut fugit, sed quia consequuntur magni dolores eos qui 
							ratione voluptatem sequi nesciunt.Ut enim ad minima veniam, quis nostrum 
							exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea 
							commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate 
							velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat 
							quo voluptas nulla pariatur.</p>
					</div>	

					<div class="tab-2 resp-tab-content additional_info_grid" aria-labelledby="tab_item-1">
						<h4>(2) Reviews</h4>
						<div class="additional_info_sub_grids">
							<div class="col-xs-2 additional_info_sub_grid_left">
								<img src="images/t1.png" alt=" " class="img-responsive" />
							</div>
							<div class="col-xs-10 additional_info_sub_grid_right">
								<div class="additional_info_sub_grid_rightl">
									<a href="single.html">Laura</a>
									<h5>Oct 06, 2016.</h5>
									<p>Quis autem vel eum iure reprehenderit qui in ea voluptate 
										velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat 
										quo voluptas nulla pariatur.</p>
								</div>
								<div class="additional_info_sub_grid_rightr">
									<div class="rating">
										<div class="rating-left">
											<img src="images/star-.png" alt=" " class="img-responsive">
										</div>
										<div class="rating-left">
											<img src="images/star-.png" alt=" " class="img-responsive">
										</div>
										<div class="rating-left">
											<img src="images/star-.png" alt=" " class="img-responsive">
										</div>
										<div class="rating-left">
											<img src="images/star.png" alt=" " class="img-responsive">
										</div>
										<div class="rating-left">
											<img src="images/star.png" alt=" " class="img-responsive">
										</div>
										<div class="clearfix"> </div>
									</div>
								</div>
								<div class="clearfix"> </div>
							</div>
							<div class="clearfix"> </div>
						</div>
						<div class="additional_info_sub_grids">
							<div class="col-xs-2 additional_info_sub_grid_left">
								<img src="images/t2.png" alt=" " class="img-responsive" />
							</div>
							<div class="col-xs-10 additional_info_sub_grid_right">
								<div class="additional_info_sub_grid_rightl">
									<a href="single.html">Michael</a>
									<h5>Oct 04, 2016.</h5>
									<p>Quis autem vel eum iure reprehenderit qui in ea voluptate 
										velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat 
										quo voluptas nulla pariatur.</p>
								</div>
								<div class="additional_info_sub_grid_rightr">
									<div class="rating">
										<div class="rating-left">
											<img src="images/star-.png" alt=" " class="img-responsive">
										</div>
										<div class="rating-left">
											<img src="images/star-.png" alt=" " class="img-responsive">
										</div>
										<div class="rating-left">
											<img src="images/star.png" alt=" " class="img-responsive">
										</div>
										<div class="rating-left">
											<img src="images/star.png" alt=" " class="img-responsive">
										</div>
										<div class="rating-left">
											<img src="images/star.png" alt=" " class="img-responsive">
										</div>
										<div class="clearfix"> </div>
									</div>
								</div>
								<div class="clearfix"> </div>
							</div>
							<div class="clearfix"> </div>
						</div>
						<div class="review_grids">
							<h5>Add A Review</h5>
							<form action="#" method="post">
								<input type="text" name="Name" value="Name" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Name';}" required="">
								<input type="email" name="Email" placeholder="Email" required="">
								<input type="text" name="Telephone" value="Telephone" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Telephone';}" required="">
								<textarea name="Review" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Add Your Review';}" required="">Add Your Review</textarea>
								<input type="submit" value="Submit" >
							</form>
						</div>
					</div> 			        					            	      
				</div>	
			</div>
			<script src="js/easyResponsiveTabs.js" type="text/javascript"></script>
			<script type="text/javascript">
				$(document).ready(function () {
					$('#horizontalTab1').easyResponsiveTabs({
						type: 'default', //Types: default, vertical, accordion           
						width: 'auto', //auto or any width like 600px
						fit: true   // 100% fit in a container
					});
				});
			</script>
		</div>
	</div> -->
	<!-- Related Products -->
	<div class="w3l_related_products">
		<div class="container">
			<h3>Related Products</h3>
			<ul id="flexiselDemo2">	
			<?php	
			$relatedqry=mysqli_query($con, "
				SELECT * FROM `stc_product`
				INNER JOIN `stc_sub_category`
				ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
				ORDER BY RAND()
				LIMIT 0,10;
			");	
			foreach($relatedqry as $getrelatpro){
				echo '
					<li>
						<div class="w3l_related_products_grid">
							<div class="agile_ecommerce_tab_left mobiles_grid">
								<div class="hs-wrapper hs-wrapper3">
									<img src="../stc_symbiote/stc_product_image/'.$getrelatpro['stc_product_image'].'" alt=" " class="img-responsive" />
									<div class="w3_hs_bottom">
										<div class="flex_ecommerce">
											<a href="singlepeices.php?productname='.$getrelatpro['stc_product_name'].'&&pdid='.$getrelatpro['stc_product_id'].'""><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
										</div>
									</div>
								</div>
								<h5><a href="singlepeices.php?productname='.$getrelatpro['stc_product_name'].'&&pdid='.$getrelatpro['stc_product_id'].'""></a></h5>
								<div class="">
									<form action="#" method="post">  
										<a href="singlepeices.php?productname='.$getrelatpro['stc_product_name'].'&&pdid='.$getrelatpro['stc_product_id'].'"" id="'.$callonlinepro['stc_product_id'].'">Get for qoute</a>
									</form>
								</div> 
							</div>
						</div>
					</li>
				';
			}
			?>
				
			</ul>
			
				<script type="text/javascript">
					$(window).load(function() {
						$("#flexiselDemo2").flexisel({
							visibleItems:4,
							animationSpeed: 1000,
							autoPlay: true,
							autoPlaySpeed: 3000,    		
							pauseOnHover: true,
							enableResponsiveBreakpoints: true,
							responsiveBreakpoints: { 
								portrait: { 
									changePoint:480,
									visibleItems: 1
								}, 
								landscape: { 
									changePoint:640,
									visibleItems:2
								},
								tablet: { 
									changePoint:768,
									visibleItems: 3
								}
							}
						});
						
					});
				</script>
				<script type="text/javascript" src="js/jquery.flexisel.js"></script>
		</div>
	</div>
	<!-- //Related Products -->
	<?php
		foreach($relatedqry as $getrelatproa){
			$subcat='';
			if($getrelatpro['stc_sub_cat_name']=="OTHERS"){
				$subcat='';
			}else{
				$subcat=$getrelatpro['stc_sub_cat_name'];
			}
			echo '
				<div class="modal video-modal fade" id="myModal'.$getrelatproa['stc_product_id'].'" tabindex="-1" role="dialog" aria-labelledby="myModal'.$getrelatproa['stc_product_id'].'">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>						
							</div>
							<section>
								<div class="modal-body">
									<div class="col-md-5 modal_body_left">
										<img src="../stc_symbiote/stc_product_image/'.$getrelatproa['stc_product_image'].'" alt=" " class="img-responsive" />
									</div>
									<div class="col-md-7 modal_body_right">
										<h4>'.$subcat.' '.$getrelatproa['stc_product_name'].'</h4>
										<p>'.$subcat.' '.$getrelatproa['stc_product_name'].'</p>
										<div class="">
											<form action="#" method="post">  
												<a href="#" id="'.$callonlinepro['stc_product_id'].'" class="w3ls-cart">Get for qoute</a>
											</form>
										</div> 
									</div>
									<div class="clearfix"> </div>
								</div>
							</section>
						</div>
					</div>
				</div>
			';
		}
	?>
						
	<!-- //single -->
	<!-- newsletter -->
	<div class="newsletter">
		<div class="container">
			<div class="col-md-6 w3agile_newsletter_left">
				<h3>Newsletter</h3>
				<!-- <p>Excepteur sint occaecat cupidatat non proident, sunt.</p> -->
			</div>
			<div class="col-md-6 w3agile_newsletter_right">
				<form action="#" method="post">
					<input type="email" name="Email" placeholder="Email" required="">
					<input type="submit" value="" />
				</form>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
	<!-- //newsletter -->
	<!-- footer -->
	<div class="footer">
		<div class="container">
			<div class="w3_footer_grids">
				<div class="col-md-3 w3_footer_grid">
					<h3>Contact</h3>
					<ul class="address">
						<li><i class="glyphicon glyphicon-map-marker" aria-hidden="true"></i>STC Electronics Near Chepapul 1st Floor of HDFC Bank, Azad Nagar, Mango<span>Jamshedpur</span></li>
						<li>
							<i class="glyphicon glyphicon-envelope" aria-hidden="true"></i>
							<a href="mailto:info@stcassociate.com">info@stcassociate.com</a>
						</li>
						<li>
							<i class="glyphicon glyphicon-envelope" aria-hidden="true"></i>
							<a href="mailto:stc111213@gmail.commodo">stc111213@gmail.com</a>
						</li>
						<li><i class="glyphicon glyphicon-earphone" aria-hidden="true"></i>+91-8986811304</li>
					</ul>
				</div>
				<div class="col-md-3 w3_footer_grid">
					<h3>Information</h3>
					<ul class="info"> 
						<li><a href="about.html">About Us</a></li>
						<li><a href="mail.html">Contact Us</a></li>
						<!-- <li><a href="stc_online/codes.html">Short Codes</a></li> -->
						<li><a href="faq.html">FAQ's</a></li>
						<li><a href="products.html">Special Products</a></li>
					</ul>
				</div>
				<div class="col-md-3 w3_footer_grid">
					<h3>Category</h3>
					<ul class="info"> 
						<li><a href="products.php?product=cement">Cement</a></li>
						<li><a href="products.php?product=tmt">TMT Bars</a></li>
						<li><a href="products.php?product=tiscon">TATA Tiscon</a></li>
					</ul>
				</div>
				<div class="col-md-3 w3_footer_grid">
					<h3>Profile</h3>
					<ul class="info"> 
						<li><a href="../index.html">Home</a></li>
						<li><a href="products.html">Today's Deals</a></li>
					</ul>
					<h4>Follow Us</h4>
					<div class="agileits_social_button">
						<ul>
							<li><a href="#" class="facebook"> </a></li>
							<li><a href="#" class="twitter"> </a></li>
							<li><a href="#" class="google"> </a></li>
							<li><a href="#" class="pinterest"> </a></li>
						</ul>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
		<div class="footer-copy">
			<div class="footer-copy1">
				<div class="footer-copy-pos">
					<a href="#home1" class="scroll"><img src="images/arrow.png" alt=" " class="img-responsive" /></a>
				</div>
			</div>
			<div class="container">
				<p>&copy; 2020 STC Associate. All rights reserved | Design by <a href="#">@Punisher aka King117</a></p>
			</div>
		</div>
	</div>
	<!-- //footer -->  
	<!-- cart-js -->
	<script src="js/minicart.js"></script>
	<script src="js/script.js"></script>
	<script>
        // w3ls.render();

        // w3ls.cart.on('w3sb_checkout', function (evt) {
        // 	var items, len, i;

        // 	if (this.subtotal() > 0) {
        // 		items = this.items();

        // 		for (i = 0, len = items.length; i < len; i++) { 
        // 		}
        // 	}
        // });
    </script>  
	<!-- //cart-js --> 
</body>
</html>