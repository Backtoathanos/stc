<?php
  ini_set("session.gc_maxlifetime", 21600);
  session_set_cookie_params(21600);
  session_start();
  if(empty(@$_SESSION['stc_school_user_id'])){
      header('location:../index.html');
  }
  if($_SESSION['stc_school_user_for']==4){
      header('location:forbidden.html');
  }
  $stc_school_sections = array(
    'teachers' => array('label' => 'Teacher Management', 'icon' => 'person_add_alt_1', 'pane' => 'stc-create-teacher'),
    'students' => array('label' => 'Student Management', 'icon' => 'school', 'pane' => 'stc-create-student'),
    'subjects' => array('label' => 'Subject Management', 'icon' => 'menu_book', 'pane' => 'stc-create-subject'),
    'classes' => array('label' => 'Class Management', 'icon' => 'class', 'pane' => 'stc-create-classroom'),
    'schedule' => array('label' => 'Schedule Management', 'icon' => 'event_note', 'pane' => 'stc-create-shedule'),
  );
  $stc_school_section = isset($_GET['school-section']) ? (string) $_GET['school-section'] : 'teachers';
  if (!isset($stc_school_sections[$stc_school_section])) {
    $stc_school_section = 'teachers';
  }
  $stc_school_current = $stc_school_sections[$stc_school_section];
?>
<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="../assets/img/stc_logo_title.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
      STC School || School Management
    </title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
    <!-- CSS Files -->
    <link href="../assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
    <link href="../assets/css/school-theme.css?v=8" rel="stylesheet" />
  </head>

  <body class="school-app">
    <div class="wrapper ">
      <?php include_once("bar/sidebar.php");?>
      <div class="main-panel">
        <!-- Navbar -->
        <?php $stc_nav_page_title = $stc_school_current['label']; include_once("bar/navbar.php");?>
        <!-- End Navbar -->
        <div class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="card school-card-shell">
                  <div class="card-header card-header-primary school-mgmt-header-tabs">
                    <div class="school-mgmt-tabnav">
                      <div class="school-mgmt-tabnav-inner">
                        <div class="school-mgmt-tabnav-brand">
                          <span class="nav-tabs-title" id="school-mgmt-tabs-heading">
                            <i class="material-icons school-nav-tab-icon" aria-hidden="true"><?php echo htmlspecialchars($stc_school_current['icon'], ENT_QUOTES, 'UTF-8'); ?></i>
                            <?php echo htmlspecialchars($stc_school_current['label'], ENT_QUOTES, 'UTF-8'); ?>
                          </span>
                          <small class="school-mgmt-tabnav-tagline">Opened from the left menu as a separate management page.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="tab-content">
                      <div class="tab-pane<?php echo $stc_school_section === 'teachers' ? ' active' : ''; ?>" id="stc-create-teacher" role="tabpanel" aria-labelledby="school-tab-teachers">
                        <div class="row">
                          <div class="col-12">
                            <h2 class="school-page-title mb-3">Teachers</h2>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                              <div class="row align-items-start">
                                <div class="col-12">
                                  <div class="school-toolbar-row">
                                    <button type="button" class="btn btn-primary btn-school-primary stc-school-show-teach-btn">Add teacher</button>
                                    <div class="school-search-row">
                                      <input id="teachersearch" type="text" class="form-control school-search-field" placeholder="Search teachers..." />
                                    </div>
                                  </div>
                                </div>
                                <div class="col-12 mx-auto">
                                  <div class="school-table-wrap">
                                    <div class="school-table-inner">
                                      <table class="table table-hover table-striped mb-3">
                                      <thead>
                                        <tr>
                                          <th class="text-center"><b>Sl No</b></th>
                                          <th class="text-center"><b>Teacher Name</b></th>
                                          <th class="text-center"><b>Teacher DOB</b></th>
                                          <th class="text-center"><b>Gender</b></th>
                                          <th class="text-center"><b>Blood Group</b></th>
                                          <th class="text-center"><b>Email</b></th>
                                          <th class="text-center"><b>Contact</b></th>
                                          <th class="text-center"><b>Address</b></th>
                                          <th class="text-center"><b>Skills</b></th>
                                          <th class="text-center"><b>Religion</b></th>
                                          <th class="text-center"><b>Join Date</b></th>
                                          <th class="text-center"><b>Remarks</b></th>
                                        </tr>
                                      </thead>
                                      <tbody class="stc-teacher-rec-show"></tbody>
                                    </table>
                                    </div>
                                  </div>
                                  <div class="stc-teacher-pagination school-rec-pagination-holder"></div>
                                </div>
                              </div>
                          </div>
                        </div>
                      </div>

                      <!-- Create Student -->

                      <div class="tab-pane<?php echo $stc_school_section === 'students' ? ' active' : ''; ?>" id="stc-create-student" role="tabpanel" aria-labelledby="school-tab-students">
                        <div class="row">
                          <div class="col-12">
                            <h2 class="school-page-title mb-3">Students</h2>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                              <div class="row align-items-start">
                                <div class="col-12">
                                  <div class="school-toolbar-row">
                                    <button type="button" class="btn btn-primary btn-school-primary stc-school-show-stud-btn">Add student</button>
                                    <div class="school-search-row">
                                      <input id="studentsearch" type="text" class="form-control school-search-field" placeholder="Search students..." />
                                    </div>
                                    <div class="school-page-size-row">
                                      <label for="studentPageLimit" class="school-page-size-label">Rows</label>
                                      <select id="studentPageLimit" class="form-control school-page-size-select" aria-label="Students per page">
                                        <option value="15">15</option>
                                        <option value="25" selected>25</option>
                                        <option value="50">50</option>
                                      </select>
                                    </div>
                                    <div class="school-student-excel-row">
                                      <a href="../vanaheim/school-management.php?download_student_sample=1" class="btn btn-info btn-sm school-student-excel-btn">Sample Excel</a>
                                      <button type="button" class="btn btn-success btn-sm school-student-excel-btn stc-student-excel-upload-btn">Upload Excel</button>
                                      <input id="studentExcelUpload" type="file" class="d-none" accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
                                    </div>
                                  </div>
                                </div>
                                <div class="col-12 mx-auto">
                                  <div class="school-table-wrap">
                                    <div class="school-table-inner">
                                      <table class="table table-hover table-striped mb-3">
                                      <thead>
                                        <tr>
                                          <th class="text-center"><b>Sl No</b></th>
                                          <th class="text-center"><b>Student Id</b></th>
                                          <th class="text-center"><b>Student Name</b></th>
                                          <th class="text-center"><b>Student DOB</b></th>
                                          <th class="text-center"><b>Gender</b></th>
                                          <th class="text-center"><b>Blood Group</b></th>
                                          <th class="text-center"><b>Email</b></th>
                                          <th class="text-center"><b>Contact</b></th>
                                          <th class="text-center"><b>Address</b></th>
                                          <th class="text-center"><b>Religion</b></th>
                                          <th class="text-center"><b>Admission Date</b></th>
                                          <th class="text-center"><b>Classroom</b></th>
                                          <th class="text-center"><b>Parent/Guardian Name</b></th>
                                          <th class="text-center"><b>Remarks</b></th>
                                        </tr>
                                      </thead>
                                      <tbody class="stc-student-rec-show"></tbody>
                                    </table>
                                    </div>
                                  </div>
                                  <div class="stc-student-pagination school-rec-pagination-holder"></div>
                                </div>
                              </div>
                          </div>
                        </div>
                      </div>

                      <!-- Field Create Subject -->

                      <div class="tab-pane<?php echo $stc_school_section === 'subjects' ? ' active' : ''; ?>" id="stc-create-subject" role="tabpanel" aria-labelledby="school-tab-subjects">
                        <div class="row">
                          <div class="col-12">
                            <h2 class="school-page-title mb-3">Subjects</h2>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                              <div class="row align-items-start">
                                <div class="col-12">
                                  <div class="school-toolbar-row">
                                    <button type="button" class="btn btn-primary btn-school-primary stc-school-show-subject-btn">Add subject</button>
                                    <div class="school-search-row">
                                      <input id="subjectsearch" type="text" class="form-control school-search-field" placeholder="Search subjects..." />
                                    </div>
                                  </div>
                                </div>
                                <div class="col-12 mx-auto">
                                  <div class="school-table-wrap">
                                    <div class="school-table-inner">
                                      <table class="table table-hover table-striped mb-3">
                                      <thead>
                                        <tr>
                                          <th class="text-center"><b>Subject Id</b></th>
                                          <th class="text-center"><b>Subject Title</b></th>
                                          <th class="text-center"><b>Syllabus Details</b></th>
                                          <th class="text-center"><b>Add Date</b></th>
                                          <th class="text-center"><b>Add by</b></th>
                                          <th class="text-center"><b>Action</b></th>
                                          </tr>
                                      </thead>
                                      <tbody class="stc-subject-rec-show"></tbody>
                                    </table>
                                    </div>
                                  </div>
                                  <div class="stc-subject-pagination school-rec-pagination-holder"></div>
                                </div>
                              </div>
                          </div>
                        </div>
                      </div>

                      <!-- Field Class Room -->

                      <div class="tab-pane<?php echo $stc_school_section === 'classes' ? ' active' : ''; ?>" id="stc-create-classroom" role="tabpanel" aria-labelledby="school-tab-classrooms">
                        <div class="row">
                          <div class="col-12">
                            <h2 class="school-page-title mb-3">Classrooms</h2>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                              <div class="row align-items-start">
                                <div class="col-12">
                                  <div class="school-toolbar-row">
                                    <button type="button" class="btn btn-primary btn-school-primary stc-school-show-classroom-btn">Add classroom</button>
                                    <div class="school-search-row">
                                      <input id="classsearch" type="text" class="form-control school-search-field" placeholder="Search classrooms..." />
                                    </div>
                                  </div>
                                </div>
                                <div class="col-12 mx-auto">
                                  <div class="school-table-wrap">
                                    <div class="school-table-inner">
                                      <table class="table table-hover table-striped mb-3">
                                      <thead>
                                        <tr>
                                          <th class="text-center"><b>Classroom ID</b></th>
                                          <th class="text-center"><b>Classroom Title</b></th>
                                          <th class="text-center"><b>Loaction</b></th>
                                          <th class="text-center"><b>Capacity</b></th>
                                          <th class="text-center"><b>Add Date</b></th>
                                          <th class="text-center"><b>Add by</b></th>
                                          <th class="text-center"><b>Action</b></th>
                                        </tr>
                                      </thead>
                                      <tbody class="stc-classroom-rec-show"></tbody>
                                    </table>
                                    </div>
                                  </div>
                                  <div class="stc-classroom-pagination school-rec-pagination-holder"></div>
                                </div>
                              </div>
                          </div>
                        </div>
                      </div>                     

                      <!-- Field Schedule Routine -->

                      <div class="tab-pane<?php echo $stc_school_section === 'schedule' ? ' active' : ''; ?>" id="stc-create-shedule" role="tabpanel" aria-labelledby="school-tab-schedule">
                        <div class="school-weekly-surface">
                          <header class="school-weekly-header">
                            <div class="school-weekly-header-copy">
                              <h2 class="school-page-title school-weekly-title mb-2">Weekly schedule</h2>
                              <p class="school-weekly-lead mb-0">Pick a weekday to load all classes for that day. Unused slots show <span class="school-weekly-mono">na</span>; the card in the matching time band is tinted as <strong class="school-week-active-word">live</strong> when viewing today.</p>
                            </div>
                          </header>

                          <div class="school-weekly-controls">
                            <div class="school-weekly-controls-row">
                              <button type="button" class="btn btn-primary btn-school-primary btn-school-lg stc-school-show-schedule-btn">
                                <i class="material-icons align-middle mr-2" aria-hidden="true" style="font-size:18px;">add_circle_outline</i>
                                Add timetable entry
                              </button>
                              <div class="school-weekly-field school-weekly-field-day">
                                <label class="school-inline-label" for="weekly-sched-day-select"><i class="material-icons school-field-icon" aria-hidden="true">event</i> Day</label>
                                <select id="weekly-sched-day-select" class="form-control school-week-picker stc-schedule-week" autocomplete="off">
                                  <option>Select</option>
                                  <?php
                                  $day_arr=array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
                                  $day=date('l');
                                  foreach($day_arr as $day_arr_row){
                                    if ($day === $day_arr_row) {
                                      echo '<option selected>'.htmlspecialchars($day_arr_row, ENT_QUOTES, 'UTF-8').'</option>';
                                    } else {
                                      echo '<option>'.htmlspecialchars($day_arr_row, ENT_QUOTES, 'UTF-8').'</option>';
                                    }
                                  }
                                  ?>
                                </select>
                              </div>
                              <div class="school-weekly-field school-weekly-field-search flex-grow-md-1">
                                <label class="school-inline-label" for="schedulesearch"><i class="material-icons school-field-icon" aria-hidden="true">search</i> Search classes</label>
                                <div class="school-search-input-wrap">
                                  <input id="schedulesearch" type="text" class="form-control school-search-field" placeholder="Filter by class, subject or teacher..." autocomplete="off" />
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="school-table-wrap school-weekly-table-wrap">
                            <div class="school-weekly-table-bar">
                              <span class="school-weekly-table-bar-title"><i class="material-icons align-middle mr-2" aria-hidden="true">grid_on</i> Weekly grid</span>
                              <small class="text-muted school-weekly-table-hint d-none d-md-inline ml-md-4">Swipe or scroll sideways on phones.</small>
                            </div>
                            <div class="school-table-inner">
                                  <table class="table table-hover table-striped mb-0 stc-weekly-sched-grid">
                                    <thead>
                                      <tr>
                                        <th class="text-center"><b>Class</b></th>
                                        <th class="text-center"><b>1<span style="vertical-align: top;font-size: 11px;">st</span> Period</b></th>
                                        <th class="text-center"><b>2<span style="vertical-align: top;font-size: 11px;">nd</span> Period</b></th>
                                        <th class="text-center"><b>3<span style="vertical-align: top;font-size: 11px;">rd</span> Period</b></th>
                                        <th class="text-center"><b>4<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></th>
                                        <th class="text-center"><b>5<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></th>
                                        <th class="text-center"><b>6<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></th>
                                        <th class="text-center"><b>7<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></th>
                                      </tr>
                                    </thead>
                                    <tbody class="stc-schedule-rec-show"></tbody>
                                  </table>
                                </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

