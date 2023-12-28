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
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <!-- <link href="../assets/demo/demo.css" rel="stylesheet" /> -->
    <style>
        .fade:not(.show) {
          opacity: 10;
        }
      .d-flex {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
      }

      .mb-3{
        border-block: initial;
        border: 2px solid grey;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        padding: 10px;
        margin: 5px;
      }
      .align-center {
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
      }

      .flex-centerY-centerX {
        justify-content: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
      }

      .page-wrapper {
        height: 100%;
        display: table;
      }

      .page-wrapper .page-inner {
        display: table-cell;
        vertical-align: middle;
      }

      .el-wrapper {
        width: 360px;
        padding: 15px;
        margin: 15px auto;
        background-color: #fff;
      }

      @media (max-width: 991px) {
        .el-wrapper {
          width: 345px;
        }
      }

      @media (max-width: 767px) {
        .el-wrapper {
          width: 290px;
          margin: 30px auto;
        }
      }

      .el-wrapper:hover .h-bg {
        left: 0px;
      }

      .el-wrapper:hover .price {
        left: 20px;
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -o-transform: translateY(-50%);
        transform: translateY(-50%);
        color: #818181;
      }

      .el-wrapper:hover .add-to-cart {
        left: 50%;
      }

      .el-wrapper:hover .img {
        webkit-filter: blur(7px);
        -o-filter: blur(7px);
        -ms-filter: blur(7px);
        filter: blur(7px);
        filter: progid:DXImageTransform.Microsoft.Blur(pixelradius='7', shadowopacity='0.0');
        opacity: 0.4;
      }

      .el-wrapper:hover .info-inner {
        bottom: 155px;
      }

      .el-wrapper:hover .a-size {
        -webkit-transition-delay: 300ms;
        -o-transition-delay: 300ms;
        transition-delay: 300ms;
        bottom: 50px;
        opacity: 1;
      }

      .el-wrapper .box-down {
        width: 100%;
        height: 60px;
        position: relative;
        overflow: hidden;
      }

      .el-wrapper .box-up {
        width: 100%;
        height: 400px;
        position: relative;
        overflow: hidden;
        text-align: center;
      }

      .el-wrapper .img {
        padding: 20px 0;
        -webkit-transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
        -moz-transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
        -o-transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
        transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        -webkit-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -moz-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -o-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
      }

      .h-bg {
        -webkit-transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
        -moz-transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
        -o-transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
        transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        -webkit-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -moz-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -o-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        width: 660px;
        height: 100%;
        background-color: #3f96cd;
        position: absolute;
        left: -659px;
      }

      .h-bg .h-bg-inner {
        width: 50%;
        height: 100%;
        background-color: #464646;
      }

      .info-inner {
        -webkit-transition: all 400ms cubic-bezier(0, 0, 0.18, 1);
        -moz-transition: all 400ms cubic-bezier(0, 0, 0.18, 1);
        -o-transition: all 400ms cubic-bezier(0, 0, 0.18, 1);
        transition: all 400ms cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        -webkit-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -moz-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -o-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        position: absolute;
        width: 100%;
        bottom: 25px;
      }

      .info-inner .p-name,
      .info-inner .p-company,
       .info-inner .p-qty {
        display: block;
      }

      .info-inner .p-name {
        font-family: 'PT Sans', sans-serif;
        font-size: 18px;
        color: #252525;
      }

      .info-inner .p-company {
        font-family: 'Lato', sans-serif;
        font-size: 12px;
        text-transform: uppercase;
        color: #8c8c8c;
      }

      .info-inner .p-qty {
        font-family: 'Lato', sans-serif;
        font-size: 25px;
        font-weight: bold;
        text-transform: uppercase;
        color: #020822;;
      }

      .a-size {
        -webkit-transition: all 300ms cubic-bezier(0, 0, 0.18, 1);
        -moz-transition: all 300ms cubic-bezier(0, 0, 0.18, 1);
        -o-transition: all 300ms cubic-bezier(0, 0, 0.18, 1);
        transition: all 300ms cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        -webkit-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -moz-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -o-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        position: absolute;
        width: 100%;
        bottom: -20px;
        font-family: 'PT Sans', sans-serif;
        color: #828282;
        opacity: 0;
      }

      .a-size .size {
        color: #252525;
      }

      .cart {
        display: block;
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        font-family: 'Lato', sans-serif;
        font-weight: 700;
      }

      .cart .price {
        -webkit-transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
        -moz-transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
        -o-transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
        transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        -webkit-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -moz-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -o-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        -webkit-transition-delay: 100ms;
        -o-transition-delay: 100ms;
        transition-delay: 100ms;
        display: block;
        position: absolute;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        -o-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        font-size: 16px;
        color: #252525;
      }

      .cart .add-to-cart {
        -webkit-transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
        -moz-transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
        -o-transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
        transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        -webkit-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -moz-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -o-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        -webkit-transition-delay: 100ms;
        -o-transition-delay: 100ms;
        transition-delay: 100ms;
        display: block;
        position: absolute;
        top: 50%;
        left: 110%;
        -webkit-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        -o-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
      }

      .cart .add-to-cart .txt {
        font-size: 12px;
        color: #fff;
        letter-spacing: 0.045em;
        text-transform: uppercase;
        white-space: nowrap;
      }

      .bootstrap-tagsinput {
       width: 100%;
       color: blue;
      }
      .bootstrap-tagsinput .tag {
         color: black;
       }

      .search-box,.close-icon,.search-wrapper {
        position: relative;
        padding: 10px;
      }
      .search-wrapper {
        width: 500px;
        margin: auto;
        margin-top: 50px;
      }
      .search-box {
        width: 98%;
        border: 1px solid #ccc;
        outline: 0;
        border-radius: 15px;
      }
      .search-box:focus {
        box-shadow: 0 0 15px 5px #b0e0ee;
        border: 2px solid #bebede;
      }
      .close-icon {
        border:1px solid transparent;
        background-color: transparent;
        display: inline-block;
        vertical-align: middle;
        outline: 0;
        cursor: pointer;
      }
      .close-icon:after {
        content: "X";
        display: block;
        width: 15px;
        height: 15px;
        position: absolute;
        background-color: #FA9595;
        z-index:1;
        right: 35px;
        top: 0;
        bottom: 0;
        margin: auto;
        padding: 0px;
        border-radius: 50%;
        text-align: center;
        color: white;
        font-weight: normal;
        font-size: 12px;
        box-shadow: 0 0 2px #E50F0F;
        cursor: pointer;
      }
      .search-box:not(:valid) ~ .close-icon {
        display: none;
      }
      .stc_print_page i{
        color: black;
      }

      .remove.icon{
        display: none;
      }

      .schedule-box{
        background: linear-gradient(37deg, #d4fffd , #1de4ff)
      }
      .schedule-box a{
        color: #7e6060;
        text-decoration: none;
        font-size: 15px;
        font-weight: bold;
      }
      .schedule-box:hover  {
        background: linear-gradient(37deg, #eecda3, #ef629f);
      }

      .schedule-box:hover .remove.icon{
        display: block;
      }

      .schedule-box:hover a{
        font-size: 17px;
        color: black;
      }

      .schedule-box a:hover .remove.icon{
        display: block;
      }

      .schedule-show-na{
        text-align: center;
        font-size: 40px;
        font-weight: bold;
        background: linear-gradient(45deg, #f31414, #c5f72c);
      }
      .remove.icon {
        color: red;
        position: absolute;
        margin-left: 3px;
        margin-top: 38px;
        background-color: #000;
      }

      .remove.icon:before {
        content: '';
        position: absolute;
        width: 15px;
        height: 2px;
        background-color: #000;
        background-color: currentColor;
        -webkit-transform: rotate(45deg);
                transform: rotate(45deg);
      }

      .remove.icon:after {
        content: '';
        position: absolute;
        width: 15px;
        height: 2px;
        background-color: #000;
        background-color: currentColor;
        -webkit-transform: rotate(-45deg);
                transform: rotate(-45deg);
      }

      .hover-box{
        display: block;padding: 5px;
        background: linear-gradient(45deg, #00c9b0, #e9ad02);
        font-size: 20px;
        font-weight: bold;
        color: #4affdf;
      }

      .hover-box:hover{
        background: linear-gradient(45deg, #ff6565, #df7bff);
        text-decoration: underline;
        color: black;
      }
    </style>
  </head>

  <body>
    <div class="wrapper ">
      <?php include_once("bar/sidebar.php");?>
      <div class="main-panel">
        <!-- Navbar -->
        <?php include_once("bar/navbar.php");?>
        <!-- End Navbar -->
        <div class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="card">
                  <div class="card-header card-header-tabs card-header-primary">
                    <div class="nav-tabs-navigation">
                      <div class="nav-tabs-wrapper">
                        <span class="nav-tabs-title">Tasks:</span>
                        <ul class="nav nav-tabs" data-tabs="tabs">
                          <li class="nav-item">
                            <a class="nav-link active" href="#stc-create-teacher" data-toggle="tab">
                              <i class="material-icons">add_circle</i> teacher
                              <div class="ripple-container"></div>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link " href="#stc-create-student" data-toggle="tab">
                              <i class="material-icons">add_circle</i> student
                              <div class="ripple-container"></div>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link " href="#stc-create-subject" data-toggle="tab">
                              <i class="material-icons">add_circle</i> Subject
                              <div class="ripple-container"></div>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link " href="#stc-create-classroom" data-toggle="tab">
                              <i class="material-icons">add_circle</i> Class Room
                              <div class="ripple-container"></div>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link " href="#stc-create-shedule" data-toggle="tab">
                              <i class="material-icons">add_circle</i> Schedule
                              <div class="ripple-container"></div>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="tab-content">
                      <div class="tab-pane active" id="stc-create-teacher">
                        <div class="row">
                          <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Add Teacher</h2>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                            <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                              <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <a
                                      href="javascript:void(0)"
                                      class="form-control btn btn-success stc-school-show-teach-btn"
                                    >Add Teacher</a>
                                  </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
                                  <div class="mb-3"  style="width: auto;overflow-x: auto; white-space: nowrap;" >
                                    <input id="teachersearch" type="text" class="form-control mb-3" placeholder="Search here...">
                                    <table class="table table-hover table-bordered mb-3">
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
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Create Student -->

                      <div class="tab-pane " id="stc-create-student">
                        <div class="row">
                          <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Add Student</h2>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                            <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                              <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <a
                                      href="javascript:void(0)"
                                      class="btn btn-success form-control stc-school-show-stud-btn"
                                      value=""
                                    >Add Student</a>
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3"  style="width: auto;overflow-x: auto; white-space: nowrap;" >
                                    <input id="studentsearch" type="text" class="form-control mb-3" placeholder="Search here...">
                                    <table class="table table-hover table-bordered mb-3">
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
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Field Create Subject -->

                      <div class="tab-pane " id="stc-create-subject">
                        <div class="row">
                          <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Add Subject</h2>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                            <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                              <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <a
                                      href="javascript:void(0)"
                                      class="form-control btn btn-success stc-school-show-subject-btn"
                                      value=""
                                    >Add Subject</a>
                                  </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
                                  <div class="mb-3" style="width: auto;overflow-x: auto; white-space: nowrap;">
                                    <input id="subjectsearch" type="text" class="form-control mb-3" placeholder="Search here...">
                                    <table class="table table-hover table-bordered mb-3">
                                      <thead>
                                        <tr>
                                          <th class="text-center"><b>Subject Id</b></th>
                                          <th class="text-center"><b>Subject Title</b></th>
                                          <th class="text-center"><b>Syllabus Details</b></th>
                                          <th class="text-center"><b>Addd Date</b></th>
                                          <th class="text-center"><b>Addd by</b></th>
                                          <th class="text-center"><b>Action</b></th>
                                          </tr>
                                      </thead>
                                      <tbody class="stc-subject-rec-show"></tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Field Class Room -->

                      <div class="tab-pane " id="stc-create-classroom">
                        <div class="row">
                          <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Add Class Room</h2>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                            <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                              <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <a
                                      href="javascript:void(0)"
                                      class="form-control btn btn-success stc-school-show-classroom-btn"
                                      value=""
                                    >Add Classroom</a>
                                  </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
                                  <div class="mb-3" style="width: auto;overflow-x: auto; white-space: nowrap;">
                                    <input id="classsearch" type="text" class="form-control mb-3" placeholder="Search here...">
                                    <table class="table table-hover table-bordered mb-3">
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
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>                     

                      <!-- Field Schedule Routine -->

                      <div class="tab-pane " id="stc-create-shedule">
                        <div class="row">
                          <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Add Schedule</h2>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                            <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                              <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <a
                                      href="javascript:void(0)"
                                      class="form-control btn btn-success stc-school-show-schedule-btn"
                                      value=""
                                    >Add Schedule</a>
                                  </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
                                  <select class="form-control btn btn-info stc-schedule-week">
                                    <option>Select</option>
                                    <?php
                                    $day_arr=array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
                                    $day=date("l");
                                      foreach($day_arr as $day_arr_row){
                                        if($day==$day_arr_row){
                                          echo '<option selected>'.$day_arr_row.'</option>';
                                        }else{
                                          echo '<option>'.$day_arr_row.'</option>';
                                        }
                                      }
                                    ?>
                                  </select>                       
                                  <div class="mb-3" style="width: auto;overflow-x: auto; white-space: nowrap;">
                                    <input id="schedulesearch" type="text" class="form-control mb-3" placeholder="Search here...">
                                    <table class="table table-hover table-bordered mb-3">
                                      <thead>
                                        <tr>
                                          <td class="text-center"><b>Class</b></td>
                                          <td class="text-center"><b>1<span style="vertical-align: top;font-size: 11px;">st</span> Period</b></td>
                                          <td class="text-center"><b>2<span style="vertical-align: top;font-size: 11px;">nd</span> Period</b></td>
                                          <td class="text-center"><b>3<span style="vertical-align: top;font-size: 11px;">rd</span> Period</b></td>
                                          <td class="text-center"><b>4<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></td>
                                          <td class="text-center"><b>5<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></td>
                                          <td class="text-center"><b>6<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></td>
                                          <td class="text-center"><b>7<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></td>
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
        </div>
      </div>
    </div>

    <!--   Core JS Files   -->
    <script src="../assets/js/core/jquery.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap-material-design.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
        if(value=="yes"){
          $('.school-management').addClass('active');
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
        $("#teachersearch").on("keyup", function() {
          var value = $(this).val().toLowerCase();
          $(".stc-teacher-rec-show tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
          });
        });

        $("#studentsearch").on("keyup", function() {
          var value = $(this).val().toLowerCase();
          $(".stc-student-rec-show tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
          });
        });

        $("#classsearch").on("keyup", function() {
          var value = $(this).val().toLowerCase();
          $(".stc-classroom-rec-show tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
          });
        });

        $("#subjectsearch").on("keyup", function() {
          var value = $(this).val().toLowerCase();
          $(".stc-subject-rec-show tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
          });
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
        if(value=="access"){
          $('.stc-school-addsyllabus-res').modal('show');
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
             }else if(response="empty"){
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
              // window.location.reload();
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

        // show data sections 
        call_records();
        function call_records(){          
          $.ajax({  
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stc_load_record_action : 1
            },
            dataType: `JSON`,
            success   : function(response){
             // console.log(response);
             if(response.status=="success"){
              var teacher=response.response_teacher;
              $('.stc-teacher-rec-show').html(teacher);


              var student=response.response_student;
              $('.stc-student-rec-show').html(student);


              var subject=response.response_subject;
              $('.stc-subject-rec-show').html(subject);


              var classroom=response.response_class;
              $('.stc-classroom-rec-show').html(classroom);
              
             }else if(response.status="reload"){
              window.location.reload();
             }
            }
          });
        }

        load_schedule();
        function load_schedule(){
          var day=$('.stc-schedule-week').val();
          if(day!="Select"){
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

                
               }else if(response.status="reload"){
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
               }else if(response.status="failed"){
                alert(response.message);
               }else if(response.status="reload"){
                window.location.reload();
               }
              }
            });
          }
        });

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
            <button class="btn btn-success stc-school-sylsave-btn form-control">Save</button>  
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
              <button type="button" name="search" class="form-control btn btn-success" id="stcschoolteachersave">Add Teacher</button>
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
                      <button type="button" name="search" class="form-control btn btn-success" id="stcschoolstudentsave">Add Student</button>
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
              <button type="button" name="search" class="form-control btn btn-success" id="stcschoolsubjectsave">Add Subject</button>
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
        <h4 class="modal-title">School Clasroom</h4>
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
              <button type="button" name="search" class="form-control btn btn-success" id="stcschoolclassroomsave">Add Class Room</button>
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
                ><option value="NA">Select</option>
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
              <button type="button" name="search" class="form-control btn btn-success" id="stcschoolschedulesave">Add Schedule</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>