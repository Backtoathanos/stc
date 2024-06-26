<?php //session_start();?>
<nav class="navbar navbar-expand-xl">
  <div class="container h-100">
    <a class="navbar-brand animated bounceInRight" href="dashboard.php">
      <img style="width: 50%;border-radius: 34px;" src="img/stc_logo.png">
    </a>
    <button class="navbar-toggler ml-auto mr-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars tm-nav-icon"></i>
    </button>
    <div class="collapse navbar-collapse animated bounceInDown" id="navbarSupportedContent">
              <ul class="navbar-nav mx-auto h-100">
                <li class="nav-item">
                  <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard              
                  </a>
                </li>                        
                <li class="nav-item">
                  <a class="nav-link active" href="erp.php">
                    <i class="fas fa-cog"></i>
                      ERP 
                      <span class="sr-only">(current)</span>  
                  </a>
                </li>                
                <li class="nav-item">
                  <a class="nav-link" href="accounts.php">
                    <i class="far fa-user"></i>
                    Accounts
                  </a>
                </li>
                <!-- <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                    <span>
                        Settings <i class="fas fa-angle-down"></i>
                    </span>
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Profile</a>
                    <a class="dropdown-item" href="#">Billing</a>
                    <a class="dropdown-item" href="#">Customize</a>
                  </div>
                </li> -->
                <li class="nav-item dropdown">
                  <!-- <a class="nav-link dropdown-toggle" href="agent-order.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
 -->                  <a href="agent-order.php" class="nav-link dropdown-toggle">
                    <span style="float: left;position: absolute;top: 20px;left: 40px;font-size: 12px;background: #bb5656;padding: 5px;border-radius: 50%;" class="badge"></span>
                    <i class="far fa-bell"></i>
                    <span >
                        <!-- <i class="fas fa-angle-down"></i> -->
                    </span>
                  </a>
                  <!-- <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Daily Report</a>
                    <a class="dropdown-item" href="#">Weekly Report</a>
                    <a class="dropdown-item" href="#">Yearly Report</a>
                  </div> -->
                </li>
              </ul>
              <ul class="navbar-nav">
                <li class="nav-item">
                  <a class="nav-link d-block" href="asgard/logout.php">
                    <?php echo @$_SESSION["stc_admin_info_name"];?>, <b>Logout</b>
                  </a>
                </li>
              </ul>
    </div>
  </div>
</nav>