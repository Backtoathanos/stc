        <div class="app-header header-shadow">
            <div class="app-header__logo">
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
            </div>
            <div class="app-header__menu close-tag">
                <span>
                    <a href="agent-order.php" style="width: 100%;font-size: 25px;position: relative;top: 10px;left: 30px;">
                        <i class="metismenu-icon pe-7s-bell"></i>
                        <span class="stc-notf-badge-for-order-left" style="
                            font-size: 12px;
                            background: #ff7676;
                            position: relative;
                            padding: 5px;
                            border-radius: 100%;
                            margin: 0;
                            left: -45px;
                            top: -16px;
                        ">0</span>
                        <span class="stc-notf-badge-for-order-right" style="
                            font-size: 12px;
                            background: #72df70;
                            position: relative;
                            padding: 5px;
                            border-radius: 100%;
                            margin: 0;
                            left: -40px;
                            top: -16px;
                        ">0</span>
                    </a>
                    <button style="z-index: 10;" type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>    
            <div class="app-header__content">
                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                            <div class="round-circl"><h2 style="position: relative;top: 10px;line-height: 1.66;margin: 0;padding: 0;font-weight: bold;color: #222;font-family: Poppins;font-size: 36px;">Y</h2></div>
                                            <i class="fa fa-angle-down ml-2 opacity-8" style="position: relative;font-size: 25px;padding: 0;margin: 0;left: -4px;top: -8px"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                            <h6 tabindex="-1" class="dropdown-header">STC ASSOCIATE</h6>
                                            <a href="accounts.php" tabindex="0" class="dropdown-item">User Account</a>
                                            <a href="kattegat/logout.php" tabindex="0" class="dropdown-item">Logout</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content-left  ml-3 header-user-info">
                                    <div class="widget-heading">Hello, 
                                        <?php 
                                            echo $_SESSION['stc_empl_name'];
                                        ?>
                                    </div>
                                </div>
                                <div class="widget-content-right header-user-info ml-3">
                                    <a href="agent-order.php" style="font-size: 25px;">
                                        <i class="metismenu-icon pe-7s-bell"></i>
                                        <span class="stc-notf-badge-for-order-left" style="
                                            font-size: 17px;
                                            background: #ff7676;
                                            position: relative;
                                            padding: 0px;
                                            border-radius: 100%;
                                            margin: 0;
                                            left: -37px;
                                            top: -16px;
                                        ">0</span>
                                        <span class="stc-notf-badge-for-order-right" style="
                                            font-size: 17px;
                                            background: #72df70;
                                            position: relative;
                                            padding: 0px;
                                            border-radius: 100%;
                                            margin: 0;
                                            left: -27px;
                                            top: -16px;
                                        ">0</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>