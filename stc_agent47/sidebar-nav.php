<div class="app-sidebar sidebar-shadow">
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
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>    
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">Dashboards</li>
                <li>
                    <a href="dashboard.php" class="mm-active">
                        <i class="metismenu-icon pe-7s-home"></i>
                        Home
                    </a>
                </li>
                <li>
                    <a href="stc-product.php" class="mm-active">
                        <i class="metismenu-icon pe-7s-box2"></i>
                        Products
                    </a>
                </li>
                <li>
                    <a href="#" class="mm-active">
                        <i class="metismenu-icon pe-7s-diamond"></i>
                        Project
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="manage-project.php">
                                <i class="metismenu-icon"></i>
                                Project Management
                            </a>
                        </li>
                        <li>
                            <a href="manage-supervisor.php">
                                <i class="metismenu-icon"></i>
                                Supervisor Management
                            </a>
                        </li>
                        <li>
                            <a href="order-management.php">
                                <i class="metismenu-icon"></i>
                                Order Management
                            </a>
                        </li>
                        <?php 
                            if($_SESSION['stc_agent_role']==3){
                                echo '
                        <li>
                            <a href="stc-std.php">
                                <i class="metismenu-icon"></i>
                                Status Down List
                            </a>
                        </li>
                                ';
                            }
                        ?>
                        <?php 
                            if($_SESSION['stc_agent_role']==2){
                                echo '

                        <li>
                            <a href="procurement-management.php">
                                <i class="metismenu-icon"></i>
                                Procurement
                            </a>
                        </li>
                                ';
                            }
                        ?>
                    </ul>
                </li>
                <li>
                    <a href="safety.php" class="mm-active">
                        <i class="metismenu-icon pe-7s-note2"></i>
                        Safety
                    </a>
                </li>
                <li>
                    <a href="stc-supervisor-balance-reports.php" class="mm-active">
                        <i class="metismenu-icon pe-7s-note2"></i>
                        Reports
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>