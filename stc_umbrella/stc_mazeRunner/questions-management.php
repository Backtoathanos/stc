<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
require_once __DIR__ . '/includes/school_session_defaults.php';
if(empty(@$_SESSION['stc_school_user_id'])){
    header('location:../index.html');
}
// if($_SESSION['stc_school_user_for']==2){
//     header('location:forbidden.html');
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/png" href="../assets/img/stc_logo_title.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    STC School || School Attendance
  </title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css"
    href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="../assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" />
  <style>
    .stc-schoolattendance-show {
      width: 100%;
      overflow-x: hidden;
      background: #f8f9fd;
      border-radius: 12px;
      padding: 0.35rem 0.2rem 0.55rem;
      border: 1px solid rgba(92, 107, 192, 0.08);
    }

    #stc-questions-datatable_wrapper {
      padding-top: 0.15rem;
    }

    #stc-questions-datatable_wrapper > .row {
      align-items: flex-end;
      margin-left: -10px;
      margin-right: -10px;
    }

    #stc-questions-datatable_wrapper > .row > [class*="col-"] {
      padding-left: 10px;
      padding-right: 10px;
    }

    #stc-questions-datatable_wrapper .dataTables_length,
    #stc-questions-datatable_wrapper .dataTables_filter {
      padding: 0.35rem 0 0.65rem;
    }

    #stc-questions-datatable_wrapper .dataTables_filter label {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      margin: 0;
    }

    #stc-questions-datatable_wrapper .dataTables_filter input {
      border-radius: 8px;
      border: 1px solid #c5cae9;
      padding: 0.42rem 0.75rem;
      min-width: 14rem;
      background: #fff;
      box-shadow: 0 2px 8px rgba(26, 35, 126, 0.05);
    }

    #stc-questions-datatable_wrapper .dataTables_length select {
      border-radius: 8px;
      border: 1px solid #c5cae9;
      padding: 0.35rem 0.5rem;
      background: #fff;
      min-height: 38px;
    }

    #stc-questions-datatable_wrapper .dataTables_info {
      padding-top: 0.75rem;
      font-size: 0.85rem;
      color: #546e7a;
    }

    #stc-questions-datatable_wrapper .dataTables_paginate {
      padding-top: 0.45rem;
    }

    #stc-questions-datatable_wrapper .paginate_button {
      padding: 0.32rem 0.65rem !important;
      border-radius: 8px !important;
      margin: 0 3px !important;
      border: 1px solid rgba(92, 107, 192, 0.3) !important;
      background: #fff !important;
      color: #3949ab !important;
    }

    #stc-questions-datatable_wrapper .paginate_button.disabled {
      opacity: 0.45 !important;
    }

    #stc-questions-datatable_wrapper .paginate_button:hover:not(.disabled) {
      border-color: #3949ab !important;
      background: #eef1fb !important;
      color: #1a237e !important;
    }

    #stc-questions-datatable_wrapper .paginate_button.current,
    #stc-questions-datatable_wrapper .paginate_button.current:hover {
      background: #3949ab !important;
      color: #fff !important;
      border-color: #303f9f !important;
    }

    /* —— Questions Details page (fixes global .mb-3 grey boxes here) —— */
    .stc-questions-mgmt .mb-3,
    .stc-questions-mgmt .form-group {
      border: none !important;
      box-shadow: none !important;
    }

    .stc-q-toolbar-card {
      position: relative;
      color: #fff;
      border-radius: 18px !important;
      overflow: hidden;
      background:
        radial-gradient(circle at top left, rgba(255, 255, 255, 0.22), transparent 30%),
        linear-gradient(135deg, #243c96 0%, #4e63d7 54%, #6f80ee 100%);
      box-shadow: 0 18px 44px rgba(36, 60, 150, 0.26) !important;
    }

    .stc-q-toolbar-card::after {
      content: "";
      position: absolute;
      right: -70px;
      top: -80px;
      width: 190px;
      height: 190px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.12);
      pointer-events: none;
    }

    .stc-q-toolbar-card .card-body {
      position: relative;
      z-index: 1;
      padding: 1.35rem !important;
    }

    .stc-q-toolbar-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      padding-bottom: 1rem;
      margin-bottom: 1rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .stc-q-toolbar-title {
      margin: 0;
      color: #fff;
      font-size: 1.02rem;
      font-weight: 700;
      line-height: 1.25;
    }

    .stc-q-toolbar-subtitle,
    .stc-q-toolbar-card .text-muted-soft {
      margin: 0.25rem 0 0;
      color: rgba(255, 255, 255, 0.76) !important;
      font-size: 0.82rem !important;
      line-height: 1.45;
    }

    .stc-q-btn-add {
      flex: 0 0 auto;
      border-radius: 999px !important;
      padding: 0.55rem 1.1rem !important;
      font-weight: 700 !important;
      letter-spacing: 0.02em !important;
      background: #22c55e !important;
      color: #fff !important;
      border: 1px solid rgba(255, 255, 255, 0.24) !important;
      box-shadow: 0 10px 22px rgba(21, 128, 61, 0.34) !important;
      text-transform: none !important;
    }

    .stc-q-btn-add .material-icons,
    .stc-q-btn-show .material-icons,
    .stc-q-filter-icon {
      font-size: 20px !important;
      line-height: 1 !important;
      margin: 0 0.42rem 0 0 !important;
      vertical-align: middle !important;
    }

    .stc-q-filter-grid {
      display: grid;
      grid-template-columns: minmax(180px, 1fr) minmax(220px, 1.35fr) minmax(190px, 0.85fr);
      gap: 1rem;
      align-items: end;
    }

    .stc-q-field {
      margin: 0 !important;
      min-width: 0;
    }

    .stc-q-field label.text-up-label {
      display: block;
      color: rgba(255, 255, 255, 0.88) !important;
      font-size: 0.68rem;
      font-weight: 700;
      letter-spacing: 0.09em;
      text-transform: uppercase;
      margin: 0 0 0.45rem !important;
    }

    .stc-q-input-wrap {
      display: flex;
      align-items: center;
      min-height: 48px;
      padding: 0 0.85rem;
      border-radius: 14px;
      background: rgba(255, 255, 255, 0.96);
      border: 1px solid rgba(255, 255, 255, 0.45);
      box-shadow: 0 8px 22px rgba(15, 23, 42, 0.12);
    }

    .stc-q-filter-icon {
      color: #4e63d7;
      opacity: 0.9;
    }

    .stc-q-input-wrap .form-control,
    .stc-q-input-wrap select.form-control {
      height: 46px !important;
      min-height: 46px !important;
      padding: 0 !important;
      border: 0 !important;
      background: transparent !important;
      box-shadow: none !important;
      color: #1f2937 !important;
      font-weight: 600;
    }

    .stc-q-input-wrap .form-control:focus {
      box-shadow: none !important;
    }

    .stc-q-btn-show {
      width: 100%;
      min-height: 48px;
      border-radius: 14px !important;
      padding: 0.7rem 1.25rem !important;
      font-weight: 800 !important;
      letter-spacing: 0.04em !important;
      background: linear-gradient(135deg, #ffffff 0%, #eef2ff 100%) !important;
      color: #263a9b !important;
      border: 1px solid rgba(255, 255, 255, 0.7) !important;
      box-shadow: 0 10px 24px rgba(15, 23, 42, 0.18) !important;
      text-transform: uppercase !important;
    }

    .stc-q-btn-show:not(:disabled):hover {
      transform: translateY(-1px);
      color: #172554 !important;
      box-shadow: 0 14px 30px rgba(15, 23, 42, 0.22) !important;
    }

    .stc-q-btn-show:disabled {
      opacity: 0.65 !important;
      cursor: wait !important;
    }

    @media (max-width: 991.98px) {
      .stc-q-filter-grid {
        grid-template-columns: 1fr 1fr;
      }
      .stc-q-action-field {
        grid-column: 1 / -1;
      }
    }

    @media (max-width: 575.98px) {
      .stc-q-toolbar-top,
      .stc-q-filter-grid {
        display: block;
      }
      .stc-q-btn-add,
      .stc-q-field {
        width: 100%;
        margin-top: 0.85rem !important;
      }
    }

    .stc-q-results-card {
      border-radius: 14px !important;
      overflow: hidden;
      border: 1px solid rgba(92, 107, 192, 0.15) !important;
      box-shadow: 0 8px 30px rgba(22, 40, 90, 0.08) !important;
      background: #fff !important;
    }

    .stc-q-results-card .card-header {
      padding: 0.85rem 1.35rem !important;
      margin: 0 !important;
      background: linear-gradient(180deg, #eef1fb 0%, #fff 100%) !important;
      border-bottom: 1px solid rgba(92, 107, 192, 0.12) !important;
      font-weight: 600 !important;
      font-size: 0.95rem !important;
      color: #1a237e !important;
    }

    #stc-questions-datatable {
      table-layout: fixed !important;
      width: 100% !important;
      margin: 0 !important;
      border-collapse: separate !important;
      border-spacing: 0 !important;
    }

    #stc-questions-datatable thead th {
      background: linear-gradient(180deg, #5c6bc0 0%, #3949ab 96%) !important;
      color: #fff !important;
      border: none !important;
      font-weight: 500 !important;
      font-size: 0.8rem !important;
      vertical-align: middle !important;
    }

    #stc-questions-datatable thead th:first-child {
      border-radius: 10px 0 0 0 !important;
    }

    #stc-questions-datatable thead th:last-child {
      border-radius: 0 10px 0 0 !important;
    }

    #stc-questions-datatable tbody td {
      vertical-align: middle !important;
      font-size: 0.86rem !important;
    }

    #stc-questions-datatable tbody td:not(.stc-q-col-question) {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 0;
    }

    #stc-questions-datatable tbody td.stc-q-col-question {
      white-space: normal !important;
      word-break: break-word;
      line-height: 1.44;
      text-align: left !important;
      font-size: 0.878rem;
      color: #37474f;
    }

    .fade:not(.show) {
      opacity: 10;
    }

    .d-flex {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
    }

    .mb-3 {
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
      color: #020822;
      ;
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

    .search-box,
    .close-icon,
    .search-wrapper {
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
      border: 1px solid transparent;
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
      z-index: 1;
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

    .search-box:not(:valid)~.close-icon {
      display: none;
    }

    .stc_print_page i {
      color: black;
    }

    /* Add Question modal — scoped layout (tabs + fields) */
    .stc-school-showteacher-res .modal-dialog {
      max-width: min(920px, 96vw);
    }

    .stc-school-showteacher-res .qm-modal-head {
      border-bottom: none;
      padding-bottom: 0;
      background: linear-gradient(60deg, #5c6bc0 0%, #3949ab 92%);
      color: #fff;
    }

    .stc-school-showteacher-res .qm-modal-head .modal-title {
      color: #fff;
      font-weight: 500;
    }

    .stc-school-showteacher-res .qm-modal-head .close {
      color: #fff;
      opacity: 0.92;
      text-shadow: none;
    }

    .stc-school-showteacher-res .qm-modal-head .subtitle {
      display: block;
      font-size: 0.8rem;
      opacity: 0.88;
      margin-top: 2px;
    }

    .stc-school-showteacher-res .modal-body.qm-modal-body {
      padding-top: 0;
      background: linear-gradient(to bottom, #f8faff 0%, #fff 40%);
    }

    .stc-school-showteacher-res .qm-tabs-card {
      border-radius: 0 0 6px 6px;
      box-shadow: 0 2px 12px rgba(22, 40, 80, 0.06);
    }

    .stc-school-showteacher-res .qm-tab-nav-wrap {
      border-bottom: 1px solid rgba(25, 40, 80, 0.1);
      margin: 0 -15px;
      padding: 0 10px;
    }

    /* Modal tabs — override Material Dashboard nav-tabs (often white/low-contrast on pale bg). */
    .stc-school-showteacher-res .qm-tab-nav-wrap.nav-tabs {
      gap: 0.35rem;
    }

    .stc-school-showteacher-res .qm-tab-nav-wrap.nav-tabs > .nav-item > .nav-link {
      font-weight: 600 !important;
      font-size: 0.8125rem !important;
      text-transform: none !important;
      color: #1a237e !important;
      background-color: #eef1fb !important;
      border: 1px solid rgba(26, 35, 126, 0.18) !important;
      margin: 0 2px !important;
      border-radius: 8px !important;
      padding: 0.62rem 0.85rem !important;
      opacity: 1 !important;
    }

    .stc-school-showteacher-res .qm-tab-nav-wrap.nav-tabs > .nav-item > .nav-link:hover,
    .stc-school-showteacher-res .qm-tab-nav-wrap.nav-tabs > .nav-item > .nav-link:focus {
      color: #0d47a1 !important;
      background-color: #dfe4fb !important;
      border-color: rgba(26, 35, 126, 0.28) !important;
    }

    .stc-school-showteacher-res .qm-tab-nav-wrap.nav-tabs > .nav-item > .nav-link.active,
    .stc-school-showteacher-res .qm-tab-nav-wrap.nav-tabs > .nav-item > .nav-link.active.show {
      color: #ffffff !important;
      background: linear-gradient(180deg, #5c6bc0 0%, #3949ab 100%) !important;
      border-color: #303f9f !important;
    }

    .stc-school-showteacher-res .qm-tab-nav-wrap.nav-tabs > .nav-item > .nav-link .material-icons {
      font-size: 18px !important;
      vertical-align: middle !important;
      margin-right: 6px !important;
      color: inherit !important;
      opacity: 1 !important;
    }

    /* Green action buttons inside modal stay readable even if `.form-control`/theme overrides text. */
    .stc-school-showteacher-res .btn.btn-success {
      color: #fff !important;
      border-color: #43a047 !important;
      background-color: #2e7d32 !important;
    }

    .stc-school-showteacher-res .btn.btn-success:hover,
    .stc-school-showteacher-res .btn.btn-success:focus {
      color: #fff !important;
      background-color: #1b5e20 !important;
      border-color: #1b5e20 !important;
    }

    .stc-school-showteacher-res .qm-tab-content {
      padding-top: 1rem;
    }

    .stc-school-showteacher-res .qm-tab-content-outer {
      position: relative;
      min-height: 220px;
    }

    .stc-school-showteacher-res .qm-tab-loader {
      position: absolute;
      inset: 0;
      z-index: 12;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      gap: 0.75rem;
      background: rgba(255, 255, 255, 0.82);
      border-radius: 8px;
      backdrop-filter: blur(2px);
      -webkit-backdrop-filter: blur(2px);
      transition: opacity 0.15s ease;
    }

    .stc-school-showteacher-res .qm-tab-loader-inner {
      text-align: center;
    }

    .stc-school-showteacher-res .qm-tab-spinner {
      display: inline-block;
      width: 2.35rem;
      height: 2.35rem;
      border: 3px solid rgba(57, 73, 171, 0.18);
      border-top-color: #3949ab;
      border-radius: 50%;
      animation: qm-tab-spin 0.72s linear infinite;
      vertical-align: middle;
    }

    .stc-school-showteacher-res .qm-tab-loader-text {
      font-size: 0.875rem;
      font-weight: 600;
      color: #3949ab;
    }

    @keyframes qm-tab-spin {
      to {
        transform: rotate(360deg);
      }
    }

    /* Snappier tab panes inside this modal (default BS fade feels sluggish). */
    .stc-school-showteacher-res .tab-pane.fade {
      transition: opacity 0.1s linear;
    }

    .stc-school-showteacher-res .mb-3.qm-field {
      border: 1px solid rgba(25, 40, 80, 0.1);
      border-radius: 10px;
      padding: 12px 14px;
      margin-bottom: 1rem !important;
      margin-left: 0;
      margin-right: 0;
      background: rgba(255, 255, 255, 0.96);
      box-shadow: 0 1px 6px rgba(22, 40, 80, 0.04);
    }

    .stc-school-showteacher-res .qm-field>h5 {
      font-size: 0.73rem !important;
      font-weight: 600 !important;
      letter-spacing: 0.05em !important;
      text-transform: uppercase !important;
      color: rgba(43, 55, 120, 0.72) !important;
      margin-bottom: 10px !important;
    }

    .stc-school-showteacher-res .qm-alert {
      padding: 0.65rem 0.85rem;
      margin-bottom: 1rem;
      border-radius: 8px;
      font-size: 0.815rem;
      background: rgba(92, 107, 192, 0.08);
      border: 1px solid rgba(92, 107, 192, 0.2);
      color: rgba(26, 35, 90, 0.9);
    }

    .stc-school-showteacher-res .qm-actions .btn-success {
      font-weight: 500;
      border-radius: 8px !important;
      padding: 0.55rem 1.25rem;
      box-shadow: 0 4px 12px rgba(46, 125, 50, 0.25);
    }

    .stc-school-showteacher-res .qm-table-wrap {
      border-radius: 10px;
      border: 1px solid rgba(25, 40, 80, 0.1);
      overflow: hidden;
      background: #fff;
    }

    .stc-school-showteacher-res .qm-table-wrap thead th {
      background: linear-gradient(180deg, #5c6bc0 0%, #3949ab 98%);
      color: #fff;
      border: none;
      font-weight: 500;
      font-size: 0.8125rem;
      padding: 0.55rem !important;
    }

    .stc-school-showteacher-res .qm-table-wrap .table {
      margin-bottom: 0 !important;
    }

    .stc-school-showteacher-res .qm-form-error {
      font-size: 0.875rem;
      border-radius: 8px !important;
    }

    .stc-school-showteacher-res .qm-form-error.alert-warning {
      background: rgba(255, 193, 7, 0.15) !important;
      border: 1px solid rgba(255, 152, 0, 0.55) !important;
      color: #5d4037 !important;
    }

    .stc-school-showteacher-res .qm-form-error.alert-success {
      background: rgba(46, 125, 50, 0.12) !important;
      border: 1px solid rgba(76, 175, 80, 0.45) !important;
      color: #1b5e20 !important;
    }

    .stc-school-showteacher-res .qm-steps .badge {
      font-size: 0.72rem;
      padding: 0.35rem 0.55rem;
    }

    .stc-school-showteacher-res .qm-section {
      border: 1px solid rgba(26, 35, 126, 0.14) !important;
      border-radius: 10px;
      padding: 0.75rem 1rem 0.35rem !important;
      margin-bottom: 1rem !important;
      background: rgba(255, 255, 255, 0.98);
    }

    .stc-school-showteacher-res .qm-section legend {
      width: auto;
      padding: 0 0.35rem !important;
      margin-bottom: 0 !important;
      font-size: 0.8rem !important;
      font-weight: 700 !important;
      color: #3949ab !important;
      text-transform: none;
      border: none;
    }

    .stc-school-showteacher-res .qm-req {
      color: #c62828 !important;
      font-weight: 700;
      text-decoration: none;
      cursor: help;
    }

    .stc-school-showteacher-res .qm-optional-note {
      font-weight: normal;
      font-size: 0.74rem;
      color: rgba(93, 64, 55, 0.75);
    }
  </style>
</head>

<body class="">
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
                          <a class="nav-link active" href="#stc-create-attendance" data-toggle="tab">
                            <i class="material-icons">add_circle</i> Questions Details
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="tab-pane active" id="stc-create-attendance">
                      <div class="stc-questions-mgmt">
                        <div class="row align-items-start mb-3 mb-md-4">
                          <div class="col-12">
                            <h2 class="h4 mb-1 font-weight-bold d-inline-flex align-items-center"
                              style="color:#283593;font-size:1.35rem;">
                              <i class="material-icons mr-2" style="font-size:1.65rem;line-height:1;">quiz</i>
                              Questions Details
                            </h2>
                            <p class="small text-muted mb-0 mt-1 pl-1 ml-1 ml-md-0 pl-md-0"
                              style="max-width: 42rem;line-height:1.45;">
                              Filter by month and class, then load rows. Search and paging apply to the table below after
                              you click&nbsp;<strong>Show questions</strong>.
                            </p>
                          </div>
                        </div>

                        <div class="card stc-q-toolbar-card mb-4 border-0">
                          <div class="card-body">
                            <div class="stc-q-toolbar-top">
                              <div>
                                <p class="stc-q-toolbar-title">Question browser</p>
                                <p class="stc-q-toolbar-subtitle">Choose a month and class, then load saved lecture questions.</p>
                              </div>
                              <div>
                                <button type="button" id="stcschooladdquest"
                                  class="btn btn-success stc-q-btn-add d-inline-flex align-items-center mb-2">
                                  <span class="material-icons" aria-hidden="true">post_add</span>
                                  Add questions
                                </button>
                              </div>
                            </div>
                            <div class="stc-q-filter-grid">
                              <div class="form-group stc-q-field">
                                <label class="text-up-label d-block" for="stcattendmonth-input">Month</label>
                                <div class="stc-q-input-wrap">
                                  <span class="material-icons stc-q-filter-icon" aria-hidden="true">calendar_today</span>
                                  <input id="stcattendmonth-input" name="stcattendmonth" type="month"
                                    class="form-control validate stcattendmonth" value="<?php echo date('Y-m'); ?>" />
                                </div>
                              </div>
                              <div class="form-group stc-q-field">
                                <label class="text-up-label d-block" for="stcattendclassname-input">Class</label>
                                <div class="stc-q-input-wrap">
                                  <span class="material-icons stc-q-filter-icon" aria-hidden="true">school</span>
                                  <select id="stcattendclassname-input" name="stcattendclassname"
                                    class="form-control validate stcattendclassname">
                                    <?php
                                          include_once("../../MCU/db.php");
                                          $school_sql=mysqli_query($con, "
                                              SELECT DISTINCT `stc_school_class_id`,`stc_school_class_title` FROM stc_school_class ORDER BY `stc_school_class_title` ASC
                                          ");
                                          foreach($school_sql as $school_row){
                                              echo '<option value="'.$school_row['stc_school_class_id'].'">'.$school_row['stc_school_class_title'].'</option>';
                                          }
                                      ?>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group stc-q-field stc-q-action-field">
                                <label class="text-up-label d-none d-md-block" for="stcschoolattendance">&nbsp;</label>
                                <button type="button" id="stcschoolattendance"
                                  class="btn btn-block stc-q-btn-show d-inline-flex align-items-center justify-content-center mb-2 mb-md-0">
                                  <span class="material-icons" aria-hidden="true">search</span>
                                  Show questions
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row stc-schoolattendance-div" style="display:none;">
                          <div class="col-12 mx-auto">
                            <div class="card stc-q-results-card mb-4 border-0">
                              <div class="card-header d-flex align-items-center">
                                <i class="material-icons mr-2" style="color:#3949ab;">table_chart</i>
                                Loaded questions
                              </div>
                              <div class="card-body py-4">
                                <div class="px-1 stc-schoolattendance-show rounded" aria-live="polite">
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
    $(document).ready(function () {
      $().ready(function () {
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

        $('.fixed-plugin a').click(function (event) {
          // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
          if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

        $('.fixed-plugin .active-color span').click(function () {
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

        $('.fixed-plugin .background-color .badge').click(function () {
          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('background-color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-background-color', new_color);
          }
        });

        $('.fixed-plugin .img-holder').click(function () {
          $full_page_background = $('.full-page-background');

          $(this).parent('li').siblings().removeClass('active');
          $(this).parent('li').addClass('active');


          var new_image = $(this).find("img").attr('src');

          if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            $sidebar_img_container.fadeOut('fast', function () {
              $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
              $sidebar_img_container.fadeIn('fast');
            });
          }

          if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $full_page_background.fadeOut('fast', function () {
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

        $('.switch-sidebar-image input').change(function () {
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

        $('.switch-sidebar-mini input').change(function () {
          $body = $('body');

          $input = $(this);

          if (md.misc.sidebar_mini_active == true) {
            $('body').removeClass('sidebar-mini');
            md.misc.sidebar_mini_active = false;

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

          } else {

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

            setTimeout(function () {
              $('body').addClass('sidebar-mini');

              md.misc.sidebar_mini_active = true;
            }, 300);
          }

          // we simulate the window Resize so the charts will get updated in realtime.
          var simulateWindowResize = setInterval(function () {
            window.dispatchEvent(new Event('resize'));
          }, 180);

          // we stop the simulation of Window Resize after the animations are completed
          setTimeout(function () {
            clearInterval(simulateWindowResize);
          }, 1000);

        });
      });
    });
  </script>
  <script>
    $(document).ready(function () {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();

    });
  </script>
  <script>
    $(document).ready(function () {
      $('.questions-management').addClass('active');
    });
  </script>
  <script>
    $(document).ready(function () {
      $('.close-icon').on('click', function (e) {
        e.preventDefault();
        $('.stc-electro-pd-show-ch').fadeOut(500);
      });

      $('.upward').on('click', function (e) {
        e.preventDefault();
        $('.downward').toggle(500);
        $('.stc-electro-pd-show-ch').fadeOut(500);
        $('.upward').fadeOut(500);
      });

      $('.downward').on('click', function (e) {
        e.preventDefault();
        $('.upward').toggle(500);
        $('.stc-electro-pd-show-ch').toggle(500);
        $('.downward').fadeOut(500);
      });

      $(".ddd").on("click", function (w) {
        w.preventDefault();
        var $button = $(this);
        var $input = $button.closest('.sp-quantity').find("input.quntity-input");
        $input.val((i, v) => Math.max(0, +v + 1 * $button.data('multi')));
      });
    });
  </script>
  <!-- canteen section -->
  <script>
    $(document).ready(function () {
      function stcCleanupModalState(force) {
        if (force || $('.modal.in:visible, .modal.show:visible').length === 0) {
          $('body').removeClass('modal-open').css({ 'padding-right': '', 'overflow': '' });
          $('.modal-backdrop').remove();
        }
      }

      // Keep Bootstrap modals under <body> so close/open does not leave a stale backdrop.
      $('.stc-school-showattendancedet-res, .stc-school-showteacher-res').appendTo('body');
      $(document).on('hidden.bs.modal', '.stc-school-showattendancedet-res, .stc-school-showteacher-res', function () {
        setTimeout(stcCleanupModalState, 10);
      });
      function stcShowPageModal($modal) {
        if (!$modal.length) {
          return;
        }
        stcCleanupModalState(true);
        $modal.appendTo('body')
          .css('display', 'block')
          .removeAttr('aria-hidden')
          .attr('aria-modal', 'true')
          .addClass('show in');
        $('body').addClass('modal-open');
        $('<div class="modal-backdrop fade show in"></div>').appendTo('body');
        $modal.trigger('shown.bs.modal');
      }
      function stcHidePageModal($modal) {
        if (!$modal.length) {
          return;
        }
        $modal.removeClass('show in')
          .css('display', 'none')
          .attr('aria-hidden', 'true')
          .removeAttr('aria-modal');
        stcCleanupModalState(true);
        $modal.trigger('hidden.bs.modal');
      }
      $('#stcschooladdquest').off('click.stcPageModal').on('click.stcPageModal', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        stcShowPageModal($('.stc-school-showteacher-res'));
      });
      $('.stc-school-showteacher-res .close, .stc-school-showattendancedet-res .close')
        .off('click.stcPageModal')
        .on('click.stcPageModal', function (e) {
          e.preventDefault();
          e.stopImmediatePropagation();
          stcHidePageModal($(this).closest('.modal'));
        });

      /** Destroy Questions DataTable before injecting new markup (avoid duplicate-ID / leaks). */
      function stcDestroyQuestionsDataTable() {
        var $t = $('#stc-questions-datatable');
        if ($t.length && $.fn.DataTable && $.fn.DataTable.isDataTable($t)) {
          $t.DataTable().destroy();
        }
      }

      function stcInitQuestionsDataTable() {
        var $t = $('#stc-questions-datatable');
        if (!$t.length || !$.fn.DataTable) {
          return;
        }
        $t.DataTable({
          pageLength: 15,
          lengthMenu: [[10, 15, 25, 50, 100], [10, 15, 25, 50, 100]],
          ordering: true,
          order: [[0, 'asc']],
          searching: true,
          info: true,
          paging: true,
          autoWidth: false,
          columnDefs: [
            { targets: 0, width: '3.5rem', className: 'text-center align-middle' },
            { targets: [1, 2, 3], className: 'text-center align-middle' },
            { targets: 4, className: 'stc-q-col-question text-left align-middle' }
          ],
          language: {
            search: 'Search:',
            lengthMenu: 'Show _MENU_ rows',
            info: 'Showing _START_–_END_ of _TOTAL_',
            infoEmpty: 'No rows',
            zeroRecords: 'No matching rows',
            paginate: { next: 'Next', previous: 'Prev' }
          },
          dom: "<'row'<'col-md-6'l><'col-md-6'f>>" + "rt<'row'<'col-md-5'i><'col-md-7'p>>",
          initComplete: function () {
            $('.stc-schoolattendance-show').find('.dataTables_filter input[type="search"]').attr({
              'placeholder': 'Type to filter rows…',
              'aria-label': 'Search questions'
            });
          }
        });
      }

      $(document).on('click', '#stcschoolattendance', function (e) {
        e.preventDefault();
        var month = $('.stcattendmonth').val();
        var class_id = $('.stcattendclassname').val();
        $('#stcschoolattendance').prop('disabled', true).addClass('disabled');
        $('.stc-schoolattendance-div').hide();
        $.ajax({
          url: "../vanaheim/school-management.php",
          method: "POST",
          data: {
            stc_call_questions: 1,
            class_id: class_id,
            month: month
          },
          dataType: `JSON`,
          complete: function () {
            $('#stcschoolattendance').prop('disabled', false).removeClass('disabled');
          },
          error: function () {
            stcDestroyQuestionsDataTable();
            $('.stc-schoolattendance-div').show();
            $('.stc-schoolattendance-show').html(
              '<p class="alert alert-danger mb-0"><strong>Load failed.</strong> Check your connection and click Show Questions again.</p>'
            );
          },
          success: function (response_student) {
            if (response_student && typeof response_student === 'object' && response_student.reload === 'reload') {
              window.location.reload();
              return;
            }
            stcDestroyQuestionsDataTable();
            $('.stc-schoolattendance-div').show();
            $('.stc-schoolattendance-show').html(typeof response_student === 'string' ? response_student : '');
            window.requestAnimationFrame(function () {
              stcInitQuestionsDataTable();
            });
          }
        });
      });

      $(document).on('click', '.stc-school-student-att-show', function (e) {
        e.preventDefault();
        var student_id = $(this).attr("id");
        var month = $('.stcattendmonth').val();
        var classes = $('.stcattendclassname').val();
        window.location.href = 'student-attendance.php?student-attendance=yes&student_id=' + student_id + '&month=' + month + '&class=' + classes;
      });

      const queryString = window.location.search;
      const urlParams = new URLSearchParams(queryString);

      if (urlParams.get('student_id') != undefined) {
        get_student_attendance();
      }

      function get_student_attendance() {
        const student_id = urlParams.get('student_id');
        const month = urlParams.get('month');
        const classes = urlParams.get('class');
        $.ajax({
          url: "../vanaheim/school-management.php",
          method: "POST",
          data: {
            stc_student_attendance_get: 1,
            student_id: student_id
          },
          dataType: "JSON",
          success: function (reponse) {
            if (reponse.status == "success") {
              $('.modallabelstudentid').html('<br><b>' + reponse.data.studentid + '</b>');
              $('.modallabelstudentname').html('<br><b>' + reponse.data.name + '</b>');
              $('.modallabelclass').html('<br><b>' + reponse.data.class + '</b>');
              $('.modallabeltatttendance').html('<br><b>' + reponse.data.total_attendance + '</b>');
              $('.modallabelatttendance').html(reponse.data.attendance);
              $('.stcattendmonth').val(month);
              $('.stcattendclassname').val(classes);
              $('#stcschoolattendance').click();
              stcShowPageModal($('#exampleModal'));
            }
          }
        });
      }

      // hide attendance modal
      $(document).on('click', '.stc-school-exit-attend-details', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        stcHidePageModal($('.stc-school-showattendancedet-res'));
      });

      if (urlParams.get('action') != undefined) {
        const month = urlParams.get('month');
        const classes = urlParams.get('class');
        $('.stcattendmonth').val(month);
        $('.stcattendclassname').val(classes);
        $('#stcschoolattendance').click();
      }

      $(document).on('scroll', '.stc-schoolattendance-show table', function (e) {
        e.preventDefault();
        var space_width = $('.stc-schoolattendance-show table').width();
        // console.log(space_width);
      });

      /** Add-question modal: trim / NA guards + banner messages */
      var _qmTeacherModal = function () {
        return $('.stc-school-showteacher-res');
      };
      function qmTrim(v) {
        return $.trim((v !== undefined && v !== null ? String(v) : ''));
      }
      /** True when value has non-whitespace content and is not literal NA/select placeholder */
      function qmFilledSel(v) {
        var t = qmTrim(v);
        return t !== '' && t.toUpperCase() !== 'NA';
      }
      function qmFilledText(v) {
        return qmTrim(v) !== '';
      }
      function qmBannerClear() {
        if (window._qmSuccTimer) {
          clearTimeout(window._qmSuccTimer);
          window._qmSuccTimer = null;
        }
        var $e = _qmTeacherModal().find('.qm-form-error');
        $e.addClass('d-none').removeClass('alert-warning alert-success').empty();
      }
      function qmBannerMsg(html, isSuccess) {
        qmBannerClear();
        if (!html) {
          return;
        }
        window._qmSuccTimer = isSuccess ? setTimeout(qmBannerClear, 5200) : null;
        _qmTeacherModal().find('.qm-form-error').removeClass('d-none alert-warning alert-success')
          .addClass(isSuccess ? 'alert-success' : 'alert-warning').html(html);
      }
      $(document).on('shown.bs.modal hidden.bs.modal', '.stc-school-showteacher-res', function () {
        qmBannerClear();
      });

      /** Tab / syllabus loaders (ref-counted overlay on `.qm-tab-content-outer`). */
      var _qmTeacherLoaderDepth = 0;
      function qmTeacherLoaderSync() {
        var $l = _qmTeacherModal().find('.qm-tab-loader');
        if (_qmTeacherLoaderDepth > 0) {
          $l.removeClass('d-none').attr('aria-busy', 'true');
        } else {
          $l.addClass('d-none').removeAttr('aria-busy');
        }
      }
      function qmTeacherLoaderBegin() {
        _qmTeacherLoaderDepth++;
        qmTeacherLoaderSync();
      }
      function qmTeacherLoaderEnd() {
        _qmTeacherLoaderDepth = Math.max(0, _qmTeacherLoaderDepth - 1);
        qmTeacherLoaderSync();
      }
      function qmTeacherLoaderReset() {
        _qmTeacherLoaderDepth = 0;
        qmTeacherLoaderSync();
      }
      $(document).on('hidden.bs.modal', '.stc-school-showteacher-res', function () {
        qmTeacherLoaderReset();
      });
      $(document).on('show.bs.tab', '.stc-school-showteacher-res .qm-tab-nav-wrap a[data-toggle="tab"]', function () {
        qmTeacherLoaderBegin();
      });
      $(document).on('shown.bs.tab', '.stc-school-showteacher-res .qm-tab-nav-wrap a[data-toggle="tab"]', function () {
        qmTeacherLoaderEnd();
      });

      $(document).on('click', '.save-lecture', function (e) {
        e.preventDefault();
        qmBannerClear();

        var schedule_id = qmTrim($('.stc-school-hidden-schedule-id').val());
        var classtype = qmTrim($('#classtype').val());
        var chapter = qmTrim($('#chapter').val());
        var lession = qmTrim($('#lession').val());
        var syllabus = qmTrim($('#Syllabus').val());
        var unitSel = $('#unit-should-be').val();
        var unit = ($.trim(unitSel) === '' || qmTrim(unitSel).toUpperCase() === 'NA') ? '' : qmTrim(unitSel);
        var remarks = qmTrim($('#remarks').val());

        var per = qmFilledSel($('.scheduledaily').val());
        if (!(per && qmFilledSel(schedule_id))) {
          $('#qm-tab-lecture-link').tab('show');
          qmBannerMsg('<strong>Select a period first.</strong> Choose a timetable row under Schedule slot.');
          $('.scheduledaily').focus();
          return;
        }
        if (!qmFilledSel(classtype) || !qmFilledSel(syllabus) || !qmFilledSel(chapter) || !qmFilledSel(lession)) {
          $('#qm-tab-lecture-link').tab('show');
          qmBannerMsg('<strong>Required fields missing.</strong> Fill class type, syllabus title, chapter, and lesson (&ldquo;Select&rdquo; and blanks are ignored). Unit and remarks stay optional.');
          return;
        }

        $.ajax({
          url: "../vanaheim/school-management.php",
          method: "POST",
          data: {
            stc_lecturedet_save: 1,
            schedule_id: schedule_id,
            classtype: classtype,
            chapter: chapter,
            lession: lession,
            Syllabus: syllabus,
            Unit: unit,
            remarks: remarks
          },
          success: function (response_student) {
            var response = $.trim(response_student);
            if (response == "reload") {
              window.location.reload();
              return;
            }
            if (response == "success") {
              call_syllabus_det();
              $('#classtype').val('NA');
              $('#Syllabus').html('<option value="NA">Select (after period)</option>');
              $('#chapter').html('<option value="NA">Select</option>');
              $('#lession').html('<option value="NA">Select</option>');
              $('#unit-should-be').html('<option value="NA">Select</option>');
              $('#remarks').val('');
              $('#complete-date').val('');
              $('#Questions').val('');
              qmBannerMsg('<strong>Lecture saved.</strong> Review it under Saved lectures.', true);
              return;
            }
            if (response == "empty") {
              $('#qm-tab-lecture-link').tab('show');
              qmBannerMsg('<strong>Could not save.</strong> Avoid blank rows and &ldquo;Select&rdquo; placeholders for period, class type, syllabus, chapter, and lesson.');
              return;
            }
            qmBannerMsg('<strong>Something went wrong.</strong> Please try again in a moment.');
          }
        });
      });

      $(document).on('click', '.add-question', function (e) {
        e.preventDefault();
        qmBannerClear();

        var schedule_id = qmTrim($('.stc-school-hidden-schedule-id').val());
        var questionsText = $('#Questions').val();
        var questions = qmTrim(questionsText);

        if (!$('.scheduledaily').val() || $('.scheduledaily').val() === 'NA' || !qmFilledSel(schedule_id)) {
          $('#qm-tab-lecture-link').tab('show');
          qmBannerMsg('<strong>Select a period first.</strong> Questions tie to today&rsquo;s schedule.');
          $('.scheduledaily').focus();
          return;
        }
        if (!qmFilledText(questions)) {
          $('#qm-tab-questions-link').tab('show');
          qmBannerMsg('<strong>Add question text.</strong> Whitespace-only lines are skipped.');
          $('#Questions').focus();
          return;
        }

        $.ajax({
          url: "../vanaheim/school-management.php",
          method: "POST",
          data: {
            stc_lecturedetquestion_save: 1,
            schedule_id: schedule_id,
            questions: questions
          },
          success: function (response_student) {
            var response = $.trim(response_student);
            if (response == "reload") {
              window.location.reload();
              return;
            }
            if (response == "success") {
              call_syllabus_quest();
              $('#Questions').val('');
              qmBannerMsg('<strong>Question added.</strong> It appears in the list below.', true);
              $('#qm-tab-questions-link').tab('show');
              return;
            }
            if (response == "empty") {
              $('#qm-tab-questions-link').tab('show');
              qmBannerMsg('<strong>Could not save question.</strong> Enter wording and ensure a timetable period is chosen.');
              return;
            }
            qmBannerMsg('<strong>Something went wrong.</strong> Please try again in a moment.');
          }
        });
      });

        var sy_syllabus = new Array();
        function call_syllabus_det(){
          var schedule_id=$('.stc-school-hidden-schedule-id').val();
          var class_id=$('.stc-school-hidden-scclass-id').val();
          var sub_id=$('.stc-school-hidden-scsub-id').val();
          qmTeacherLoaderBegin();
          $.ajax({
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stc_syllabusdet_call : 1,
              schedule_id:schedule_id,
              class_id:class_id,
              sub_id:sub_id
            },
            dataType: `JSON`,
            complete  : function () {
              qmTeacherLoaderEnd();
            },
            success   : function(response_student){
              $('.stc-show-student-syllabusdet-show').html(response_student.lecture_details);
              var syl_result= response_student.syllabus_details;
              sy_syllabus = [];
              sy_syllabus.push(syl_result);
              var syllabus_output='<option value="NA">Select</option>';
              for(var i=0; i<sy_syllabus[0].length;i++){
                syllabus_output+='<option value="' + sy_syllabus[0][i].stc_school_syllabus_id + '">' + sy_syllabus[0][i].stc_school_syllabus_title + '</option>';
              }
              $('#Syllabus').html(syllabus_output);
              call_syllabus_quest();
            }
          });
        }

        $(document).on('change', '#Syllabus', function(){
          var syll_id = $(this).val();
          var chapter_output = '<option value="NA">Select</option>';
          for(var i=0; i<sy_syllabus[0].length;i++){
            chapter_output+='<option value="' + sy_syllabus[0][i].stc_school_syllabus_chapter + '" syll-id="' + sy_syllabus[0][i].stc_school_syllabus_id + '">' + sy_syllabus[0][i].stc_school_syllabus_chapter + '</option>';
          }
          $('#chapter').html(chapter_output);
        });

        $(document).on('change', '#chapter', function(){
          var syll_id = $(this).val();
          var lession_output = '<option value="NA">Select</option>';
          for(var i=0; i<sy_syllabus[0].length;i++){
            lession_output+='<option value="' + sy_syllabus[0][i].stc_school_syllabus_lesson + '" syll-id="' + sy_syllabus[0][i].stc_school_syllabus_id + '">' + sy_syllabus[0][i].stc_school_syllabus_lesson + '</option>';
          }
          $('#lession').html(lession_output);
        });

        $(document).on('change', '#lession', function(){
          var syll_id = $(this).val();
          var unit_output = '<option value="NA">Select</option>';
          for(var i=0; i<sy_syllabus[0].length;i++){
            unit_output+='<option value="' + sy_syllabus[0][i].stc_school_syllabus_unit + '" syll-id="' + sy_syllabus[0][i].stc_school_syllabus_id + '">' + sy_syllabus[0][i].stc_school_syllabus_unit + '</option>';
          }
          $('#unit-should-be').html(unit_output);
        });

        $(document).on('change', '#unit-should-be', function(){
          var syll_id = $(this).val();
          var cdate_output = '';
          for(var i=0; i<sy_syllabus[0].length;i++){
            if(syll_id==sy_syllabus[0][i].stc_school_syllabus_id){
              cdate_output=sy_syllabus[0][i].stc_school_syllabus_completedate;
            }
          }
          $('#complete-date').val(cdate_output);
        });

        function call_syllabus_quest(){
          var question_id=$('.stc-syllabus-out:checked').attr("id");
          if(question_id>0){
            qmTeacherLoaderBegin();
            $.ajax({
              url       : "../vanaheim/school-management.php",
              method    : "POST",  
              data      : {
                stc_syllabusquest_call : 1,
                question_id:question_id
              },
              // dataType: `JSON`,
              complete   : function () {
                qmTeacherLoaderEnd();
              },
              success   : function(response_student){
                $('.stc-show-student-syllabusquest-show').html(response_student);
              }
            });
          }
        }

        $(document).on('click', '.stc-syllabus-out', function(){
          call_syllabus_quest();
        });

        $(document).on('change', '.scheduledaily', function() {            
            qmBannerClear();
            var id = $(this).val();  
            var class_id = $(this).find('option:selected').attr('class_id');
            var sub_id = $(this).find('option:selected').attr('sub_id');

            if (id != "NA") {
                $('.stc-school-hidden-schedule-id').val(id);
                $('.stc-school-hidden-scclass-id').val(class_id);
                $('.stc-school-hidden-scsub-id').val(sub_id);
                call_syllabus_det();
            } else {
                $('.stc-school-hidden-schedule-id, .stc-school-hidden-scclass-id, .stc-school-hidden-scsub-id').val('');
                sy_syllabus = [];
                $('#Syllabus').html('<option value="NA">Select (after period)</option>');
                $('#chapter, #lession, #unit-should-be').html('<option value="NA">Select</option>');
                $('#complete-date').val('');
                $('.stc-show-student-syllabusdet-show').empty();
            }
        });

    });
  </script>
<div class="modal fade stc-school-showattendancedet-res" data-backdrop="static" data-keyboard="false" id="exampleModal"
  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Questions Details</h5>
        <button type="button" class="close stc-school-exit-attend-details"
          aria-label="Close">X</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-12 col-md-12">
            <div class="card">
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="stc-show-attendance">
                    <div class="row">
                      <div class="col-12">
                        <h2 class="tm-block-title d-inline-block">Questions Details</h2>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 mx-auto">
                        <div class="mb-3">
                          <label>Student Id : <span class="modallabelstudentid"></span></label>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 mx-auto">
                        <div class="mb-3">
                          <label>Student Name : <span class="modallabelstudentname"></span></label>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 mx-auto">
                        <div class="mb-3">
                          <label>Class : <span class="modallabelclass"></span></label>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 mx-auto">
                        <div class="mb-3">
                          <label>Total Attendance : <span class="modallabeltatttendance"></span></label>
                        </div>
                      </div>
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
                        <div class="mb-3">
                          <div class="row modallabelatttendance">
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
      <div class="modal-footer">
        <!-- <button type="button" class="btn stc-school-showattendancedet-res" data-dismiss="modal" aria-label="Close">Exit</button> -->
      </div>
    </div>
  </div>
</div>
<!-- teacher modal -->
<div class="modal fade bd-example-modal-xl qm-modal stc-school-showteacher-res" tabindex="-1" role="dialog"
  aria-labelledby="qmAddQuestionTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header qm-modal-head">
        <div>
          <h4 class="modal-title mb-0" id="qmAddQuestionTitle">Add question</h4>
          <small class="subtitle">Use <strong>Lecture</strong> to bind today&rsquo;s period and syllabus rows, save, review under <strong>Saved lectures</strong>, then write questions. Blank entries are skipped.</small>
        </div>
        <button type="button" class="close" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body qm-modal-body">
        <div class="card mb-0 qm-tabs-card border-0 shadow-none">
          <div class="px-3 pt-2">
            <ul class="nav nav-tabs nav-justified qm-tab-nav-wrap" role="tablist">
              <li class="nav-item">
                <a class="nav-link active show" href="#qm-tab-lecture" id="qm-tab-lecture-link" data-toggle="tab" role="tab" aria-controls="qm-tab-lecture" aria-selected="true">
                  <i class="material-icons">event_note</i> Lecture & syllabus
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#qm-tab-records" id="qm-tab-records-link" data-toggle="tab" role="tab" aria-controls="qm-tab-records" aria-selected="false">
                  <i class="material-icons">list_alt</i> Saved lectures
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#qm-tab-questions" id="qm-tab-questions-link" data-toggle="tab" role="tab" aria-controls="qm-tab-questions" aria-selected="false">
                  <i class="material-icons">help_outline</i> Questions
                </a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="qm-form-error alert mb-3 d-none" role="alert" aria-live="polite"></div>
            <div class="qm-steps row mx-0 mb-3 pb-3 justify-content-between align-items-start border-bottom" style="border-color: rgba(0,0,0,.08)!important;">
              <div class="col-4 px-2 text-center">
                <span class="badge badge-pill badge-primary">1</span>
                <div class="small text-secondary mt-1">Pick period</div>
              </div>
              <div class="col-4 px-2 text-center">
                <span class="badge badge-pill badge-secondary">2</span>
                <div class="small text-secondary mt-1">Syllabus + save</div>
              </div>
              <div class="col-4 px-2 text-center">
                <span class="badge badge-pill badge-secondary">3</span>
                <div class="small text-secondary mt-1">Add questions</div>
              </div>
            </div>
            <div class="qm-tab-content-outer">
              <div class="qm-tab-loader d-none" aria-hidden="true">
                <div class="qm-tab-loader-inner">
                  <span class="qm-tab-spinner" aria-hidden="true"></span>
                  <div class="qm-tab-loader-text">Loading…</div>
                  <span class="sr-only">Loading tab content.</span>
                </div>
              </div>
              <div class="tab-content qm-tab-content">
              <!-- Tab 1: lecture form -->
              <div class="tab-pane fade active show" id="qm-tab-lecture" role="tabpanel" aria-labelledby="qm-tab-lecture-link">
                <p class="qm-alert mb-3">
                  <strong><?php echo htmlspecialchars(date('l'), ENT_QUOTES, 'UTF-8'); ?>:</strong>
                  only <strong>today&rsquo;s</strong> timetable rows load here. Pick the period first; if the list is empty, there is no schedule for this day.
                </p>
                <fieldset class="qm-section">
                  <legend>Step 1&nbsp;&mdash; Period</legend>
                  <div class="row">
                    <div class="col-12">
                      <div class="mb-3 qm-field">
                        <h5>Schedule slot <abbr class="qm-req" title="required">*</abbr></h5>
                        <span class="bmd-form-group">
                          <select class="form-control scheduledaily" aria-required="true">
                            <option value="NA">Select a period</option>
                            <?php
                              include_once("../../MCU/db.php");
                              $date = date('l');
                              $school_sql=mysqli_query($con, "
                                  SELECT `stc_school_teacher_schedule_id`, `stc_school_subject_id`, `stc_school_class_id`, `stc_school_subject_title`, `stc_school_class_title`, `stc_school_teacher_schedule_period` FROM `stc_school_teacher_schedule` LEFT JOIN `stc_school_subject` ON `stc_school_teacher_schedule_subjectid`=`stc_school_subject_id` LEFT JOIN `stc_school_class` ON `stc_school_teacher_schedule_classid`=`stc_school_class_id` WHERE `stc_school_teacher_schedule_day`='".$date."' AND `stc_school_teacher_schedule_teacherid`='".$_SESSION['stc_school_teacher_id']."' ORDER BY `stc_school_teacher_schedule_period` ASC
                              ");
                              foreach($school_sql as $school_row){
                                $period=$school_row['stc_school_teacher_schedule_period'];
                                if($period==1){$period=$period."st";}
                                if($period==2){$period=$period."nd";}
                                if($period==3){$period=$period."rd";}
                                if($period>3){$period=$period."th";}
                                echo '<option value="'.$school_row['stc_school_teacher_schedule_id'].'" class_id="'.$school_row['stc_school_class_id'].'" sub_id="'.$school_row['stc_school_subject_id'].'">'.$school_row['stc_school_subject_title'].' | '.$period.' | '.$school_row['stc_school_class_title'].'</option>';
                              }
                            ?>
                          </select>
                          <input type="hidden" class="stc-school-hidden-schedule-id">
                          <input type="hidden" class="stc-school-hidden-scclass-id">
                          <input type="hidden" class="stc-school-hidden-scsub-id">
                        </span>
                      </div>
                    </div>
                  </div>
                </fieldset>
                <fieldset class="qm-section">
                  <legend>Step 2&nbsp;&mdash; Syllabus row you covered</legend>
                  <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12">
                      <div class="mb-3 qm-field">
                        <h5>Class type <abbr class="qm-req" title="required">*</abbr></h5>
                        <span class="bmd-form-group">
                          <select name="stcschoolmanagementteacherid" id="classtype" class="form-control validate classtype" aria-required="true">
                            <option value="NA">Select</option>
                            <option value="Syllabus">Syllabus class</option>
                            <option value="Revised">Revised class</option>
                            <option value="Doubt">Doubt class</option>
                          </select>
                        </span>
                      </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12">
                      <div class="mb-3 qm-field">
                        <h5>Syllabus title <abbr class="qm-req" title="required">*</abbr></h5>
                        <span class="bmd-form-group">
                          <select name="stcschoolmanagementteacherid" id="Syllabus" class="form-control validate Syllabus" aria-required="true">
                            <option value="NA">Select (after period)</option>
                          </select>
                        </span>
                      </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12">
                      <div class="mb-3 qm-field">
                        <h5>Chapter <abbr class="qm-req" title="required">*</abbr></h5>
                        <span class="bmd-form-group">
                          <select name="stcschoolmanagementteacherid" id="chapter" class="form-control validate chapter" aria-required="true">
                            <option value="NA">Select</option>
                          </select>
                        </span>
                      </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12">
                      <div class="mb-3 qm-field">
                        <h5>Lesson <abbr class="qm-req" title="required">*</abbr></h5>
                        <span class="bmd-form-group">
                          <select name="stcschoolmanagementteacherid" id="lession" class="form-control validate lession" aria-required="true">
                            <option value="NA">Select</option>
                          </select>
                        </span>
                      </div>
                    </div>
                  </div>
                </fieldset>
                <fieldset class="qm-section">
                  <legend>Extras <span class="qm-optional-note">optional &mdash; skipped if left on &ldquo;Select&rdquo;</span></legend>
                  <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12">
                      <div class="mb-3 qm-field">
                        <h5>Unit</h5>
                        <span class="bmd-form-group">
                          <select name="stcschoolmanagementteacherid" id="unit-should-be" class="form-control validate unit-should-be">
                            <option value="NA">Select</option>
                          </select>
                        </span>
                      </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12">
                      <div class="mb-3 qm-field">
                        <h5>Complete date <span class="qm-optional-note">read-only</span></h5>
                        <span class="bmd-form-group">
                          <input type="text" class="form-control" id="complete-date" placeholder="From syllabus" disabled>
                        </span>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="mb-3 qm-field">
                        <h5>Remarks</h5>
                        <span class="bmd-form-group">
                          <textarea class="form-control" id="remarks" placeholder="Short note (optional)" rows="2"></textarea>
                        </span>
                      </div>
                    </div>
                  </div>
                </fieldset>
                <div class="qm-actions text-right mb-1">
                  <button type="button" class="btn btn-success btn-block save-lecture">
                    Save lecture entry
                  </button>
                  <small class="text-muted d-block mt-2 text-right">Only non-blank required fields are accepted. Open <strong>Saved lectures</strong> to confirm.</small>
                </div>
              </div>

              <!-- Tab 2: saved lecture rows -->
              <div class="tab-pane fade" id="qm-tab-records" role="tabpanel" aria-labelledby="qm-tab-records-link">
                <p class="text-muted small mb-3">Pick a lecture row below (server markup may include radios or actions) before moving to Questions.</p>
                <div class="table-responsive qm-table-wrap">
                  <table class="table table-hover table-striped table-bordered mb-0">
                    <thead>
                      <tr>
                        <th class="text-center">Select</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Class type</th>
                        <th class="text-center">Syllabus</th>
                        <th class="text-center">Chapter</th>
                        <th class="text-center">Lesson</th>
                      </tr>
                    </thead>
                    <tbody class="stc-show-student-syllabusdet-show">
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Tab 3: questions -->
              <div class="tab-pane fade" id="qm-tab-questions" role="tabpanel" aria-labelledby="qm-tab-questions-link">
                <div class="row">
                  <div class="col-12">
                    <div class="mb-3 qm-field">
                      <h5>New question text</h5>
                      <span class="bmd-form-group">
                        <input type="text" class="form-control" id="Questions" placeholder="Enter question wording">
                      </span>
                    </div>
                  </div>
                  <div class="col-12 mb-3">
                    <button type="button" class="btn btn-success btn-block add-question">Add to list</button>
                  </div>
                  <div class="col-12">
                    <div class="table-responsive qm-table-wrap">
                      <table class="table table-hover table-striped table-bordered mb-0">
                        <thead>
                          <tr>
                            <th class="text-center">Sl no</th>
                            <th class="text-center">Questions</th>
                          </tr>
                        </thead>
                        <tbody class="stc-show-student-syllabusquest-show">
                        </tbody>
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
</body>
</html>