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
                    <a href="dashboard.php?page=home" class="home">
                        <i class="metismenu-icon pe-7s-home"></i>
                        Home
                    </a>
                </li>
                <li>
                    <a href="stc-product.php?page=product" class="product">
                        <i class="metismenu-icon pe-7s-box2"></i>
                        Products
                    </a>
                </li>
                <li>
                    <a href="manage-project.php?page=projectmanagement" class="projectmanagement">
                        <i class="metismenu-icon pe-7s-news-paper"></i>
                        Project Management
                    </a>
                </li>
                <li>
                    <a href="manage-user.php?page=usermanagement" class="usermanagement">
                        <i class="metismenu-icon pe-7s-user"></i>
                        User Management
                    </a>
                </li>
                <li>
                    <a href="order-management.php?page=ordermanagement" class="ordermanagement">
                        <i class="metismenu-icon pe-7s-cart"></i>
                        Order Management
                    </a>
                </li>
                <?php 
                    if($_SESSION['stc_agent_role']==2){
                        echo '
                            <li>
                                <a href="procurement-management.php?page=procurementmanagement" class="procurementmanagement">
                                    <i class="metismenu-icon pe-7s-cart"></i>
                                    Procurement
                                </a>
                            </li>
                        ';
                    }
                ?>
                
                <li>
                    <a href="stc-std.php?page=statusdownlist" class="statusdownlist">
                        <i class="metismenu-icon pe-7s-switch"></i>
                        Status Down List
                    </a>
                </li>
                <li>
                    <a href="safety.php?page=safety" class="safety">
                        <i class="metismenu-icon pe-7s-id"></i>
                        Safety
                    </a>
                </li>
                <li>
                    <a href="procurement-tracker.php?page=procurementtracker" class="procurementtracker">
                        <i class="metismenu-icon pe-7s-pin"></i>
                        Procurement Tracker
                    </a>
                </li>
                <li>
                    <a href="item-tracker.php?page=itemtracker" class="itemtracker">
                        <i class="metismenu-icon pe-7s-map-marker"></i>
                        Item Tracker
                    </a>
                </li>
                <li>
                    <a href="stc-supervisor-balance-reports.php?page=reports" class="reports">
                        <i class="metismenu-icon pe-7s-note2"></i>
                        Reports
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>