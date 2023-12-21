<div class="sidebar" data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
      <div class="logo"><a href="#" class="simple-text logo-normal">
          STC School
        </a></div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item dashboard">
            <a class="nav-link" href="./dashboard.php?dashboard=yes">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <?php
          if($_SESSION['stc_school_user_for']!=4){
            echo '
          <li class="nav-item canteen">
            <a class="nav-link" href="./canteen.php?canteen=yes">
              <i class="material-icons">content_paste</i>
              <p>Canteen</p>
            </a>
          </li>';
          }
          if($_SESSION['stc_school_user_for']!=4){
            echo '
          <li class="nav-item fee-collection">
            <a class="nav-link" href="./fee-collection.php?fee-collection=yes">
              <i class="material-icons">feed</i>
              <p>Fee Collection</p>
            </a>
          </li>';
          }
          if($_SESSION['stc_school_user_for']==3){
            echo '
          <li class="nav-item school-management">
            <a class="nav-link" href="./school-management.php?school-management=yes">
                <i class="material-icons">school</i>
                <p>School Management</p>
            </a>
          </li>';
          }
          if($_SESSION['stc_school_user_for']==4){
            echo '
          <li class="nav-item daily-schedule">
            <a class="nav-link" href="./daily-schedule.php?daily-schedule=yes">
                <i class="material-icons">schedule</i>
                <p>Schedule</p>
            </a>
          </li>';
          }
          ?>
          <li class="nav-item student-attendance">
            <a class="nav-link" href="./student-attendance.php?student-attendance=yes">
                <i class="material-icons">edit_document</i>
                <p>Student Attendance</p>
            </a>
          </li>
          <!-- <li class="nav-item syllabus">
            <a class="nav-link" href="./syllabus.php?syllabus=yes">
                <i class="material-icons">menu_book</i>
                <p>Syllabus</p>
            </a>
          </li> -->
        </ul>
      </div>
    </div>