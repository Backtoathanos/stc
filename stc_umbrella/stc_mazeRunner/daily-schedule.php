<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
require_once __DIR__ . '/includes/school_session_defaults.php';
if(empty(@$_SESSION['stc_school_user_id'])){
    header('location:../index.html');
}
if($_SESSION['stc_school_user_for']==2){
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
      STC School || School Schedule
    </title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link href="../assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
    <style>
        .stc-stuattendance-table{
          width:auto;
          overflow-x: scroll;
        }
        .stc-schoolschedule-show{
          width:auto;
          overflow-x: auto;
          -webkit-overflow-scrolling: touch;
          border-radius: 12px;
          background: linear-gradient(to bottom, #fbfcfe 0%, #fff 56%);
          box-shadow: 0 4px 18px rgba(30, 40, 60, 0.08), 0 1px 0 rgba(255, 255, 255, 0.95) inset;
          border: 1px solid rgba(92, 107, 192, 0.12) !important;
          padding: 0 !important;
          margin: 0.35rem 0 0.85rem !important;
        }

        table.stc-daily-schedule-table {
          margin-bottom: 0 !important;
          border-collapse: separate;
          border-spacing: 0;
          font-size: 0.8rem;
          border: none !important;
        }

        table.stc-daily-schedule-table thead td {
          background: linear-gradient(180deg, #3f6fba 0%, #2d5ea3 92%) !important;
          background-color: transparent !important;
          color: #fff !important;
          font-weight: 600 !important;
          letter-spacing: 0.02em;
          text-transform: none;
          vertical-align: middle !important;
          padding: 0.7rem 0.56rem !important;
          border: none !important;
          border-right: 1px solid rgba(255, 255, 255, 0.12) !important;
          white-space: nowrap;
        }

        table.stc-daily-schedule-table thead td:last-child {
          border-right: none !important;
        }

        table.stc-daily-schedule-table tbody td {
          border-color: rgba(55, 71, 90, 0.1) !important;
          padding: 0.58rem 0.5rem !important;
          vertical-align: middle !important;
          line-height: 1.35;
        }

        table.stc-daily-schedule-table thead td:first-child,
        table.stc-daily-schedule-table tbody td.stc-sched-day-cell {
          position: sticky;
          left: 0;
          z-index: 2;
          min-width: 6.75rem;
        }

        table.stc-daily-schedule-table thead td:first-child {
          z-index: 4;
          box-shadow: 3px 0 12px rgba(0, 0, 0, 0.12);
        }

        table.stc-daily-schedule-table tbody td.stc-sched-day-cell {
          background: #edf1f8 !important;
          font-weight: 700 !important;
          letter-spacing: 0.025em;
          color: #1e3a54 !important;
          box-shadow: 3px 0 12px rgba(20, 30, 50, 0.06);
        }

        table.stc-daily-schedule-table tbody tr.stc-sched-tr-today td.stc-sched-day-cell {
          background: linear-gradient(to right, #fde68a, #fcd34d) !important;
          color: #422006 !important;
        }

        table.stc-daily-schedule-table tbody tr.stc-sched-tr-today td.stc-sched-td-slot {
          background: rgba(253, 230, 138, 0.45) !important;
        }

        table.stc-daily-schedule-table tbody tr.stc-sched-tr-today td.stc-sched-td-slot b {
          color: #78350f;
        }

        table.stc-daily-schedule-table tbody td.stc-sched-td-slot {
          min-height: 3.85rem;
        }

        table.stc-daily-schedule-table tbody td.stc-sched-td-slot b {
          font-weight: 600;
          font-size: 0.78rem;
          color: #2c3e50;
          display: block;
        }

        table.stc-daily-schedule-table tbody td.stc-sched-td-live {
          background: linear-gradient(148deg, #047857 0%, #0d9488 48%, #10b981 100%) !important;
          box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.22);
        }

        table.stc-daily-schedule-table tbody tr.stc-sched-tr-today td.stc-sched-td-live {
          background: linear-gradient(148deg, #065f46 0%, #0f766e 45%, #059669 100%) !important;
        }

        table.stc-daily-schedule-table tbody td.stc-sched-td-live b {
          color: #fff !important;
        }

        table.stc-daily-schedule-table tbody td.stc-sched-td-live a.stc-sched-period-link {
          color: #fff !important;
          text-decoration: none;
          display: block;
          border-radius: 6px;
          padding: 0.12rem;
          transition: background 0.15s ease, box-shadow 0.15s ease;
        }

        table.stc-daily-schedule-table tbody td.stc-sched-td-live a.stc-sched-period-link:hover {
          background: rgba(255, 255, 255, 0.12);
          box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        }

        table.stc-daily-schedule-table tbody td.stc-sched-td-live a.stc-sched-period-link:active {
          background: rgba(0, 0, 0, 0.12);
        }

        table.stc-daily-schedule-table tbody td.stc-sched-td-na {
          background: #f5f7fa !important;
        }

        table.stc-daily-schedule-table tbody tr.stc-sched-tr-today td.stc-sched-td-na {
          background: rgba(251, 191, 36, 0.15) !important;
        }

        table.stc-daily-schedule-table .stc-sched-na {
          display: inline-block;
          color: #9aa5b8;
          font-weight: 500;
          letter-spacing: 0.06em;
          font-size: 0.74rem;
        }

        table.stc-daily-schedule-table.table-hover tbody tr:hover td:not(.stc-sched-td-live):not(.stc-sched-day-cell) {
          background: #f0f9ff !important;
        }

        table.stc-daily-schedule-table.table-hover tbody tr.stc-sched-tr-today:hover td.stc-sched-td-slot:not(.stc-sched-td-live):not(.stc-sched-day-cell) {
          background: rgba(253, 230, 138, 0.75) !important;
        }

        table.stc-daily-schedule-table tbody tr:not(.stc-sched-tr-today):nth-child(even) td:not(.stc-sched-day-cell) {
          background: rgba(248, 250, 252, 0.65);
        }
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

      /* —— Teacher period modal (Attendance + Lecture) —— */
      .stc-school-showstudent-res.ds-period-modal .modal-dialog {
        max-width: min(960px, 98vw);
      }

      .stc-school-showstudent-res .ds-period-modal-head {
        background: linear-gradient(60deg, #5c6bc0 0%, #3949ab 94%);
        color: #fff;
        padding-bottom: 1rem !important;
      }

      .stc-school-showstudent-res .ds-period-modal-head .modal-title {
        color: #fff;
        font-weight: 600;
      }

      .stc-school-showstudent-res .ds-period-modal-head .subtitle {
        display: block;
        font-size: 0.8rem;
        opacity: 0.9;
      }

      .stc-school-showstudent-res.ds-period-modal .modal-body.ds-period-modal-body {
        padding-top: 0;
        background: linear-gradient(to bottom, #f5f8ff 0%, #fff 52%);
      }

      .stc-school-showstudent-res .dsp-sched-tab-nav.nav-tabs > .nav-item > .nav-link {
        font-weight: 700 !important;
        font-size: 0.8rem !important;
        color: #1a237e !important;
        background: #eef1fb !important;
        border: 1px solid rgba(26, 35, 126, 0.2) !important;
        margin: 0 2px !important;
        border-radius: 8px !important;
      }

      .stc-school-showstudent-res .dsp-sched-tab-nav.nav-tabs > .nav-item > .nav-link.active.show {
        color: #fff !important;
        background: linear-gradient(180deg, #5c6bc0 0%, #3949ab 98%) !important;
        border-color: #283593 !important;
      }

      .stc-school-showstudent-res .dsp-sched-tab-nav .material-icons {
        font-size: 18px !important;
        vertical-align: middle !important;
        margin-right: 6px !important;
        color: inherit !important;
      }

      .stc-school-showstudent-res .tab-pane.fade {
        transition: opacity 0.1s linear;
      }

      .stc-school-showstudent-res .dsp-tab-panel-outer {
        position: relative;
        min-height: 220px;
      }

      .stc-school-showstudent-res .dsp-tab-loader {
        position: absolute;
        inset: 0;
        z-index: 14;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.86);
        border-radius: 8px;
      }

      .stc-school-showstudent-res .dsp-tab-spinner {
        width: 2.25rem;
        height: 2.25rem;
        border: 3px solid rgba(57, 73, 171, 0.2);
        border-top-color: #3949ab;
        border-radius: 50%;
        animation: dsp-tab-spin 0.72s linear infinite;
      }

      @keyframes dsp-tab-spin {
        to { transform: rotate(360deg); }
      }

      .stc-school-showstudent-res .dsp-form-error.alert-warning {
        background: rgba(255, 193, 7, 0.12) !important;
        border: 1px solid rgba(255, 152, 0, 0.45) !important;
        color: #5d4037 !important;
        border-radius: 10px !important;
        font-size: 0.88rem !important;
      }

      .stc-school-showstudent-res .dsp-form-error.alert-success {
        background: rgba(46, 125, 50, 0.1) !important;
        border: 1px solid rgba(76, 175, 80, 0.42) !important;
        color: #1b5e20 !important;
        border-radius: 10px !important;
        font-size: 0.88rem !important;
      }

      .stc-school-showstudent-res .dsp-modal-hint {
        font-size: 0.8525rem;
        padding: 0.55rem 0.72rem;
        border-radius: 8px;
        background: rgba(92, 107, 192, 0.08);
        border: 1px solid rgba(92, 107, 192, 0.15);
        color: rgba(26, 35, 90, 0.92);
      }

      .stc-school-showstudent-res .dsp-sched-tabs-card.mb-3,
      .stc-school-showstudent-res .dsp-section .mb-3,
      .stc-school-showstudent-res .dsp-sched-tab-content .mb-3 {
        border-radius: 10px !important;
        padding: 0.72rem 0.92rem !important;
        margin: 0 0 0.92rem !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        background: rgba(255, 255, 255, 0.98) !important;
        border: 1px solid rgba(92, 107, 192, 0.12) !important;
      }

      .stc-school-showstudent-res fieldset.dsp-section {
        border: 1px solid rgba(26, 35, 126, 0.15);
        border-radius: 10px;
        padding: 0.72rem 0.92rem;
        margin-bottom: 1rem;
        background: rgba(255, 255, 255, 0.98);
      }

      .stc-school-showstudent-res fieldset.dsp-section legend {
        width: auto;
        padding: 0 0.35rem;
        margin-bottom: 0 !important;
        font-size: 0.76rem !important;
        font-weight: 700 !important;
        color: #3949ab !important;
      }

      .stc-school-showstudent-res fieldset.dsp-section h5.dsp-field-label,
      .stc-school-showstudent-res fieldset.dsp-section .dsp-field-label {
        font-size: 0.7rem !important;
        letter-spacing: 0.04em !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        color: rgba(43, 55, 120, 0.68) !important;
        margin-bottom: 8px !important;
      }

      .stc-school-showstudent-res .dsp-req {
        color: #c62828;
        cursor: help;
        text-decoration: none;
      }

      .stc-school-showstudent-res .dsp-table-wrap {
        border-radius: 10px;
        border: 1px solid rgba(92, 107, 192, 0.12);
        overflow: hidden;
        background: #fff;
      }

      .stc-school-showstudent-res .dsp-table-wrap thead th {
        background: linear-gradient(180deg, #5c6bc0 0%, #3949ab 98%);
        color: #fff;
        font-size: 0.795rem !important;
        border: none;
        vertical-align: middle !important;
        padding: 0.52rem !important;
      }

      .stc-school-showstudent-res .btn.btn-success.save-lecture,
      .stc-school-showstudent-res .btn.btn-success.add-question {
        color: #fff !important;
        background: #2e7d32 !important;
        border-radius: 8px !important;
        border: none !important;
        padding: 0.55rem 1rem !important;
        box-shadow: 0 6px 16px rgba(46, 125, 50, 0.25) !important;
      }

      .stc-school-showstudent-res.ds-period-modal .ds-period-modal-head .close {
        margin: -4px -4px 0 0;
        opacity: 0.7;
      }

      .stc-school-showstudent-res.ds-period-modal .ds-period-modal-head .close:hover {
        opacity: 1;
      }

      .stc-school-showstudent-res.ds-period-modal .modal-footer {
        border-top: 1px solid rgba(92, 107, 192, 0.12);
        background: linear-gradient(to bottom, #fafbff, #fff);
      }

      .stc-school-showstudent-res.ds-period-modal .modal-footer .btn.dsp-period-cancel {
        font-weight: 600;
        border-radius: 8px !important;
        padding: 0.5rem 1.25rem !important;
      }

      .stc-school-showstudent-res.ds-period-modal .btn.dsp-period-finish {
        font-weight: 600;
        letter-spacing: 0.03em;
        border-radius: 8px !important;
        padding: 0.5rem 1.25rem !important;
      }

      /* Attendance sheet scroll without breaking modal width */
      .stc-school-showstudent-res .stc-stuattendance-table {
        overflow-x: auto !important;
        max-width: 100%;
      }

      /* Nested attendance table injected from school-management.php – match blue header + green/red body cells */
      .stc-school-showstudent-res .stc-stuattendance-table table.stc-show-student-nested-show {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
      }

      .stc-school-showstudent-res .stc-stuattendance-table table.stc-show-student-nested-show thead th,
      .stc-school-showstudent-res .stc-stuattendance-table table.stc-show-student-nested-show thead td {
        background: #2f6fcb !important;
        background-image: none !important;
        color: #fff !important;
        font-weight: 600 !important;
        font-size: 0.815rem !important;
        vertical-align: middle !important;
        padding: 0.55rem 0.65rem !important;
        border: 1px solid rgba(255, 255, 255, 0.18) !important;
      }

      .stc-school-showstudent-res .stc-stuattendance-table table.stc-show-student-nested-show tbody td {
        vertical-align: middle !important;
        border-color: rgba(45, 45, 45, 0.12);
      }

      .stc-school-showstudent-res .stc-stuattendance-table table.stc-show-student-nested-show tbody td:nth-child(1),
      .stc-school-showstudent-res .stc-stuattendance-table table.stc-show-student-nested-show tbody td:nth-child(2) {
        background: #fff !important;
        color: #212529 !important;
      }

      .stc-school-showstudent-res .stc-stuattendance-table table.stc-show-student-nested-show tbody td:nth-child(3) {
        background: #1b7948 !important;
        color: #fff !important;
        text-align: center;
        padding: 0.6rem 0.5rem !important;
      }

      .stc-school-showstudent-res .stc-stuattendance-table table.stc-show-student-nested-show tbody td:nth-child(4) {
        background: #d32f2f !important;
        color: #fff !important;
        text-align: center;
        padding: 0.6rem 0.5rem !important;
      }

      .stc-school-showstudent-res .stc-stuattendance-table table.stc-show-student-nested-show tbody td:nth-child(3) label,
      .stc-school-showstudent-res .stc-stuattendance-table table.stc-show-student-nested-show tbody td:nth-child(4) label {
        color: #fff !important;
        font-weight: 600;
        margin: 0 !important;
        cursor: pointer;
        vertical-align: middle;
      }

      .stc-school-showstudent-res .stc-stuattendance-table table.stc-show-student-nested-show tbody td:nth-child(4) input.stc-attend-check,
      .stc-school-showstudent-res .stc-stuattendance-table table.stc-show-student-nested-show tbody td:nth-child(3) input.stc-attend-check {
        margin: 0 0.4rem 0 0 !important;
        accent-color: #fff;
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
                            <a class="nav-link stc-school-show-schedultwtype active" href="#stc-create-attendance" id="1" data-toggle="tab">
                              <i class="material-icons">add_circle</i> Academic Schedule
                              <div class="ripple-container"></div>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link stc-school-show-schedultwtype" href="#stc-create-attendance" id="2" data-toggle="tab">
                              <i class="material-icons">add_circle</i> Coaching Schedule
                              <div class="ripple-container"></div>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link stc-school-show-schedultwtype" href="#stc-create-attendance" id="3" data-toggle="tab">
                              <i class="material-icons">add_circle</i> Self Study Schedule
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
                        <div class="row">
                          <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Daily Schedule</h2>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                            <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3 stc-schoolschedule-show">
                                    <table class="table table-hover table-bordered stc-daily-schedule-table">
                                      <thead>
                                        <tr>
                                          <td class="text-center"><b>Day</b></td>
                                          <td class="text-center"><b>1<span style="vertical-align: top;font-size: 11px;">st</span> Period</b></td>
                                          <td class="text-center"><b>2<span style="vertical-align: top;font-size: 11px;">nd</span> Period</b></td>
                                          <td class="text-center"><b>3<span style="vertical-align: top;font-size: 11px;">rd</span> Period</b></td>
                                          <td class="text-center"><b>4<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></td>
                                          <td class="text-center"><b>5<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></td>
                                          <td class="text-center"><b>6<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></td>
                                          <td class="text-center"><b>7<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></td>
                                        </tr>
                                      </thead>
                                      <tbody class="stc-teacher-schedule-show-table">
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
          </div>
        </div>
      </div>
    </div>

<div class="modal fade stc-school-showstudent-res ds-period-modal" data-backdrop="static" data-keyboard="false" id="exampleModal"
  tabindex="-1" role="dialog" aria-labelledby="dsPeriodModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header ds-period-modal-head border-bottom-0 pb-3 align-items-start justify-content-between">
        <div class="pr-lg-4 mb-2 mb-lg-0 flex-grow-1">
          <h4 class="modal-title mb-0" id="dsPeriodModalTitle">Period workspace</h4>
          <small class="subtitle">Mark attendance here, capture <strong>Lecture</strong> details when ready, then use <strong>Finish period</strong> to submit. Use <strong>Cancel</strong> or the header close button to leave without submitting.</small>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      <div class="modal-body ds-period-modal-body">
        <input type="hidden" class="stc-school-hidden-schedule-id">
        <input type="hidden" class="stc-school-hidden-scclass-id">
        <input type="hidden" class="stc-school-hidden-scsub-id">

        <div class="dsp-form-error alert mb-3 d-none" role="alert" aria-live="polite"></div>

        <div class="card border-0 shadow-none mb-0 dsp-sched-tabs-card">
          <div class="px-2 pt-2">
            <ul class="nav nav-tabs nav-justified dsp-sched-tab-nav" role="tablist">
              <li class="nav-item">
                <a class="nav-link active show resp-1" id="dsp-tab-attendance-link" href="#stc-show-attendance" data-toggle="tab" role="tab"
                  aria-controls="stc-show-attendance" aria-selected="true">
                  <span class="material-icons" aria-hidden="true">how_to_reg</span> Attendance
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link resp-2" id="dsp-tab-lecture-link" href="#stc-show-lecture" data-toggle="tab" role="tab"
                  aria-controls="stc-show-lecture" aria-selected="false">
                  <span class="material-icons" aria-hidden="true">school</span> Lecture
                </a>
              </li>
            </ul>
          </div>
          <div class="card-body px-3 px-md-3 pb-4">
            <div class="dsp-tab-panel-outer">
              <div class="dsp-tab-loader d-none" aria-hidden="true" role="status">
                <span class="dsp-tab-spinner"></span>
                <span style="font-size: 0.86rem; color: #3949ab; font-weight: 600;">Loading…</span>
              </div>
              <div class="tab-content dsp-sched-tab-content">
                <div class="tab-pane fade active show" id="stc-show-attendance" role="tabpanel"
                  aria-labelledby="dsp-tab-attendance-link">
                  <p class="dsp-modal-hint mb-3">Attendance saves per row as you toggle. Switch to Lecture when students are documented.</p>
                  <div class="mb-3 stc-stuattendance-table table-responsive dsp-table-wrap">
                    <table class="table table-hover table-bordered mb-0 stc-show-student-nested-show">
                    </table>
                  </div>
                </div>
                <div class="tab-pane fade" id="stc-show-lecture" role="tabpanel"
                  aria-labelledby="dsp-tab-lecture-link">
                  <p class="dsp-modal-hint mb-3">Required fields reject blank or &ldquo;Select&rdquo;. Unit &amp; remarks stay optional.</p>

                  <fieldset class="dsp-section">
                    <legend>Syllabus you covered</legend>
                    <div class="row">
                      <div class="col-xl-6 col-lg-6 col-md-12">
                        <div class="mb-3">
                          <div class="dsp-field-label">Class type <abbr class="dsp-req" title="required">*</abbr></div>
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
                        <div class="mb-3">
                          <div class="dsp-field-label">Syllabus title <abbr class="dsp-req" title="required">*</abbr></div>
                          <span class="bmd-form-group">
                            <select name="stcschoolmanagementteacherid" id="Syllabus" class="form-control validate Syllabus"
                              aria-required="true">
                              <option value="NA">Select (loaded with period)</option>
                            </select>
                          </span>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-12">
                        <div class="mb-3">
                          <div class="dsp-field-label">Chapter <abbr class="dsp-req" title="required">*</abbr></div>
                          <span class="bmd-form-group">
                            <select name="stcschoolmanagementteacherid" id="chapter" class="form-control validate chapter"
                              aria-required="true">
                              <option value="NA">Select</option>
                            </select>
                          </span>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-12">
                        <div class="mb-3">
                          <div class="dsp-field-label">Lesson <abbr class="dsp-req" title="required">*</abbr></div>
                          <span class="bmd-form-group">
                            <select name="stcschoolmanagementteacherid" id="lession" class="form-control validate lession"
                              aria-required="true">
                              <option value="NA">Select</option>
                            </select>
                          </span>
                        </div>
                      </div>
                    </div>
                  </fieldset>

                  <fieldset class="dsp-section">
                    <legend>Extras <span style="font-weight:400;color:rgba(93,64,55,.76);">&mdash; optional</span></legend>
                    <div class="row">
                      <div class="col-xl-6 col-lg-6 col-md-12">
                        <div class="mb-3">
                          <div class="dsp-field-label">Unit</div>
                          <span class="bmd-form-group">
                            <select name="stcschoolmanagementteacherid" id="unit-should-be" class="form-control validate unit-should-be">
                              <option value="NA">Select</option>
                            </select>
                          </span>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-12">
                        <div class="mb-3">
                          <div class="dsp-field-label">Complete date <small style="text-transform:none;letter-spacing:0;color:#78909c;">read-only</small></div>
                          <span class="bmd-form-group">
                            <input type="text" class="form-control" id="complete-date" placeholder="From syllabus row" disabled>
                          </span>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="mb-3">
                          <div class="dsp-field-label">Remarks</div>
                          <span class="bmd-form-group">
                            <textarea class="form-control" id="remarks" placeholder="Quick session note (optional)"
                              rows="2"></textarea>
                          </span>
                        </div>
                      </div>
                    </div>
                  </fieldset>

                  <div class="mb-3 text-right dsp-actions px-1">
                    <button type="button" class="btn btn-success px-5 save-lecture"><span class="material-icons align-middle mr-2"
                      style="font-size:18px;">save</span>Save lecture row</button>
                    <small class="text-muted d-block mt-2">Pick a syllabus row below before attaching questions.</small>
                  </div>

                  <div class="table-responsive dsp-table-wrap mb-3 px-1">
                    <table class="table table-hover table-bordered mb-0">
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

                  <div class="row mx-1">
                    <div class="col-12 mb-3">
                      <div class="dsp-field-label mb-2">Question text</div>
                      <span class="bmd-form-group">
                        <input type="text" class="form-control" id="Questions" placeholder="Wording saved for chosen lecture row">
                      </span>
                    </div>
                    <div class="col-12 mb-3">
                      <button type="button" class="btn btn-success btn-block add-question px-5">Add to list</button>
                    </div>
                    <div class="col-12">
                      <div class="table-responsive dsp-table-wrap">
                        <table class="table table-hover table-bordered mb-0">
                          <thead>
                            <tr>
                              <th class="text-center">Sl no</th>
                              <th class="text-center">Questions</th>
                              <th class="text-center">Created date</th>
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
      <div class="modal-footer border-top clearfix">
        <button type="button" class="btn btn-secondary dsp-period-cancel px-5 pull-left">Cancel</button>
        <button type="button" class="btn btn-danger dsp-period-finish px-5 pull-right stc-school-exit-period">Finish period</button>
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
        const value = urlParams.get('daily-schedule');
        if(value=="yes"){
          $('.daily-schedule').addClass('active');
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
    <script>
      $(document).ready(function(){

        $(document).on('click', '.stc-school-show-schedultwtype', function(e){
          var type=$(this).attr("id");
          call_scedule(type, 2);
        });
        
        call_scedule(0, 0);
        function call_scedule(type, type2){
          $.ajax({
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stc_teacherschedule_call : 1,
              type:type
            },
            dataType: `JSON`,
            success   : function(response_teacher){
              $('.stc-teacher-schedule-show-table').html(response_teacher.schedule);
              if(type==1){
                $('.stc-schoolschedule-show table thead tr td').show();
              }else{
                $('.stc-schoolschedule-show table thead tr td:eq(4),td:eq(5),td:eq(6),td:eq(7)').hide();
              }
              if(response_teacher.att_result=="y"){
                $('.stc-school-show-student-default').click();
              }else{
                if(type2==0){
                  type2++;
                  call_scedule(1);
                }
              }
            }
          });
        }

        $(document).on('click', '.stc-school-show-student', function(e){
          e.preventDefault();
          var schedule_id=$(this).attr('id');
          var class_id=$(this).attr('class-id');
          var sub_id=$(this).attr('sub-id');
          $.ajax({  
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stc_call_student : 1,
              schedule_id:schedule_id,
              class_id:class_id
            },
            // dataType: `JSON`,
            success   : function(response_student){
             // console.log(data);
              $('.stc-school-hidden-schedule-id').val(schedule_id);
              $('.stc-show-student-nested-show').html(response_student);
              $('.stc-school-hidden-scclass-id').val(class_id);
              $('.stc-school-hidden-scsub-id').val(sub_id);
              $('.stc-school-showstudent-res').modal('show');
              call_syllabus_det();
            }
          });
        });

        $(document).on('click', '.stc-school-show-student-default', function(e){
          e.preventDefault();
          var schedule_id=$(this).attr('id');
          var class_id=$(this).attr('class-id');
          var sub_id=$(this).attr('sub-id');
          $.ajax({  
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stc_call_student_default : 1,
              schedule_id:schedule_id,
              class_id:class_id
            },
            // dataType: `JSON`,
            success   : function(response_student){
             // console.log(data);
              $('.stc-school-hidden-schedule-id').val(schedule_id);
              $('.stc-show-student-nested-show').html(response_student);
              $('.stc-school-hidden-scclass-id').val(class_id);
              $('.stc-school-hidden-scsub-id').val(sub_id);
              $('.stc-school-showstudent-res').modal('show');
              call_syllabus_det();
            }
          });
        });

        /* Static backdrop blocks click-outside; Cancel and header close must hide explicitly. */
        $(document).on('click', '.dsp-period-cancel, .ds-period-modal-head [data-dismiss="modal"]', function (e) {
          e.preventDefault();
          $('.stc-school-showstudent-res').modal('hide');
        });

        $(document).on('click', '.stc-school-exit-period', function(e){
          e.preventDefault();
          var student_id = [];
          var sub_id = [];
          var class_id = [];
          var attendance = [];
          $('.stc-attend-check:checked').each(function () {
            student_id.push($(this).attr('id')); 
            sub_id.push($(this).attr('subid')); 
            class_id.push($(this).attr('classid')); 
            attendance.push($(this).val()); 
          });
          $.ajax({  
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stc_call_lecture_end : 1,
              student_id : student_id,
              sub_id : sub_id,
              class_id : class_id,
              attendance : attendance

            },
            // dataType: `JSON`,
            success   : function(response_student){
             // console.log(data);
              var response=response_student.trim();
              if(response=="reload"){
                window.location.reload();
              }else{
                $('.stc-school-showstudent-res').modal('hide');
                window.location.reload();
              }
            }
          });
        });

        // $(document).on('click', '.stc-attend-check', function(e){
        //   var stvalue=$(this).val();
        //   if(stvalue==0){
        //     $(this).parent().parent().find('.stc-school-student-att-save').hide();
        //   }else{
        //     $(this).parent().parent().find('.stc-school-student-att-save').show();
        //   }
        // });
        
        $(document).on('click', '.stc-school-student-att-save', function(e){
          e.preventDefault();
          $(this).hide(500);
          var stc_stid = $(this).attr('id');
          var stc_stclassid = $(this).attr('classid');
          var stc_stsubid = $(this).attr('subid');
          var stc_sthwperc = $('.stc-school-stu-attendance-hw'+stc_stid).val();
          var stc_stcatt = $('.stc-school-stu-attendance-but'+stc_stid + ':checked').val();
          $.ajax({  
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stc_student_save : 1,
              stc_stid : stc_stid,
              stc_stsubid : stc_stsubid,
              stc_stclassid : stc_stclassid,
              stc_sthwperc : stc_sthwperc,
              stc_stcatt : stc_stcatt
            },
            // dataType: `JSON`,
            success   : function(response_student){
             // console.log(response_student);
              var response=response_student.trim();
              if(response=="reload"){
                window.location.reload();
              }else if(response=="success"){
                alert("Student record updated!!!");
              }else{
                alert("Something went wrong!!! Please check & try again.");
                $('.stc-school-student-att-save').show(500);
              }
            }
          });
        });
        
        $(document).on('change', '.stc-attend-check', function(e){
          var stc_stid = $(this).attr('id');
          var stc_stclassid = $(this).attr('classid');
          var stc_stsubid = $(this).attr('subid');
          var stc_sthwperc = 0;
          var stc_stcatt = $(this).val();
          if(stc_stcatt>0){
            $.ajax({  
              url       : "../vanaheim/school-management.php",
              method    : "POST",  
              data      : {
                stc_student_save : 1,
                stc_stid : stc_stid,
                stc_stsubid : stc_stsubid,
                stc_stclassid : stc_stclassid,
                stc_sthwperc : stc_sthwperc,
                stc_stcatt : stc_stcatt
              },
              // dataType: `JSON`,
              success   : function(response_student){
              // console.log(response_student);
                var response=response_student.trim();
                if(response=="reload"){
                  window.location.reload();
                }else if(response=="success"){
                  // alert("Student attendance updated!!!");
                }else if(response=="duplicate"){
                  alert("Student attendance already updated!!!");
                }else{
                  alert("Something went wrong!!! Please check & try again.");
                  $('.stc-school-student-att-save').show(500);
                }
              }
            });
          }
        });

        $(document).on('click', '.save-lecture', function (e) {
          e.preventDefault();
          dspBannerClear();

          var schedule_id = dspTrim($('.stc-school-hidden-schedule-id').val());
          var classtype = dspTrim($('#classtype').val());
          var chapter = dspTrim($('#chapter').val());
          var lession = dspTrim($('#lession').val());
          var syllabus = dspTrim($('#Syllabus').val());
          var unitSel = $('#unit-should-be').val();
          var unit = ($.trim(unitSel) === '' || dspTrim(unitSel).toUpperCase() === 'NA') ? '' : dspTrim(unitSel);
          var remarks = dspTrim($('#remarks').val());

          if (!dspFilledSel(schedule_id)) {
            $('#dsp-tab-lecture-link').tab('show');
            dspBannerMsg('<strong>Period not ready.</strong> Re-open this modal from the schedule.', false);
            return;
          }
          if (!dspFilledSel(classtype) || !dspFilledSel(syllabus) || !dspFilledSel(chapter) || !dspFilledSel(lession)) {
            $('#dsp-tab-lecture-link').tab('show');
            dspBannerMsg('<strong>Required fields missing.</strong> Choose class type, syllabus, chapter, and lesson.', false);
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
                $('#Syllabus').html('<option value="NA">Select (loaded with period)</option>');
                $('#chapter').html('<option value="NA">Select</option>');
                $('#lession').html('<option value="NA">Select</option>');
                $('#unit-should-be').html('<option value="NA">Select</option>');
                $('#remarks').val('');
                $('#complete-date').val('');
                $('#Questions').val('');
                dspBannerMsg('<strong>Lecture row saved.</strong> Select it in the table before adding questions.', true);
                return;
              }
              if (response == "empty") {
                $('#dsp-tab-lecture-link').tab('show');
                dspBannerMsg('<strong>Could not save.</strong> Avoid blank required fields.', false);
                return;
              }
              dspBannerMsg('<strong>Error.</strong> Please try again shortly.', false);
            }
          });
        });

        $(document).on('click', '.add-question', function (e) {
          e.preventDefault();
          dspBannerClear();

          var schedule_id = dspTrim($('.stc-school-hidden-schedule-id').val());
          var questions = dspTrim($('#Questions').val());

          if (!dspFilledSel(schedule_id)) {
            $('#dsp-tab-lecture-link').tab('show');
            dspBannerMsg('<strong>No period binding.</strong> Re-open from the timetable.', false);
            return;
          }
          if (!dspFilledText(questions)) {
            $('#dsp-tab-lecture-link').tab('show');
            dspBannerMsg('<strong>Add question text.</strong> Whitespace-only input is skipped.', false);
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
                dspBannerMsg('<strong>Question queued.</strong> It appears beside the lecture row.', true);
                $('#dsp-tab-lecture-link').tab('show');
                return;
              }
              if (response == "empty") {
                $('#dsp-tab-lecture-link').tab('show');
                dspBannerMsg('<strong>Could not save.</strong> Add wording and confirm a timetable row.', false);
                return;
              }
              dspBannerMsg('<strong>Error.</strong> Try again.', false);
            }
          });
        });

        var sy_syllabus = new Array();

        /** Period modal banners + loaders */
        function _dspStudentModal() {
          return $('.stc-school-showstudent-res');
        }

        function dspTrim(v) {
          return $.trim((v !== undefined && v !== null ? String(v) : ''));
        }

        function dspFilledSel(v) {
          var t = dspTrim(v);
          return t !== '' && t.toUpperCase() !== 'NA';
        }

        function dspFilledText(v) {
          return dspTrim(v) !== '';
        }

        var _dspSuccTimer = null;
        function dspBannerClear() {
          if (_dspSuccTimer) {
            clearTimeout(_dspSuccTimer);
            _dspSuccTimer = null;
          }
          _dspStudentModal().find('.dsp-form-error').addClass('d-none').removeClass('alert-warning alert-success').empty();
        }

        function dspBannerMsg(html, ok) {
          dspBannerClear();
          if (!html) {
            return;
          }
          _dspSuccTimer = ok ? setTimeout(dspBannerClear, 5000) : null;
          _dspStudentModal().find('.dsp-form-error').removeClass('d-none alert-warning alert-success')
            .addClass(ok ? 'alert-success' : 'alert-warning').html(html);
        }

        var _dspLoaderDepth = 0;

        function dspLoaderSync() {
          var $e = _dspStudentModal().find('.dsp-tab-loader');
          if (_dspLoaderDepth > 0) {
            $e.removeClass('d-none').attr('aria-busy', 'true');
          } else {
            $e.addClass('d-none').removeAttr('aria-busy');
          }
        }

        function dspLoaderBegin() {
          _dspLoaderDepth++;
          dspLoaderSync();
        }

        function dspLoaderEnd() {
          _dspLoaderDepth = Math.max(0, _dspLoaderDepth - 1);
          dspLoaderSync();
        }

        function dspLoaderReset() {
          _dspLoaderDepth = 0;
          dspLoaderSync();
        }

        $(document).on('show.bs.tab', '.stc-school-showstudent-res .dsp-sched-tab-nav a[data-toggle="tab"]', function () {
          dspLoaderBegin();
        });

        $(document).on('shown.bs.tab', '.stc-school-showstudent-res .dsp-sched-tab-nav a[data-toggle="tab"]', function () {
          dspLoaderEnd();
        });

        $(document).on('hidden.bs.modal', '.stc-school-showstudent-res', function () {
          dspLoaderReset();
          dspBannerClear();
        });

        $(document).on('shown.bs.modal', '.stc-school-showstudent-res', function () {
          dspBannerClear();
          dspLoaderReset();
        });

        function call_syllabus_det() {
          var schedule_id = $('.stc-school-hidden-schedule-id').val();
          var class_id = $('.stc-school-hidden-scclass-id').val();
          var sub_id = $('.stc-school-hidden-scsub-id').val();
          dspLoaderBegin();
          $.ajax({
            url: "../vanaheim/school-management.php",
            method: "POST",
            data: {
              stc_syllabusdet_call: 1,
              schedule_id: schedule_id,
              class_id: class_id,
              sub_id: sub_id
            },
            dataType: `JSON`,
            complete: function () {
              dspLoaderEnd();
            },
            success: function (response_student) {
              $('.stc-show-student-syllabusdet-show').html(response_student.lecture_details);
              var syl_result = response_student.syllabus_details;
              sy_syllabus = [];
              sy_syllabus.push(syl_result);
              var syllabus_output = '<option value="NA">Select</option>';
              for (var i = 0; i < sy_syllabus[0].length; i++) {
                syllabus_output += '<option value="' + sy_syllabus[0][i].stc_school_syllabus_id + '">' + sy_syllabus[0][i].stc_school_syllabus_title + '</option>';
              }
              $('#Syllabus').html(syllabus_output);
              call_syllabus_quest();
            }
          });
        }

        $(document).on('change', '#Syllabus', function () {
          var syll_id = $(this).val();
          var chapter_output = '<option value="NA">Select</option>';
          for (var i = 0; i < sy_syllabus[0].length; i++) {
            if(sy_syllabus[0][i].stc_school_syllabus_chapter==""){
              continue;
            }
            chapter_output += '<option value="' + sy_syllabus[0][i].stc_school_syllabus_chapter + '" syll-id="' + sy_syllabus[0][i].stc_school_syllabus_id + '">' + sy_syllabus[0][i].stc_school_syllabus_chapter + '</option>';
          }
          $('#chapter').html(chapter_output);
        });

        $(document).on('change', '#chapter', function () {
          var syll_id = $(this).val();
          var lession_output = '<option value="NA">Select</option>';
          for (var i = 0; i < sy_syllabus[0].length; i++) {
            if(sy_syllabus[0][i].stc_school_syllabus_lesson==""){
              continue;
            }
            lession_output += '<option value="' + sy_syllabus[0][i].stc_school_syllabus_lesson + '" syll-id="' + sy_syllabus[0][i].stc_school_syllabus_id + '">' + sy_syllabus[0][i].stc_school_syllabus_lesson + '</option>';
          }
          $('#lession').html(lession_output);
        });

        $(document).on('change', '#lession', function () {
          var syll_id = $(this).val();
          var unit_output = '<option value="NA">Select</option>';
          for (var i = 0; i < sy_syllabus[0].length; i++) {
            if(sy_syllabus[0][i].stc_school_syllabus_unit==""){
              continue;
            }
            unit_output += '<option value="' + sy_syllabus[0][i].stc_school_syllabus_unit + '" syll-id="' + sy_syllabus[0][i].stc_school_syllabus_id + '">' + sy_syllabus[0][i].stc_school_syllabus_unit + '</option>';
          }
          $('#unit-should-be').html(unit_output);
        });

        $(document).on('change', '#unit-should-be', function () {
          var syll_id = $(this).val();
          var cdate_output = '';
          for (var i = 0; i < sy_syllabus[0].length; i++) {
            if (syll_id == sy_syllabus[0][i].stc_school_syllabus_id) {
              if(sy_syllabus[0][i].stc_school_syllabus_completedate==""){
                continue;
              }
              cdate_output = sy_syllabus[0][i].stc_school_syllabus_completedate;
            }
          }
          $('#complete-date').val(cdate_output);
        });

        function call_syllabus_quest() {
          var question_id = $('.stc-syllabus-out:checked').attr("id");
          if (question_id > 0) {
            dspLoaderBegin();
            $.ajax({
              url: "../vanaheim/school-management.php",
              method: "POST",
              data: {
                stc_syllabusquest_call: 1,
                question_id: question_id
              },
              complete: function () {
                dspLoaderEnd();
              },
              success: function (response_student) {
                $('.stc-show-student-syllabusquest-show').html(response_student);
              }
            });
          }
        }

        $(document).on('click', '.stc-syllabus-out', function () {
          call_syllabus_quest();
        });
      });
    </script>
  </body>
  </html>

