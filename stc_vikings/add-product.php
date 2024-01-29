<?php  
session_start(); 
if(isset($_SESSION["stc_empl_id"]) && ($_SESSION["stc_empl_role"]>0)){ 
}else{ 
    header("Location:index.html"); 
} 
$page_code=301;
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
    <title>Add Product - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous"> -->
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
                                <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
                                    <span>Add New Products</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link active" id="tab-2" data-toggle="tab" href="#tab-content-2">
                                    <span>View All Products</span>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-3" data-toggle="tab" href="#tab-content-3">
                                    <span>View Product Purchase</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-4" data-toggle="tab" href="#tab-content-4">
                                    <span>View Product Sale</span>
                                </a>
                            </li> -->
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade  " id="tab-content-1" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >Add Product
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <form action="" class="stc-add-product-form">
                                        <div class="card-border mb-3 card card-body border-primary">
                                            <h5
                                              for="description"
                                              >Product Name
                                            </h5><br>
                                            <textarea
                                              class="form-control validate"
                                              rows="4"
                                              name="stcpdname"
                                              placeholder="Product Name"
                                              required
                                            /></textarea>
                                        </div>
                                        <div class="card-border mb-3 card card-body border-secondary">
                                            <h5
                                              for="description"
                                              >Description</h5
                                            >
                                            <textarea
                                              class="form-control validate"
                                              rows="4"
                                              name="stcpddesc"
                                              placeholder="Product Description"
                                              required
                                            ></textarea>
                                        </div>
                                        <div class="card-border mb-3 card card-body border-primary">
                                            <h5
                                              for="category"
                                              >Category</h5
                                            ><br>
                                            <select
                                              class="custom-select tm-select-accounts call_cat"
                                              name="stcpdcategory"
                                            >
                                            </select>
                                        </div>
                                        <div class="card-border mb-3 card card-body border-primary">
                                                <h5
                                                  for="quantity"
                                                  >HSN Code
                                                </h5><br>
                                                <input
                                                  id="name"
                                                  name="stcpdhsncode"
                                                  type="number"
                                                  placeholder="Product HSN Code"
                                                  class="form-control validate"
                                                  required
                                                />
                                        </div>                                            
                                            <input type="hidden" name="stc_add_product_hit">
                                        <div class="card-border mb-3 card card-body border-primary">
                                            <h5
                                              for="status"
                                              >Percentage</h5
                                            ><br>
                                            <input
                                              id="name"
                                              name="stcpdpercentage"
                                              type="number"
                                              placeholder="Product Percentage"
                                              class="form-control validate"
                                              required
                                            />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-border mb-3 card card-body border-primary" style="height: 243px;">
                                            <i
                                              class="fas fa-cloud-upload-alt tm-upload-icon"
                                              onclick="document.getElementById('fileInput').click();"
                                              style="font-size: 50px;margin-left: 180px;margin-top: 60px;"
                                            ></i>
                                        </div>
                                        <div class="card-border mb-3 card card-body border-primary">
                                            <input id="fileInput" type="file" name="stcpdimage" style="display:none;" />
                                            <input
                                              type="button"
                                              class="btn btn-primary btn-block mx-auto"
                                              value="UPLOAD PRODUCT IMAGE"
                                              onclick="document.getElementById('fileInput').click();"
                                            />
                                        </div>
                                        <div class="card-border mb-3 card card-body border-primary">
                                            <h5
                                              for="subcategory"
                                              >Sub Category</h5
                                            >
                                            <select
                                              class="custom-select tm-select-accounts call_sub_cat"
                                              name="stcpdsubcategory"
                                            >
                                            </select>
                                        </div>
                                        <div class="card-border mb-3 card card-body border-primary">
                                            <h5
                                              for="unit"
                                              >Unit</h5
                                            >
                                            <select
                                              class="custom-select tm-select-accounts"
                                              name="stcpdunit"
                                            >
                                              <option value="NA" selected>Select Unit</option>
                                              <option value="Nos">Nos</option>
                                              <option value="Set">Set</option>
                                              <option value="Feet">Feet</option>
                                              <option value="Mtr">Mtr</option>
                                              <option value="Sqmt">Sqmt</option>
                                              <option value="Ltr">Ltr</option>
                                              <option value="Bag">Bag</option>
                                              <option value="Roll">Roll</option>
                                              <option value="Lot">Lot</option>
                                              <option value="Kgs">Kgs</option>
                                              <option value="Pkt">Pkt</option>
                                              <option value="Case">Case</option>
                                              <option value="Bundle">Bundle</option>
                                              <option value="Pair">Pair</option>
                                              
                                            </select>
                                        </div>
                                        <div class="card-border mb-3 card card-body border-primary">
                                            <h5
                                              for="status"
                                              >GST</h5
                                            >
                                            <select
                                              class="custom-select tm-select-accounts"
                                              name="stcpdgst"
                                            >
                                              <option selected>Select GST</option>
                                              <option value="5">5%</option>
                                              <option value="12">12%</option>
                                              <option value="18">18%</option>
                                              <option value="28">28%</option>
                                            </select>
                                        </div>
                                        <div class="card-border mb-3 card card-body border-primary">
                                            <h5
                                              for="make"
                                              >Make </h5
                                            >
                                            <select
                                              class="custom-select tm-select-accounts call_brand"
                                              name="stcpdbrand"
                                            >
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                          <button type="submit" class="btn btn-primary btn-block text-uppercase">Add Product Now</button>
                                        </div>
                                        <div class="col-12">
                                            <div class="progress">
                                                <div class="progress-bar"></div>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade show active" id="tab-content-2" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >View Products
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <form action="" class="stc-view-product-form">
                                            <table class="table table-hover ">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">By Category</th>
                                                        <th scope="col">By Name</th>
                                                        <th scope="col">By Sub Category</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="card-border mb-3 card card-body border-success">
                                                              <select
                                                                class="custom-select tm-select-accounts call_cat stcprosearchsame"
                                                                id="filterbycat"
                                                                name="stcpdcategory"
                                                              >
                                                              </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="card-border mb-3 card card-body border-success">
                                                              <input
                                                                id="searchbystcname"
                                                                name="stcsearchpdname"
                                                                type="text"
                                                                placeholder="Product Name"
                                                                class="form-control validate stcprosearchsame"
                                                                required
                                                              />
                                                              <input type="hidden" name="search_alo_in">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="card-border mb-3 card card-body border-success">
                                                                <select
                                                                  class="custom-select tm-select-accounts call_sub_cat stcprosearchsame"
                                                                  id="filterbysubcat"
                                                                  name="stcpdsubcategory"
                                                                >
                                                                </select>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                                <div class="row stc-call-view-product-row">
                                </div>
                                <div 
                                  class="modal fade" id="stc-edit-product-modal" 
                                  tabindex="-1" 
                                  role="dialog" 
                                  aria-labelledby="myLargeModalLabel" 
                                  style="display: none;" 
                                  aria-modal="true">
                                  <div class="modal-dialog modal-lg">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLongTitle">Edit Product</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">×</span>
                                              </button>
                                          </div>
                                          <div class="modal-body">
                                              
                                          </div>
                                          <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                              <button type="button" class="btn btn-primary">Save changes</button>
                                          </div>
                                      </div>
                                  </div>
                                </div>
                            </div> 
                            <div class="tab-pane tabs-animation fade" id="tab-content-3" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >View Single Product Purchased Record
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row stc-view-purhcase-product-row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <form action="" class="stc-view-purhcase-product-form">
                                            <table class="table table-hover ">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" colspan="2">By Product ID</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input
                                                              id="searchbystcpdid"
                                                              name="searchbystcpdid"
                                                              type="number"
                                                              placeholder="Product ID"
                                                              class="form-control validate"
                                                              required
                                                            />
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-success btn-block text-uppercase product-id-search-hit">
                                                              Search <i class="fa fa-search"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>

                                <div class="row stc-call-view-product-withid-row">                                  
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="tab-content-4" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >View Single Product Sale Record
                                            </h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="row stc-view-purhcase-product-row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <form action="" class="stc-view-purhcase-product-form">
                                            <table class="table table-hover ">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" colspan="2">By Product ID</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input
                                                              id="searchbystcsopdid"
                                                              name="searchbystcsopdid"
                                                              type="number"
                                                              placeholder="Product ID"
                                                              class="form-control validate"
                                                              required
                                                            />
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-success btn-block text-uppercase product-id-sosearch-hit">
                                                                Search <i class="fa fa-search"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>

                                <div class="row stc-call-view-product-sale-withid-row">                                  
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
          // js add product with some support
          $('.stc-add-product-form').on('submit',function(e){
            e.preventDefault();
            $.ajax({
              xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = ((evt.loaded / evt.total) * 100);
                                $(".progress-bar").width(percentComplete + '%');
                                $(".progress-bar").html(percentComplete+'%');
                            }
                        }, false);
                        return xhr;
                    },
              url : "kattegat/ragnar_product.php",
              method : "post",
              data : new FormData(this),
              contentType : false,
              cache: false,
              processData : false,
              success : function(data){
                data=data.trim();
                alert(data);
                if(data=="Product's added!!"){
                  $('.stc-add-product-form')[0].reset();
                }
              }
            });
          });

          var jsfiltercat;
          var jsfiltersubcat;
          var jsfiltername;
          // filter function
          // filter by cat
          $("#filterbycat").change(function(e){
            e.preventDefault();
            $('.stc-call-view-product-row').html("Loading...");
            jsfiltercat = $(this).val();
            jsfiltersubcat = $('#filterbysubcat').val();
            jsfiltername = $('#searchbystcname').val();
            stc_filter_pro(jsfiltercat, jsfiltersubcat ,jsfiltername);
          });

          // filter by sub cat
          $("#filterbysubcat").change(function(e){
            e.preventDefault();
            $('.stc-call-view-product-row').html("Loading...");
            jsfiltercat = $('#filterbycat').val();
            jsfiltersubcat = $(this).val();
            jsfiltername = $('#searchbystcname').val();
            stc_filter_pro(jsfiltercat, jsfiltersubcat ,jsfiltername);
          });

          // filter by name
          $("#searchbystcname").on('keyup', function(e){
            e.preventDefault();
            jsfiltercat = $('#filterbycat').val();
            jsfiltersubcat = $('#filterbysubcat').val();
            jsfiltername = $(this).val();
            var responset='<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12"><a href="#" class="btn btn-primary btn-block text-uppercase mb-3">Atleast type word in text field or search via category or sub category!!!</a></div>';
            var countlen=jsfiltername.length;
            if((+jsfiltername!='') && countlen>=3){
              stc_filter_pro(jsfiltercat, jsfiltersubcat ,jsfiltername);
            }else{
              $('.stc-call-view-product-row').html(responset);
            }
          });

          // filter function
          function stc_filter_pro(jsfiltercat, jsfiltersubcat ,jsfiltername){
            $.ajax({
              url     : "kattegat/ragnar_product.php",
              method  : "post",
              data    : {
                stcaction:1,
                phpfiltercatout:jsfiltercat,
                phpfiltersubcatout:jsfiltersubcat,
                phpfilternameout:jsfiltername
              },
              // dataType : 'JSON',
              success : function(data){
                // console.log(data);
                $('.stc-call-view-product-row').html(data);
              }
            });
          }

          // search by item id from purchase
          $('.product-id-search-hit').on('click', function(e){
            e.preventDefault();
              jsfilterprobyid = $('#searchbystcpdid').val();
              $.ajax({
                url : "kattegat/ragnar_product.php",
                method : "post",
                data : {
                  jsfilterprobypoidout:1,
                  jsfilterprobyid:jsfilterprobyid
                },
                dataType : 'JSON',
                success : function(data){
                  // console.log(data);
                  $('.stc-call-view-product-withid-row').html(data);
                }
              });
          });

          // search by item id from sale
          $('.product-id-sosearch-hit').on('click', function(e){
            e.preventDefault();
              jsfilterprobyid = $('#searchbystcsopdid').val();
              $.ajax({
                url : "kattegat/ragnar_product.php",
                method : "post",
                data : {
                  jsfilterprobysoidout:1,
                  jsfilterprobyid:jsfilterprobyid
                },
                dataType : 'JSON',
                success : function(data){
                  // console.log(data);
                  $('.stc-call-view-product-sale-withid-row').html(data);
                }
              });
          });
          // $('.view-purchase-mod-btn').on('click', function(e){
          $('body').delegate('.view-purchase-mod-btn', 'click', function(e){
              var productbyid = $(this).attr('id');
              $.ajax({
                url : "kattegat/ragnar_product.php",
                method : "post",
                data : {
                  jsfilterprobypoidout:1,
                  jsfilterprobyid:productbyid
                },
                dataType : 'JSON',
                success : function(data){
                  // console.log(data);
                  $('.purchase-view-modal-div').html(data);
                }
              });
          });
          // $('.view-sale-mod-btn').on('click', function(e){
          $('body').delegate('.view-sale-mod-btn', 'click', function(e){
              var productbyid = $(this).attr('id');
              $.ajax({
                url : "kattegat/ragnar_product.php",
                method : "post",
                data : {
                  jsfilterprobysoidout:1,
                  jsfilterprobyid:productbyid
                },
                dataType : 'JSON',
                success : function(data){
                  // console.log(data);
                  $('.sale-view-modal-div').html(data);
                }
              });
          });
          
        });
    </script>
</body>
</html>
<!-- modals -->
<div class="modal fade view-products-purchase-modal" id="view-products-purchase-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">View Purchase</h4>
        <button type="button" class="close" data-dismiss="modal" aria-h5="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">View Purchase</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form class="pms-adjustment-form">
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                  <div class="card-border mb-3 card card-body border-success purchase-view-modal-div">
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- modals -->
<div class="modal fade view-products-sale-modal" id="view-products-sale-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">View Sale</h4>
        <button type="button" class="close" data-dismiss="modal" aria-h5="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">View Sale</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form class="pms-adjustment-form">
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                  <div class="card-border mb-3 card card-body border-success sale-view-modal-div">
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->