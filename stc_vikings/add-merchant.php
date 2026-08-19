<?php  
// Include authentication helper
require_once 'kattegat/auth_helper.php';

// Check authentication using the new hybrid system
STCAuthHelper::checkAuth();$page_code=304;
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
    <title>Add Merchant - STC</title>
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
    <style>
        .stc-mer-shell {
            background: #fff;
            border: 1px solid #e4e9f2;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(43, 53, 83, .06);
        }
        .stc-mer-hero {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            padding: 22px 24px 18px;
            background:
                radial-gradient(1200px 180px at -10% -40%, rgba(63,106,216,.18), transparent 55%),
                linear-gradient(180deg, #f7f9fe 0%, #fff 100%);
            border-bottom: 1px solid #eef1f6;
        }
        .stc-mer-hero h4 { margin: 0; font-size: 22px; font-weight: 700; color: #1d2740; letter-spacing: -.3px; }
        .stc-mer-hero p { margin: 4px 0 0; font-size: 13px; color: #7a8599; }
        .stc-mer-total-pill {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 12px; border-radius: 999px; background: #fff;
            border: 1px solid #e4e9f2; color: #2b3553; font-weight: 700; font-size: 13px;
            box-shadow: 0 1px 2px rgba(32,48,86,.05);
        }
        .stc-mer-total-pill span { color: #7a8599; font-weight: 600; }
        .stc-mer-filters { padding: 16px 24px 8px; }
        .stc-mer-field { margin-bottom: 12px; }
        .stc-mer-label {
            display: block; margin: 0 0 6px; font-size: 12px; font-weight: 700;
            letter-spacing: .3px; text-transform: uppercase; color: #7a8599;
            position: relative !important; left: auto !important; top: auto !important; transform: none !important;
        }
        .stc-mer-input-wrap { position: relative; display: block; }
        .stc-mer-input-wrap .stc-mer-in-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            margin: 0; line-height: 1; width: 16px; text-align: center;
            color: #98a2b3; font-size: 14px; pointer-events: none; z-index: 2;
        }
        .stc-mer-input-wrap .form-control {
            height: 44px; line-height: 44px; padding: 0 40px 0 40px;
            border: 1px solid #d7deea; border-radius: 10px; background: #fbfcfe;
        }
        .stc-mer-input-wrap .form-control:focus {
            background: #fff; border-color: #3f6ad8; box-shadow: 0 0 0 4px rgba(63,106,216,.12);
        }
        .stc-mer-clear {
            position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
            border: 0; background: transparent; color: #98a2b3;
            width: 28px; height: 28px; padding: 0; line-height: 1; border-radius: 50%;
            display: none; align-items: center; justify-content: center; z-index: 2;
        }
        .stc-mer-field.has-value .stc-mer-clear { display: inline-flex; }
        .stc-mer-clear:hover { background: #eef1f6; color: #2b3553; }
        .stc-mer-search-actions { display: flex; align-items: flex-end; gap: 8px; height: 100%; padding-bottom: 12px; }
        .stc-mer-search-actions .btn { height: 44px; border-radius: 10px; font-weight: 700; min-width: 118px; }
        .stc-mer-bar {
            display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;
            padding: 10px 24px; border-top: 1px solid #eef1f6; border-bottom: 1px solid #eef1f6; background: #fbfcfe;
        }
        .stc-mer-count { font-size: 13px; color: #6c757d; }
        .stc-mer-count b { color: #1d2740; }
        .stc-mer-bar-right { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .stc-mer-pagesize { height: 34px; border: 1px solid #d7deea; border-radius: 8px; padding: 0 8px; background: #fff; font-size: 12px; font-weight: 600; color: #2b3553; }
        .stc-mer-view-toggle { display: inline-flex; background: #fff; border: 1px solid #d7deea; border-radius: 8px; overflow: hidden; }
        .stc-mer-view-toggle button {
            border: 0; background: transparent; width: 36px; height: 32px; color: #7a8599;
        }
        .stc-mer-view-toggle button.is-active { background: #3f6ad8; color: #fff; }
        .stc-mer-body { padding: 16px 24px 8px; min-height: 240px; }
        .stc-mer-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
        .stc-mer-grid .stc-mer-empty { grid-column: 1 / -1; }
        .stc-mer-shell.is-table .stc-mer-grid,
        .stc-mer-shell.is-cards .stc-mer-table-wrap { display: none; }
        .stc-mer-shell.is-table .stc-mer-table-wrap { display: block; }
        .stc-mer-card {
            position: relative; background: #fff; border: 1px solid #e6ebf5; border-radius: 14px;
            padding: 16px 16px 12px; overflow: hidden;
            box-shadow: 0 1px 2px rgba(32,48,86,.04);
            transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
        }
        .stc-mer-card:before {
            content: ""; position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
            background: linear-gradient(180deg, #3f6ad8, #16aaff);
        }
        .stc-mer-card:hover { transform: translateY(-2px); border-color: #cfd9ee; box-shadow: 0 12px 24px rgba(43,53,83,.08); }
        .stc-mer-card-top { display: flex; gap: 12px; align-items: flex-start; margin-bottom: 10px; padding-left: 6px; }
        .stc-mer-avatar {
            width: 44px; height: 44px; flex: 0 0 44px; border-radius: 12px; color: #fff;
            display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;
            letter-spacing: .4px; box-shadow: inset 0 0 0 1px rgba(255,255,255,.18);
        }
        .stc-mer-avatar.sm { width: 34px; height: 34px; flex-basis: 34px; border-radius: 9px; font-size: 12px; }
        .stc-mer-identity { min-width: 0; flex: 1; }
        .stc-mer-name-row { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; }
        .stc-mer-name {
            margin: 0; font-size: 15px; font-weight: 800; color: #1d2740; line-height: 1.3;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .stc-mer-id { flex: 0 0 auto; font-size: 11px; font-weight: 800; color: #7a8599; background: #f4f6fb; border-radius: 999px; padding: 3px 8px; }
        .stc-mer-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 6px; align-items: center; }
        .stc-mer-chip { display: inline-block; max-width: 100%; padding: 2px 8px; border-radius: 999px; background: #eef3fd; color: #3f6ad8; font-size: 11px; font-weight: 700; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .stc-mer-chip-cat { background: #e9f8f0; color: #1e8a4c; }
        .stc-mer-loc { font-size: 12px; color: #7a8599; font-weight: 600; }
        .stc-mer-loc .fa { margin-right: 4px; color: #d92550; }
        .stc-mer-address { margin: 0 0 12px; padding-left: 6px; font-size: 12px; color: #5c677b; line-height: 1.45; }
        .stc-mer-address .fa { margin-right: 6px; color: #98a2b3; }
        .stc-mer-facts { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; padding: 10px 6px 0; border-top: 1px dashed #e6ebf5; }
        .stc-mer-fact { display: flex; align-items: flex-start; gap: 8px; min-width: 0; }
        .stc-mer-fact > .fa { margin-top: 3px; color: #3f6ad8; width: 14px; text-align: center; }
        .stc-mer-fact em { display: block; font-style: normal; font-size: 10px; font-weight: 800; letter-spacing: .4px; text-transform: uppercase; color: #98a2b3; }
        .stc-mer-fact b, .stc-mer-fact a { display: block; font-size: 12px; font-weight: 700; color: #2b3553; word-break: break-word; }
        .stc-mer-fact a:hover { color: #3f6ad8; }
        .stc-mer-fact-wide { grid-column: 1 / -1; }
        .stc-mer-copy {
            margin-left: auto; border: 0; background: #f4f6fb; color: #7a8599; width: 26px; height: 26px;
            border-radius: 7px; flex: 0 0 26px; line-height: 26px; padding: 0;
        }
        .stc-mer-copy:hover { background: #3f6ad8; color: #fff; }
        .stc-mer-table-wrap { overflow: auto; border: 1px solid #e6ebf5; border-radius: 12px; }
        .stc-mer-table { width: 100%; margin: 0; }
        .stc-mer-table thead th {
            position: sticky; top: 0; background: #f8faff; font-size: 11px; text-transform: uppercase;
            letter-spacing: .4px; color: #7a8599; border-bottom: 1px solid #e6ebf5; white-space: nowrap; padding: 12px;
        }
        .stc-mer-table td { padding: 12px; border-top: 1px solid #f0f3f9; vertical-align: middle; font-size: 13px; color: #2b3553; }
        .stc-mer-table tbody tr:hover { background: #f8faff; }
        .stc-mer-td-name { display: flex; align-items: center; gap: 10px; }
        .stc-mer-td-name strong { display: block; font-size: 13px; color: #1d2740; }
        .stc-mer-td-name small { display: block; color: #7a8599; }
        .stc-mer-td-stack a, .stc-mer-td-stack span { display: block; }
        .stc-mer-td-stack span { color: #7a8599; font-size: 12px; }
        .stc-mer-table code { background: #f4f6fb; padding: 2px 6px; border-radius: 6px; font-size: 12px; color: #2b3553; }
        .stc-mer-empty { text-align: center; padding: 48px 16px; }
        .stc-mer-empty-icon {
            width: 64px; height: 64px; margin: 0 auto 12px; border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            background: #eef3fd; color: #3f6ad8; font-size: 26px;
        }
        .stc-mer-empty h5 { margin: 0 0 4px; color: #1d2740; font-weight: 800; }
        .stc-mer-empty p { margin: 0; color: #7a8599; }
        .stc-mer-skel { height: 168px; border-radius: 14px; background: linear-gradient(90deg, #f4f6fb 25%, #eef1f6 37%, #f4f6fb 63%); background-size: 400% 100%; animation: stcMerShimmer 1.2s infinite; }
        @keyframes stcMerShimmer { 0% { background-position: 100% 0; } 100% { background-position: 0 0; } }
        .stc-mer-footer { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding: 12px 24px 18px; }
        .stc-mer-pagination { display: flex; align-items: center; flex-wrap: wrap; gap: 4px; }
        .stc-mer-pagination .stc-mer-pg {
            min-width: 34px; height: 34px; border: 1px solid #e4e9f2; background: #fff; color: #2b3553;
            border-radius: 8px; font-weight: 700; font-size: 12px;
        }
        .stc-mer-pagination .stc-mer-pg:hover:not(:disabled):not(.is-current) { border-color: #3f6ad8; color: #3f6ad8; }
        .stc-mer-pagination .stc-mer-pg.is-current { background: #3f6ad8; border-color: #3f6ad8; color: #fff; }
        .stc-mer-pagination .stc-mer-pg:disabled { opacity: .45; }
        .stc-mer-pagination .stc-mer-gap { padding: 0 4px; color: #98a2b3; font-weight: 700; }
        .stc-mer-toast {
            position: fixed; right: 24px; bottom: 24px; z-index: 4000; display: none;
            background: #1d2740; color: #fff; padding: 10px 14px; border-radius: 10px; font-size: 13px; font-weight: 600;
            box-shadow: 0 10px 24px rgba(0,0,0,.18);
        }
        @media (max-width: 1199px) { .stc-mer-grid { grid-template-columns: 1fr; } }
        @media (max-width: 767px) {
            .stc-mer-hero, .stc-mer-filters, .stc-mer-bar, .stc-mer-body, .stc-mer-footer { padding-left: 14px; padding-right: 14px; }
            .stc-mer-search-actions .btn { width: 100%; }
            .stc-mer-hero { flex-direction: column; align-items: flex-start; }
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
                                <a role="tab" class="nav-link active" id="tab-1" data-toggle="tab" href="#tab-content-1">
                                    <span>Add New Merchant</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                                    <span>View All Merchant</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-3" data-toggle="tab" href="#tab-content-3">
                                    <span>Merchant Items</span>
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
                                              >Add Merchant
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                      <form action="" class="stc-add-merchant-form">
                                        <div class="card-border mb-3 card card-body border-primary">
                                          <h5
                                            for="name"
                                            >Merchant Name
                                          </h5>
                                          <input
                                            id="name"
                                            name="stcmername"
                                            type="text"
                                            placeholder="Enter Merchant Name"
                                            class="form-control validate"
                                            required
                                          />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-border mb-3 card card-body border-primary">
                                          <h5
                                            for="category"
                                            >Category
                                          </h5>
                                          <select
                                            class="custom-select tm-select-accounts"
                                            name="stcmercategory"
                                            required
                                          >
                                            <option value="NA" selected>Select Category</option>
                                            <option value="Manufacturer">Manufacturer</option>
                                            <option value="Retailer">Retailer</option>
                                            <option value="Wholesaler">Wholesaler</option>
                                            <option value="Distributor">Distributor</option>
                                            <option value="Dealer">Dealer</option>
                                            <option value="Supplier">Supplier</option>
                                            <option value="Trader">Trader</option>
                                            <option value="Service Provider">Service Provider</option>
                                            <option value="Others">Others</option>
                                          </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="card-border mb-3 card card-body border-primary">
                                          <h5
                                            for="address"
                                            >Merchant Address</h5
                                          >
                                          <textarea
                                            class="form-control validate"
                                            rows="2"
                                            name="stcmeraddress"
                                            placeholder="Enter Merchant Description"
                                            required
                                          ></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-border mb-3 card card-body border-primary">
                                          <h5
                                            for="city"
                                            >Merchant City</h5
                                          >
                                          <select
                                            class="custom-select tm-select-accounts call_city"
                                            name="stcmercity"
                                          >
                                          </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="card-border mb-3 card card-body border-primary">
                                        <h5
                                          for="state"
                                          >Merchant State</h5
                                        >
                                        <select
                                          class="custom-select tm-select-accounts call_state"
                                          name="stcmerstate"
                                        >
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-border mb-3 card card-body border-primary">
                                          <h5
                                            for="contactperson"
                                            >Merchant Contact Person
                                          </h5>
                                          <input
                                            id="name"
                                            name="stcmercontperson"
                                            type="text"
                                            placeholder="Enter Merchant Contact Person"
                                            class="form-control validate"
                                            required
                                          />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-border mb-3 card card-body border-primary">
                                          <h5
                                            for="rack"
                                            >Merchant Contact Number</h5
                                          >
                                          <input
                                            id="name"
                                            name="stcmercontnumber"
                                            type="number"
                                            placeholder="Enter Merchant Contact Number"
                                            class="form-control validate"
                                            required
                                          />
                                          </select>

                                          <input type="hidden" name="stc_add_merchant_hit">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="card-border mb-3 card card-body border-primary">
                                        <h5
                                          for="email"
                                          >Merchant Email</h5
                                        >
                                        <input
                                          id="name"
                                          name="stcmeremail"
                                          type="text"
                                          placeholder="Enter Merchant Email"
                                          class="form-control validate"
                                          required
                                        />
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="card-border mb-3 card card-body border-primary">
                                        <h5
                                          for="knownfor"
                                          >Merchant Specially Known For</h5
                                        >
                                        <input
                                          id="name"
                                          name="stcmerskf"
                                          type="text"
                                          placeholder="Enter Merchant Specially Known For"
                                          class="form-control validate"
                                          required
                                        />
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="card-border mb-3 card card-body border-primary">
                                        <h5
                                          for="knownfor"
                                          >Merchant GSTIN</h5
                                        >
                                        <input
                                          id="name"
                                          name="stcmergstin"
                                          type="text"
                                          placeholder="Enter Merchant GSTIN"
                                          class="form-control validate"
                                          required
                                        />
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-border mb-3 card card-body border-primary">
                                          <h5
                                            for="knownfor"
                                            >Merchant PAN</h5
                                          >
                                          <input
                                            id="name"
                                            name="stcmerpan"
                                            type="text"
                                            placeholder="Enter Merchant PAN"
                                            class="form-control validate"
                                            required
                                          />
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                      <button type="submit" class="btn btn-primary btn-block text-uppercase">Add Merchant Now</button>
                                    </div>
                                      </form>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">
                                <div class="stc-mer-shell is-cards">
                                    <div class="stc-mer-hero">
                                        <div>
                                            <h4>View Merchants</h4>
                                            <p>Find a vendor by name, GSTIN, phone, or specialty.</p>
                                        </div>
                                        <div class="stc-mer-total-pill"><span>Directory</span> <b id="stc-merchant-total">—</b></div>
                                    </div>
                                    <div class="stc-mer-filters">
                                        <form action="" class="stc-view-Merchant-form" autocomplete="off">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="stc-mer-field" id="stc-mer-name-field">
                                                        <span class="stc-mer-label">Search</span>
                                                        <div class="stc-mer-input-wrap">
                                                            <i class="fa fa-search stc-mer-in-icon"></i>
                                                            <input id="searchbystcmername" name="stcmername" type="text" placeholder="Name, address, GSTIN, phone or email" class="form-control" />
                                                            <button type="button" class="stc-mer-clear" data-clear="#searchbystcmername" title="Clear">&times;</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="stc-mer-field" id="stc-mer-skf-field">
                                                        <span class="stc-mer-label">Known for</span>
                                                        <div class="stc-mer-input-wrap">
                                                            <i class="fa fa-industry stc-mer-in-icon"></i>
                                                            <input id="searchbystcmerskf" name="stcmerskf" type="text" placeholder="Specialty, e.g. electrical" class="form-control" />
                                                            <button type="button" class="stc-mer-clear" data-clear="#searchbystcmerskf" title="Clear">&times;</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="stc-mer-search-actions">
                                                        <button type="submit" class="btn btn-primary" id="stc-merchant-search-btn">Search</button>
                                                        <button type="button" class="btn btn-light" id="stc-merchant-reset-btn">Reset</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="stc-mer-bar">
                                        <div class="stc-mer-count" id="stc-merchant-count">Click Search to load merchants.</div>
                                        <div class="stc-mer-bar-right">
                                            <select id="stc-merchant-pagesize" class="stc-mer-pagesize">
                                                <option value="12" selected>12 / page</option>
                                                <option value="24">24 / page</option>
                                                <option value="48">48 / page</option>
                                            </select>
                                            <div class="stc-mer-view-toggle">
                                                <button type="button" class="is-active" data-view="cards" title="Card view"><i class="fa fa-th-large"></i></button>
                                                <button type="button" data-view="table" title="Table view"><i class="fa fa-list"></i></button>
                                            </div>
                                            <div class="stc-mer-pagination" id="stc-merchant-pagination-top"></div>
                                        </div>
                                    </div>
                                    <div class="stc-mer-body">
                                        <div class="stc-mer-grid stc-call-view-Merchant-row"></div>
                                        <div class="stc-mer-table-wrap">
                                            <table class="stc-mer-table">
                                                <thead>
                                                    <tr>
                                                        <th>Merchant</th>
                                                        <th>Category</th>
                                                        <th>Location</th>
                                                        <th>Contact</th>
                                                        <th>GSTIN</th>
                                                        <th>Contact person</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="stc-mer-table-body"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="stc-mer-footer">
                                        <div class="stc-mer-count" id="stc-merchant-pageinfo"></div>
                                        <div class="stc-mer-pagination" id="stc-merchant-pagination"></div>
                                    </div>
                                </div>
                                <div class="stc-mer-toast" id="stc-mer-toast">Copied</div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="tab-content-3" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >Merchant Items
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Merchant Name
                                        </h5>
                                        <select
                                          id="stc_get_merchant"
                                          class="custom-select stc-select-vendor"
                                          name="stcvendor"
                                        >
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <button class="form-control btn btn-success stc-get-merchant-item-hit">Find</button>
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <table class="table table-hover table-bordered table-responsive">
                                          <thead>
                                            <th class="text-center" >Item ID</th>
                                            <th class="text-center" width="25%">Item Description</th>
                                            <th class="text-center" >Total PO Qty</th>
                                            <th class="text-center" >PO Qty</th>
                                            <th class="text-center" >Total GRN Qty</th>
                                            <th class="text-center" >GRN Qty</th>
                                            <th class="text-center" >Inventory Qty</th>
                                            <th class="text-center" >Challan Qty</th>
                                            <th class="text-center" >Electronics Inventory Qty</th>
                                            <th class="text-center" >Electronics Challan Qty</th>
                                          </thead>
                                          <tbody class="stc-show-merchant-item-rec">                                            
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
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
    <script type="text/javascript" src="./assets/scripts/sidebar-persist.js"></script>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
    <script>
        $(document).ready(function(){
          // add merchant
          $('.stc-add-merchant-form').on('submit',function(e){
            e.preventDefault();
            $.ajax({
              url             : "kattegat/ragnar_merchant.php",
              method          : "post",
              data            : new FormData(this),
              contentType     : false,
              cache           : false,
              processData     : false,
              success         : function(data){
                data=data.trim();  
                if(data=="Merchant added!!"){
                  alert(data);
                  $('.stc-add-merchant-form')[0].reset();
                }else{
                  alert(data);
                }
                
              }
            });
          });

          // state & city call
          stc_vendor_page_on_call();
          function stc_vendor_page_on_call(){
            $.ajax({
              url         : "kattegat/ragnar_merchant.php",
              method      : "post",
              data        : {indialocation:1},
              dataType    : 'JSON',
              success     : function(data){
                // console.log(data);
                $('.call_city').html(data[0]);
                $('.call_state').html(data[1]);
              }
            });
          }

          // merchant search
          var stcMerPage = 1;
          var stcMerPerPage = parseInt($('#stc-merchant-pagesize').val(), 10) || 12;
          var stcMerTotalPages = 1;
          var stcMerLoaded = false;
          var stcMerXhr = null;
          var stcMerView = localStorage.getItem('stcMerView') || 'cards';

          function applyMerchantView(view) {
            stcMerView = view === 'table' ? 'table' : 'cards';
            localStorage.setItem('stcMerView', stcMerView);
            $('.stc-mer-shell').removeClass('is-cards is-table').addClass('is-' + stcMerView);
            $('.stc-mer-view-toggle button').removeClass('is-active');
            $('.stc-mer-view-toggle button[data-view="' + stcMerView + '"]').addClass('is-active');
          }
          applyMerchantView(stcMerView);

          function syncClearButtons() {
            $('#searchbystcmername, #searchbystcmerskf').each(function(){
              $(this).closest('.stc-mer-field').toggleClass('has-value', $.trim($(this).val()) !== '');
            });
          }

          function stcMerShowIdle(message) {
            $('#stc-merchant-count').text(message || 'Click Search to load merchants.');
            $('#stc-merchant-pageinfo').text('');
            $('#stc-merchant-total').text('—');
            $('#stc-merchant-pagination, #stc-merchant-pagination-top').empty();
            $('.stc-call-view-Merchant-row').html('');
            $('.stc-mer-table-body').html('');
            stcMerLoaded = false;
          }

          function stcMerShowError() {
            $('#stc-merchant-count').text('Could not load merchants.');
            $('#stc-merchant-pageinfo').text('');
            $('.stc-call-view-Merchant-row').html('<div class="stc-mer-empty"><h5>Could not load merchants</h5><p>Please click Search and try again.</p></div>');
            $('.stc-mer-table-body').html('<tr><td colspan="6" class="text-center text-muted py-4">Could not load merchants.</td></tr>');
          }

          function merchantSkeleton() {
            $('#stc-merchant-count').text('Searching...');
            var html = '';
            for (var i = 0; i < 4; i++) html += '<div class="stc-mer-skel"></div>';
            $('.stc-call-view-Merchant-row').html(html);
            $('.stc-mer-table-body').html('<tr><td colspan="6" class="text-center text-muted py-4">Searching...</td></tr>');
          }

          function renderMerchantPagination(totalPages, page) {
            var html = '';
            if (!totalPages || totalPages <= 1) {
              $('#stc-merchant-pagination, #stc-merchant-pagination-top').empty();
              return;
            }
            html += '<button type="button" class="stc-mer-pg" data-page="' + Math.max(1, page - 1) + '"' + (page <= 1 ? ' disabled' : '') + '>&laquo;</button>';
            var start = Math.max(1, page - 2);
            var end = Math.min(totalPages, page + 2);
            if (start > 1) {
              html += '<button type="button" class="stc-mer-pg" data-page="1">1</button>';
              if (start > 2) html += '<span class="stc-mer-gap">...</span>';
            }
            for (var i = start; i <= end; i++) {
              html += '<button type="button" class="stc-mer-pg' + (i === page ? ' is-current' : '') + '" data-page="' + i + '">' + i + '</button>';
            }
            if (end < totalPages) {
              if (end < totalPages - 1) html += '<span class="stc-mer-gap">...</span>';
              html += '<button type="button" class="stc-mer-pg" data-page="' + totalPages + '">' + totalPages + '</button>';
            }
            html += '<button type="button" class="stc-mer-pg" data-page="' + Math.min(totalPages, page + 1) + '"' + (page >= totalPages ? ' disabled' : '') + '>&raquo;</button>';
            $('#stc-merchant-pagination, #stc-merchant-pagination-top').html(html);
          }

          function stcMerToast(text) {
            var $t = $('#stc-mer-toast');
            $t.text(text).fadeIn(120);
            clearTimeout($t.data('timer'));
            $t.data('timer', setTimeout(function(){ $t.fadeOut(180); }, 1400));
          }

          function stc_search_merchants(goPage) {
            if (typeof goPage === 'number' && goPage >= 1) stcMerPage = goPage;
            if (stcMerXhr && stcMerXhr.readyState !== 4) stcMerXhr.abort();
            merchantSkeleton();
            stcMerXhr = $.ajax({
              url: 'kattegat/ragnar_merchant.php',
              method: 'post',
              dataType: 'json',
              data: {
                stc_search_merchant_paged: 1,
                stcmername: $('#searchbystcmername').val() || '',
                stcmerskf: $('#searchbystcmerskf').val() || '',
                page: stcMerPage,
                per_page: stcMerPerPage
              },
              success: function(res) {
                if (!res || typeof res.html === 'undefined') {
                  stcMerShowError();
                  return;
                }
                stcMerPage = parseInt(res.page, 10) || 1;
                stcMerTotalPages = parseInt(res.total_pages, 10) || 1;
                $('.stc-call-view-Merchant-row').html(res.html);
                $('.stc-mer-table-body').html(res.table_html || '');
                var total = parseInt(res.total, 10) || 0;
                $('#stc-merchant-total').text(total);
                if (total === 0) {
                  $('#stc-merchant-count').text('No merchants found.');
                  $('#stc-merchant-pageinfo').text('');
                  $('.stc-mer-table-body').html('<tr><td colspan="6" class="text-center text-muted py-4">No merchants found.</td></tr>');
                } else {
                  $('#stc-merchant-count').html('Showing <b>' + res.from + '&ndash;' + res.to + '</b> of <b>' + total + '</b> merchants');
                  $('#stc-merchant-pageinfo').text('Page ' + stcMerPage + ' of ' + stcMerTotalPages);
                }
                renderMerchantPagination(stcMerTotalPages, stcMerPage);
                stcMerLoaded = true;
              },
              error: function(xhr, status) {
                if (status === 'abort') return;
                stcMerShowError();
              }
            });
          }

          $('.stc-view-Merchant-form').on('submit', function(e){
            e.preventDefault();
            stc_search_merchants(1);
          });
          $('#searchbystcmername, #searchbystcmerskf').on('keyup input', function(){
            syncClearButtons();
          });
          $('.stc-mer-clear').on('click', function(){
            $($(this).attr('data-clear')).val('');
            syncClearButtons();
          });
          $('#stc-merchant-reset-btn').on('click', function(){
            $('#searchbystcmername, #searchbystcmerskf').val('');
            syncClearButtons();
            stcMerShowIdle();
          });
          $('#stc-merchant-pagesize').on('change', function(){
            stcMerPerPage = parseInt($(this).val(), 10) || 12;
            if (stcMerLoaded) stc_search_merchants(1);
          });
          $('.stc-mer-view-toggle button').on('click', function(){
            applyMerchantView($(this).attr('data-view'));
          });
          $(document).on('click', '.stc-mer-pg', function(){
            if ($(this).prop('disabled') || $(this).hasClass('is-current')) return;
            var page = parseInt($(this).attr('data-page'), 10) || 1;
            stc_search_merchants(page);
          });
          $(document).on('click', '.stc-mer-copy', function(e){
            e.preventDefault();
            e.stopPropagation();
            var val = $(this).attr('data-copy') || '';
            var label = $(this).attr('data-label') || 'Value';
            if (!val) return;
            if (navigator.clipboard && navigator.clipboard.writeText) {
              navigator.clipboard.writeText(val).then(function(){ stcMerToast(label + ' copied'); });
            } else {
              var $tmp = $('<textarea>').val(val).appendTo('body').select();
              document.execCommand('copy');
              $tmp.remove();
              stcMerToast(label + ' copied');
            }
          });
          $('a[href="#tab-content-2"]').on('shown.bs.tab', function(){
            syncClearButtons();
          }); 

          // call merchant
          stc_vendor_on_purchase_page();
          function stc_vendor_on_purchase_page(){
            $.ajax({
              url       : "kattegat/ragnar_purchase.php",
              method    : "post",
              data      : {friday_vendor:1},
              dataType  : 'JSON',
              success   : function(data){
                // console.log(data);
                $('.stc-select-vendor').html(data['vendor']);
              }
            });
          }

          // get item from merchant
          $('body').delegate('.stc-get-merchant-item-hit', 'click', function(e){
            e.preventDefault();
            var mer_id=$('#stc_get_merchant').val();
            $.ajax({
              url       : "kattegat/ragnar_merchant.php",
              method    : "post",
              data      : {
                stc_get_merchant_item:1,
                mer_id:mer_id
              },
              success   : function(data){
                // console.log(data);
                $('.stc-show-merchant-item-rec').html(data);
              }
            });
          });
        });
    </script>
</body>
</html>
