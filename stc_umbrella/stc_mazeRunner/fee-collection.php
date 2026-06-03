<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
require_once __DIR__ . '/includes/school_session_defaults.php';
if(empty(@$_SESSION['stc_school_user_id'])){
    header('location:../index.html');
}

if($_SESSION['stc_school_user_for']>3){
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
    STC School || Fee Collection
  </title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="../assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="../assets/demo/demo.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput-typeahead.css" />
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

    /* Fee collection UI refresh */
    .fee-collection-page {
      color: #263238;
    }

    .fee-collection-page .fc-page-head {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: 1.25rem;
      padding: 1rem 1.15rem;
      border-radius: 16px;
      background: linear-gradient(135deg, #eef2ff 0%, #fff 58%, #ecfeff 100%);
      border: 1px solid rgba(63, 81, 181, 0.12);
      box-shadow: 0 10px 28px rgba(26, 35, 126, 0.08);
    }

    .fee-collection-page .fc-page-kicker {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      margin-bottom: 0.3rem;
      color: #3949ab;
      font-size: 0.72rem;
      font-weight: 800;
      letter-spacing: 0.08em;
      text-transform: uppercase;
    }

    .fee-collection-page .fc-page-title {
      margin: 0;
      color: #1a237e;
      font-size: 1.35rem;
      font-weight: 800;
      line-height: 1.25;
    }

    .fee-collection-page .fc-page-subtitle {
      margin: 0.35rem 0 0;
      max-width: 44rem;
      color: #64748b;
      font-size: 0.9rem;
      line-height: 1.5;
    }

    .fee-collection-page .fc-pill {
      flex: 0 0 auto;
      padding: 0.48rem 0.8rem;
      border-radius: 999px;
      background: #fff;
      color: #3949ab;
      font-size: 0.76rem;
      font-weight: 800;
      box-shadow: 0 8px 20px rgba(26, 35, 126, 0.1);
      border: 1px solid rgba(63, 81, 181, 0.12);
    }

    .fee-collection-page .fc-entry-tabs {
      margin-top: 1rem;
    }

    .fee-collection-page .fc-entry-tab-nav.nav-tabs {
      display: flex;
      gap: 0.7rem;
      margin: 0 0 1rem;
      padding: 0.6rem;
      border: 0;
      border-radius: 16px;
      background: #eef2ff;
      box-shadow: inset 0 0 0 1px rgba(63, 81, 181, 0.12);
    }

    .fee-collection-page .fc-entry-tab-nav .nav-item {
      flex: 1 1 0;
      margin: 0;
    }

    .fee-collection-page .fc-entry-tab-nav .nav-link {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.45rem;
      min-height: 48px;
      margin: 0 !important;
      padding: 0.7rem 1rem !important;
      border: 1px solid transparent !important;
      border-radius: 12px !important;
      background: transparent !important;
      color: #3949ab !important;
      font-weight: 800 !important;
      letter-spacing: 0.03em;
      text-transform: uppercase;
      opacity: 1 !important;
    }

    .fee-collection-page .fc-entry-tab-nav .nav-link.active,
    .fee-collection-page .fc-entry-tab-nav .nav-link.active.show {
      background: #fff !important;
      color: #1a237e !important;
      border-color: rgba(63, 81, 181, 0.14) !important;
      box-shadow: 0 10px 24px rgba(26, 35, 126, 0.12);
    }

    .fee-collection-page .fc-entry-tab-nav .material-icons {
      font-size: 20px;
      line-height: 1;
    }

    .fee-collection-page .fc-save-bar {
      margin-top: 1rem;
      padding: 1rem;
      border-radius: 16px;
      background: #f8fafc;
      border: 1px solid rgba(148, 163, 184, 0.22);
      box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
    }

    .fee-collection-page .tm-bg-primary-dark.tm-block {
      padding: 0 !important;
      border-radius: 18px !important;
      overflow: hidden;
      background: #fff !important;
      border: 1px solid rgba(148, 163, 184, 0.22);
      box-shadow: 0 12px 34px rgba(15, 23, 42, 0.08);
    }

    .fee-collection-page .stc-view + .stc-view {
      margin-top: 1.1rem;
    }

    .fee-collection-page .fc-section-banner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      margin: 0 !important;
      padding: 1rem 1.25rem !important;
      border: 0 !important;
      border-radius: 0 !important;
      color: #fff;
      box-shadow: none !important;
    }

    .fee-collection-page .fc-section-banner.fc-income {
      background: linear-gradient(135deg, #0f9f6e 0%, #10b981 52%, #38bdf8 100%);
    }

    .fee-collection-page .fc-section-banner.fc-expense {
      background: linear-gradient(135deg, #f97316 0%, #ef4444 58%, #a855f7 100%);
    }

    .fee-collection-page .fc-section-banner h3 {
      display: flex;
      align-items: center;
      gap: 0.55rem;
      margin: 0;
      color: #fff;
      font-size: 1.08rem;
      font-weight: 800;
      letter-spacing: 0.01em;
    }

    .fee-collection-page #stc-create-canteen .tm-block > .row,
    .fee-collection-page .fc-panel-body {
      margin: 0;
      padding: 1.15rem;
    }

    .fee-collection-page #stc-create-canteen .mb-3:not(.fc-section-banner),
    .fee-collection-page .show-monthly-income-modal .mb-3 {
      margin: 0 0 1rem !important;
      padding: 0 !important;
      border: 0 !important;
      border-radius: 0 !important;
      box-shadow: none !important;
      background: transparent !important;
    }

    .fee-collection-page #stc-create-canteen h5,
    .fee-collection-page .fc-field-label,
    .fee-collection-page .show-monthly-income-modal h5 {
      margin: 0 0 0.45rem;
      color: #475569;
      font-size: 0.74rem;
      font-weight: 800;
      letter-spacing: 0.06em;
      text-transform: uppercase;
    }

    .fee-collection-page #stc-create-canteen .form-control,
    .fee-collection-page .fc-filter-card .form-control,
    .fee-collection-page .show-monthly-income-modal .form-control {
      min-height: 46px;
      border-radius: 12px !important;
      border: 1px solid #dbe3ef !important;
      background: #f8fafc !important;
      color: #1e293b !important;
      font-weight: 600;
      box-shadow: none !important;
      padding: 0.55rem 0.8rem !important;
    }

    .fee-collection-page #stc-create-canteen .form-control:focus,
    .fee-collection-page .fc-filter-card .form-control:focus,
    .fee-collection-page .show-monthly-income-modal .form-control:focus {
      border-color: #4f46e5 !important;
      background: #fff !important;
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
    }

    .fee-collection-page textarea.form-control {
      min-height: 94px;
      resize: vertical;
    }

    .fee-collection-page #stcschoolreached,
    .fee-collection-page .stc-monthly-income-btn {
      min-height: 48px;
      border-radius: 14px !important;
      border: 0 !important;
      background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%) !important;
      color: #fff !important;
      font-weight: 800 !important;
      letter-spacing: 0.04em;
      box-shadow: 0 12px 26px rgba(22, 163, 74, 0.24) !important;
      text-transform: uppercase;
    }

    .fee-collection-page .fc-filter-card {
      margin-bottom: 1rem;
      padding: 1rem;
      border-radius: 16px;
      background: linear-gradient(135deg, #f8fafc 0%, #fff 100%);
      border: 1px solid rgba(148, 163, 184, 0.22);
    }

    .fee-collection-page .fc-filter-row {
      display: grid;
      grid-template-columns: minmax(180px, 1fr) minmax(150px, 0.35fr);
      gap: 0.85rem;
      align-items: end;
    }

    .fee-collection-page .fc-btn-primary,
    .fee-collection-page #schoolsearch,
    .fee-collection-page .call-monthly-income-modal {
      min-height: 46px;
      border-radius: 12px !important;
      border: 0 !important;
      background: linear-gradient(135deg, #3949ab 0%, #5c6bc0 100%) !important;
      color: #fff !important;
      font-weight: 800 !important;
      letter-spacing: 0.03em;
      box-shadow: 0 10px 24px rgba(57, 73, 171, 0.22) !important;
      text-transform: uppercase;
    }

    .fee-collection-page .fc-table-panel {
      margin-top: 1rem;
      border-radius: 16px;
      overflow-x: auto;
      background: #fff;
      border: 1px solid rgba(148, 163, 184, 0.22);
    }

    .fee-collection-page .fc-table-panel table {
      margin-bottom: 0 !important;
      background: #fff;
    }

    .fee-collection-page .fc-table-panel th {
      background: #eef2ff;
      color: #1a237e;
      font-weight: 800;
      border-top: 0 !important;
    }

    .fee-collection-page .show-monthly-income-modal .modal-content,
    .fee-collection-page .stc-school-showdeep-res .modal-content {
      border: 0;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 24px 54px rgba(15, 23, 42, 0.24);
    }

    .fee-collection-page .show-monthly-income-modal .modal-header,
    .fee-collection-page .stc-school-showdeep-res .modal-header {
      background: linear-gradient(135deg, #3949ab 0%, #5c6bc0 100%);
      color: #fff;
      border-bottom: 0;
    }

    .fee-collection-page .show-monthly-income-modal .modal-title,
    .fee-collection-page .stc-school-showdeep-res .modal-title,
    .fee-collection-page .show-monthly-income-modal .close,
    .fee-collection-page .stc-school-showdeep-res .close {
      color: #fff;
      opacity: 0.95;
      text-shadow: none;
    }

    @media (max-width: 767.98px) {
      .fee-collection-page .fc-page-head,
      .fee-collection-page .fc-filter-row {
        display: block;
      }

      .fee-collection-page .fc-pill {
        display: inline-block;
        margin-top: 0.85rem;
      }

      .fee-collection-page .fc-filter-row > * + * {
        margin-top: 0.85rem;
      }
    }

    .show-monthly-income-modal .modal-content,
    .stc-school-showdeep-res .modal-content {
      border: 0;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 24px 54px rgba(15, 23, 42, 0.24);
    }

    .show-monthly-income-modal .modal-header,
    .stc-school-showdeep-res .modal-header {
      background: linear-gradient(135deg, #3949ab 0%, #5c6bc0 100%);
      color: #fff;
      border-bottom: 0;
    }

    .show-monthly-income-modal .modal-title,
    .stc-school-showdeep-res .modal-title,
    .show-monthly-income-modal .close,
    .stc-school-showdeep-res .close {
      color: #fff;
      opacity: 0.95;
      text-shadow: none;
    }

    .show-monthly-income-modal .mb-3 {
      margin: 0 0 1rem !important;
      padding: 0 !important;
      border: 0 !important;
      box-shadow: none !important;
      background: transparent !important;
    }

    .show-monthly-income-modal h5 {
      margin: 0 0 0.45rem;
      color: #475569;
      font-size: 0.74rem;
      font-weight: 800;
      letter-spacing: 0.06em;
      text-transform: uppercase;
    }

    .show-monthly-income-modal .form-control {
      min-height: 46px;
      border-radius: 12px !important;
      border: 1px solid #dbe3ef !important;
      background: #f8fafc !important;
      color: #1e293b !important;
      font-weight: 600;
      box-shadow: none !important;
      padding: 0.55rem 0.8rem !important;
    }

    .show-monthly-income-modal .stc-monthly-income-btn {
      min-height: 48px;
      border-radius: 14px !important;
      border: 0 !important;
      background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%) !important;
      color: #fff !important;
      font-weight: 800 !important;
      letter-spacing: 0.04em;
      box-shadow: 0 12px 26px rgba(22, 163, 74, 0.24) !important;
      text-transform: uppercase;
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
                            <a class="nav-link active" href="#stc-create-canteen" data-toggle="tab">
                              <i class="material-icons">add_circle</i> Create Fee
                              <div class="ripple-container"></div>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link " href="#stc-show-canteen" data-toggle="tab">
                              <i class="material-icons">visibility</i> Show Fee
                              <div class="ripple-container"></div>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link " href="#stc-show-monthly-close" data-toggle="tab">
                              <i class="material-icons">visibility</i> Monthly Closing
                              <div class="ripple-container"></div>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="tab-content fee-collection-page">
                      <div class="tab-pane active" id="stc-create-canteen">
                        <div class="fc-page-head">
                          <div>
                            <span class="fc-page-kicker"><i class="material-icons">receipt_long</i> Fee Collection</span>
                            <h2 class="fc-page-title">Create Fee Entry</h2>
                            <p class="fc-page-subtitle">Record monthly income and expenditure for the selected school. Use zero or leave blank where a head does not apply.</p>
                          </div>
                          <span class="fc-pill">Create</span>
                        </div>
                        <div class="fc-entry-tabs">
                          <ul class="nav nav-tabs fc-entry-tab-nav" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active show" href="#fc-income-entry" data-toggle="tab" role="tab" aria-controls="fc-income-entry" aria-selected="true">
                                <i class="material-icons">south_west</i> Income
                              </a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href="#fc-expense-entry" data-toggle="tab" role="tab" aria-controls="fc-expense-entry" aria-selected="false">
                                <i class="material-icons">north_east</i> Expenditure
                              </a>
                            </li>
                          </ul>
                          <div class="tab-content">
                            <div class="tab-pane fade active show" id="fc-income-entry" role="tabpanel">
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                            <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                              <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12" >
                                  <div class="mb-3 fc-section-banner fc-income">
                                    <h3><i class="material-icons">south_west</i> Income</h3>
                                    <span>Collections</span>
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >School Name
                                    </h5>
                                    <select
                                      name="stcwhichschool"
                                      type="text"
                                      class="form-control validate stcwhichschool"
                                      required
                                    >
                                      <option value="SGMS">Sara Girls Mission School</option>
                                      <option value="SHS">Sara Hafiza Section</option>
                                      <option value="SIS" selected>Sara International School</option>
                                      <option value="SMS">Sara Model School</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Monthly Fee
                                    </h5>
                                    <input
                                      name="stcschoolmonthlyfee"
                                      type="number"
                                      class="form-control validate stcschoolmonthlyfee"
                                      placeholder="Enter Monthly Fee"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >New Admission Fee
                                    </h5>
                                    <input
                                      name="stcschooladmissionfee"
                                      type="number"
                                      class="form-control validate stcschooladmissionfee"
                                      placeholder="Enter New Admission Fee"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Re Admission Fee
                                    </h5>
                                    <input
                                      name="stcschoolreadmissionfee"
                                      type="number"
                                      class="form-control validate stcschoolreadmissionfee"
                                      placeholder="Enter RE Admission Fee"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Book Charges
                                    </h5>
                                    <input
                                      name="stcschoolbooks"
                                      type="number"
                                      class="form-control validate stcschoolbooks"
                                      placeholder="Enter Book Charges"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Transportation
                                    </h5>
                                    <input
                                      name="stcschooltransporation"
                                      type="number"
                                      class="form-control validate stcschooltransporation"
                                      placeholder="Enter Transportation"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Donation
                                    </h5>
                                    <input
                                      name="stcschooldonation"
                                      type="number"
                                      class="form-control validate stcschooldonation"
                                      placeholder="Enter Donation"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Others(Bags, Shoes, Medical & Copies)
                                    </h5>
                                    <input
                                      name="stcschoolothercharges"
                                      type="number"
                                      class="form-control validate stcschoolothercharges"
                                      placeholder="Enter Other Charges"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Day Boarding Collection
                                    </h5>
                                    <input
                                      name="stcschooldayboarding"
                                      type="number"
                                      class="form-control validate stcschooldayboarding"
                                      placeholder="Enter Day Boarding Collection"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >NEET Collection
                                    </h5>
                                    <input
                                      name="stcschoolneatcoll"
                                      type="number"
                                      class="form-control validate stcschoolneatcoll"
                                      placeholder="Enter NEET Collection"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Cashback
                                    </h5>
                                    <input
                                      name="stcschoolcashback"
                                      type="number"
                                      class="form-control validate stcschoolcashback"
                                      placeholder="Enter Cashback"
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
                                      name="stcschoolremarks"
                                      type="text"
                                      class="form-control validate stcschoolremarks"
                                      placeholder="Enter Remarks"
                                    /></textarea>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                            </div>
                            <div class="tab-pane fade" id="fc-expense-entry" role="tabpanel">
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                            <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                              <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3 fc-section-banner fc-expense">
                                    <h3><i class="material-icons">north_east</i> Expenditure</h3>
                                    <span>Outgoings</span>
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >D'Staff Salary
                                    </h5>
                                    <input
                                      name="stcschooldssalary"
                                      type="number"
                                      class="form-control validate stcschooldssalary"
                                      placeholder="Enter D' Staff Salary"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Teachers Salary
                                    </h5>
                                    <input
                                      name="stcschooltsalary"
                                      type="number"
                                      class="form-control validate stcschooltsalary"
                                      placeholder="Enter Teachers Salary"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Vehicle Fuel
                                    </h5>
                                    <input
                                      name="stcschoolvfuel"
                                      type="number"
                                      class="form-control validate stcschoolvfuel"
                                      placeholder="Enter Vehicle Fuel"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Vehicle Maintenance
                                    </h5>
                                    <input
                                      name="stcschoolvmaint"
                                      type="number"
                                      class="form-control validate stcschoolvmaint"
                                      placeholder="Enter Vehicle Maintenance"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Electricity 
                                    </h5>
                                    <input
                                      name="stcschoolelectricity"
                                      type="number"
                                      class="form-control validate stcschoolelectricity"
                                      placeholder="Enter Electricity Amount"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Canteen
                                    </h5>
                                    <input
                                      name="stcschoolcanteen"
                                      type="number"
                                      class="form-control validate stcschoolcanteen"
                                      placeholder="Enter Canteen Amount"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Maintenance Cost
                                    </h5>
                                    <input
                                      name="stcschoolmaintcost"
                                      type="number"
                                      class="form-control validate stcschoolmaintcost"
                                      placeholder="Enter Maintenance Cost"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Project Cost
                                    </h5>
                                    <input
                                      name="stcschoolprojectcost"
                                      type="number"
                                      class="form-control validate stcschoolprojectcost"
                                      placeholder="Enter Project Cost"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Other Expenses
                                    </h5>
                                    <input
                                      name="stcschoolexpenses"
                                      type="number"
                                      class="form-control validate stcschoolexpenses"
                                      placeholder="Enter Other Expenses"
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
                                      name="stcschoolremarks"
                                      type="text"
                                      class="form-control validate stcschoolremarks"
                                      placeholder="Enter Remarks"
                                    /></textarea>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                            </div>
                          </div>
                          <div class="fc-save-bar">
                            <button type="button" name="search" class="form-control btn btn-success" id="stcschoolreached">Save Fee Entry</button>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane" id="stc-show-canteen">
                        <div class="fc-page-head">
                          <div>
                            <span class="fc-page-kicker"><i class="material-icons">search</i> Reports</span>
                            <h2 class="fc-page-title">Show Fee</h2>
                            <p class="fc-page-subtitle">Search by month and review saved fee entries in one place.</p>
                          </div>
                          <span class="fc-pill">Browse</span>
                        </div>
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                            <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                              <div class="row stc-view-product-row fc-panel-body">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                  <form action="" class="stc-silent-challan-form fc-filter-card">
                                    <?php
                                        $date = date("d-m-Y");
                                        $currentDate = date('Y-m', strtotime($date));
                                    ?>
                                    <div class="fc-filter-row">
                                      <div>
                                        <p class="fc-field-label">By Date</p>
                                        <input
                                          type="month"
                                          class="form-control validate stcfilterbyattr"
                                          value="<?php echo $currentDate;?>"
                                          required
                                        />
                                      </div>
                                      <div>
                                        <p class="fc-field-label">&nbsp;</p>
                                        <button type="button" name="challansearch" class="btn btn-primary btn-block" id="schoolsearch">Search</button>
                                      </div>
                                    </div>
                                  </form>
                                </div>
                              </div>
                              <div class="row stc-view-canteen-row fc-panel-body">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                  <form action="" class="stc-view-silent-fee-row-fetch fc-table-panel">
                                      <table class="table table-hover table-responsive">
                                        <tr><td>Loading....</td></tr>
                                      </table>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </div>
                      </div>
                      <div class="tab-pane" id="stc-show-monthly-close">
                        <div class="fc-page-head">
                          <div>
                            <span class="fc-page-kicker"><i class="material-icons">event_available</i> Month End</span>
                            <h2 class="fc-page-title">Monthly Closing</h2>
                            <p class="fc-page-subtitle">Add monthly income and review closing entries for school fee records.</p>
                          </div>
                          <span class="fc-pill">Closing</span>
                        </div>
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                            <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                              <div class="row stc-view-product-row fc-panel-body">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                  <a href="#" class="form-control btn btn-primary call-monthly-income-modal">Add Monthly Income</a>
                                </div>
                              </div>
                              <div class="row stc-view-canteen-row fc-panel-body">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                  <form action="" class="stc-view-silent-monthly-closer-row-fetch fc-table-panel">
                                      <table class="table table-hover table-responsive">
                                        <tr><td>Loading....</td></tr>
                                      </table>
                                  </form>
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
      const queryString = window.location.search;
      const urlParams = new URLSearchParams(queryString);
      const value = urlParams.get('fee-collection');
      if(value=="yes"){
        $('.fee-collection').addClass('active');
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
  <!-- canteen section -->
  <script>
      $(document).ready(function(){
        // save fee to db
        $(document).on('click', '#stcschoolreached', function(e){
          e.preventDefault();
          $(this).prop('disabled', true);
          var stcwhichschool                =  $('.stcwhichschool').val();
          var stcschoolmonthlyfee           =  $('.stcschoolmonthlyfee').val();
          var stcschooladmissionfee         =  $('.stcschooladmissionfee').val();
          var stcschoolreadmissionfee       =  $('.stcschoolreadmissionfee').val();
          var stcschoolbooks                =  $('.stcschoolbooks').val();
          var stcschooltransporation        =  $('.stcschooltransporation').val();
          var stcschooldonation             =  $('.stcschooldonation').val();
          var stcschooldayboarding          =  $('.stcschooldayboarding').val();
          var stcschoolneatcoll             =  $('.stcschoolneatcoll').val();
          var stcschoolothercharges         =  $('.stcschoolothercharges').val();
          var stcschoolcashback             =  $('.stcschoolcashback').val();
          var stcschooldssalary             =  $('.stcschooldssalary').val();
          var stcschooltsalary              =  $('.stcschooltsalary').val();
          var stcschoolvfuel                =  $('.stcschoolvfuel').val();
          var stcschoolvmaint               =  $('.stcschoolvmaint').val();
          var stcschoolelectricity          =  $('.stcschoolelectricity').val();
          var stcschoolcanteen              =  $('.stcschoolcanteen').val();
          var stcschoolexpenses             =  $('.stcschoolexpenses').val();
          var stcschoolmaintcost            =  $('.stcschoolmaintcost').val();
          var stcschoolprojectcost          =  $('.stcschoolprojectcost').val();
          var stcschoolremarks1              =  $('.stcschoolremarks:eq(0)').val();
          var stcschoolremarks2              =  $('.stcschoolremarks:eq(1)').val();
          var stcschoolremarks              =  stcschoolremarks1 + " " + stcschoolremarks2;
          $.ajax({  
            url       : "../vanaheim/fee-raised.php",
            method    : "POST",  
            data      : {
              stcwhichschool:stcwhichschool,
              stcschoolmonthlyfee:stcschoolmonthlyfee,
              stcschooladmissionfee:stcschooladmissionfee,
              stcschoolreadmissionfee:stcschoolreadmissionfee,
              stcschoolbooks:stcschoolbooks,
              stcschooltransporation:stcschooltransporation,
              stcschooldonation:stcschooldonation,
              stcschooldayboarding:stcschooldayboarding,
              stcschoolneatcoll:stcschoolneatcoll,
              stcschoolothercharges:stcschoolothercharges,
              stcschoolcashback:stcschoolcashback,
              stcschooldssalary:stcschooldssalary,
              stcschooltsalary:stcschooltsalary,
              stcschoolvfuel:stcschoolvfuel,
              stcschoolvmaint:stcschoolvmaint,
              stcschoolelectricity:stcschoolelectricity,
              stcschoolcanteen:stcschoolcanteen,
              stcschoolexpenses:stcschoolexpenses,
              stcschoolmaintcost:stcschoolmaintcost,
              stcschoolprojectcost:stcschoolprojectcost,
              stcschoolremarks:stcschoolremarks,
              save_school_fee_action : 1
            },
            // dataType: `JSON`,
            success   : function(data){
              // console.log(data);
              var response=data.trim();
              if(response=="Record saved successfully."){
                $('.stcschoolmonthlyfee').val('');
                $('.stcschooladmissionfee').val('');
                $('.stcschoolreadmissionfee').val('');
                $('.stcschoolbooks').val('');
                $('.stcschooltransporation').val('');
                $('.stcschooldonation').val('');
                $('.stcschooldayboarding').val('');
                $('.stcschoolneatcoll').val('');
                $('.stcschoolothercharges').val('');
                $('.stcschoolcashback').val('');
                $('.stcschooldssalary').val('');
                $('.stcschooltsalary').val('');
                $('.stcschoolvfuel').val('');
                $('.stcschoolvmaint').val('');
                $('.stcschoolelectricity').val('');
                $('.stcschoolcanteen').val('');
                $('.stcschoolexpenses').val('');
                $('.stcschoolmaintcost').val('');
                $('.stcschoolprojectcost').val('');
                $('.stcschoolremarks').val('');
                $("#stcschoolreached").prop('disabled', false);
                alert(data);
                $('#schoolsearch').click();
              }else if(response=="empty"){
                alert("Please select school.");
              }else{
                $(this).show();
                alert(data);
              }
            }
          });
        });

        // call school
        stc_call_fee();
        function stc_call_fee(){
          $.ajax({  
            url       : "../vanaheim/fee-raised.php",
            method    : "POST",  
            data      : {
              stc_call_fee : 1
            },
            // dataType: `JSON`,
            success   : function(response_fee){
             // console.log(response_fee);
             $('.stc-view-silent-fee-row-fetch').html(response_fee);
            }
          });
        }

        // search school
        $(document).on('click', '#schoolsearch', function(e){
          e.preventDefault();
          var search=$('.stcfilterbyattr').val();
          $.ajax({  
            url       : "../vanaheim/fee-raised.php",
            method    : "POST",  
            data      : {
              stc_search_school_fee : 1,
              search:search
            },
            // dataType: `JSON`,
            success   : function(response_fee){
             // console.log(data);
             $('.stc-view-silent-fee-row-fetch').html(response_fee);
            }
          });
        });

        // call monthly
        $(document).on('click', '.call-monthly-income-modal', function(e){
          e.preventDefault();
          $('.show-monthly-income-modal').modal('show');
        });

        // call income
        stc_call_monthly_closer();
        function stc_call_monthly_closer(){
          $.ajax({  
            url       : "../vanaheim/fee-raised.php",
            method    : "POST",  
            data      : {
              stc_call_monthly_closer : 1
            },
            // dataType: `JSON`,
            success   : function(response_fee){
             // console.log(response_fee);
             $('.stc-view-silent-monthly-closer-row-fetch').html(response_fee);
            }
          });
        }

        // save income
        $(document).on('click', '.stc-monthly-income-btn', function(e){
          e.preventDefault();
          var schoolname=$('.stcmwhichschool').val();
          var income=$('.stc-monthly-income-text').val();
          $.ajax({  
            url       : "../vanaheim/fee-raised.php",
            method    : "POST",  
            data      : {
              stc_save_school_income : 1,
              schoolname:schoolname,
              income:income
            },
            // dataType: `JSON`,
            success   : function(response_fee){
              // console.log(data);
              var response=response_fee.trim();
              if(response=="Income is saved."){
                alert(response_fee);
                stc_call_monthly_closer();
                window.location.reload();
              }else{
                alert(response_fee);
              }
            }
          });
        });
      });
  </script>
<div class="modal fade bd-example-modal-xl stc-school-showdeep-res" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">School Canteen</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <h5>School Canteen</h5>
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th class="text-center">Time</th>
                  <th class="text-center">Student</th>
                  <th class="text-center">Teacher</th>
                  <th class="text-center">Staff</th>
                  <th class="text-center">Guest</th>
                </tr>
              </thead>
              <tbody class="stc-show-canteen-nested-show">
                
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade bd-example-modal-xl show-monthly-income-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">School Monthly Closing</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
            <div class="mb-3">
              <h5
                for="name"
                >School Name
              </h5>
              <select
                name="stcmwhichschool"
                type="text"
                class="form-control validate stcmwhichschool"
                required
              >
                <option value="NA" selected>Please Select School</option>
                <option value="SGMS">Sara Girls Mission School</option>
                <option value="SHS">Sara Hafiza Section</option>
                <option value="SIS">Sara International School</option>
                <option value="SMS">Sara Model School</option>
              </select>
            </div>
          </div>
          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
            <div class="mb-3">
              <h5
                for="name"
                >Monthly Income
              </h5>
              <input type="number" class="form-control stc-monthly-income-text" placeholder="Enter Last Month Income">
            </div>
          </div>
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <a class="form-control btn btn-success stc-monthly-income-btn">Save</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>