<!-- syllabus modal -->

<div class="modal bd-example-modal-xl stc-school-addsyllabus-res" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Syllabus</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>        
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <div class="mb-3">
              <h3>Title</h3>
              <textarea class="form-control stc-school-sylTitle" placeholder="Enter Title"></textarea>
            </div>
          </div>
          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
            <div class="mb-3">
              <h3>Chapter</h3>
              <input type="text" class="form-control stc-school-sylChapter" placeholder="Enter Chapter">
            </div>
          </div>
          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
            <div class="mb-3">
              <h3>Lesson</h3>
              <input type="text" class="form-control stc-school-sylLesson" placeholder="Enter Lesson">
            </div>
          </div>
          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
            <div class="mb-3">
              <h3>Unit</h3>
              <input type="text" class="form-control stc-school-sylUnit" placeholder="Enter Unit">
            </div>
          </div>
          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
            <div class="mb-3">
              <h3>Complete Date</h3>
              <input type="date" class="form-control stc-school-sylDate">
            </div>
          </div>
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto mt-3 mb-3">
        <button class="btn btn-success school-modal-save stc-school-sylsave-btn">Save syllabus</button>  
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal bd-example-modal-xl stc-school-viewsyllabus-res" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">View Syllabus</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>        
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <div class="mb-3">
              <table class="table table-bordered stc-school-syllabus-table">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Chapter</th>
                    <th>Lesson</th>
                    <th>Unit</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody class="show-syllabus-data">
                  <tr>
                    <td>Loading</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- teacher modal -->
