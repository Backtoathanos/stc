		<div class="app-header header-shadow">
            <div class="app-header__logo">
                <!-- <div class="logo"></div> -->
                <div class="logo-src"></div>
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
                <div class="logo-src2" style="position: relative; left: 10px; top: -4px; height: 23px; width: 100px; background: url(assets/images/logo-inverse.png);"></div>
            </div>
            <div class="app-header__menu">
                <span>
                    <a href="ag-cart.php" class="metismenu-icon pe-7s-cart" style="position: relative; top: 10px; font-size: 35px;">
                    </a>
                    <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>    
            <div class="app-header__content">
                <div class="app-header-left">
                    <div class="search-wrapper">
                        <div class="input-holder">
                        </div>
                        <button class="close close-co"></button>
                    </div>  
                </div>
                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                            <!-- <div class="round-circl"><h2>Y</h2></div> -->
                                            <img width="42" class="rounded-circle" src="<?php echo (isset($_SESSION['stc_agent_sub_image']) && !empty($_SESSION['stc_agent_sub_image']))?'assets/images/user_images/'.$_SESSION['stc_agent_sub_image']:'assets/images/avatars/userimg.jpg';?>" alt="">
                                            <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                            <h6 tabindex="-1" class="dropdown-header">STC ASSOCIATE</h6>
                                            <a href="my-order.php" tabindex="0" class="dropdown-item">My Orders</a>
                                            <a href="account.php" tabindex="0" class="dropdown-item">User Account</a>
                                            <!-- <button type="button" tabindex="0" class="dropdown-item">Actions</button> -->
                                            <div tabindex="-1" class="dropdown-divider"></div>
                                            <a href="nemesis/logout.php" tabindex="0" class="dropdown-item">Logout</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content-left  ml-3 header-user-info">
                                    <div class="widget-heading">
                                        <?php 
                                            // if(!empty($_COOKIE['agentsperformename'])){
                                                echo @$_SESSION['stc_agent_sub_name'];
                                            // }else{
                                                // header("location:index.php");
                                            // }
                                        ?>
                                    </div>
                                    <div class="widget-subheading">
                                        <?php echo @$_SESSION['stc_agent_sub_category'];?>
                                    </div>
                                </div>
                                <div class="widget-content-right header-user-info ml-3">
                                    <a href="ag-cart.php" style="font-size: 25px;" class="">
                                        <i class="metismenu-icon pe-7s-cart"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>        
                </div>
            </div>
        </div>