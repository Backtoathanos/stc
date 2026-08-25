<?php  
// Include authentication helper
require_once 'kattegat/auth_helper.php';

// Check authentication using the new hybrid system
STCAuthHelper::checkAuth();$page_code=301;
include("kattegat/role_check.php");
$stc_embed = isset($_GET['embed']) && (string) $_GET['embed'] === '1';
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
    <link rel="stylesheet" href="assets/vendor/cropperjs/cropper.min.css">
    <style>
        .stc-pd-upload-card {
            min-height: 243px;
            padding: 16px;
        }
        .stc-pd-dropzone {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 211px;
            padding: 18px 16px;
            border: 2px dashed #3f6ad8;
            border-radius: 8px;
            background: #f7f9fe;
            cursor: pointer;
            text-align: center;
            transition: border-color .2s ease, background-color .2s ease;
        }
        .stc-pd-dropzone:hover,
        .stc-pd-dropzone.is-dragover {
            background: #eef3fd;
            border-color: #2952c8;
        }
        .stc-pd-dropzone-idle {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
        }
        .stc-pd-dropzone-idle .stc-pd-upload-icon {
            font-size: 42px;
            color: #3f6ad8;
            margin-bottom: 10px;
        }
        .stc-pd-dropzone-title {
            margin: 0 0 4px;
            font-size: 15px;
            font-weight: 600;
            color: #3f6ad8;
        }
        .stc-pd-dropzone-hint {
            margin: 0;
            font-size: 13px;
            color: #6c757d;
        }
        .stc-pd-dropzone-types {
            margin: 10px 0 0;
            font-size: 12px;
            color: #98a2b3;
        }
        .stc-pd-dropzone-preview {
            display: none;
            width: 100%;
            align-items: center;
            text-align: left;
        }
        .stc-pd-dropzone.has-file .stc-pd-dropzone-idle {
            display: none;
        }
        .stc-pd-dropzone.has-file .stc-pd-dropzone-preview {
            display: flex;
        }
        .stc-pd-dropzone.has-file {
            cursor: default;
        }
        .stc-pd-preview-actions {
            display: none;
            margin-top: 12px;
        }
        .stc-pd-upload-card.has-file .stc-pd-preview-actions {
            display: block;
        }
        .stc-pd-preview-thumb {
            width: 92px;
            height: 92px;
            flex: 0 0 92px;
            border-radius: 6px;
            overflow: hidden;
            background: #fff;
            border: 1px solid #d9e2f5;
            margin-right: 14px;
        }
        .stc-pd-preview-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .stc-pd-preview-meta {
            min-width: 0;
            flex: 1;
        }
        .stc-pd-preview-name {
            display: block;
            font-weight: 600;
            color: #3d4a5c;
            word-break: break-all;
            margin-bottom: 4px;
        }
        .stc-pd-preview-size {
            display: block;
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 10px;
        }
        .stc-pd-preview-actions .btn {
            margin-right: 6px;
            margin-bottom: 4px;
        }
        .stc-pd-cropper-wrap {
            position: relative;
            width: 100%;
            height: 420px;
            background: #2a3042;
            overflow: hidden;
        }
        .stc-pd-cropper-wrap img {
            display: block;
            max-width: 100%;
            max-height: 420px;
        }
        .stc-pd-cropper-wrap .cropper-container {
            width: 100% !important;
            height: 420px !important;
        }
        .stc-pd-crop-toolbar {
            margin-top: 12px;
        }
        .stc-pd-crop-toolbar .btn-group {
            margin: 0 6px 8px 0;
        }
        .stc-pd-crop-toolbar .btn.active {
            background-color: #3f6ad8;
            border-color: #3f6ad8;
            color: #fff;
        }
        #stcPdCropModal {
            z-index: 2000;
        }
        #stcPdCropModal .modal-header,
        #stcPdCropModal .modal-footer {
            position: relative;
            z-index: 6;
        }
        #stcPdCropModal .modal-body {
            position: relative;
            z-index: 1;
            overflow: hidden;
        }
        #stcPdCropClose {
            padding: 0 8px;
            font-size: 28px;
            line-height: 1;
            font-weight: 700;
            opacity: .7;
            cursor: pointer;
        }
        .stc-ap-wrap {
            background: #fff;
            border: 1px solid #e4e9f2;
            border-radius: 10px;
            overflow: hidden;
        }
        .stc-ap-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 22px;
            border-bottom: 1px solid #eef1f6;
            background: #f8faff;
        }
        .stc-ap-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #2b3553;
        }
        .stc-ap-header p {
            margin: 3px 0 0;
            font-size: 13px;
            color: #7a8599;
        }
        .stc-ap-body {
            padding: 20px 22px 8px;
        }
        .stc-ap-section {
            margin-bottom: 22px;
        }
        .stc-ap-section-title {
            display: flex;
            align-items: center;
            margin: 0 0 14px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .4px;
            text-transform: uppercase;
            color: #3f6ad8;
        }
        .stc-ap-section-title:before {
            content: "";
            width: 4px;
            height: 14px;
            margin-right: 8px;
            border-radius: 2px;
            background: #3f6ad8;
        }
        .stc-ap-field {
            position: relative;
            margin-bottom: 16px;
        }
        .stc-add-product-form label,
        .stc-ap-label {
            position: relative !important;
            left: auto !important;
            top: auto !important;
            transform: none !important;
            display: block;
            margin: 0 0 6px;
            font-size: 13px;
            font-weight: 600;
            color: #495057;
            line-height: 1.3;
        }
        .stc-add-product-form input,
        .stc-add-product-form select,
        .stc-add-product-form textarea {
            appearance: auto !important;
            -webkit-appearance: auto !important;
        }
        .stc-ap-field .form-control,
        .stc-ap-field .custom-select {
            display: block;
            width: 100%;
            height: 42px;
            padding: 8px 12px;
            border: 1px solid #d7deea;
            border-radius: 6px;
            background: #fff;
            line-height: 1.4;
        }
        .stc-ap-field textarea.form-control {
            height: auto;
            min-height: 92px;
            padding-top: 10px;
            resize: vertical;
        }
        .stc-req {
            color: #e63757;
            font-weight: 700;
            margin-left: 3px;
        }
        .stc-ap-field.is-invalid .form-control,
        .stc-ap-field.is-invalid .custom-select {
            border-color: #e63757;
            box-shadow: 0 0 0 3px rgba(230, 55, 87, .12);
        }
        .stc-ap-field .form-control:focus,
        .stc-ap-field .custom-select:focus {
            border-color: #3f6ad8;
            box-shadow: 0 0 0 3px rgba(63, 106, 216, .12);
        }
        .swal2-container {
            z-index: 30000 !important;
        }
        .stc-ap-upload-card {
            margin-bottom: 0;
            padding: 0;
            min-height: 0;
            background: transparent;
            border: 0;
        }
        .stc-ap-wrap .stc-pd-dropzone {
            min-height: 188px;
        }
        .stc-ap-footer {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 22px;
            border-top: 1px solid #eef1f6;
            background: #fbfcfe;
        }
        .stc-ap-footer .progress {
            flex: 1;
            height: 10px;
            margin: 0;
            background: #e9eef6;
        }
        .stc-ap-footer .btn {
            min-width: 180px;
            padding: 10px 18px;
            font-weight: 600;
            letter-spacing: .3px;
        }
        @media (max-width: 767px) {
            .stc-ap-footer {
                flex-direction: column;
                align-items: stretch;
            }
            .stc-ap-footer .btn {
                min-width: 0;
            }
        }
        body.stc-embed {
            background: #fff;
        }
        body.stc-embed .stc-embed-app,
        body.stc-embed .stc-embed-app .app-main,
        body.stc-embed .stc-embed-app .app-main__outer {
            padding: 0 !important;
            margin: 0 !important;
        }
        body.stc-embed .stc-embed-app .app-main__outer {
            padding-left: 0 !important;
            padding-top: 0 !important;
        }
        body.stc-embed .stc-embed-app .app-main__inner {
            padding: 10px 12px 16px !important;
        }
    </style>