<div class="modal bd-example-modal-xl stc-school-showteacher-res" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">School Teacher</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Teacher ID
              </h5>
              <input
                name="stcschoolmanagementteacherid"
                type="text"
                class="form-control validate stcschoolmanagementteacherid"
                value=""
                placeholder="Enter Teacher ID No"
              />
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >First Name
              </h5>
              <input
                name="stcschoolmanagementteacherfirstname"
                type="text"
                class="form-control validate stcschoolmanagementteacherfirstname"
                value=""
                placeholder="Enter Teacher First Name"
              />
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Last Name
              </h5>
              <input
                name="stcschoolmanagementteacherlastname"
                type="text"
                class="form-control validate stcschoolmanagementteacherlastname"
                value=""
                placeholder="Enter Teacher Last Name"
              />
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Date of Birth
              </h5>
              <input
                name="stcschoolmanagementteacherdateofbirth"
                type="date"
                class="form-control validate stcschoolmanagementteacherdateofbirth"
                value=""
                placeholder="Date of Birth"
              />
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Gender
              </h5>
              <label
                for="name"
                >
                <input
                  name="stcschoolmanagementteachergender"
                  type="radio"
                  class="stcschoolmanagementteachergender"
                  value="Male"
                  checked
                />
                Male
              </label> &nbsp &nbsp
              <label
                for="name"
                >
                <input
                  name="stcschoolmanagementteachergender"
                  type="radio"
                  class="stcschoolmanagementteachergender"
                  value="Female"
                />
                Female
              </label>
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Blood Group
              </h5>
               <select 
                  class="form-control stcschoolmanagementteacherbloodgroup" 
                  name="stcschoolmanagementteacherbloodgroup" 
                  >
                  <option value="0">--Select--</option> 
                  <option value="a_positive">A+</option>
                  <option value="a_negative">A-</option>
                  <option value="b_positive">B+</option>
                  <option value="b_negative">B-</option>
                  <option value="o_positive">O+</option>
                  <option value="o_negative">O-</option>
                  <option value="ab_positive">AB+</option>
                  <option value="ab_negative">AB-</option>
              </select>
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Email
              </h5>
              <input
                name="stcschoolmanagementteacheremail"
                type="email"
                class="form-control validate stcschoolmanagementteacheremail"
                value=""
                placeholder="Enter Teacher Email"
              />
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Contact
              </h5>
              <input
                name="stcschoolmanagementteachernumber"
                type="number"
                class="form-control validate stcschoolmanagementteachernumber"
                value=""
                placeholder="Enter Teacher Contact No"
                maxlength="10"
              />
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Address
              </h5>
              <textarea 
                name="stcschoolmanagementteacheraddress"
                class="form-control validate stcschoolmanagementteacheraddress"
                value=""
                placeholder="Enter Teacher Address"
                ></textarea>
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Skills
              </h5>
              <textarea 
                name="stcschoolmanagementteacherskills"
                class="form-control validate stcschoolmanagementteacherskills"
                value=""
                placeholder="Enter Teacher Skills"
                ></textarea>
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Religion
              </h5>
              <input
                name="stcschoolmanagementteacherreligion"
                type="text"
                class="form-control validate stcschoolmanagementteacherreligion"
                value=""
                placeholder="Enter Teacher Religion"
              />
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Joining Date
              </h5>
              <input
                name="stcschoolmanagementteacherjoiningdate"
                type="date"
                class="form-control validate stcschoolmanagementteacherjoiningdate"
              />
            </div>
          </div>
          <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="mb-3">
              <h5
                for="name"
                >Remarks
              </h5>
              <textarea 
                name="stcschoolmanagementteacherremarks"
                class="form-control validate stcschoolmanagementteacherremarks"
                value=""
                placeholder="Remarks"
                ></textarea>
            </div>
          </div>
          <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="mb-3">
              <button type="button" name="search" class="btn btn-success school-modal-save" id="stcschoolteachersave">Add Teacher</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- student modal -->
