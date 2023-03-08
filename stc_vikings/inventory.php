<?php  
session_start(); 
if(isset($_SESSION["stc_empl_id"]) && ($_SESSION["stc_empl_role"]>0)){ 
}else{ 
    header("Location:index.html"); 
} 
$page_code=406;
include("kattegat/role_check.php");
?> 
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Inventory - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous"> -->
    <link rel = "icon" type = "image/png" href="images/stc_logo_title.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php include_once("header-nav.php");?>
        <div class="app-main">
            <?php include_once("sidebar-nav.php");?>                   
            <div class="app-main__outer">
                <div class="app-main__inner"> 
                    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                        <li class="nav-item">
                            <a role="tab" class="nav-link active" id="tab-1" data-toggle="tab" href="#tab-content-1">
                                <span>Inventory</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade show active" id="tab-content-1" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="description" align="center"
                                          >Inventory
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="row stc-view-product-row">
                              <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="card-border mb-3 card card-body border-primary">
                                        <form action="" class="stc-view-product-form">
                                            <table class="table table-hover ">
                                              <thead>
                                                <tr>
                                                  <th scope="col" width="100%">Search By Material Name/ Rack/ Category/ Sub Category</th>
                                                  <th scope="col">Search</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <tr>
                                                  <td>
                                                    <input
                                                      id="invtags"
                                                      data-role="tagsinput"
                                                      type="text"
                                                      placeholder="Material Name/ Rack/ Category/ Sub Category"
                                                      class="form-control validate"
                                                      required
                                                    />
                                                  </td>
                                                  <td>
                                                    <button type="button" name="search" class="btn btn-primary" id="invfilter">Search</button>
                                                  </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                        </form>
                                    </div>
                              </div>
                            </div>
                            <div class="row stc-view-quote-row">
                              <div class="col-xl-12 col-lg-12 col-md-12">
                                <div class="card-border mb-3 card card-body border-danger">
                                  <form action="" class="stc-inventory-form">
                                      <table class="table table-hover ">
                                        <tr>
                                          <td>
                                            Loading.....
                                          </td>
                                        </tr>
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
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
    <script>
        $(document).ready(function(){
          inventory();
          function inventory(){
            var begvalue=0;
            var endvalue=20;
            $.ajax({
              url : "kattegat/ragnar_inventory.php",
              method : "post",
              data : {
                invent_call:1,
                begvalue:begvalue,
                endvalue:endvalue
              },
              dataType : 'JSON',
              success : function(data){
                // console.log(data);
                $('.stc-inventory-form').html(data['inventory_items']);
                $('.stc-inventory-callrack').html(data['rack']);
              }
            });
          }
          
          // for before paging
          $('body').delegate('.begbuttoninv', 'click', function(e){
            e.preventDefault();
            var begvalueinputted=$('.begvalueinput').val();
            var endvalueinputted=$('.endvalueinput').val();
            if(begvalueinputted==0){
              alert("Seriously!!!");
            }else{
              begvalueinputted= +begvalueinputted - 20;
              endvalueinputted= +endvalueinputted - 20;
              $.ajax({
                url : "kattegat/ragnar_inventory.php",
                method : "post",
                data : {
                  invent_call:1,
                  begvalue:begvalueinputted,
                  endvalue:endvalueinputted
                },
                dataType : 'JSON',
                success : function(data){
                  // console.log(data);
                  $('.stc-inventory-form').html(data['inventory_items']);
                  $('.stc-inventory-callrack').html(data['rack']);
                }
              });
            }
          });
          
          // for after paging
          $('body').delegate('.endbuttoninv', 'click', function(e){
            e.preventDefault();
            var begvalueinputted=$('.begvalueinput').val();
            var endvalueinputted=$('.endvalueinput').val();
            var outbegvalueinputted=0;
            var outendvalueinputted=0;
            if(endvalueinputted==0){
              alert("Seriously!!!");
            }else{
              outbegvalueinputted= (+begvalueinputted) + 20;
              outendvalueinputted= (+endvalueinputted) + 20;
              $.ajax({
                url : "kattegat/ragnar_inventory.php",
                method : "post",
                data : {
                  invent_call:1,
                  begvalue:outbegvalueinputted,
                  endvalue:outendvalueinputted
                },
                dataType : 'JSON',
                success : function(data){
                  // console.log(data);
                  $('.stc-inventory-form').html(data['inventory_items']);
                  $('.stc-inventory-callrack').html(data['rack']);
                }
              });
            }
          });

          // material search with paging
          $('body').delegate('#invfilter', 'click', function(e){
            e.preventDefault();
            var query = $('#invtags').val();
            var begvalue=0;
            var endvalue=20;
            $.ajax({
              url       : "kattegat/ragnar_inventory.php",
              method    : "POST",
              data      : {
                  search_alo_in:query,
                  begvalue:begvalue,
                  endvalue:endvalue
              },
              dataType  : "json",
              success   : function(data){
                // console.log(data);
                $('.stc-inventory-form').html(data['inventory_items_byname']);
                $('.stc-inventory-callrack').html(data['rack']);
              }
            });
          });

          // paging before search
          $('body').delegate('.begbuttoninvsearch', 'click', function(e){
            e.preventDefault();
            var query = $('#invtags').val();
            var begvalue=$('.begvalueinputsearch').val();
            var endvalue=$('.endvalueinputsearch').val();
            if(endvalue==0){
              alert("Seriously!!!");
            }else{
              outbegvalueinputted= (+begvalue) - 20;
              outendvalueinputted= (+endvalue) - 20;
              $.ajax({
                url       :"kattegat/ragnar_inventory.php",
                method    :"POST",
                data      :{
                    search_alo_in:query,
                    begvalue:outbegvalueinputted,
                    endvalue:outendvalueinputted
                },
                dataType  :"json",
                success:function(data){
                  // console.log(data);
                  $('.stc-inventory-form').html(data['inventory_items_byname']);
                  $('.stc-inventory-callrack').html(data['rack']);
                }
              });
            }
          });

          // paging after search
          $('body').delegate('.endbuttoninvsearch', 'click', function(e){
            e.preventDefault();
            var query = $('#invtags').val();
            var begvalue=$('.begvalueinputsearch').val();
            var endvalue=$('.endvalueinputsearch').val();
            if(endvalue==0){
              alert("Seriously!!!");
            }else{
              outbegvalueinputted= (+begvalue) + 20;
              outendvalueinputted= (+endvalue) + 20;
              $.ajax({
                url       :"kattegat/ragnar_inventory.php",
                method    :"POST",
                data      :{
                    search_alo_in:query,
                    begvalue:outbegvalueinputted,
                    endvalue:outendvalueinputted
                },
                dataType  :"json",
                success:function(data){
                  // console.log(data);
                  $('.stc-inventory-form').html(data['inventory_items_byname']);
                  $('.stc-inventory-callrack').html(data['rack']);
                }
              });
            }
          });
        });
    </script>
</body>
</html>
