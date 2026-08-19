<?php
require_once 'kattegat/auth_helper.php';
STCAuthHelper::checkAuth();
$page_code=303;
include("kattegat/role_check.php");
include "../MCU/db.php";

$pdid = isset($_GET['pdid']) ? (int) $_GET['pdid'] : 0;
$stcretresult = null;
if ($pdid > 0) {
    $stcretquery = mysqli_query($con, "
        SELECT * FROM `stc_product`
        LEFT JOIN `stc_category` ON `stc_product_cat_id`=`stc_cat_id`
        LEFT JOIN `stc_sub_category` ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
        LEFT JOIN `stc_brand` ON `stc_product_brand_id`=`stc_brand_id`
        WHERE `stc_product_id`='".$pdid."'
        LIMIT 1
    ");
    if ($stcretquery) {
        $stcretresult = mysqli_fetch_assoc($stcretquery);
    }
}

$stc_pd_types = array('Mechanical','Electrical','Civil','Instrumentation','Plumbing','HVAC','Safety','Electronics','Hardware','Consumable','Tools','Others');
$stc_pd_units = array('Nos','Set','Feet','Mtr','Sqmt','Ltr','Bag','Roll','Lot','Kgs','Pkt','Case','Bundle','Pair');
$curType = $stcretresult && !empty($stcretresult['stc_product_type']) ? $stcretresult['stc_product_type'] : '';
$curUnit = $stcretresult ? $stcretresult['stc_product_unit'] : '';
$curGst = $stcretresult ? (string) $stcretresult['stc_product_gst'] : '';
$curImg = ($stcretresult && !empty($stcretresult['stc_product_image'])) ? stc_product_image_url($stcretresult['stc_product_image']) : '';
$curImgName = $curImg ? basename(parse_url($curImg, PHP_URL_PATH)) : '';
$h = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Product - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/vendor/cropperjs/cropper.min.css">
    <style>
        .stc-pd-upload-card { min-height: 243px; padding: 16px; }
        .stc-pd-dropzone {
            position: relative; display: flex; align-items: center; justify-content: center;
            min-height: 211px; padding: 18px 16px; border: 2px dashed #3f6ad8; border-radius: 8px;
            background: #f7f9fe; cursor: pointer; text-align: center;
            transition: border-color .2s ease, background-color .2s ease;
        }
        .stc-pd-dropzone:hover, .stc-pd-dropzone.is-dragover { background: #eef3fd; border-color: #2952c8; }
        .stc-pd-dropzone-idle { display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%; }
        .stc-pd-dropzone-idle .stc-pd-upload-icon { font-size: 42px; color: #3f6ad8; margin-bottom: 10px; }
        .stc-pd-dropzone-title { margin: 0 0 4px; font-size: 15px; font-weight: 600; color: #3f6ad8; }
        .stc-pd-dropzone-hint { margin: 0; font-size: 13px; color: #6c757d; }
        .stc-pd-dropzone-types { margin: 10px 0 0; font-size: 12px; color: #98a2b3; }
        .stc-pd-dropzone-preview { display: none; width: 100%; align-items: center; text-align: left; }
        .stc-pd-dropzone.has-file .stc-pd-dropzone-idle { display: none; }
        .stc-pd-dropzone.has-file .stc-pd-dropzone-preview { display: flex; }
        .stc-pd-dropzone.has-file { cursor: default; }
        .stc-pd-preview-actions { display: none; margin-top: 12px; }
        .stc-pd-upload-card.has-file .stc-pd-preview-actions { display: block; }
        .stc-pd-preview-thumb {
            width: 92px; height: 92px; flex: 0 0 92px; border-radius: 6px; overflow: hidden;
            background: #fff; border: 1px solid #d9e2f5; margin-right: 14px;
        }
        .stc-pd-preview-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .stc-pd-preview-meta { min-width: 0; flex: 1; }
        .stc-pd-preview-name { display: block; font-weight: 600; color: #3d4a5c; word-break: break-all; margin-bottom: 4px; }
        .stc-pd-preview-size { display: block; font-size: 12px; color: #6c757d; margin-bottom: 10px; }
        .stc-pd-preview-actions .btn { margin-right: 6px; margin-bottom: 4px; }
        .stc-pd-cropper-wrap { position: relative; width: 100%; height: 420px; background: #2a3042; overflow: hidden; }
        .stc-pd-cropper-wrap img { display: block; max-width: 100%; max-height: 420px; }
        .stc-pd-cropper-wrap .cropper-container { width: 100% !important; height: 420px !important; }
        .stc-pd-crop-toolbar { margin-top: 12px; }
        .stc-pd-crop-toolbar .btn-group { margin: 0 6px 8px 0; }
        .stc-pd-crop-toolbar .btn.active { background-color: #3f6ad8; border-color: #3f6ad8; color: #fff; }
        #stcPdCropModal { z-index: 2000; }
        #stcPdCropModal .modal-header, #stcPdCropModal .modal-footer { position: relative; z-index: 6; }
        #stcPdCropModal .modal-body { position: relative; z-index: 1; overflow: hidden; }
        #stcPdCropClose { padding: 0 8px; font-size: 28px; line-height: 1; font-weight: 700; opacity: .7; cursor: pointer; }
        .stc-ap-wrap { background: #fff; border: 1px solid #e4e9f2; border-radius: 10px; overflow: hidden; }
        .stc-ap-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 22px; border-bottom: 1px solid #eef1f6; background: #f8faff; }
        .stc-ap-header h4 { margin: 0; font-size: 18px; font-weight: 600; color: #2b3553; }
        .stc-ap-header p { margin: 3px 0 0; font-size: 13px; color: #7a8599; }
        .stc-ap-body { padding: 20px 22px 8px; }
        .stc-ap-section { margin-bottom: 22px; }
        .stc-ap-section-title { display: flex; align-items: center; margin: 0 0 14px; font-size: 13px; font-weight: 700; letter-spacing: .4px; text-transform: uppercase; color: #3f6ad8; }
        .stc-ap-section-title:before { content: ""; width: 4px; height: 14px; margin-right: 8px; border-radius: 2px; background: #3f6ad8; }
        .stc-ap-field { position: relative; margin-bottom: 16px; }
        .stc-edit-product-form label, .stc-ap-label {
            position: relative !important; left: auto !important; top: auto !important; transform: none !important;
            display: block; margin: 0 0 6px; font-size: 13px; font-weight: 600; color: #495057; line-height: 1.3;
        }
        .stc-edit-product-form input, .stc-edit-product-form select, .stc-edit-product-form textarea {
            appearance: auto !important; -webkit-appearance: auto !important;
        }
        .stc-ap-field .form-control, .stc-ap-field .custom-select {
            display: block; width: 100%; height: 42px; padding: 8px 12px; border: 1px solid #d7deea;
            border-radius: 6px; background: #fff; line-height: 1.4;
        }
        .stc-ap-field textarea.form-control { height: auto; min-height: 92px; padding-top: 10px; resize: vertical; }
        .stc-ap-field .form-control:focus, .stc-ap-field .custom-select:focus {
            border-color: #3f6ad8; box-shadow: 0 0 0 3px rgba(63, 106, 216, .12);
        }
        .stc-ap-upload-card { margin-bottom: 0; padding: 0; min-height: 0; background: transparent; border: 0; }
        .stc-ap-wrap .stc-pd-dropzone { min-height: 188px; }
        .stc-ap-footer { display: flex; align-items: center; gap: 16px; padding: 14px 22px; border-top: 1px solid #eef1f6; background: #fbfcfe; }
        .stc-ap-footer .progress { flex: 1; height: 10px; margin: 0; background: #e9eef6; }
        .stc-ap-footer .btn { min-width: 180px; padding: 10px 18px; font-weight: 600; letter-spacing: .3px; }
        @media (max-width: 767px) {
            .stc-ap-footer { flex-direction: column; align-items: stretch; }
            .stc-ap-footer .btn { min-width: 0; }
        }
    </style>
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
                            <a role="tab" class="nav-link active" href="#tab-content-1">
                                <span>Edit Product</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add-product.php?page=master&subpage=addproduct">
                                <span>View All Products</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade show active" id="tab-content-1" role="tabpanel">
                            <?php if (!$stcretresult) { ?>
                                <div class="alert alert-danger">Product not found.</div>
                            <?php } else { ?>
                            <form action="" class="stc-edit-product-form"
                                data-cat-id="<?php echo $h($stcretresult['stc_product_cat_id']); ?>"
                                data-subcat-id="<?php echo $h($stcretresult['stc_product_sub_cat_id']); ?>"
                                data-brand-id="<?php echo $h($stcretresult['stc_product_brand_id']); ?>"
                                data-unit="<?php echo $h($curUnit); ?>"
                                data-gst="<?php echo $h($curGst); ?>">
                                <input type="hidden" name="stc_edit_product_hit" value="1">
                                <input type="hidden" name="stc_set_product_id" value="<?php echo (int) $stcretresult['stc_product_id']; ?>">
                                <div id="stc-edit-current-vals" style="display:none">
                                    <h5 for="category"><span><?php echo $h($stcretresult['stc_cat_name']); ?></span></h5>
                                    <h5 for="subcategory"><span><?php echo $h($stcretresult['stc_sub_cat_name']); ?></span></h5>
                                    <h5 for="unit"><span><?php echo $h($curUnit); ?></span></h5>
                                    <h5 for="gst"><span><?php echo $h($curGst); ?></span></h5>
                                    <h5 for="make"><span><?php echo $h($stcretresult['stc_brand_title']); ?></span></h5>
                                </div>
                                <div class="stc-ap-wrap">
                                    <div class="stc-ap-header">
                                        <div>
                                            <h4>Edit Product #<?php echo (int) $stcretresult['stc_product_id']; ?></h4>
                                            <p>Update details, image, tax and pricing for this product.</p>
                                        </div>
                                    </div>
                                    <div class="stc-ap-body">
                                        <div class="row">
                                            <div class="col-lg-7">
                                                <div class="stc-ap-section">
                                                    <h6 class="stc-ap-section-title">Basic details</h6>
                                                    <div class="stc-ap-field">
                                                        <span class="stc-ap-label">Product Name</span>
                                                        <textarea class="form-control validate" rows="3" name="stcpdname" placeholder="Enter product name" required><?php echo $h($stcretresult['stc_product_name']); ?></textarea>
                                                    </div>
                                                    <div class="stc-ap-field">
                                                        <span class="stc-ap-label">Description</span>
                                                        <textarea class="form-control validate" rows="3" name="stcpddesc" placeholder="Enter product description" required><?php echo $h($stcretresult['stc_product_desc']); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="stc-ap-section">
                                                    <h6 class="stc-ap-section-title">Classification</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="stc-ap-field">
                                                                <span class="stc-ap-label">Category</span>
                                                                <select class="custom-select tm-select-accounts call_cat" name="stcpdcategory"></select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="stc-ap-field">
                                                                <span class="stc-ap-label">Sub Category</span>
                                                                <select class="custom-select tm-select-accounts call_sub_cat" name="stcpdsubcategory"></select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="stc-ap-field">
                                                                <span class="stc-ap-label">Product Type</span>
                                                                <select class="custom-select tm-select-accounts" name="stcpdtype" required>
                                                                    <option value="NA">Select Product Type</option>
                                                                    <?php foreach ($stc_pd_types as $stc_pd_type) { ?>
                                                                        <option value="<?php echo $h($stc_pd_type); ?>"<?php echo ($curType === $stc_pd_type) ? ' selected' : ''; ?>><?php echo $h($stc_pd_type); ?></option>
                                                                    <?php } ?>
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
                                                                <span class="stc-ap-label">Unit</span>
                                                                <select class="custom-select tm-select-accounts stcpdunit" name="stcpdunit">
                                                                    <option value="NA">Select Unit</option>
                                                                    <?php foreach ($stc_pd_units as $u) { ?>
                                                                        <option value="<?php echo $h($u); ?>"<?php echo (strcasecmp((string) $curUnit, $u) === 0) ? ' selected' : ''; ?>><?php echo $h($u); ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="stc-ap-field">
                                                                <span class="stc-ap-label">Available</span>
                                                                <select class="custom-select tm-select-accounts" name="available">
                                                                    <option value="1"<?php echo ((int) $stcretresult['stc_product_avail'] === 1) ? ' selected' : ''; ?>>Available</option>
                                                                    <option value="0"<?php echo ((int) $stcretresult['stc_product_avail'] === 0) ? ' selected' : ''; ?>>Not Available</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="stc-ap-section">
                                                    <h6 class="stc-ap-section-title">Product image</h6>
                                                    <div class="stc-pd-upload-card stc-ap-upload-card<?php echo $curImg ? ' has-file' : ''; ?>">
                                                        <input id="fileInput" type="file" name="stcpdimage" accept="image/*" style="display:none;" />
                                                        <div class="stc-pd-dropzone<?php echo $curImg ? ' has-file' : ''; ?>" id="stcPdDropzone" role="button" tabindex="0" aria-label="Upload product image">
                                                            <div class="stc-pd-dropzone-idle">
                                                                <i class="fas fa-cloud-upload-alt stc-pd-upload-icon"></i>
                                                                <p class="stc-pd-dropzone-title">Drag & drop product image</p>
                                                                <p class="stc-pd-dropzone-hint">or click to browse from your computer</p>
                                                                <p class="stc-pd-dropzone-types">JPG, PNG, GIF or WEBP</p>
                                                            </div>
                                                            <div class="stc-pd-dropzone-preview">
                                                                <div class="stc-pd-preview-thumb">
                                                                    <img id="stcPdPreviewImg" alt="Product image preview" src="<?php echo $h($curImg); ?>">
                                                                </div>
                                                                <div class="stc-pd-preview-meta">
                                                                    <span class="stc-pd-preview-name" id="stcPdPreviewName"><?php echo $h($curImgName ? $curImgName : ($curImg ? 'Current image' : '')); ?></span>
                                                                    <span class="stc-pd-preview-size" id="stcPdPreviewSize"><?php echo $curImg ? 'Saved image' : ''; ?></span>
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
                                                                <span class="stc-ap-label">HSN Code</span>
                                                                <input name="stcpdhsncode" type="number" placeholder="HSN Code" class="form-control validate" required value="<?php echo $h($stcretresult['stc_product_hsncode']); ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="stc-ap-field">
                                                                <span class="stc-ap-label">GST</span>
                                                                <select class="custom-select tm-select-accounts gst" name="stcpdgst">
                                                                    <option value="0">Select GST</option>
                                                                    <?php foreach (array('5','12','18','28') as $g) { ?>
                                                                        <option value="<?php echo $g; ?>"<?php echo ($curGst === $g) ? ' selected' : ''; ?>><?php echo $g; ?>%</option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="stc-ap-field">
                                                                <span class="stc-ap-label">Percentage</span>
                                                                <input name="stcpdpercentage" type="number" placeholder="Sale percentage" class="form-control validate" required value="<?php echo $h($stcretresult['stc_product_sale_percentage']); ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="stc-ap-field">
                                                                <span class="stc-ap-label">Initial Rate</span>
                                                                <input name="stcpdinitrate" type="number" min="0" step="0.01" placeholder="0.00" class="form-control validate" required value="<?php echo $h(isset($stcretresult['stc_product_initial_rate']) ? $stcretresult['stc_product_initial_rate'] : '0'); ?>" />
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
                                        <button type="submit" class="btn btn-primary">Update Now</button>
                                    </div>
                                </div>
                            </form>
                            <?php } ?>
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
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-light" id="stcPdCropZoomIn" title="Zoom in"><i class="fas fa-search-plus"></i></button>
                <button type="button" class="btn btn-light" id="stcPdCropZoomOut" title="Zoom out"><i class="fas fa-search-minus"></i></button>
              </div>
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-light" id="stcPdCropRotateLeft" title="Rotate left"><i class="fas fa-undo"></i></button>
                <button type="button" class="btn btn-light" id="stcPdCropRotateRight" title="Rotate right"><i class="fas fa-redo"></i></button>
              </div>
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-light" id="stcPdCropFlipH" title="Flip horizontal"><i class="fas fa-arrows-alt-h"></i></button>
                <button type="button" class="btn btn-light" id="stcPdCropFlipV" title="Flip vertical"><i class="fas fa-arrows-alt-v"></i></button>
              </div>
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-light stc-pd-aspect-btn active" data-aspect="NaN">Free</button>
                <button type="button" class="btn btn-light stc-pd-aspect-btn" data-aspect="1">1:1</button>
                <button type="button" class="btn btn-light stc-pd-aspect-btn" data-aspect="1.333333">4:3</button>
                <button type="button" class="btn btn-light stc-pd-aspect-btn" data-aspect="1.777778">16:9</button>
                <button type="button" class="btn btn-light stc-pd-aspect-btn" data-aspect="0.75">3:4</button>
              </div>
              <div class="btn-group" role="group">
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
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
    <script type="text/javascript" src="./assets/scripts/sidebar-persist.js"></script>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
    <script src="assets/vendor/cropperjs/cropper.min.js"></script>
    <script>
        $(document).ready(function(){
          var $fileInput = $('#fileInput');
          var $dropzone = $('#stcPdDropzone');
          var $uploadCard = $('.stc-pd-upload-card');
          var previewUrl = '';
          var originalImageFile = null;
          var productCropper = null;
          var cropFlipH = 1;
          var cropFlipV = 1;
          var pendingCropFile = null;
          var pendingCropSrc = '';
          var cropObjectUrl = '';
          var existingImageSrc = $('#stcPdPreviewImg').attr('src') || '';

          function applySavedSelects() {
            var $f = $('.stc-edit-product-form');
            if (!$f.length) return;
            var cat = $f.attr('data-cat-id');
            var sub = $f.attr('data-subcat-id');
            var brand = $f.attr('data-brand-id');
            var unit = $f.attr('data-unit');
            var gst = $f.attr('data-gst');
            if (cat) { $('.call_cat').val(cat); }
            if (sub) { $('.call_sub_cat').val(sub); }
            if (brand) { $('.call_brand').val(brand); }
            if (unit) {
              $('.stcpdunit option').each(function(){
                if ($(this).val().toLowerCase() === String(unit).toLowerCase() || $(this).text().trim().toLowerCase() === String(unit).toLowerCase()) {
                  $(this).prop('selected', true);
                  return false;
                }
              });
            }
            if (gst) { $('.gst').val(String(gst).replace('%','')); }
          }
          setTimeout(applySavedSelects, 400);
          setTimeout(applySavedSelects, 1200);

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
            if (revokeUrl !== false && cropObjectUrl && cropObjectUrl.indexOf('blob:') === 0) {
              URL.revokeObjectURL(cropObjectUrl);
              cropObjectUrl = '';
            }
          }
          function restoreExistingPreview() {
            if (!existingImageSrc) {
              $dropzone.removeClass('has-file is-dragover');
              $uploadCard.removeClass('has-file');
              $('#stcPdPreviewImg').attr('src', '');
              $('#stcPdPreviewName').text('');
              $('#stcPdPreviewSize').text('');
              return;
            }
            $('#stcPdPreviewImg').attr('src', existingImageSrc);
            $('#stcPdPreviewName').text('Current image');
            $('#stcPdPreviewSize').text('Saved image');
            $dropzone.addClass('has-file').removeClass('is-dragover');
            $uploadCard.addClass('has-file');
          }
          function clearProductImagePreview() {
            if (previewUrl) {
              URL.revokeObjectURL(previewUrl);
              previewUrl = '';
            }
            originalImageFile = null;
            pendingCropFile = null;
            pendingCropSrc = '';
            destroyProductCropper();
            $fileInput.val('');
            restoreExistingPreview();
          }
          function showProductImagePreview(file) {
            if (!file) {
              clearProductImagePreview();
              return;
            }
            if (previewUrl) URL.revokeObjectURL(previewUrl);
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
              if (asOriginal) originalImageFile = file;
              showProductImagePreview(file);
              return true;
            } catch (err) {
              alert('This browser cannot update the selected file automatically. Please click to browse or use Change.');
              return false;
            }
          }
          function openProductImagePicker() { $fileInput.trigger('click'); }
          function croppedFileName(sourceFile, mime) {
            var base = (sourceFile && sourceFile.name) ? sourceFile.name.replace(/\.[^.]+$/, '') : 'product-image';
            var ext = mime === 'image/png' ? 'png' : 'jpg';
            return base + '-cropped.' + ext;
          }
          function showCropModal() {
            var $modal = $('#stcPdCropModal');
            if ($.fn.modal) $modal.modal('show');
            else {
              $modal.addClass('show').css('display', 'block').attr('aria-hidden', 'false');
              $('body').addClass('modal-open');
              $modal.trigger('shown.bs.modal');
            }
          }
          function hideCropModal() {
            destroyProductCropper();
            var $modal = $('#stcPdCropModal');
            if ($.fn.modal) $modal.modal('hide');
            $modal.removeClass('show').css('display', 'none').attr('aria-hidden', 'true');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('padding-right', '');
            var wrap = document.getElementById('stcPdCropperWrap');
            if (wrap) wrap.innerHTML = '<img id="stcPdCropperImg" alt="Crop product image">';
          }
          function startCropperOnImage(image) {
            var CropperLib = getCropperLib();
            if (!CropperLib) {
              alert('Cropping tool could not load. Please refresh and try again.');
              return;
            }
            destroyProductCropper(false);
            productCropper = new CropperLib(image, {
              viewMode: 1, dragMode: 'move', autoCropArea: 0.85, background: true,
              responsive: true, restore: false, checkOrientation: false, guides: true,
              center: true, highlight: false, cropBoxMovable: true, cropBoxResizable: true
            });
          }

          $dropzone.on('click', function() {
            if ($dropzone.hasClass('has-file')) return;
            openProductImagePicker();
          });
          $('#stcPdChangeBtn').on('click', function(e) {
            e.preventDefault(); e.stopPropagation();
            openProductImagePicker();
          });
          $('#stcPdRemoveBtn').on('click', function(e) {
            e.preventDefault(); e.stopPropagation();
            $fileInput.val('');
            originalImageFile = null;
            if (previewUrl) { URL.revokeObjectURL(previewUrl); previewUrl = ''; }
            $dropzone.removeClass('has-file is-dragover');
            $uploadCard.removeClass('has-file');
            $('#stcPdPreviewImg').attr('src', '');
            $('#stcPdPreviewName').text('');
            $('#stcPdPreviewSize').text('');
          });
          $fileInput.on('change', function() {
            var file = this.files && this.files[0];
            if (file) {
              originalImageFile = file;
              showProductImagePreview(file);
            }
          });
          $dropzone.on('dragover dragenter', function(e) {
            e.preventDefault(); e.stopPropagation();
            $dropzone.addClass('is-dragover');
          });
          $dropzone.on('dragleave dragend', function(e) {
            e.preventDefault(); e.stopPropagation();
            $dropzone.removeClass('is-dragover');
          });
          $dropzone.on('drop', function(e) {
            e.preventDefault(); e.stopPropagation();
            $dropzone.removeClass('is-dragover');
            var files = e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files;
            if (files && files[0]) assignProductImage(files[0], true);
          });

          $('#stcPdCropBtn').on('click', function(e) {
            e.preventDefault(); e.stopImmediatePropagation();
            var file = originalImageFile || ($fileInput[0].files && $fileInput[0].files[0]);
            var src = $('#stcPdPreviewImg').attr('src');
            if (file) {
              pendingCropFile = file;
              pendingCropSrc = '';
            } else if (src) {
              pendingCropFile = null;
              pendingCropSrc = src;
            } else {
              alert('Please select an image first.');
              return;
            }
            if (!getCropperLib()) {
              alert('Cropping tool could not load. Please refresh and try again.');
              return;
            }
            showCropModal();
          });
          $(document).on('mousedown click', '#stcPdCropClose, #stcPdCropCancel', function(e) {
            e.preventDefault(); e.stopImmediatePropagation();
            hideCropModal();
          });
          $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $('#stcPdCropModal').is(':visible')) hideCropModal();
          });
          $('#stcPdCropModal').on('shown.bs.modal', function() {
            var file = pendingCropFile || originalImageFile || ($fileInput[0].files && $fileInput[0].files[0]);
            var src = pendingCropSrc || (file ? '' : ($('#stcPdPreviewImg').attr('src') || ''));
            if (!file && !src) return;
            destroyProductCropper();
            var wrap = document.getElementById('stcPdCropperWrap');
            wrap.innerHTML = '<img id="stcPdCropperImg" alt="Crop product image">';
            var image = document.getElementById('stcPdCropperImg');
            cropObjectUrl = file ? URL.createObjectURL(file) : src;
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
            if (wrap) wrap.innerHTML = '<img id="stcPdCropperImg" alt="Crop product image">';
          });
          $('#stcPdCropZoomIn').on('click', function() { if (productCropper) productCropper.zoom(0.1); });
          $('#stcPdCropZoomOut').on('click', function() { if (productCropper) productCropper.zoom(-0.1); });
          $('#stcPdCropRotateLeft').on('click', function() { if (productCropper) productCropper.rotate(-90); });
          $('#stcPdCropRotateRight').on('click', function() { if (productCropper) productCropper.rotate(90); });
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
            cropFlipH = 1; cropFlipV = 1;
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
              maxWidth: 1600, maxHeight: 1600, imageSmoothingEnabled: true,
              imageSmoothingQuality: 'high', fillColor: mime === 'image/jpeg' ? '#fff' : 'transparent'
            });
            if (!canvas) {
              alert('Unable to crop this image. Please try another file.');
              return;
            }
            function saveCroppedBlob(blob) {
              if (!blob) { alert('Unable to save the cropped image.'); return; }
              var croppedFile;
              try {
                croppedFile = new File([blob], croppedFileName(sourceFile, mime), { type: mime, lastModified: Date.now() });
              } catch (err) {
                croppedFile = blob;
                croppedFile.name = croppedFileName(sourceFile, mime);
              }
              if (assignProductImage(croppedFile, false)) hideCropModal();
            }
            if (canvas.toBlob) canvas.toBlob(saveCroppedBlob, mime, 0.92);
            else {
              var dataUrl = canvas.toDataURL(mime, 0.92);
              var arr = dataUrl.split(',');
              var bstr = atob(arr[1]);
              var n = bstr.length;
              var u8arr = new Uint8Array(n);
              while (n--) u8arr[n] = bstr.charCodeAt(n);
              saveCroppedBlob(new Blob([u8arr], { type: mime }));
            }
          });

          $('.stc-edit-product-form').on('submit', function(e){
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
                data = String(data).trim().replace(/^"|"$/g, '');
                alert(data);
                if (data.indexOf('Updated Successfully') !== -1) {
                  var newSrc = $('#stcPdPreviewImg').attr('src');
                  if (newSrc && newSrc.indexOf('blob:') !== 0) existingImageSrc = newSrc;
                }
              }
            });
          });
        });
    </script>
</body>
</html>