<div class="modal bd-example-modal-xl stc-school-showstud-res" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">School Add Student</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        
      <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
              <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                <div class="row">
                  <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="mb-3">
                      <h5
                        for="name"
                        >Student ID
                      </h5>
                      <input
                        name="stcschoolmanagementstudentid"
                        type="text"
                        class="form-control validate stcschoolmanagementstudentid"
                        value=""
                        placeholder="Enter Student ID No"
                      />
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="mb-3">
                      <h5
                        for="name"
                        >First Name
                      </h5>
                      <input
                        name="stcschoolmanagementstudentfirstname"
                        type="text"
                        class="form-control validate stcschoolmanagementstudentfirstname"
                        value=""
                        placeholder="Enter Student First Name"
                      />
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="mb-3">
                      <h5
                        for="name"
                        >Last Name
                      </h5>
                      <input
                        name="stcschoolmanagementstudentlastname"
                        type="text"
                        class="form-control validate stcschoolmanagementstudentlastname"
                        value=""
                        placeholder="Enter Student Last Name"
                      />
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="mb-3">
                      <h5
                        for="name"
                        >Date of Birth
                      </h5>
                      <input
                        name="stcschoolmanagementstudentdateofbirth"
                        type="date"
                        class="form-control validate stcschoolmanagementstudentdateofbirth"
                        value=""
                        placeholder="Date of Birth"
                      />
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="mb-3">
                      <h5
                        for="name"
                        >Gender
                      </h5>
                      <label
                        for="name"
                        >
                        <input
                          name="stcschoolmanagementstudentgender"
                          type="radio"
                          class="stcschoolmanagementstudentgender"
                          value="Male"
                          checked
                        />
                        Male
                      </label> &nbsp &nbsp
                      <label
                        for="name"
                        >
                        <input
                          name="stcschoolmanagementstudentgender"
                          type="radio"
                          class="stcschoolmanagementstudentgender"
                          value="Female"
                        />
                        Female
                      </label>
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="mb-3">
                      <h5
                        for="name"
                        >Blood Group
                      </h5>
                       <select 
                          class="form-control stcschoolmanagementstudentbloodgroup" 
                          name="stcschoolmanagementstudentbloodgroup" 
                          >
                          <option value="0">--Select--</option> 
                          <option value="a_positive">A+</option>
                          <option value="a_negative">A-</option>
                          <option value="b_positive">B+</option>
                          <option value="b_negative">B-</option>
                          <option value="o_positive">O+</option>
                          <option value="o_negative">O-</option>
                          <option value="ab_positive">AB+</option>
                          <option value="ab_negative">AB-</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="mb-3">
                      <h5
                        for="name"
                        >Email
                      </h5>
                      <input
                        name="stcschoolmanagementstudentemail"
                        type="email"
                        class="form-control validate stcschoolmanagementstudentemail"
                        value=""
                        placeholder="Enter Student Email"
                      />
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="mb-3">
                      <h5
                        for="name"
                        >Contact
                      </h5>
                      <input
                        name="stcschoolmanagementstudentnumber"
                        type="number"
                        class="form-control validate stcschoolmanagementstudentnumber"
                        value=""
                        placeholder="Enter Student Contact No"
                        maxlength="10"
                      />
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="mb-3">
                      <h5
                        for="name"
                        >Address
                      </h5>
                      <textarea 
                        name="stcschoolmanagementStudentaddress"
                        class="form-control validate stcschoolmanagementStudentaddress"
                        value=""
                        placeholder="Enter Student Address"
                        ></textarea>
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="mb-3">
                      <h5
                        for="name"
                        >Religion
                      </h5>
                      <input
                        name="stcschoolmanagementstudentreligion"
                        type="text"
                        class="form-control validate stcschoolmanagementstudentreligion"
                        value=""
                        placeholder="Enter Student Religion"
                      />
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="mb-3">
                      <h5
                        for="name"
                        >Admission Date
                      </h5>
                      <input
                        name="stcschoolmanagementstudentjoiningdate"
                        type="date"
                        class="form-control validate stcschoolmanagementstudentjoiningdate"
                      />
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="mb-3">
                      <h5
                        for="name"
                        >Class Room
                      </h5>
                       <select 
                          class="form-control stcschoolmanagementstudentclassroom" 
                          name="stcschoolmanagementstudentclassroom" 
                          >
                          <option value="0">--Select--</option>
                          <?php
                            include_once("../../MCU/db.php");

                            $class_sql=mysqli_query($con, "
                                select * from stc_school_class where stc_school_class_status=1
                            ");
                            foreach($class_sql as $classrow){
                              echo '<option value="'.$classrow['stc_school_class_id'].'">'.$classrow['stc_school_class_title'].'</option>';

                          ?> 
                          <?php 
                            }                                          
                          ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="mb-3">
                      <h5
                        for="name"
                        >Parent / Guardian Name
                      </h5>
                      <input
                        name="stcschoolmanagementstudentparentguardianfullname"
                        type="text"
                        class="form-control validate stcschoolmanagementstudentparentguardianfullname"
                        value=""
                        placeholder="Enter Parent / Guardian Full Name"
                      />
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="mb-3">
                      <h5
                        for="name"
                        >Remarks
                      </h5>
                      <textarea 
                        name="stcschoolmanagementstudentremarks"
                        class="form-control validate stcschoolmanagementstudentremarks"
                        value=""
                        placeholder="Remarks"
                        ></textarea>
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="mb-3">
                      <button type="button" name="search" class="btn btn-success school-modal-save" id="stcschoolstudentsave">Add Student</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- subject modal -->
<div class="modal bd-example-modal-xl stc-school-showsub-res" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">School Subject</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Subject ID
              </h5>
              <input
                name="stcschoolmanagementsubjectid"
                type="text"
                class="form-control validate stcschoolmanagementsubjectid"
                value=""
                placeholder="Enter Subject ID"
              />
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Subject Title
              </h5>
              <input
                name="stcschoolmanagementsubjecttitle"
                type="text"
                class="form-control validate stcschoolmanagementsubjecttitle"
                value=""
                placeholder="Enter Subject Title"
              />
            </div>
          </div>
          <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="mb-3">
              <h5
                for="name"
                >Syllubus Details
              </h5>
              <textarea 
                name="stcschoolmanagementsubjectdetails"
                class="form-control validate stcschoolmanagementsubjectdetails"
                value=""
                placeholder="Enter Complete Syllubus Details"
                ></textarea>
            </div>
          </div>
          <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="mb-3">
              <button type="button" name="search" class="btn btn-success school-modal-save" id="stcschoolsubjectsave">Add Subject</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- class room modal-->
<div class="modal bd-example-modal-xl stc-school-showclassroom-res" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Classroom</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Class Room ID
              </h5>
              <input
                name="stcschoolmanagementclassroomid"
                type="text"
                class="form-control validate stcschoolmanagementclassroomid"
                value=""
                placeholder="Enter Class Room ID"
              />
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Class Room Title
              </h5>
              <input
                name="stcschoolmanagementclassroomtitle"
                type="text"
                class="form-control validate stcschoolmanagementclassroomtitle"
                value=""
                placeholder="Enter Class Room Title"
              />
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Location
              </h5>
              <input
                name="stcschoolmanagementclassroomlocation"
                type="text"
                class="form-control validate stcschoolmanagementclassroomlocation"
                value=""
                placeholder="Enter Class Room Location"
              />
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Capacity
              </h5>
              <input
                name="stcschoolmanagementclassroomcapacity"
                type="text"
                class="form-control validate stcschoolmanagementclassroomcapacity"
                value=""
                placeholder="Enter Class Room Capacity"
              />
            </div>
          </div>
          <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="mb-3">
              <button type="button" name="search" class="btn btn-success school-modal-save" id="stcschoolclassroomsave">Add Class Room</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- schedule modal -->
<div class="modal bd-example-modal-xl stc-school-showschedule-res" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">School Schedule</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="mb-3">
              <h5
                for="name"
                >Class Type
              </h5>
              <select
                name="stcschoolscheduletype"
                type="text"
                class="form-control validate stcschoolscheduletype"
                ><option value="NA">Select</option>
                <option value="1">Academic Class</option>   
                <option value="2">Coaching Class</option>   
                <option value="3">Self Study</option>                
              </select>
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Teacher
              </h5>
              <select
                name="stcschoolscheduleteacher"
                type="text"
                class="form-control validate stcschoolscheduleteacher"
                ><option value="NA">Select</option>
                <?php
                  $teacher_sql=mysqli_query($con, "
                      select * from stc_school_teacher where stc_school_teacher_status=1
                  ");
                  foreach($teacher_sql as $teacherrow){
                    echo '<option value="'.$teacherrow['stc_school_teacher_id'].'">'.$teacherrow['stc_school_teacher_firstname'].' - '.$teacherrow['stc_school_teacher_teachid'].'</option>';

                ?> 
                <?php 
                  }                                          
                ?>
                
              </select>
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Subject
              </h5>
              <select
                name="stcschoolschedulesubject"
                type="text"
                class="form-control validate stcschoolschedulesubject"
                ><option value="NA">Select</option>
                <?php
                  $subject_sql=mysqli_query($con, "
                      select * from stc_school_subject where stc_school_subject_status=1
                  ");
                  foreach($subject_sql as $subjectrow){
                    echo '<option value="'.$subjectrow['stc_school_subject_id'].'">'.$subjectrow['stc_school_subject_title'].' - '.$subjectrow['stc_school_subject_subid'].'</option>';

                ?> 
                <?php 
                  }                                          
                ?>
                
              </select>
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Class
              </h5>
              <select
                name="stcschoolscheduleclass"
                type="text"
                class="form-control validate stcschoolscheduleclass"
                ><option value="NA">Select</option>
                <?php
                  $sclass_sql=mysqli_query($con, "
                      select * from stc_school_class where stc_school_class_status=1
                  ");
                  foreach($sclass_sql as $sclassrow){
                    echo '<option value="'.$sclassrow['stc_school_class_id'].'">'.$sclassrow['stc_school_class_title'].' - '.$sclassrow['stc_school_class_classid'].'</option>';

                ?> 
                <?php 
                  }      

                  // $date=date();
                  date_default_timezone_set('Asia/Kolkata');
                  $time=date('H:i:s');                                    
                ?>                                      
              </select>
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
              <h5
                for="name"
                >Day
              </h5>
              <select
                name="stcschoolscheduleday"
                type="text"
                class="form-control validate stcschoolscheduleday"
              >
                <option value="NA">Select</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                
              </select>
            </div>
          </div>
          <div class="col-sm-12 col-md-4 col-lg-4">
            <div class="mb-3">
              <h5
                for="name"
                >Period
              </h5>
              <select
                name="stcschoolscheduleperiod"
                class="form-control validate stcschoolscheduleperiod" 
              ><option>Select</option>
              <option value="1"><b>1<span style="vertical-align: top;font-size: 11px;">st</span> Period</b></option>
              <option value="2"><b>2<span style="vertical-align: top;font-size: 11px;">nd</span> Period</b></option>
              <option value="3"><b>3<span style="vertical-align: top;font-size: 11px;">rd</span> Period</b></option>
              <option value="4"><b>4<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></option>
              <option value="5"><b>5<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></option>
              <option value="6"><b>6<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></option>
              <option value="7"><b>7<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></option>
              </select>
            </div>
          </div>
          <div class="col-sm-12 col-md-4 col-lg-4">
            <div class="mb-3">
              <h5
                for="name"
                >Start Time
              </h5>
              <input
                name="stcschoolschedulestarttime"
                type="time"
                class="form-control validate stcschoolschedulestarttime"
                value="<?php echo $time;?>"
              />
            </div>
          </div>
          <div class="col-sm-12 col-md-4 col-lg-4">
            <div class="mb-3">
              <h5
                for="name"
                >End Time
              </h5>
              <input
                name="stcschoolscheduleendtime"
                type="time"
                class="form-control validate stcschoolscheduleendtime"
                value="<?php echo $time;?>"
              />
            </div>
          </div>
          <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="mb-3">
              <button type="button" name="search" class="btn btn-success school-modal-save" id="stcschoolschedulesave">Add Schedule</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


    <!--   Core JS Files   -->
    <script src="../assets/js/core/jquery.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap-material-design.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <!-- Plugin for the momentJs  -->
    <script src="../assets/js/plugins/moment.min.js"></script>
    <!--  Plugin for Sweet Alert -->
    <script src="../assets/js/plugins/sweetalert2.js"></script>
    <!-- Forms Validations Plugin -->
    <script src="../assets/js/plugins/jquery.validate.min.js"></script>
    <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
    <script src="../assets/js/plugins/jquery.bootstrap-wizard.js"></script>
    <!--  Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
    <script src="../assets/js/plugins/bootstrap-selectpicker.js"></script>
    <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
    <script src="../assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
    <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
    <script src="../assets/js/plugins/jquery.dataTables.min.js"></script>
    <!--  Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
    <script src="../assets/js/plugins/bootstrap-tagsinput.js"></script>
    <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
    <script src="../assets/js/plugins/jasny-bootstrap.min.js"></script>
    <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
    <script src="../assets/js/plugins/fullcalendar.min.js"></script>
    <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
    <script src="../assets/js/plugins/jquery-jvectormap.js"></script>
    <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
    <script src="../assets/js/plugins/nouislider.min.js"></script>
    <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
    <!-- Library for adding dinamically elements -->
    <script src="../assets/js/plugins/arrive.min.js"></script>
    <!--  Google Maps Plugin    -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
    <!-- Chartist JS -->
    <script src="../assets/js/plugins/chartist.min.js"></script>
    <!--  Notifications Plugin    -->
    <script src="../assets/js/plugins/bootstrap-notify.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../assets/js/material-dashboard.js?v=2.1.2" type="text/javascript"></script>
    <!-- Material Dashboard DEMO methods, don't include it in your project! -->
    <script src="../assets/demo/demo.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script>
      $(document).ready(function() {
        $().ready(function() {
          $sidebar = $('.sidebar');

          $sidebar_img_container = $sidebar.find('.sidebar-background');

          $full_page = $('.full-page');

          $sidebar_responsive = $('body > .navbar-collapse');

          window_width = $(window).width();

          fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

          if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
            if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
              $('.fixed-plugin .dropdown').addClass('open');
            }

          }

          $('.fixed-plugin a').click(function(event) {
            // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
            if ($(this).hasClass('switch-trigger')) {
              if (event.stopPropagation) {
                event.stopPropagation();
              } else if (window.event) {
                window.event.cancelBubble = true;
              }
            }
          });

          $('.fixed-plugin .active-color span').click(function() {
            $full_page_background = $('.full-page-background');

            $(this).siblings().removeClass('active');
            $(this).addClass('active');

            var new_color = $(this).data('color');

            if ($sidebar.length != 0) {
              $sidebar.attr('data-color', new_color);
            }

            if ($full_page.length != 0) {
              $full_page.attr('filter-color', new_color);
            }

            if ($sidebar_responsive.length != 0) {
              $sidebar_responsive.attr('data-color', new_color);
            }
          });

          $('.fixed-plugin .background-color .badge').click(function() {
            $(this).siblings().removeClass('active');
            $(this).addClass('active');

            var new_color = $(this).data('background-color');

            if ($sidebar.length != 0) {
              $sidebar.attr('data-background-color', new_color);
            }
          });

          $('.fixed-plugin .img-holder').click(function() {
            $full_page_background = $('.full-page-background');

            $(this).parent('li').siblings().removeClass('active');
            $(this).parent('li').addClass('active');


            var new_image = $(this).find("img").attr('src');

            if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
              $sidebar_img_container.fadeOut('fast', function() {
                $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
                $sidebar_img_container.fadeIn('fast');
              });
            }

            if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
              var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

              $full_page_background.fadeOut('fast', function() {
                $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
                $full_page_background.fadeIn('fast');
              });
            }

            if ($('.switch-sidebar-image input:checked').length == 0) {
              var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
              var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

              $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
              $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
            }

            if ($sidebar_responsive.length != 0) {
              $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
            }
          });

          $('.switch-sidebar-image input').change(function() {
            $full_page_background = $('.full-page-background');

            $input = $(this);

            if ($input.is(':checked')) {
              if ($sidebar_img_container.length != 0) {
                $sidebar_img_container.fadeIn('fast');
                $sidebar.attr('data-image', '#');
              }

              if ($full_page_background.length != 0) {
                $full_page_background.fadeIn('fast');
                $full_page.attr('data-image', '#');
              }

              background_image = true;
            } else {
              if ($sidebar_img_container.length != 0) {
                $sidebar.removeAttr('data-image');
                $sidebar_img_container.fadeOut('fast');
              }

              if ($full_page_background.length != 0) {
                $full_page.removeAttr('data-image', '#');
                $full_page_background.fadeOut('fast');
              }

              background_image = false;
            }
          });

          $('.switch-sidebar-mini input').change(function() {
            $body = $('body');

            $input = $(this);

            if (md.misc.sidebar_mini_active == true) {
              $('body').removeClass('sidebar-mini');
              md.misc.sidebar_mini_active = false;

              $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

            } else {

              $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

              setTimeout(function() {
                $('body').addClass('sidebar-mini');

                md.misc.sidebar_mini_active = true;
              }, 300);
            }

            // we simulate the window Resize so the charts will get updated in realtime.
            var simulateWindowResize = setInterval(function() {
              window.dispatchEvent(new Event('resize'));
            }, 180);

            // we stop the simulation of Window Resize after the animations are completed
            setTimeout(function() {
              clearInterval(simulateWindowResize);
            }, 1000);

          });
        });
      });
    </script>
    <script>
      $(document).ready(function() {
        // Javascript method's body can be found in assets/js/demos.js
        md.initDashboardPageCharts();

      });
    </script>
    <script>
      $(document).ready(function() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const value = urlParams.get('school-management');
        const section = urlParams.get('school-section') || 'teachers';
        if(value=="yes"){
          $('.school-management-' + section).addClass('active');
        }
      });
    </script>
    <script>
      $(document).ready(function(){
        $('.close-icon').on('click', function(e){
          e.preventDefault();
            $('.stc-electro-pd-show-ch').fadeOut(500);
        });

        $('.upward').on('click', function(e){
          e.preventDefault();
            $('.downward').toggle(500);
            $('.stc-electro-pd-show-ch').fadeOut(500);
            $('.upward').fadeOut(500);
        });

        $('.downward').on('click', function(e){
          e.preventDefault();
            $('.upward').toggle(500);
            $('.stc-electro-pd-show-ch').toggle(500);
            $('.downward').fadeOut(500);
        });

        $(".ddd").on("click", function(w) {
          w.preventDefault();
          var $button = $(this);
          var $input = $button.closest('.sp-quantity').find("input.quntity-input");
          $input.val((i, v) => Math.max(0, +v + 1 * $button.data('multi')));
        });
      });
    </script>
    <!-- data show section -->
    <script>
      $(document).ready(function(){
        var schoolRecPageState = { teacher_page: 1, student_page: 1, student_per_page: 25, subject_page: 1, class_page: 1 };

        function schoolStudentUploadAlert(title, text, type) {
          if (window.Swal && typeof window.Swal.fire === 'function') {
            window.Swal.fire(title, text, type || 'info');
          } else if (typeof window.swal === 'function') {
            window.swal(title, text, type || 'info');
          } else {
            alert(title + (text ? "\n" + text : ""));
          }
        }

        /* Bootstrap's tab.js is not loaded on this page; toggle panes manually (Material Dashboard + BM-D only). */
        $(document).on('click', '.school-mgmt-header-tabs .nav-link[href^="#"]', function (e) {
          e.preventDefault();
          var $link = $(this);
          var sel = $link.attr('href');
          if (!sel || sel.indexOf('#') !== 0) return;
          var $shell = $link.closest('.school-card-shell');
          if (!$shell.length) return;

          var $pane = $(sel);
          if (!$pane.length || !$pane.hasClass('tab-pane')) return;

          $shell.find('.school-mgmt-header-tabs .nav-link').removeClass('active');
          $shell.find('.tab-content > .tab-pane').removeClass('active');

          $link.addClass('active');
          $pane.addClass('active');

          $shell.find('.school-mgmt-header-tabs .nav-link[role="tab"]').attr('aria-selected', 'false');
          $link.attr('aria-selected', 'true');

          /* Schedule grid is date-driven; refresh when that tab opens */
          if (sel === '#stc-create-shedule' && typeof load_schedule === 'function') {
            load_schedule();
          }
        });

        $("#teachersearch").on("keyup", function() {
          clearTimeout(window.__stc_mgmt_ttimer);
          window.__stc_mgmt_ttimer = setTimeout(function(){ call_records({teacher_page: 1}); }, 350);
        });

        $("#studentsearch").on("keyup", function() {
          clearTimeout(window.__stc_mgmt_sttimer);
          window.__stc_mgmt_sttimer = setTimeout(function(){ call_records({student_page: 1}); }, 350);
        });

        $("#studentPageLimit").on("change", function() {
          var perPage = parseInt($(this).val(), 10) || 25;
          call_records({student_page: 1, student_per_page: perPage});
        });

        $(".stc-student-excel-upload-btn").on("click", function(e) {
          e.preventDefault();
          $("#studentExcelUpload").trigger("click");
        });

        $("#studentExcelUpload").on("change", function() {
          var input = this;
          if (!input.files || !input.files.length) return;

          var formData = new FormData();
          formData.append("stc_student_excel_upload_action", "1");
          formData.append("student_excel_file", input.files[0]);

          $.ajax({
            url: "../vanaheim/school-management.php",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function(response) {
              input.value = "";
              if (!response || response.status === "reload") {
                window.location.reload();
                return;
              }
              if (response.status !== "success") {
                schoolStudentUploadAlert("Upload failed", response.message || "Could not upload student file.", "error");
                return;
              }
              var msg = "Inserted: " + (response.inserted || 0) + ", duplicates: " + (response.duplicates || 0) + ", failed: " + (response.failed || 0) + ".";
              if (response.details && response.details.length) {
                msg += "\n\n" + response.details.slice(0, 6).join("\n");
              }
              schoolStudentUploadAlert("Student upload complete", msg, response.failed > 0 ? "warning" : "success");
              call_records({student_page: 1});
            },
            error: function() {
              input.value = "";
              schoolStudentUploadAlert("Upload failed", "Could not reach server. Please try again.", "error");
            }
          });
        });

        $("#classsearch").on("keyup", function() {
          clearTimeout(window.__stc_mgmt_ctimer);
          window.__stc_mgmt_cttimer = setTimeout(function(){ call_records({class_page: 1}); }, 350);
        });

        $("#subjectsearch").on("keyup", function() {
          clearTimeout(window.__stc_mgmt_subtimer);
          window.__stc_mgmt_subtimer = setTimeout(function(){ call_records({subject_page: 1}); }, 350);
        });

        $("#schedulesearch").on("keyup", function() {
          var value = $(this).val().toLowerCase();
          $(".stc-schedule-rec-show tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
          });
        });

        // save teacher to db
        $(document).on('click', '#stcschoolteachersave', function(e){
          e.preventDefault();
          var stcschoolmanagementteacherid              = $('.stcschoolmanagementteacherid').val();
          var stcschoolmanagementteacherfirstname       = $('.stcschoolmanagementteacherfirstname').val();
          var stcschoolmanagementteacherlastname        = $('.stcschoolmanagementteacherlastname').val();
          var stcschoolmanagementteacherdateofbirth     = $('.stcschoolmanagementteacherdateofbirth').val();
          var stcschoolmanagementteachergender          = $('.stcschoolmanagementteachergender:checked').val();
          var stcschoolmanagementteacherbloodgroup      = $('.stcschoolmanagementteacherbloodgroup').val();
          var stcschoolmanagementteacheremail           = $('.stcschoolmanagementteacheremail').val();
          var stcschoolmanagementteachernumber          = $('.stcschoolmanagementteachernumber').val();
          var stcschoolmanagementteacheraddress         = $('.stcschoolmanagementteacheraddress').val();
          var stcschoolmanagementteacherskills          = $('.stcschoolmanagementteacherskills').val();
          var stcschoolmanagementteacherreligion        = $('.stcschoolmanagementteacherreligion').val();
          var stcschoolmanagementteacherjoiningdate     = $('.stcschoolmanagementteacherjoiningdate').val();
          var stcschoolmanagementteacherremarks         = $('.stcschoolmanagementteacherremarks').val();
          $.ajax({  
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stcschoolmanagementteacherid:stcschoolmanagementteacherid,
              stcschoolmanagementteacherfirstname:stcschoolmanagementteacherfirstname,
              stcschoolmanagementteacherlastname:stcschoolmanagementteacherlastname,
              stcschoolmanagementteacherdateofbirth:stcschoolmanagementteacherdateofbirth,
              stcschoolmanagementteachergender:stcschoolmanagementteachergender,
              stcschoolmanagementteacherbloodgroup:stcschoolmanagementteacherbloodgroup,
              stcschoolmanagementteacheremail:stcschoolmanagementteacheremail,
              stcschoolmanagementteachernumber:stcschoolmanagementteachernumber,
              stcschoolmanagementteacheraddress:stcschoolmanagementteacheraddress,
              stcschoolmanagementteacherskills:stcschoolmanagementteacherskills,
              stcschoolmanagementteacherreligion:stcschoolmanagementteacherreligion,
              stcschoolmanagementteacherjoiningdate:stcschoolmanagementteacherjoiningdate,
              stcschoolmanagementteacherremarks:stcschoolmanagementteacherremarks,
              save_teacheradd_action : 1
            },
            // dataType: `JSON`,
            success   : function(data){
             // console.log(data);
             var response=data.trim();
             if(response=="success"){
              alert("Record saved successfully.");
              window.location.reload();
             }else if(response=="reload"){
              window.location.reload();
             }else if(response=="empty"){
              alert("Do not let any field empty.");
             }else if(response=="wrong"){
              alert("Something went wrong record not saved! Please try again.");
             }else if(response=="duplicate"){
              alert("Duplicate details found! Please check contact and email and try again.");
             }
            }
          });
        });


        // save student to db
        $(document).on('click', '#stcschoolstudentsave', function(e){
          e.preventDefault();
          var stcschoolmanagementstudentid              = $('.stcschoolmanagementstudentid').val();
          var stcschoolmanagementstudentfirstname       = $('.stcschoolmanagementstudentfirstname').val();
          var stcschoolmanagementstudentlastname        = $('.stcschoolmanagementstudentlastname').val();
          var stcschoolmanagementstudentdateofbirth     = $('.stcschoolmanagementstudentdateofbirth').val();
          var stcschoolmanagementstudentgender          = $('.stcschoolmanagementstudentgender:checked').val();
          var stcschoolmanagementstudentbloodgroup      = $('.stcschoolmanagementstudentbloodgroup').val();
          var stcschoolmanagementstudentemail           = $('.stcschoolmanagementstudentemail').val();
          var stcschoolmanagementstudentnumber          = $('.stcschoolmanagementstudentnumber').val();
          var stcschoolmanagementStudentaddress         = $('.stcschoolmanagementStudentaddress').val();
          var stcschoolmanagementstudentreligion          = $('.stcschoolmanagementstudentreligion').val();
          var stcschoolmanagementstudentjoiningdate        = $('.stcschoolmanagementstudentjoiningdate').val();
          var stcschoolmanagementstudentclassroom     = $('.stcschoolmanagementstudentclassroom').val();
          var stcschoolmanagementstudentparentguardianfullname         = $('.stcschoolmanagementstudentparentguardianfullname').val();
          var stcschoolmanagementstudentremarks         = $('.stcschoolmanagementstudentremarks').val();
          $.ajax({  
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stcschoolmanagementstudentid:stcschoolmanagementstudentid,
              stcschoolmanagementstudentfirstname:stcschoolmanagementstudentfirstname,
              stcschoolmanagementstudentlastname:stcschoolmanagementstudentlastname,
              stcschoolmanagementstudentdateofbirth:stcschoolmanagementstudentdateofbirth,
              stcschoolmanagementstudentgender:stcschoolmanagementstudentgender,
              stcschoolmanagementstudentbloodgroup:stcschoolmanagementstudentbloodgroup,
              stcschoolmanagementstudentemail:stcschoolmanagementstudentemail,
              stcschoolmanagementstudentnumber:stcschoolmanagementstudentnumber,
              stcschoolmanagementStudentaddress:stcschoolmanagementStudentaddress,
              stcschoolmanagementstudentreligion:stcschoolmanagementstudentreligion,
              stcschoolmanagementstudentjoiningdate:stcschoolmanagementstudentjoiningdate,
              stcschoolmanagementstudentclassroom:stcschoolmanagementstudentclassroom,
              stcschoolmanagementstudentparentguardianfullname:stcschoolmanagementstudentparentguardianfullname,
              stcschoolmanagementstudentremarks:stcschoolmanagementstudentremarks,
              save_studentadd_action : 1
            },
            // dataType: `JSON`,
            success   : function(data){
             // console.log(data);
             var response=data.trim();
             if(response=="success"){
              alert("Record saved successfully.");
              // window.location.reload();
             }else if(response=="reload"){
              window.location.reload();
             }else if(response=="empty"){
              alert("Do not let any field empty.");
             }else if(response=="wrong"){
              alert("Something went wrong record not saved! Please try again.");
             }else if(response=="duplicate"){
              alert("Duplicate details found! Please check contact and email other, details and try again.");
             }
            }
          });
        });


        // save subject to db
        $(document).on('click', '#stcschoolsubjectsave', function(e){
          e.preventDefault();
          var stcschoolmanagementsubjectid        = $('.stcschoolmanagementsubjectid').val();
          var stcschoolmanagementsubjecttitle     = $('.stcschoolmanagementsubjecttitle').val();
          var stcschoolmanagementsubjectdetails   = $('.stcschoolmanagementsubjectdetails').val();
          $.ajax({  
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stcschoolmanagementsubjectid:stcschoolmanagementsubjectid,
              stcschoolmanagementsubjecttitle:stcschoolmanagementsubjecttitle,
              stcschoolmanagementsubjectdetails:stcschoolmanagementsubjectdetails,
              save_subjectadd_action : 1
            },
            // dataType: `JSON`,
            success   : function(data){
             // console.log(data);
             var response=data.trim();
             if(response=="success"){
              alert("Record saved successfully.");
              window.location.reload();
             }else if(response=="reload"){
              window.location.reload();
             }else if(response=="empty"){
              alert("Do not let any field empty.");
             }else if(response=="wrong"){
              alert("Something went wrong record not saved! Please try again.");
             }else if(response=="duplicate"){
              alert("Duplicate details found! Please check and try again.");
             }
            }
          });
        });

        // get url 
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const value = urlParams.get('modal');
        
        var syllabus_id=0;
        if(value=="access"){
          $('.stc-school-addsyllabus-res').modal('show');
        }
        if(value=="accessview"){
          $('.stc-school-viewsyllabus-res').modal('show');
          syllabus_id = urlParams.get('id');
          load_syllabus_pert(syllabus_id)
        }

        // save syllabus
        $(document).on('click', '.stc-school-sylsave-btn', function(e){
          e.preventDefault();
          var title=$('.stc-school-sylTitle').val();
          var chapter=$('.stc-school-sylChapter').val();
          var lesson=$('.stc-school-sylLesson').val();
          var unit=$('.stc-school-sylUnit').val();
          var date=$('.stc-school-sylDate').val();
          var subject_id = urlParams.get('id');
          $.ajax({  
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stc_add_syllabus_action : 1,
              stc_subject_id:subject_id,
              stc_title:title,
              stc_chapter:chapter,
              stc_lesson:lesson,
              stc_unit:unit,
              stc_date:date
            },
            success   : function(response){
             // console.log(response);
             response=response.trim();
             if(response=="success"){
              alert("Syllabus saved successfully.");
              $('.stc-school-addsyllabus-res .form-control').val('');
             }else if(response=="empty"){
              alert("Please enter title.");
             }else if(response="failed"){
              alert("Syllabus not saved. Please check and try again");
             }else if(response="reload"){
              window.location.reload();
             }
            }
          });
        });

        // save class to db
        $(document).on('click', '#stcschoolclassroomsave', function(e){
          e.preventDefault();
          var stcschoolmanagementclassroomid        = $('.stcschoolmanagementclassroomid').val();
          var stcschoolmanagementclassroomtitle     = $('.stcschoolmanagementclassroomtitle').val();
          var stcschoolmanagementclassroomlocation   = $('.stcschoolmanagementclassroomlocation').val();
          var stcschoolmanagementclassroomcapacity   = $('.stcschoolmanagementclassroomcapacity').val();
          $.ajax({  
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stcschoolmanagementclassroomid:stcschoolmanagementclassroomid,
              stcschoolmanagementclassroomtitle:stcschoolmanagementclassroomtitle,
              stcschoolmanagementclassroomlocation:stcschoolmanagementclassroomlocation,
              stcschoolmanagementclassroomcapacity:stcschoolmanagementclassroomcapacity,
              save_classadd_action : 1
            },
            // dataType: `JSON`,
            success   : function(data){
             // console.log(data);
             var response=data.trim();
             if(response=="success"){
              alert("Record saved successfully.");
              window.location.reload();
             }else if(response=="reload"){
              window.location.reload();
             }else if(response=="empty"){
              alert("Do not let any field empty.");
             }else if(response=="wrong"){
              alert("Something went wrong record not saved! Please try again.");
             }else if(response=="duplicate"){
              alert("Duplicate details found! Please check and try again.");
             }
            }
          });
        });

        // save shedule to db
        $(document).on('click', '#stcschoolschedulesave', function(e){
          e.preventDefault();
          var stcschoolscheduletype         = $('.stcschoolscheduletype').val();
          var stcschoolscheduleteacher      = $('.stcschoolscheduleteacher').val();
          var stcschoolschedulesubject      = $('.stcschoolschedulesubject').val();
          var stcschoolscheduleclass        = $('.stcschoolscheduleclass').val();
          var stcschoolscheduleday          = $('.stcschoolscheduleday').val();
          var stcschoolschedulestarttime    = $('.stcschoolschedulestarttime').val();
          var stcschoolscheduleendtime      = $('.stcschoolscheduleendtime').val();
          var stcschoolscheduleperiod      = $('.stcschoolscheduleperiod').val();
          $.ajax({  
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stcschoolscheduleteacher:stcschoolscheduleteacher,
              stcschoolscheduletype:stcschoolscheduletype,
              stcschoolschedulesubject:stcschoolschedulesubject,
              stcschoolscheduleclass:stcschoolscheduleclass,
              stcschoolscheduleday:stcschoolscheduleday,
              stcschoolschedulestarttime:stcschoolschedulestarttime,
              stcschoolscheduleendtime:stcschoolscheduleendtime,
              stcschoolscheduleperiod:stcschoolscheduleperiod,
              save_schduleadd_action : 1
            },
            // dataType: `JSON`,
            success   : function(data){
             // console.log(data);
             var response=data.trim();
             if(response=="success"){
              alert("Record saved successfully.");
              $('.stc-school-showschedule-res').modal('hide');
              load_schedule();
             }else if(response=="reload"){
              window.location.reload();
             }else if(response=="empty"){
              alert("Do not let any field empty.");
             }else if(response=="wrong"){
              alert("Something went wrong record not saved! Please try again.");
             }else if(response=="duplicate"){
              alert("Busy schedule, Please check availability first.");
             }
            }
          });
        });

        // show data sections (paginated lists)
        function call_records(extra){
          if (extra){ $.extend(schoolRecPageState, extra); }
          $.ajax({  
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stc_load_record_action : 1,
              teacher_page : schoolRecPageState.teacher_page,
              student_page : schoolRecPageState.student_page,
              student_per_page : schoolRecPageState.student_per_page,
              subject_page : schoolRecPageState.subject_page,
              class_page : schoolRecPageState.class_page,
              teacher_search : ($('#teachersearch').val()||'').trim(),
              student_search : ($('#studentsearch').val()||'').trim(),
              subject_search : ($('#subjectsearch').val()||'').trim(),
              class_search : ($('#classsearch').val()||'').trim()
            },
            dataType: `JSON`,
            success   : function(response){
             // console.log(response);
             if(response.status=="success"){
              $('.stc-teacher-rec-show').html(response.response_teacher || '');
              $('.stc-teacher-pagination').html(response.response_teacher_pagination || '');

              $('.stc-student-rec-show').html(response.response_student || '');
              $('.stc-student-pagination').html(response.response_student_pagination || '');

              $('.stc-subject-rec-show').html(response.response_subject || '');
              $('.stc-subject-pagination').html(response.response_subject_pagination || '');

              $('.stc-classroom-rec-show').html(response.response_class || '');
              $('.stc-classroom-pagination').html(response.response_class_pagination || '');
              
             }else if(response.status=="reload"){
              window.location.reload();
             }
            }
          });
        }

        $(document).on('click', '.school-rec-pagination .page-link[data-page]', function(e){
          e.preventDefault();
          var $a = $(this);
          var $li = $a.closest('.page-item');
          if ($li.hasClass('disabled')) return false;
          var pg = parseInt($a.attr('data-page'), 10);
          if (!pg || pg < 1) return false;
          var $navUl = $a.closest('[data-pagination-for]');
          if (!$navUl.length) return false;
          var kind = $navUl.attr('data-pagination-for');
          var map = {teacher:'teacher_page',student:'student_page',subject:'subject_page',classroom:'class_page'};
          var key = map[kind];
          if (!key || $li.hasClass('active')) return false;
          var patch={};
          patch[key]=pg;
          call_records(patch);
          return false;
        });

        call_records();

        load_schedule();
        function load_schedule(){
          var day=$('.stc-schedule-week').val();
          if(day && day!=="Select"){
            $.ajax({  
              url       : "../vanaheim/school-management.php",
              method    : "POST",  
              data      : {
                stc_load_schedule_action : 1,
                day:day
              },
              dataType: `JSON`,
              success   : function(response){
               // console.log(response);
               if(response.status=="success"){

                var schedule=response.response_schedule;
                $('.stc-schedule-rec-show').html(schedule);
                // $('.remove.icon').hide();

                
               }else if(response.status=="reload"){
                window.location.reload();
               }
              }
            });
          }
        }

        $(document).on('change', '.stc-schedule-week', function(e){
          e.preventDefault();
          load_schedule();
        });

        $(document).on('hover', '.schedule-box', function(e){
          e.preventDefault();
          $('.remove.icon').show();
        });

        $(document).on('click', '.hover-box', function(e){
          e.preventDefault();
          var box_name = $(this).attr('id');
          $('.schedule-box').css({ 'color': 'white', 'background': 'linear-gradient(37deg, #d4fffd , #1de4ff)', 'font-size': '20px' });
          $('.box-rep-'+box_name).css({ 'color': 'white', 'background': 'linear-gradient(37deg, rgb(24 129 124), rgb(158 243 255)) black', 'font-size': '20px' });
        });
        
        $(document).on('click', '.stc-remove-schedule-btn', function(e){
          e.preventDefault();
          var sched_id=$(this).attr("id");
          if (confirm("Are you sure you want to remove this schedule.") == true) {
            $.ajax({  
              url       : "../vanaheim/school-management.php",
              method    : "POST",  
              data      : {
                stc_remove_schedule_action : 1,
                sched_id:sched_id
              },
              dataType: `JSON`,
              success   : function(response){
               // console.log(response);
               if(response.status=="success"){
                alert(response.message);
                load_schedule();
               }else if(response.status==="failed"){
                alert(response.message);
               }else if(response.status=="reload"){
                window.location.reload();
               }
              }
            });
          }
        });
        
        function load_syllabus_pert(syllabus_id){
          $.ajax({  
              url       : "../vanaheim/school-management.php",
              method    : "POST",  
              data      : {
                stc_get_syllabus_details : 1,
                syllabus_id:syllabus_id
              },
              dataType: `JSON`,
              success   : function(response){
                // console.log(response);
                if(response.status=="success"){
                  var syllabus=response.data;
                  $('.show-syllabus-data').html(syllabus);
                }else if(response.status=="reload"){
                  window.location.reload();
                }
              }
            });
        }

        $(document).on('click', '.stc-school-show-teach-btn', function(e){
          e.preventDefault();
          call_records();
          $('.stc-school-showteacher-res').modal('show');
        });

        $(document).on('click', '.stc-school-show-stud-btn', function(e){
          e.preventDefault();
          call_records();
          $('.stc-school-showstud-res').modal('show');
        }); 

        $(document).on('click', '.stc-school-show-subject-btn', function(e){
          e.preventDefault();
          call_records();
          $('.stc-school-showsub-res').modal('show');
        }); 

        $(document).on('click', '.stc-school-show-classroom-btn', function(e){
          e.preventDefault();
          call_records();
          $('.stc-school-showclassroom-res').modal('show');
        }); 

        $(document).on('click', '.stc-school-show-schedule-btn', function(e){
          e.preventDefault();
          $('.stc-school-showschedule-res').modal('show');
          load_schedule();
        });

        $(document).on('click', '.stc-add-schedule-from-na', function(e){
          e.preventDefault();
          var $slot = $(this);
          var classId = $slot.data('class-id');
          var day = $slot.data('day');
          var period = $slot.data('period');

          if (day && day !== 'Select') {
            $('.stcschoolscheduleday').val(day);
          } else {
            var weekDay = $('.stc-schedule-week').val();
            if (weekDay && weekDay !== 'Select') {
              $('.stcschoolscheduleday').val(weekDay);
            }
          }

          if (classId) {
            $('.stcschoolscheduleclass').val(String(classId));
          }

          if (period) {
            $('.stcschoolscheduleperiod').val(String(period));
          }

          $('.stc-school-showschedule-res').modal('show');
        });

        $(document).on('keydown', '.stc-add-schedule-from-na', function(e){
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            $(this).trigger('click');
          }
        });


        $(document).on('click', '.stc-class-upd-btn-show', function(e){
          e.preventDefault();
          $(this).hide();
          $(this).parent().parent().find('.stc-class-upd-btn-save').show();
          $(this).parent().parent().find('.stc-class-title-upd').show();
          $(this).parent().parent().find('.stc-class-location-upd').show();
          $(this).parent().parent().find('.stc-class-capacity-upd').show();
        }); 

        $(document).on('click', '.stc-class-upd-btn-save', function(e){
          e.preventDefault();
          $(this).hide();
          $(this).parent().parent().find('.stc-class-title-upd').hide();
          $(this).parent().parent().find('.stc-class-location-upd').hide();
          $(this).parent().parent().find('.stc-class-capacity-upd').hide();
          $(this).parent().parent().find('.stc-class-upd-btn-show').show();
        }); 
        
      });
    </script>
  </body>
</html>
