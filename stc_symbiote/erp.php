<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(empty(@$_SESSION['stc_admin_info_id'])){
    header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>ERP - STC</title>
    <link rel = "icon" type = "image/png" href="img/stc_logo_title.png">
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700"
    />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <!-- https://fontawesome.com/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/templatemo-style.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <style>      
      .menu {
        -webkit-filter: url("#shadowed-goo");
                filter: url("#shadowed-goo");
      }

      .menu-item, .menu-open-button {
        background: #000;
        border-radius: 100%;
        width: 80px;
        height: 80px;
        margin-left: -40px;
        position: absolute;
        top: 70px;
        color: white;
        text-align: center;
        line-height: 80px;
        -webkit-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0);
        -webkit-transition: -webkit-transform ease-out 200ms;
        transition: -webkit-transform ease-out 200ms;
        transition: transform ease-out 200ms;
        transition: transform ease-out 200ms, -webkit-transform ease-out 200ms;
      }

      .menu-open {
        display: none;
      }

      .hamburger {
        width: 25px;
        height: 3px;
        background: white;
        display: block;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-left: -12.5px;
        margin-top: -1.5px;
        -webkit-transition: -webkit-transform 200ms;
        transition: -webkit-transform 200ms;
        transition: transform 200ms;
        transition: transform 200ms, -webkit-transform 200ms;
      }

      .hamburger-1 {
        -webkit-transform: translate3d(0, -8px, 0);
                transform: translate3d(0, -8px, 0);
      }

      .hamburger-2 {
        -webkit-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0);
      }

      .hamburger-3 {
        -webkit-transform: translate3d(0, 8px, 0);
                transform: translate3d(0, 8px, 0);
      }

      .menu-open:checked + .menu-open-button .hamburger-1 {
        -webkit-transform: translate3d(0, 0, 0) rotate(45deg);
                transform: translate3d(0, 0, 0) rotate(45deg);
      }
      .menu-open:checked + .menu-open-button .hamburger-2 {
        -webkit-transform: translate3d(0, 0, 0) scale(0.1, 1);
                transform: translate3d(0, 0, 0) scale(0.1, 1);
      }
      .menu-open:checked + .menu-open-button .hamburger-3 {
        -webkit-transform: translate3d(0, 0, 0) rotate(-45deg);
                transform: translate3d(0, 0, 0) rotate(-45deg);
      }

      .menu {
        position: fixed;
        right: -334px;
        margin-left: -190px;
        padding-top: 20px;
        padding-left: 0px;
        width: 380px;
        height: 250px;
        box-sizing: border-box;
        font-size: 20px;
        text-align: left;
      }

      .menu-item:hover {
        background: white;
        color: #ff4081;
      }
      .menu-item:nth-child(3) {
        -webkit-transition-duration: 70ms;
                transition-duration: 70ms;
      }
      .menu-item:nth-child(4) {
        -webkit-transition-duration: 130ms;
                transition-duration: 130ms;
      }
      .menu-item:nth-child(5) {
        -webkit-transition-duration: 190ms;
                transition-duration: 190ms;
      }
      .menu-item:nth-child(6) {
        -webkit-transition-duration: 250ms;
                transition-duration: 250ms;
      }
      .menu-item:nth-child(7) {
        -webkit-transition-duration: 310ms;
                transition-duration: 310ms;
      }
      .menu-item:nth-child(8) {
        -webkit-transition-duration: 310ms;
                transition-duration: 310ms;
      }

      .menu-open-button {
        z-index: 2;
        -webkit-transition-timing-function: cubic-bezier(0.175, 0.885, 0.32, 1.275);
                transition-timing-function: cubic-bezier(0.175, 0.885, 0.32, 1.275);
        -webkit-transition-duration: 400ms;
                transition-duration: 400ms;
        -webkit-transform: scale(1.1, 1.1) translate3d(0, 0, 0);
                transform: scale(1.1, 1.1) translate3d(0, 0, 0);
        cursor: pointer;
      }

      .menu-open-button:hover {
        -webkit-transform: scale(1.2, 1.2) translate3d(0, 0, 0);
                transform: scale(1.2, 1.2) translate3d(0, 0, 0);
      }

      .menu-open:checked + .menu-open-button {
        -webkit-transition-timing-function: linear;
                transition-timing-function: linear;
        -webkit-transition-duration: 200ms;
                transition-duration: 200ms;
        -webkit-transform: scale(0.8, 0.8) translate3d(0, 0, 0);
                transform: scale(0.8, 0.8) translate3d(0, 0, 0);
      }

      .menu-open:checked ~ .menu-item {
        -webkit-transition-timing-function: cubic-bezier(0.935, 0, 0.34, 1.33);
                transition-timing-function: cubic-bezier(0.935, 0, 0.34, 1.33);
      }
      .menu-open:checked ~ .menu-item:nth-child(3) {
        -webkit-transition-duration: 160ms;
                transition-duration: 160ms;
        -webkit-transform: translate3d(114.42548px, 11.48084px, 0);
                transform: translate3d(0.09158px, -114.99996px, 0);
      }
      .menu-open:checked ~ .menu-item:nth-child(4) {
        -webkit-transition-duration: 240ms;
                transition-duration: 240ms;
        -webkit-transform: translate3d(77.18543px, 85.2491px, 0);
                transform: translate3d(-77.04956px, -62.37192px, 0);
      }
      .menu-open:checked ~ .menu-item:nth-child(5) {
        -webkit-transition-duration: 320ms;
                transition-duration: 320ms;
        -webkit-transform: translate3d(0.09158px, 114.99996px, 0);
                transform: translate3d(-114.40705px, 11.66307px, 0);
      }
      .menu-open:checked ~ .menu-item:nth-child(6) {
        -webkit-transition-duration: 400ms;
                transition-duration: 400ms;
        -webkit-transform: translate3d(-77.04956px, 85.37192px, 0);
                transform: translate3d(-77.04956px, 85.37192px, 0);
      }
      .menu-open:checked ~ .menu-item:nth-child(7) {
        -webkit-transition-duration: 480ms;
                transition-duration: 480ms;
        -webkit-transform: translate3d(-114.40705px, 11.66307px, 0);
                transform: translate3d(0.09158px, 114.99996px, 0);
      }
      .menu-open:checked ~ .menu-item:nth-child(8) {
        -webkit-transition-duration: 480ms;
                transition-duration: 480ms;
        -webkit-transform: translate3d(-114.40705px, 11.66307px, 0);
                transform: translate3d(-200.90842px, 11.99996px, 0);
      }

      .left-menu {
        -webkit-filter: url("#shadowed-goo");
                filter: url("#shadowed-goo");
      }

      .left-menu-item, .left-menu-open-button {
        background: #000;
        border-radius: 100%;
        width: 80px;
        height: 80px;
        margin-left: -40px;
        position: absolute;
        top: 70px;
        color: white;
        text-align: center;
        line-height: 80px;
        -webkit-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0);
        -webkit-transition: -webkit-transform ease-out 200ms;
        transition: -webkit-transform ease-out 200ms;
        transition: transform ease-out 200ms;
        transition: transform ease-out 200ms, -webkit-transform ease-out 200ms;
      }

      .left-menu-open {
        display: none;
      }

      .left-hamburger {
        width: 25px;
        height: 3px;
        background: white;
        display: block;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-left: -12.5px;
        margin-top: -1.5px;
        -webkit-transition: -webkit-transform 200ms;
        transition: -webkit-transform 200ms;
        transition: transform 200ms;
        transition: transform 200ms, -webkit-transform 200ms;
      }

      .left-hamburger-1 {
        -webkit-transform: translate3d(0, -8px, 0);
                transform: translate3d(0, -8px, 0);
      }

      .left-hamburger-2 {
        -webkit-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0);
      }

      .left-hamburger-3 {
        -webkit-transform: translate3d(0, 8px, 0);
                transform: translate3d(0, 8px, 0);
      }

      .left-menu-open:checked + .left-menu-open-button .left-hamburger-1 {
        -webkit-transform: translate3d(0, 0, 0) rotate(45deg);
                transform: translate3d(0, 0, 0) rotate(45deg);
      }
      .left-menu-open:checked + .left-menu-open-button .left-hamburger-2 {
        -webkit-transform: translate3d(0, 0, 0) scale(0.1, 1);
                transform: translate3d(0, 0, 0) scale(0.1, 1);
      }
      .left-menu-open:checked + .left-menu-open-button .left-hamburger-3 {
        -webkit-transform: translate3d(0, 0, 0) rotate(-45deg);
                transform: translate3d(0, 0, 0) rotate(-45deg);
      }

      .left-menu {
        position: fixed;
        left: 235px;
        margin-left: -190px;
        padding-top: 20px;
        padding-left: 0px;
        width: 0;
        height: 0;
        box-sizing: border-box;
        font-size: 20px;
        text-align: left;
      }

      .left-menu-item:hover {
        background: white;
        color: #ff4081;
      }
      .left-menu-item:nth-child(3) {
        -webkit-transition-duration: 70ms;
                transition-duration: 70ms;
      }
      .left-menu-item:nth-child(4) {
        -webkit-transition-duration: 130ms;
                transition-duration: 130ms;
      }
      .left-menu-item:nth-child(5) {
        -webkit-transition-duration: 190ms;
                transition-duration: 190ms;
      }
      .left-menu-item:nth-child(6) {
        -webkit-transition-duration: 250ms;
                transition-duration: 250ms;
      }
      .left-menu-item:nth-child(7) {
        -webkit-transition-duration: 310ms;
                transition-duration: 310ms;
      }
      .left-menu-item:nth-child(8) {
        -webkit-transition-duration: 310ms;
                transition-duration: 310ms;
      }

      .left-menu-open-button {
        z-index: 2;
        -webkit-transition-timing-function: cubic-bezier(0.175, 0.885, 0.32, 1.275);
                transition-timing-function: cubic-bezier(0.175, 0.885, 0.32, 1.275);
        -webkit-transition-duration: 400ms;
                transition-duration: 400ms;
        -webkit-transform: scale(1.1, 1.1) translate3d(0, 0, 0);
                transform: scale(1.1, 1.1) translate3d(0, 0, 0);
        cursor: pointer;
      }

      .left-menu-open-button:hover {
        -webkit-transform: scale(1.2, 1.2) translate3d(0, 0, 0);
                transform: scale(1.2, 1.2) translate3d(0, 0, 0);
      }

      .left-menu-open:checked + .left-menu-open-button {
        -webkit-transition-timing-function: linear;
                transition-timing-function: linear;
        -webkit-transition-duration: 200ms;
                transition-duration: 200ms;
        -webkit-transform: scale(0.8, 0.8) translate3d(0, 0, 0);
                transform: scale(0.8, 0.8) translate3d(0, 0, 0);
      }

      .left-menu-open:checked ~ .left-menu-item {
        -webkit-transition-timing-function: cubic-bezier(0.935, 0, 0.34, 1.33);
                transition-timing-function: cubic-bezier(0.935, 0, 0.34, 1.33);
      }
      .left-menu-open:checked ~ .left-menu-item:nth-child(3) {
        -webkit-transition-duration: 160ms;
                transition-duration: 160ms;
        -webkit-transform: translate3d(114.42548px, 11.48084px, 0);
                transform: translate3d(0.09158px, -114.99996px, 0);
      }
      .left-menu-open:checked ~ .left-menu-item:nth-child(4) {
        -webkit-transition-duration: 240ms;
                transition-duration: 240ms;
        -webkit-transform: translate3d(77.18543px, 85.2491px, 0);
                transform: translate3d(77.04956px, -62.37192px, 0);
      }
      .left-menu-open:checked ~ .left-menu-item:nth-child(5) {
        -webkit-transition-duration: 320ms;
                transition-duration: 320ms;
        -webkit-transform: translate3d(0.09158px, 114.99996px, 0);
                transform: translate3d(137.40705px, 2.66307px, 0);
      }
      .left-menu-open:checked ~ .left-menu-item:nth-child(6) {
        -webkit-transition-duration: 400ms;
                transition-duration: 400ms;
        -webkit-transform: translate3d(-77.04956px, 85.37192px, 0);
                transform: translate3d(79.04956px, 70.37192px, 0);
      }
      .left-menu-open:checked ~ .left-menu-item:nth-child(7) {
        -webkit-transition-duration: 480ms;
                transition-duration: 480ms;
        -webkit-transform: translate3d(-114.40705px, 11.66307px, 0);
                transform: translate3d(0.09158px, 114.99996px, 0);
      }

      @media screen and (max-width: 767px) {
        .menu {
          /*padding-left: 347px;
          top: 118px;*/
        }
      }
    </style>
  </head>

  <body id="reportsPage">
    <?php include "header.php";?>
    <div class="container mt-5">
      <div class="row tm-content-row">
        <!-- section 1 -->
        <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 tm-block-col">          
          <div class="tm-bg-primary-dark tm-block tm-block-products animated rollIn">
            <h2 class="tm-block-title d-inline-block">MASTER</h2>
            <a
              href="administration.php"
              class="btn btn-primary btn-block text-uppercase mb-3">
              Administration
            </a>
            <a
              href="add-product.php"
              class="btn btn-primary btn-block text-uppercase mb-3">
              product
            </a>
            <a 
              href="add-merchant.php" 
              class="btn btn-primary btn-block text-uppercase mb-3">
              Merchant
            </a>
            <a 
              href="add-customer.php" 
              class="btn btn-primary btn-block text-uppercase mb-3">
              Customer
            </a> 
            <a 
              href="own-agents.php" 
              class="btn btn-primary btn-block text-uppercase mb-3">
              Agents
            </a>
            <a 
              href="employee.php" 
              class="btn btn-primary btn-block text-uppercase mb-3">
              End Users
            </a> 
            <a 
              href="employee.php" 
              class="btn btn-primary btn-block text-uppercase mb-3">
              Inventor
            </a>   
          </div>
        </div>

        <!-- section 2 -->
        <div class="col-sm-12 col-md-8 col-lg-8 col-xl-8 tm-block-col">
          <div class="tm-bg-primary-dark tm-block tm-block-products animated zoomIn">
            <h2 class="tm-block-title d-inline-block">TRANSACTION</h2>
            <table class="table tm-table-small tm-product-table">
              <tbody>
                <tr>
                  <td>
                    <a 
                      href="vendor_quotation.php" 
                      class="btn btn-primary btn-block text-uppercase mb-3">
                      Merchant Quotations
                    </a>
                  </td> 
                  <td>
                    <a 
                      href="purchase-product.php" 
                      class="btn btn-primary btn-block text-uppercase mb-3">
                      Purchase Orders
                    </a>
                  </td>
                </tr>
                <tr>
                  <td>
                    <a 
                      href="grn.php" 
                      class="btn btn-primary btn-block text-uppercase mb-3">
                      Good Reciept Notes
                    </a>
                  </td>
                  <td>
                    <a 
                      href="purchase_order_payments.php" 
                      class="btn btn-primary btn-block text-uppercase mb-3">
                      PO Payments
                    </a>
                  </td>
                </tr>
                <tr>
                  <td>
                    <a 
                      href="challan.php" 
                      class="btn btn-primary btn-block text-uppercase mb-3">
                      Challan
                    </a>
                  </td>
                  <td> 
                    <a 
                      href="direct-challan.php" 
                      class="btn btn-primary btn-block text-uppercase mb-3">
                      Direct Challan
                    </a>
                  </td>
                </tr>
                <tr>
                  <td> 
                    <a 
                      href="virtual-challan.php" 
                      class="btn btn-primary btn-block text-uppercase mb-3">
                      Virtual Challan
                    </a>
                  </td>
                  <td>  
                    <a 
                      href="sale-product.php" 
                      class="btn btn-primary btn-block text-uppercase mb-3">
                      Sale Orders
                    </a> 
                  </td>
                </tr>
                <tr>
                  <td> 
                    <a 
                      href="sale-order-payment.php" 
                      class="btn btn-primary btn-block text-uppercase mb-3">
                      Sale Order Payments
                    </a>
                  </td>
                  <td>  
                    <a 
                      href="register.php" 
                      class="btn btn-primary btn-block text-uppercase mb-3">
                      Registers
                    </a> 
                  </td>
                </tr>
              </tbody>
            </table>         
          </div>
        </div>
        <?php include_once "storm_breaker/captain_marvel.php";?>
      </div>
    </div>
    <?php include "footer.php";?>
  </body>
</html>