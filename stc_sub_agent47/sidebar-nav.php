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
                    <a href="dashboard.php?page=dashboard" class="dashboard">
                        <i class="metismenu-icon pe-7s-home"></i>
                        Home
                    </a>
                </li>
                <?php
                    if($_SESSION['stc_agent_sub_category']=="Supervisor" || $_SESSION['stc_agent_sub_category']=="Site Incharge"){
                        echo '
                            <li>
                                <a href="stc-product.php?page=product" class="product">
                                    <i class="metismenu-icon pe-7s-box2"></i>
                                    Products
                                </a>
                            </li>
                        ';
                    }

                    if($_SESSION['stc_agent_sub_category']=="Supervisor" || $_SESSION['stc_agent_sub_category']=="Site Incharge" || $_SESSION['stc_agent_sub_category']=="Safety Supervisor"){
                        echo '
                            <li>
                                <a href="stc-requisition.php?page=requisition" class="requisition">
                                    <i class="metismenu-icon pe-7s-note2"></i>
                                    Requisition
                                </a>
                            </li>
                            <li>
                                <a href="stc-consumption.php?page=consumption" class="consumption">
                                    <i class="metismenu-icon pe-7s-box1"></i>
                                    Consumption
                                </a>
                            </li>
                            <li>
                                <a href="stc-toolstrack.php?page=toolstrack" class="toolstrack">
                                    <i class="metismenu-icon pe-7s-pin"></i>
                                    Tools Track
                                </a>
                            </li>
                        ';
                    }
                    if($_SESSION['stc_agent_sub_category']!="Safety Supervisor"){
                        echo '
                            <li>
                                <a href="stc-status-down-list.php?page=std" class="std">
                                    <i class="metismenu-icon pe-7s-switch"></i>
                                    Status Down List
                                </a>
                            </li>
                        ';
                        echo '
                            <li>
                                <a href="epermitenroll.php?page=epermitenroll" class="epermitenroll">
                                    <i class="metismenu-icon pe-7s-add-user"></i>
                                    E-Permit Enrollment
                                </a>
                            </li>
                        ';
                    }
                    if($_SESSION['stc_agent_sub_category']=="Supervisor" || $_SESSION['stc_agent_sub_category']=="Safety Supervisor"){
                        echo '
                            <li>
                                <a href="stc-safety.php?page=safety" class="safety">
                                    <i class="metismenu-icon pe-7s-id"></i>
                                    Safety
                                </a>
                            </li>
                        ';
                    }
                ?>
            </ul>
        </div>
    </div>
</div> 