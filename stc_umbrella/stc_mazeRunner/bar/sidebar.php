<?php
require_once __DIR__ . '/../includes/school_session_defaults.php';
?>
<div class="sidebar" data-color="azure" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
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
            $stc_school_side_section = isset($_GET['school-section']) ? (string) $_GET['school-section'] : '';
            if ($stc_school_side_section === '' && basename($_SERVER['PHP_SELF']) === 'school-management.php') {
              $stc_school_side_section = 'teachers';
            }
            $stc_school_side_active = function ($section) use ($stc_school_side_section) {
              return $stc_school_side_section === $section ? ' active' : '';
            };
            echo '

          <li class="nav-item school-management-students'.$stc_school_side_active('students').'">
            <a class="nav-link" href="./school-management.php?school-management=yes&school-section=students">
                <i class="material-icons">school</i>
                <p>Student Management</p>
            </a>
          </li>

          <li class="nav-item school-management-teachers'.$stc_school_side_active('teachers').'">
            <a class="nav-link" href="./school-management.php?school-management=yes&school-section=teachers">
                <i class="material-icons">person_add_alt_1</i>
                <p>Teacher Management</p>
            </a>
          </li>

          <li class="nav-item school-management-classes'.$stc_school_side_active('classes').'">
            <a class="nav-link" href="./school-management.php?school-management=yes&school-section=classes">
                <i class="material-icons">class</i>
                <p>Class Management</p>
            </a>
          </li>

          <li class="nav-item school-management-subjects'.$stc_school_side_active('subjects').'">
            <a class="nav-link" href="./school-management.php?school-management=yes&school-section=subjects">
                <i class="material-icons">menu_book</i>
                <p>Subject Management</p>
            </a>
          </li>

          <li class="nav-item school-management-schedule'.$stc_school_side_active('schedule').'">
            <a class="nav-link" href="./school-management.php?school-management=yes&school-section=schedule">
                <i class="material-icons">event_note</i>
                <p>Schedule Management</p>
            </a>
          </li>
          
          ';
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
          <li class="nav-item questions-management">
            <a class="nav-link" href="./questions-management.php?questions-management=yes">
                <i class="material-icons">edit_note</i>
                <p>Questions Management</p>
            </a>
          </li>
          <li class="nav-item complaint-register">
            <a class="nav-link" href="./complaint-register.php?complaint-register=yes">
                <i class="material-icons">report_problem</i>
                <p>Complaint Register</p>
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