</head>
<body<?php echo !empty($stc_embed) ? ' class="stc-embed"' : ''; ?>>
    <div class="app-container app-theme-white body-tabs-shadow<?php echo !empty($stc_embed) ? ' stc-embed-app' : ' fixed-sidebar fixed-header'; ?>">
        <?php if (empty($stc_embed)) { include_once("header-nav.php"); } ?>
        <div class="app-main">
            <?php if (empty($stc_embed)) { include_once("sidebar-nav.php"); } ?>
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
                                <form action="" class="stc-add-product-form" novalidate>
                                    <input type="hidden" name="stc_add_product_hit">
                                    <div class="stc-ap-wrap">
                                        <div class="stc-ap-header">
                                            <div>
                                                <h4>Add Product</h4>
                                                <p>Fill in the details, image, tax and pricing to add an item to the product master.</p>
                                            </div>
                                        </div>
                                        <div class="stc-ap-body">
                                            <div class="row">
                                                <div class="col-lg-7">
                                                    <div class="stc-ap-section">
                                                        <h6 class="stc-ap-section-title">Basic details</h6>
                                                        <div class="stc-ap-field">
                                                            <span class="stc-ap-label">Product Name <span class="stc-req">*</span></span>
                                                            <textarea class="form-control validate" rows="3" id="stcpdname" name="stcpdname" placeholder="Enter product name" required></textarea>
                                                        </div>
                                                        <div class="stc-ap-field">
                                                            <span class="stc-ap-label">Description <span class="stc-req">*</span></span>
                                                            <textarea class="form-control validate" rows="3" id="stcpddesc" name="stcpddesc" placeholder="Enter product description" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="stc-ap-section">
                                                        <h6 class="stc-ap-section-title">Classification</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="stc-ap-field">
                                                                    <span class="stc-ap-label">Category <span class="stc-req">*</span></span>
                                                                    <select class="custom-select tm-select-accounts call_cat" name="stcpdcategory"></select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="stc-ap-field">
                                                                    <span class="stc-ap-label">Sub Category <span class="stc-req">*</span></span>
                                                                    <select class="custom-select tm-select-accounts call_sub_cat" name="stcpdsubcategory"></select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="stc-ap-field">
                                                                    <span class="stc-ap-label">Product Type <span class="stc-req">*</span></span>
                                                                    <select class="custom-select tm-select-accounts" name="stcpdtype" required>
                                                                        <option value="NA" selected>Select Product Type</option>
                                                                        <option value="Mechanical">Mechanical</option>
                                                                        <option value="Electrical">Electrical</option>
                                                                        <option value="Civil">Civil</option>
                                                                        <option value="Instrumentation">Instrumentation</option>
                                                                        <option value="Plumbing">Plumbing</option>
                                                                        <option value="HVAC">HVAC</option>
                                                                        <option value="Safety">Safety</option>
                                                                        <option value="Electronics">Electronics</option>
                                                                        <option value="Hardware">Hardware</option>
                                                                        <option value="Consumable">Consumable</option>
                                                                        <option value="Tools">Tools</option>
                                                                        <option value="Others">Others</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="stc-ap-field">
                                                                    <span class="stc-ap-label">Make</span>
                                                                    <select class="custom-select tm-select-accounts call_brand" name="stcpdbrand"></select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="stc-ap-field">
                                                                    <span class="stc-ap-label">Unit <span class="stc-req">*</span></span>
                                                                    <select class="custom-select tm-select-accounts" name="stcpdunit">
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5">
                                                    <div class="stc-ap-section">
                                                        <h6 class="stc-ap-section-title">Product image</h6>
                                                        <div class="stc-pd-upload-card stc-ap-upload-card">
                                                            <input id="fileInput" type="file" name="stcpdimage" accept="image/*" style="display:none;" />
                                                            <div class="stc-pd-dropzone" id="stcPdDropzone" role="button" tabindex="0" aria-label="Upload product image">
                                                                <div class="stc-pd-dropzone-idle">
                                                                    <i class="fas fa-cloud-upload-alt stc-pd-upload-icon"></i>
                                                                    <p class="stc-pd-dropzone-title">Drag & drop product image</p>
                                                                    <p class="stc-pd-dropzone-hint">or click to browse from your computer</p>
                                                                    <p class="stc-pd-dropzone-types">JPG, PNG, GIF or WEBP</p>
                                                                </div>
                                                                <div class="stc-pd-dropzone-preview">
                                                                    <div class="stc-pd-preview-thumb">
                                                                        <img id="stcPdPreviewImg" alt="Product image preview">
                                                                    </div>
                                                                    <div class="stc-pd-preview-meta">
                                                                        <span class="stc-pd-preview-name" id="stcPdPreviewName"></span>
                                                                        <span class="stc-pd-preview-size" id="stcPdPreviewSize"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="stc-pd-preview-actions">
                                                                <button type="button" class="btn btn-success btn-sm" id="stcPdCropBtn"><i class="fas fa-crop-alt"></i> Crop</button>
                                                                <button type="button" class="btn btn-primary btn-sm" id="stcPdChangeBtn">Change</button>
                                                                <button type="button" class="btn btn-outline-danger btn-sm" id="stcPdRemoveBtn">Remove</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="stc-ap-section">
                                                        <h6 class="stc-ap-section-title">Tax & pricing</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="stc-ap-field">
                                                                    <span class="stc-ap-label">HSN Code <span class="stc-req">*</span></span>
                                                                    <input name="stcpdhsncode" type="number" placeholder="HSN Code" class="form-control validate" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="stc-ap-field">
                                                                    <span class="stc-ap-label">GST <span class="stc-req">*</span></span>
                                                                    <select class="custom-select tm-select-accounts" name="stcpdgst">
                                                                        <option value="0" selected>Select GST</option>
                                                                        <option value="5">5%</option>
                                                                        <option value="12">12%</option>
                                                                        <option value="18">18%</option>
                                                                        <option value="28">28%</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="stc-ap-field">
                                                                    <span class="stc-ap-label">Percentage <span class="stc-req">*</span></span>
                                                                    <input name="stcpdpercentage" type="number" placeholder="Sale percentage" class="form-control validate" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="stc-ap-field">
                                                                    <span class="stc-ap-label">Initial Rate <span class="stc-req">*</span></span>
                                                                    <input name="stcpdinitrate" type="number" min="0" step="0.01" placeholder="0.00" class="form-control validate" required />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="stc-ap-footer">
                                            <div class="progress">
                                                <div class="progress-bar"></div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Add Product Now</button>
                                        </div>
                                    </div>
                                </form>
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
                                                    <tr>
                                                        <td colspan="3">
                                                            <button type="button" class="btn btn-primary btn-block text-uppercase" id="stc-addproduct-view-search-btn">
                                                              Search <i class="fa fa-search"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                                <div class="row stc-call-view-product-row">
                                </div>
                                
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12 text-center stc-addproduct-view-pagination" style="margin-bottom:12px;"></div>
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
    <div class="modal fade" id="stcPdCropModal" tabindex="-1" role="dialog" aria-labelledby="stcPdCropModalTitle" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="stcPdCropModalTitle">Crop Product Image</h5>
            <button type="button" class="close" id="stcPdCropClose" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="stc-pd-cropper-wrap" id="stcPdCropperWrap">
              <img id="stcPdCropperImg" alt="Crop product image">
            </div>
            <div class="stc-pd-crop-toolbar">
              <div class="btn-group" role="group" aria-label="Zoom">
                <button type="button" class="btn btn-light" id="stcPdCropZoomIn" title="Zoom in"><i class="fas fa-search-plus"></i></button>
                <button type="button" class="btn btn-light" id="stcPdCropZoomOut" title="Zoom out"><i class="fas fa-search-minus"></i></button>
              </div>
              <div class="btn-group" role="group" aria-label="Rotate">
                <button type="button" class="btn btn-light" id="stcPdCropRotateLeft" title="Rotate left"><i class="fas fa-undo"></i></button>
                <button type="button" class="btn btn-light" id="stcPdCropRotateRight" title="Rotate right"><i class="fas fa-redo"></i></button>
              </div>
              <div class="btn-group" role="group" aria-label="Flip">
                <button type="button" class="btn btn-light" id="stcPdCropFlipH" title="Flip horizontal"><i class="fas fa-arrows-alt-h"></i></button>
                <button type="button" class="btn btn-light" id="stcPdCropFlipV" title="Flip vertical"><i class="fas fa-arrows-alt-v"></i></button>
              </div>
              <div class="btn-group" role="group" aria-label="Aspect ratio">
                <button type="button" class="btn btn-light stc-pd-aspect-btn active" data-aspect="NaN">Free</button>
                <button type="button" class="btn btn-light stc-pd-aspect-btn" data-aspect="1">1:1</button>
                <button type="button" class="btn btn-light stc-pd-aspect-btn" data-aspect="1.333333">4:3</button>
                <button type="button" class="btn btn-light stc-pd-aspect-btn" data-aspect="1.777778">16:9</button>
                <button type="button" class="btn btn-light stc-pd-aspect-btn" data-aspect="0.75">3:4</button>
              </div>
              <div class="btn-group" role="group" aria-label="Reset">
                <button type="button" class="btn btn-light" id="stcPdCropReset" title="Reset"><i class="fas fa-sync-alt"></i> Reset</button>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="stcPdCropCancel">Cancel</button>
            <button type="button" class="btn btn-success" id="stcPdCropApply"><i class="fas fa-check"></i> Apply Crop</button>
          </div>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
    <script type="text/javascript" src="./assets/scripts/sidebar-persist.js"></script>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
    <script src="assets/vendor/cropperjs/cropper.min.js"></script>
    <script>
        $(document).ready(function(){
          function stcPdAlert(icon, title, text, html) {
            if (typeof Swal !== 'undefined' && Swal.fire) {
              var opts = { icon: icon || 'info', title: title || '', confirmButtonText: 'OK' };
              if (html) opts.html = html;
              else opts.text = text || '';
              return Swal.fire(opts);
            }
            alert((title ? title + '\n' : '') + (text || '').replace(/<[^>]+>/g, ''));
          }
          function validateProductForm($form) {
            $form.find('.stc-ap-field').removeClass('is-invalid');
            var checks = [
              { name: 'stcpdname', label: 'Product Name', empty: [''] },
              { name: 'stcpddesc', label: 'Description', empty: [''] },
              { name: 'stcpdcategory', label: 'Category', empty: ['', 'NA'] },
              { name: 'stcpdsubcategory', label: 'Sub Category', empty: ['', 'NA'] },
              { name: 'stcpdtype', label: 'Product Type', empty: ['', 'NA'] },
              { name: 'stcpdunit', label: 'Unit', empty: ['', 'NA'] },
              { name: 'stcpdhsncode', label: 'HSN Code', empty: [''] },
              { name: 'stcpdgst', label: 'GST', empty: ['', '0'] },
              { name: 'stcpdpercentage', label: 'Percentage', empty: [''] },
              { name: 'stcpdinitrate', label: 'Initial Rate', empty: [''] }
            ];
            var missing = [];
            $.each(checks, function(_, c) {
              var $el = $form.find('[name="' + c.name + '"]');
              var val = $.trim(String($el.val() == null ? '' : $el.val()));
              if (c.empty.indexOf(val) !== -1) {
                missing.push(c.label);
                $el.closest('.stc-ap-field').addClass('is-invalid');
              }
            });
            if (missing.length) {
              var $first = $form.find('.stc-ap-field.is-invalid').first();
              if ($first.length && $first.offset()) {
                $('html, body').animate({ scrollTop: $first.offset().top - 120 }, 300);
                $first.find('input, select, textarea').first().focus();
              }
              stcPdAlert('warning', 'Required fields missing', '',
                '<p>Please fill the fields marked with <span style="color:#e63757">*</span>.</p>' +
                '<ul style="text-align:left;margin:8px 0 0 18px;">' +
                missing.map(function(m){ return '<li>' + m + '</li>'; }).join('') +
                '</ul>'
              );
              return false;
            }
            return true;
          }
          $('.stc-add-product-form').on('input change', 'input, select, textarea', function() {
            $(this).closest('.stc-ap-field').removeClass('is-invalid');
          });

          var $fileInput = $('#fileInput');
          var $dropzone = $('#stcPdDropzone');
          var $uploadCard = $('.stc-pd-upload-card');
          var previewUrl = '';
          var originalImageFile = null;
          var productCropper = null;
          var cropFlipH = 1;
          var cropFlipV = 1;
          var pendingCropFile = null;
          var cropObjectUrl = '';

          function formatFileSize(bytes) {
            if (!bytes && bytes !== 0) return '';
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
          }

          function getCropperLib() {
            if (typeof Cropper === 'function') return Cropper;
            if (window.Cropper && typeof window.Cropper === 'function') return window.Cropper;
            if (window.Cropper && typeof window.Cropper.default === 'function') return window.Cropper.default;
            return null;
          }

          function destroyProductCropper(revokeUrl) {
            if (productCropper) {
              try { productCropper.destroy(); } catch (err) {}
              productCropper = null;
            }
            cropFlipH = 1;
            cropFlipV = 1;
            if (revokeUrl !== false && cropObjectUrl) {
              URL.revokeObjectURL(cropObjectUrl);
              cropObjectUrl = '';
            }
          }

          function clearProductImagePreview() {
            if (previewUrl) {
              URL.revokeObjectURL(previewUrl);
              previewUrl = '';
            }
            originalImageFile = null;
            pendingCropFile = null;
            destroyProductCropper();
            $dropzone.removeClass('has-file is-dragover');
            $uploadCard.removeClass('has-file');
            $('#stcPdPreviewImg').attr('src', '');
            $('#stcPdPreviewName').text('');
            $('#stcPdPreviewSize').text('');
          }

          function showProductImagePreview(file) {
            if (!file) {
              clearProductImagePreview();
              return;
            }
            if (previewUrl) {
              URL.revokeObjectURL(previewUrl);
            }
            previewUrl = URL.createObjectURL(file);
            $('#stcPdPreviewImg').attr('src', previewUrl);
            $('#stcPdPreviewName').text(file.name);
            $('#stcPdPreviewSize').text(formatFileSize(file.size));
            $dropzone.addClass('has-file').removeClass('is-dragover');
            $uploadCard.addClass('has-file');
          }

          function putFileInInput(file) {
            var dt = new DataTransfer();
            dt.items.add(file);
            $fileInput[0].files = dt.files;
          }

          function assignProductImage(file, asOriginal) {
            if (!file) return false;
            if (file.type && file.type.indexOf('image/') !== 0) {
              alert('Please select an image file (JPG, PNG, GIF or WEBP).');
              return false;
            }
            try {
              putFileInInput(file);
              if (asOriginal) {
                originalImageFile = file;
              }
              showProductImagePreview(file);
              return true;
            } catch (err) {
              alert('This browser cannot update the selected file automatically. Please click to browse or use Change.');
              return false;
            }
          }

          function openProductImagePicker() {
            $fileInput.trigger('click');
          }

          function croppedFileName(sourceFile, mime) {
            var base = (sourceFile && sourceFile.name) ? sourceFile.name.replace(/\.[^.]+$/, '') : 'product-image';
            var ext = mime === 'image/png' ? 'png' : 'jpg';
            return base + '-cropped.' + ext;
          }

          function showCropModal() {
            var $modal = $('#stcPdCropModal');
            if ($.fn.modal) {
              $modal.modal('show');
            } else {
              $modal.addClass('show').css('display', 'block').attr('aria-hidden', 'false');
              $('body').addClass('modal-open');
              $modal.trigger('shown.bs.modal');
            }
          }

          function hideCropModal() {
            destroyProductCropper();
            var $modal = $('#stcPdCropModal');
            if ($.fn.modal) {
              $modal.modal('hide');
            }
            $modal.removeClass('show').css('display', 'none').attr('aria-hidden', 'true');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('padding-right', '');
            var wrap = document.getElementById('stcPdCropperWrap');
            if (wrap) {
              wrap.innerHTML = '<img id="stcPdCropperImg" alt="Crop product image">';
            }
          }

          function startCropperOnImage(image) {
            var CropperLib = getCropperLib();
            if (!CropperLib) {
              alert('Cropping tool could not load. Please refresh and try again.');
              return;
            }
            destroyProductCropper(false);
            productCropper = new CropperLib(image, {
              viewMode: 1,
              dragMode: 'move',
              autoCropArea: 0.85,
              background: true,
              responsive: true,
              restore: false,
              checkOrientation: false,
              guides: true,
              center: true,
              highlight: false,
              cropBoxMovable: true,
              cropBoxResizable: true
            });
          }

          $dropzone.on('click', function() {
            if ($dropzone.hasClass('has-file')) {
              return;
            }
            openProductImagePicker();
          });
          $dropzone.on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              if (!$dropzone.hasClass('has-file')) {
                openProductImagePicker();
              }
            }
          });
          $('#stcPdChangeBtn').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            openProductImagePicker();
          });
          $('#stcPdRemoveBtn').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $fileInput.val('');
            clearProductImagePreview();
          });
          $fileInput.on('change', function() {
            var file = this.files && this.files[0];
            if (file) {
              originalImageFile = file;
              showProductImagePreview(file);
            } else {
              clearProductImagePreview();
            }
          });
          $dropzone.on('dragover dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $dropzone.addClass('is-dragover');
          });
          $dropzone.on('dragleave dragend', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $dropzone.removeClass('is-dragover');
          });
          $dropzone.on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $dropzone.removeClass('is-dragover');
            var files = e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files;
            if (files && files[0]) {
              assignProductImage(files[0], true);
            }
          });

          $('#stcPdCropBtn').on('click', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var file = originalImageFile || ($fileInput[0].files && $fileInput[0].files[0]);
            if (!file) {
              alert('Please select an image first.');
              return;
            }
            if (!getCropperLib()) {
              alert('Cropping tool could not load. Please refresh and try again.');
              return;
            }
            pendingCropFile = file;
            showCropModal();
          });

          $(document).on('mousedown click', '#stcPdCropClose, #stcPdCropCancel', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            hideCropModal();
          });
          $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $('#stcPdCropModal').is(':visible')) {
              hideCropModal();
            }
          });

          $('#stcPdCropModal').on('shown.bs.modal', function() {
            var file = pendingCropFile || originalImageFile || ($fileInput[0].files && $fileInput[0].files[0]);
            if (!file) return;
            destroyProductCropper();
            var wrap = document.getElementById('stcPdCropperWrap');
            wrap.innerHTML = '<img id="stcPdCropperImg" alt="Crop product image">';
            var image = document.getElementById('stcPdCropperImg');
            cropObjectUrl = URL.createObjectURL(file);
            image.onload = function() {
              setTimeout(function() {
                if (!document.body.contains(image)) return;
                startCropperOnImage(image);
              }, 50);
            };
            image.onerror = function() {
              alert('Unable to open this image for cropping. Please try another file.');
            };
            image.src = cropObjectUrl;
            $('.stc-pd-aspect-btn').removeClass('active').filter('[data-aspect="NaN"]').addClass('active');
          });

          $('#stcPdCropModal').on('hidden.bs.modal', function() {
            destroyProductCropper();
            var wrap = document.getElementById('stcPdCropperWrap');
            wrap.innerHTML = '<img id="stcPdCropperImg" alt="Crop product image">';
          });

          $('#stcPdCropZoomIn').on('click', function() {
            if (productCropper) productCropper.zoom(0.1);
          });
          $('#stcPdCropZoomOut').on('click', function() {
            if (productCropper) productCropper.zoom(-0.1);
          });
          $('#stcPdCropRotateLeft').on('click', function() {
            if (productCropper) productCropper.rotate(-90);
          });
          $('#stcPdCropRotateRight').on('click', function() {
            if (productCropper) productCropper.rotate(90);
          });
          $('#stcPdCropFlipH').on('click', function() {
            if (!productCropper) return;
            cropFlipH = cropFlipH === 1 ? -1 : 1;
            productCropper.scaleX(cropFlipH);
          });
          $('#stcPdCropFlipV').on('click', function() {
            if (!productCropper) return;
            cropFlipV = cropFlipV === 1 ? -1 : 1;
            productCropper.scaleY(cropFlipV);
          });
          $('#stcPdCropReset').on('click', function() {
            if (!productCropper) return;
            cropFlipH = 1;
            cropFlipV = 1;
            productCropper.reset();
            productCropper.setAspectRatio(NaN);
            $('.stc-pd-aspect-btn').removeClass('active').filter('[data-aspect="NaN"]').addClass('active');
          });
          $('.stc-pd-aspect-btn').on('click', function() {
            if (!productCropper) return;
            var ratio = parseFloat($(this).attr('data-aspect'));
            productCropper.setAspectRatio(isNaN(ratio) ? NaN : ratio);
            $('.stc-pd-aspect-btn').removeClass('active');
            $(this).addClass('active');
          });
          $('#stcPdCropApply').on('click', function() {
            if (!productCropper) {
              alert('Crop tool is still loading. Please wait a moment and try again.');
              return;
            }
            var sourceFile = originalImageFile || ($fileInput[0].files && $fileInput[0].files[0]);
            var mime = (sourceFile && sourceFile.type === 'image/png') ? 'image/png' : 'image/jpeg';
            var canvas = productCropper.getCroppedCanvas({
              maxWidth: 1600,
              maxHeight: 1600,
              imageSmoothingEnabled: true,
              imageSmoothingQuality: 'high',
              fillColor: mime === 'image/jpeg' ? '#fff' : 'transparent'
            });
            if (!canvas) {
              alert('Unable to crop this image. Please try another file.');
              return;
            }
            function saveCroppedBlob(blob) {
              if (!blob) {
                alert('Unable to save the cropped image.');
                return;
              }
              var croppedFile;
              try {
                croppedFile = new File([blob], croppedFileName(sourceFile, mime), { type: mime, lastModified: Date.now() });
              } catch (err) {
                croppedFile = blob;
                croppedFile.name = croppedFileName(sourceFile, mime);
              }
              if (assignProductImage(croppedFile, false)) {
                hideCropModal();
              }
            }
            if (canvas.toBlob) {
              canvas.toBlob(saveCroppedBlob, mime, 0.92);
            } else {
              var dataUrl = canvas.toDataURL(mime, 0.92);
              var arr = dataUrl.split(',');
              var bstr = atob(arr[1]);
              var n = bstr.length;
              var u8arr = new Uint8Array(n);
              while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
              }
              saveCroppedBlob(new Blob([u8arr], { type: mime }));
            }
          });

          // js add product with some support
          $('.stc-add-product-form').on('submit',function(e){
            e.preventDefault();
            var $form = $(this);
            if (!validateProductForm($form)) return;
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
                data = String(data).trim().replace(/^"|"$/g, '');
                var ok = data.indexOf("Product's added") !== -1;
                stcPdAlert(ok ? 'success' : (data.indexOf('required fields') !== -1 ? 'warning' : 'error'),
                  ok ? 'Product added' : 'Could not add product', data);
                if(ok){
                  $('.stc-add-product-form')[0].reset();
                  clearProductImagePreview();
                }
              },
              error : function(){
                stcPdAlert('error', 'Could not add product', 'Something went wrong. Please try again.');
              }
            });
          });

          var stcAddProductViewPage = 1;
          var stcAddProductViewPerPage = 30;

          function renderStcAddProductViewPagination(totalPages, page) {
            var $el = $('.stc-addproduct-view-pagination');
            if (!totalPages || totalPages <= 1) {
              $el.empty();
              return;
            }
            var html = '';
            for (var i = 1; i <= totalPages; i++) {
              var pgCls = i === page ? 'btn-success' : 'btn-default';
              var pgTitle = i === page ? ' title="Reload this page"' : '';
              html += '<a href="javascript:void(0)" class="btn btn-sm ' + pgCls + ' stc-addproduct-pg-page" style="margin:2px;" data-page="' + i + '"' + pgTitle + '>' + i + '</a>';
            }
            $el.html(html);
          }

          function stcAddProductGridValidate() {
            var cat = $('#filterbycat').val();
            var sub = $('#filterbysubcat').val();
            var nameTrim = ($('#searchbystcname').val() || '').trim();
            var responset = '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12"><a href="#" class="btn btn-primary btn-block text-uppercase mb-3">Atleast type word in text field or search via category or sub category!!!</a></div>';
            if (cat === 'NA' && sub === 'NA') {
              if (nameTrim === '') {
                $('.stc-call-view-product-row').html(responset);
                return false;
              }
              if (nameTrim.length < 3) {
                $('.stc-call-view-product-row').html(responset);
                return false;
              }
            }
            return true;
          }

          function stc_filter_pro_paged(goPage) {
            if (typeof goPage === 'number' && goPage >= 1) {
              stcAddProductViewPage = goPage;
            }
            if (!stcAddProductGridValidate()) {
              $('.stc-addproduct-view-pagination').empty();
              return;
            }
            $('.stc-call-view-product-row').html('<div class="col-xl-12 text-center py-3">Loading...</div>');
            $.ajax({
              url: 'kattegat/ragnar_product.php',
              method: 'post',
              data: {
                stcaction: 1,
                product_view_paged: 1,
                product_list_page: stcAddProductViewPage,
                product_list_per_page: stcAddProductViewPerPage,
                phpfiltercatout: $('#filterbycat').val(),
                phpfiltersubcatout: $('#filterbysubcat').val(),
                phpfilternameout: $('#searchbystcname').val() || ''
              },
              dataType: 'json',
              success: function (res) {
                if (!res || typeof res.html === 'undefined') {
                  $('.stc-call-view-product-row').html('<div class="col-xl-12 text-center text-danger">Error loading products.</div>');
                  return;
                }
                stcAddProductViewPage = parseInt(res.page, 10) || 1;
                $('.stc-call-view-product-row').html(res.html);
                renderStcAddProductViewPagination(parseInt(res.total_pages, 10) || 1, stcAddProductViewPage);
              },
              error: function () {
                $('.stc-call-view-product-row').html('<div class="col-xl-12 text-center text-danger">Error loading products.</div>');
              }
            });
          }

          $('#stc-addproduct-view-search-btn').on('click', function (e) {
            e.preventDefault();
            stc_filter_pro_paged(1);
          });

          $('body').on('click', '.stc-addproduct-pg-page', function (e) {
            e.preventDefault();
            var p = parseInt($(this).data('page'), 10) || 1;
            stc_filter_pro_paged(p);
          });

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