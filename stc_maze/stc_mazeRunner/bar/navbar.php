<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">Dashboard</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <form class="navbar-form">
              <div class="input-group no-border">
                <input type="text" value="" class="form-control stc-elec-pd-search-val" placeholder="Search...">
                <button type="submit" href="" class="btn btn-white btn-round btn-just-icon stc-elec-pd-search-hit">
                  <i class="material-icons">search</i>
                  <div class="ripple-container"></div>
                </button>
              </div>
            </form>
            <ul class="navbar-nav">
              <li class="nav-item dropdown active">
                <a class="nav-link" href="request-action.php" id="navbarDropdownMenuLink">
                  <i class="material-icons">notifications</i>
                  <span class="notification">
                    <?php 
                      include_once("../../MCU/db.php");
                      $getnotreq=mysqli_query($con, "
                        SELECT
                            `stc_electronics_request_raised_id`
                        FROM
                            `stc_electronics_request_raised`
                        WHERE
                            `stc_electronics_request_raised_status`='1'
                      ");
                      echo $counter=mysqli_num_rows($getnotreq);
                    ?>
                  </span>
                  <p class="d-lg-none d-md-block">
                    Some Actions
                  </p>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">person</i>
                  <p class="d-lg-none d-md-block">
                    Account
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                  <a class="dropdown-item" href="#">Profile</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="../vanaheim/logoutsess.php">Log out</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>