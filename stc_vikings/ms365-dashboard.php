<?php
require_once 'kattegat/auth_helper.php';
STCAuthHelper::checkAuth();
require_once 'kattegat/ms365_access.php';
if (!stc_ms365_user_allowed()) {
    header('Location: forbidden.html');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Microsoft 365 Dashboard - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <link rel="icon" type="image/png" href="images/stc_logo_title.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .m365-hero {
            background: linear-gradient(120deg, #0f4c81 0%, #0078d4 45%, #5b2c6f 100%);
            color: #fff;
            border-radius: 12px;
            padding: 18px 22px;
            margin-bottom: 18px;
        }
        .m365-hero h4 { margin: 0 0 4px; font-weight: 700; }
        .m365-hero p { margin: 0; opacity: .9; font-size: 13px; }
        .m365-kpi {
            border: 0;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, .08);
            overflow: hidden;
            height: 100%;
        }
        .m365-kpi .card-body { padding: 16px 18px; }
        .m365-kpi .m365-kpi-label { font-size: 12px; color: #6c757d; font-weight: 600; letter-spacing: .02em; text-transform: uppercase; }
        .m365-kpi .m365-kpi-value { font-size: 28px; font-weight: 700; line-height: 1.2; margin: 4px 0; }
        .m365-kpi .m365-kpi-sub { font-size: 12px; color: #6c757d; }
        .m365-kpi-users { border-left: 4px solid #0078d4; }
        .m365-kpi-license { border-left: 4px solid #107c10; }
        .m365-kpi-active { border-left: 4px solid #00bcf2; }
        .m365-kpi-storage { border-left: 4px solid #d83b01; }
        .m365-kpi-mail { border-left: 4px solid #ca5010; }
        .m365-kpi-teams { border-left: 4px solid #6264a7; }
        .m365-card { border: 0; border-radius: 12px; box-shadow: 0 6px 18px rgba(15, 23, 42, .08); overflow: visible; }
        .card.m365-card > .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
            height: auto !important;
            min-height: 52px;
            overflow: visible;
            background: #fff;
            border-bottom: 1px solid #eef0f3;
            font-family: inherit !important;
            font-size: 15px !important;
            font-weight: 700;
            letter-spacing: 0 !important;
            text-transform: none !important;
            color: #1f2937;
            padding: 14px 18px;
        }
        .m365-progress { height: 8px; border-radius: 99px; background: #e9ecef; overflow: hidden; }
        .m365-progress > span { display: block; height: 100%; background: linear-gradient(90deg, #0078d4, #00bcf2); }
        .m365-progress.hot > span { background: linear-gradient(90deg, #d83b01, #ffb900); }
        .m365-badge-ok, .m365-badge-off, .m365-badge-guest, .m365-badge-none {
            display: inline-block;
            border-radius: 99px;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .02em;
            vertical-align: middle;
        }
        .m365-badge-ok { background: #e6f4ea; color: #137333; }
        .m365-badge-off { background: #fce8e6; color: #c5221f; }
        .m365-badge-guest { background: #e8f0fe; color: #1967d2; }
        .m365-badge-none { background: #f1f3f4; color: #5f6368; }
        .m365-table-wrap {
            max-height: 460px;
            overflow: auto;
            scrollbar-width: thin;
            scrollbar-color: #c5cdd6 #f4f6f8;
        }
        .m365-table-wrap::-webkit-scrollbar { width: 8px; height: 8px; }
        .m365-table-wrap::-webkit-scrollbar-track { background: #f4f6f8; }
        .m365-table-wrap::-webkit-scrollbar-thumb { background: #c5cdd6; border-radius: 99px; }
        .m365-split-card {
            height: 320px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .m365-split-card > .card-body {
            flex: 1 1 auto;
            min-height: 0;
            overflow: hidden;
        }
        .m365-table-wrap.m365-table-compact {
            height: 100%;
            max-height: none;
        }
        .m365-table-compact tbody td {
            padding: 8px 12px;
        }
        .m365-section-title {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .06em;
            color: #6c757d;
            text-transform: uppercase;
            margin: 4px 0 12px;
        }
        .m365-sub-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, .08);
            padding: 16px 18px;
            height: 100%;
        }
        .m365-sub-card .m365-kpi-label { margin-bottom: 4px; }
        .m365-sub-card .m365-sub-value {
            font-size: 18px;
            font-weight: 700;
            color: #1b1f23;
            line-height: 1.3;
        }
        .m365-term-left { color: #c17a2b; font-weight: 700; font-size: 14px; }
        .m365-term-bar {
            height: 10px;
            border-radius: 99px;
            background: #e9ecef;
            overflow: hidden;
            margin: 12px 0 8px;
        }
        .m365-term-bar > span {
            display: block;
            height: 100%;
            background: #0f766e;
            border-radius: 99px;
        }
        .m365-term-dates { display: flex; justify-content: space-between; font-size: 12px; color: #6c757d; }
        .m365-clip {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 260px;
        }
        .m365-table-wrap thead th {
            position: sticky;
            top: 0;
            background: #f8fafc;
            z-index: 2;
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .03em;
            white-space: nowrap;
            border-bottom: 1px solid #e2e8f0;
            padding: 10px 14px;
        }
        .m365-table-wrap tbody td {
            padding: 12px 14px;
            vertical-align: middle;
            font-size: 13px;
            color: #1f2937;
            border-top: 1px solid #f1f5f9;
        }
        #m365-users { min-width: 1080px; width: 100%; }
        #m365-users td:nth-child(1) { min-width: 140px; font-weight: 600; }
        #m365-users td:nth-child(2) {
            max-width: 240px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: #475569;
        }
        #m365-users td:nth-child(3) { min-width: 180px; white-space: normal; }
        #m365-users td:nth-child(4),
        #m365-users td:nth-child(5) { white-space: nowrap; }
        #m365-users td:nth-child(6) { white-space: nowrap; min-width: 130px; }
        #m365-users td:nth-child(7) { white-space: nowrap; min-width: 120px; }
        .m365-users-toolbar {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            padding: 12px 16px;
            background: #fafbfc;
            border-bottom: 1px solid #eef0f3;
            position: relative;
            z-index: 4;
            overflow: visible;
        }
        .m365-users-toolbar .form-control,
        .m365-users-toolbar .custom-select {
            height: 36px;
            border-radius: 8px;
            font-size: 13px;
        }
        .m365-user-search { flex: 1 1 220px; max-width: 320px; min-width: 180px; }
        .m365-users-toolbar .custom-select { width: auto; min-width: 140px; }
        .m365-user-count {
            padding: 10px 16px;
            font-size: 12px;
            color: #8a94a6;
            border-top: 1px solid #eef0f3;
        }
        .m365-setup {
            background: #fff8e1;
            border: 1px solid #ffe082;
            border-radius: 12px;
            padding: 18px 20px;
            margin-bottom: 18px;
        }
        .m365-setup ol { margin-bottom: 0; }
        .m365-setup code { background: #fff; padding: 1px 5px; border-radius: 4px; }
        .m365-warn { font-size: 12px; color: #8a6d3b; }
        .m365-toolbar .form-control, .m365-toolbar .custom-select { height: 36px; }
        .m365-muted { color: #8a94a6; }
        .m365-chart-box { position: relative; height: 180px; }
        .m365-chart-card { overflow: hidden; }
        .m365-chart-card > .card-body { padding: 12px 16px 16px; }
        .m365-data-card {
            height: 240px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .m365-data-card > .card-body {
            flex: 1 1 auto;
            min-height: 0;
            overflow: hidden;
        }
        .m365-storage-stats {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
        }
        .m365-storage-stats .m365-stat {
            background: #f8fafc;
            border-radius: 10px;
            padding: 12px;
        }
        .m365-storage-stats .m365-stat .small { margin-bottom: 4px; }
        .m365-empty { text-align: center; padding: 28px 12px; color: #8a94a6; }
        #m365-loader {
            display: none;
            position: fixed; inset: 0; z-index: 9999;
            background: rgba(15, 23, 42, .45);
            align-items: center; justify-content: center;
        }
        #m365-loader.is-open { display: flex; }
        .m365-spinner {
            width: 48px; height: 48px;
            border: 4px solid #f3f3f3; border-top: 4px solid #0078d4;
            border-radius: 50%; animation: m365spin 1s linear infinite;
            margin: 0 auto 12px;
        }
        @keyframes m365spin { to { transform: rotate(360deg); } }
        .m365-lic-chip {
            display: inline-block;
            background: #e8f0fe;
            color: #174ea6;
            border-radius: 99px;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: 600;
            margin: 1px 4px 1px 0;
            white-space: nowrap;
        }
    </style>
</head>
<body>
<div id="m365-loader">
    <div class="text-center text-white">
        <div class="m365-spinner"></div>
        <div id="m365-loader-text">Talking to Microsoft Graph…</div>
    </div>
</div>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    <?php include_once("header-nav.php");?>
    <div class="app-main">
        <?php include_once("sidebar-nav.php");?>
        <div class="app-main__outer">
            <div class="app-main__inner">

                <div class="m365-hero d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <h4><i class="fa fa-windows"></i> Microsoft 365 analytics</h4>
                        <p>Users, licenses, OneDrive / mailbox / SharePoint space, Teams activity, and sign-ins via Microsoft Graph.</p>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <button type="button" class="btn btn-light btn-sm" id="m365-open-settings">
                            <i class="fa fa-cog"></i> Azure settings
                        </button>
                    </div>
                </div>

                <div id="m365-setup" class="m365-setup" style="display:none;">
                    <h5 class="mb-2">Connect your Microsoft 365 tenant</h5>
                    <p class="mb-2">
                        App registrations are <strong>not</strong> in the Microsoft 365 Admin Center search.
                        They live in <strong>Microsoft Entra ID</strong> (Identity).
                        Open
                        <a href="https://entra.microsoft.com/#view/Microsoft_AAD_RegisteredApps/ApplicationsListBlade" target="_blank" rel="noopener">App registrations</a>
                        or, from Admin Center search, click the <strong>Identity</strong> card.
                    </p>
                    <ol>
                        <li>Entra admin center → <strong>Applications</strong> → <strong>App registrations</strong> → <strong>New registration</strong> (Accounts in this organizational directory only).</li>
                        <li>Copy <strong>Application (client) ID</strong> and <strong>Directory (tenant) ID</strong> from the Overview page.</li>
                        <li><strong>Certificates &amp; secrets</strong> → New client secret. Copy the Value once; it will not be shown again.</li>
                        <li><strong>API permissions</strong> → Add a permission → Microsoft Graph → <strong>Application permissions</strong>:
                            <code>User.Read.All</code>, <code>Directory.Read.All</code>, <code>Organization.Read.All</code>,
                            <code>Reports.Read.All</code>, <code>Files.Read.All</code> (live OneDrive GB), and optionally <code>AuditLog.Read.All</code>.
                        </li>
                        <li>Click <strong>Grant admin consent for [your org]</strong>.</li>
                        <li>On this page, click <strong>Azure settings</strong>, paste the three values, then Save.</li>
                    </ol>
                </div>

                <div class="card m365-card mb-3">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap m365-toolbar" style="gap:10px;">
                            <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
                                <span class="mb-0 font-weight-bold">Report period</span>
                                <select id="m365-period" class="custom-select" style="width:auto;">
                                    <option value="D7">Last 7 days</option>
                                    <option value="D30" selected>Last 30 days</option>
                                    <option value="D90">Last 90 days</option>
                                </select>
                                <button type="button" class="btn btn-primary btn-sm" id="m365-refresh">
                                    <i class="fa fa-refresh"></i> Refresh
                                </button>
                            </div>
                            <div class="small m365-muted" id="m365-updated">Not loaded yet</div>
                        </div>
                        <div class="m365-warn mt-2" id="m365-warnings" style="display:none;"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-xl-2 mb-3">
                        <div class="card m365-kpi m365-kpi-users">
                            <div class="card-body">
                                <div class="m365-kpi-label">Total users</div>
                                <div class="m365-kpi-value" id="kpi-total">—</div>
                                <div class="m365-kpi-sub"><span id="kpi-guests">0</span> guests</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xl-2 mb-3">
                        <div class="card m365-kpi m365-kpi-license">
                            <div class="card-body">
                                <div class="m365-kpi-label">Licensed</div>
                                <div class="m365-kpi-value" id="kpi-licensed">—</div>
                                <div class="m365-kpi-sub"><span id="kpi-unlicensed">0</span> unlicensed</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xl-2 mb-3">
                        <div class="card m365-kpi m365-kpi-active">
                            <div class="card-body">
                                <div class="m365-kpi-label">Active</div>
                                <div class="m365-kpi-value" id="kpi-active">—</div>
                                <div class="m365-kpi-sub"><span id="kpi-inactive">0</span> inactive in period</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xl-2 mb-3">
                        <div class="card m365-kpi m365-kpi-storage">
                            <div class="card-body">
                                <div class="m365-kpi-label">OneDrive used</div>
                                <div class="m365-kpi-value" id="kpi-od-used">—</div>
                                <div class="m365-kpi-sub">of <span id="kpi-od-alloc">—</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xl-2 mb-3">
                        <div class="card m365-kpi m365-kpi-mail">
                            <div class="card-body">
                                <div class="m365-kpi-label">Mailbox used</div>
                                <div class="m365-kpi-value" id="kpi-mb-used">—</div>
                                <div class="m365-kpi-sub"><span id="kpi-mb-count">0</span> mailboxes</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xl-2 mb-3">
                        <div class="card m365-kpi m365-kpi-teams">
                            <div class="card-body">
                                <div class="m365-kpi-label">Teams meetings</div>
                                <div class="m365-kpi-value" id="kpi-meetings">—</div>
                                <div class="m365-kpi-sub"><span id="kpi-messages">0</span> messages</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="m365-section-title">Subscription details</div>
                <div class="row">
                    <div class="col-md-6 col-xl-3 mb-3">
                        <div class="m365-sub-card">
                            <div class="m365-kpi-label">Purchase date</div>
                            <div class="m365-sub-value" id="sub-purchase">—</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-3">
                        <div class="m365-sub-card">
                            <div class="m365-kpi-label">Expiry date</div>
                            <div class="m365-sub-value" id="sub-expiry">—</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-3">
                        <div class="m365-sub-card">
                            <div class="m365-kpi-label">Plan</div>
                            <div class="m365-sub-value" id="sub-plan">—</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-3">
                        <div class="m365-sub-card">
                            <div class="m365-kpi-label">Purchased quantity</div>
                            <div class="m365-sub-value" id="sub-qty">—</div>
                        </div>
                    </div>
                </div>
                <div class="m365-section-title">Time remaining</div>
                <div class="card m365-card mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <strong>Subscription term</strong>
                            <span class="m365-term-left" id="sub-left">—</span>
                        </div>
                        <div class="m365-term-bar"><span id="sub-bar" style="width:0%"></span></div>
                        <div class="m365-term-dates">
                            <span id="sub-start">—</span>
                            <span id="sub-end">—</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 mb-3">
                        <div class="card m365-card m365-chart-card">
                            <div class="card-header">License usage</div>
                            <div class="card-body">
                                <div class="m365-chart-box"><canvas id="m365-license-chart"></canvas></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 mb-3">
                        <div class="card m365-card m365-chart-card">
                            <div class="card-header">Storage used vs allocated</div>
                            <div class="card-body">
                                <div class="m365-chart-box"><canvas id="m365-storage-chart"></canvas></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 mb-3">
                        <div class="card m365-card m365-data-card">
                            <div class="card-header">License breakdown</div>
                            <div class="card-body p-0">
                                <div class="table-responsive m365-table-wrap m365-table-compact">
                                    <table class="table table-sm table-hover mb-0" id="m365-license-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>License</th>
                                                <th class="text-right">Purchased</th>
                                                <th class="text-right">Assigned</th>
                                                <th class="text-right">Available</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 mb-3">
                        <div class="card m365-card m365-data-card">
                            <div class="card-header">Storage summary</div>
                            <div class="card-body">
                                <div class="m365-storage-stats">
                                    <div class="m365-stat">
                                        <div class="small text-muted">OneDrive</div>
                                        <div class="font-weight-bold" id="st-od">—</div>
                                    </div>
                                    <div class="m365-stat">
                                        <div class="small text-muted">SharePoint</div>
                                        <div class="font-weight-bold" id="st-sp">—</div>
                                    </div>
                                    <div class="m365-stat">
                                        <div class="small text-muted">Mailboxes</div>
                                        <div class="font-weight-bold" id="st-mb">—</div>
                                    </div>
                                </div>
                                <div class="mt-3 small text-muted">
                                    Users at ≥ 80% OneDrive quota: <strong id="st-hot">0</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 mb-3">
                        <div class="card m365-card m365-split-card">
                            <div class="card-header">Top users by OneDrive space</div>
                            <div class="card-body p-0">
                                <div class="table-responsive m365-table-wrap m365-table-compact">
                                    <table class="table table-sm table-hover mb-0" id="m365-top-storage">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>User</th>
                                                <th>Used / allocated</th>
                                                <th style="width:28%">Usage</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 mb-3">
                        <div class="card m365-card m365-split-card">
                            <div class="card-header">SharePoint sites</div>
                            <div class="card-body p-0">
                                <div class="table-responsive m365-table-wrap m365-table-compact">
                                    <table class="table table-sm table-hover mb-0" id="m365-sites">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Site</th>
                                                <th>Used</th>
                                                <th>Files</th>
                                                <th>Last activity</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card m365-card mb-3">
                    <div class="card-header">Active users</div>
                    <div class="m365-users-toolbar">
                        <input type="text" class="form-control form-control-sm m365-user-search" id="m365-user-q" placeholder="Search display name or username">
                        <select id="m365-user-lic" class="custom-select custom-select-sm">
                            <option value="all">All licenses</option>
                            <option value="licensed">Licensed</option>
                            <option value="unlicensed">Unlicensed</option>
                        </select>
                        <select id="m365-user-type" class="custom-select custom-select-sm">
                            <option value="all">All types</option>
                            <option value="Member">Members</option>
                            <option value="Guest">Guests</option>
                        </select>
                        <button type="button" class="btn btn-sm btn-outline-success ml-auto" id="m365-export">
                            <i class="fa fa-file-excel-o"></i> Export
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive m365-table-wrap">
                            <table class="table table-sm table-hover mb-0" id="m365-users">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Display name</th>
                                        <th>Username</th>
                                        <th>Licenses</th>
                                        <th>OneDrive</th>
                                        <th>Mailbox</th>
                                        <th>Last activity</th>
                                        <th>Sign-in</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="m365-user-count" id="m365-user-count"></div>
                    </div>
                </div>

                <div class="card m365-card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Recent sign-ins</span>
                        <span class="small text-muted">
                            Success <strong id="si-ok">0</strong> · Failed <strong id="si-fail">0</strong>
                            <span class="text-muted">(this sample)</span>
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive m365-table-wrap">
                            <table class="table table-sm table-hover mb-0" id="m365-signins">
                                <thead class="thead-light">
                                    <tr>
                                        <th>When</th>
                                        <th>User</th>
                                        <th>Status</th>
                                        <th>Location</th>
                                        <th>IP</th>
                                        <th>Browser / OS</th>
                                        <th>App</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="m365SettingsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Azure app registration</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted">
                    Create the app in
                    <a href="https://entra.microsoft.com/#view/Microsoft_AAD_RegisteredApps/ApplicationsListBlade" target="_blank" rel="noopener">Microsoft Entra → App registrations</a>
                    (not in Microsoft 365 Admin Center). Credentials are stored on this server in <code>kattegat/ms365_data/</code>. The client secret is never shown in full after save.
                </p>
                <div class="form-group">
                    <span class="d-block font-weight-bold mb-1">Directory (tenant) ID</span>
                    <input type="text" class="form-control" id="m365-tenant" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" autocomplete="off">
                </div>
                <div class="form-group">
                    <span class="d-block font-weight-bold mb-1">Application (client) ID</span>
                    <input type="text" class="form-control" id="m365-client" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" autocomplete="off">
                </div>
                <div class="form-group">
                    <span class="d-block font-weight-bold mb-1">Client secret</span>
                    <input type="password" class="form-control" id="m365-secret" placeholder="Leave blank to keep the existing secret" autocomplete="new-password">
                    <small class="form-text text-muted" id="m365-secret-hint"></small>
                </div>
                <div class="alert alert-info mb-0" style="font-size:13px;">
                    Required Graph application permissions: User.Read.All, Directory.Read.All, Organization.Read.All, Reports.Read.All, Files.Read.All (live OneDrive usage).
                    Sign-in analytics also needs AuditLog.Read.All. Grant admin consent after adding them.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="m365-save-settings">Save and connect</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="assets/vendor/bootstrap/js/popper.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
<script type="text/javascript" src="./assets/scripts/sidebar-persist.js"></script>
<script type="text/javascript" src="./assets/scripts/main.js"></script>
<script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    var dash = null;
    var licenseChart = null;
    var storageChart = null;

    function loader(on, text) {
        $('#m365-loader-text').text(text || 'Talking to Microsoft Graph…');
        $('#m365-loader').toggleClass('is-open', !!on);
    }
    function esc(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
    function dashVal(n) { return n === 0 || n ? n : '—'; }
    function fmtWhen(s) {
        if (!s) return '—';
        var t = String(s).trim();
        var m = t.match(/^(\d{4})-(\d{2})-(\d{2})[T ](\d{2}):(\d{2})/);
        if (m) {
            var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            return m[3] + ' ' + months[Number(m[2]) - 1] + ' ' + m[1] + ', ' + m[4] + ':' + m[5];
        }
        m = t.match(/^(\d{4})-(\d{2})-(\d{2})$/);
        if (m) {
            var months2 = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            return m[3] + ' ' + months2[Number(m[2]) - 1] + ' ' + m[1];
        }
        return t.replace('T', ' ').replace(/Z$/, '');
    }

    function api(data) {
        return $.ajax({
            url: 'kattegat/ragnar_ms365.php',
            method: 'POST',
            dataType: 'JSON',
            data: data
        });
    }

    function applyStatus(st) {
        $('#m365-setup').toggle(!st.configured);
        $('#m365-tenant').val(st.tenant_id || '');
        $('#m365-client').val(st.client_id || '');
        $('#m365-secret').val('');
        if (st.secret_set) {
            $('#m365-secret-hint').text('Saved secret: ' + (st.secret_hint || '••••'));
        } else {
            $('#m365-secret-hint').text('No secret saved yet.');
        }
        return !!st.configured;
    }

    function renderKpis(d) {
        var u = d.users || {};
        var s = d.storage || {};
        var t = d.teams || {};
        $('#kpi-total').text(dashVal(u.total));
        $('#kpi-guests').text(u.guests || 0);
        $('#kpi-licensed').text(dashVal(u.licensed));
        $('#kpi-unlicensed').text(u.unlicensed || 0);
        $('#kpi-active').text(dashVal(u.active));
        $('#kpi-inactive').text(u.inactive || 0);
        $('#kpi-od-used').text(s.onedriveUsed || '—');
        $('#kpi-od-alloc').text(s.onedriveAllocated || '—');
        $('#kpi-mb-used').text(s.mailboxUsed || '—');
        $('#kpi-mb-count').text(s.mailboxCount || 0);
        $('#kpi-meetings').text(dashVal(t.meetings));
        $('#kpi-messages').text(t.messages || 0);
        $('#st-od').text((s.onedriveUsed || '0 B') + ' / ' + (s.onedriveAllocated || '0 B'));
        $('#st-sp').text((s.sharepointUsed || '0 B') + ' / ' + (s.sharepointAllocated || '0 B'));
        $('#st-mb').text(s.mailboxUsed || '0 B');
        $('#st-hot').text(u.storageHot || 0);
        renderSubscription(d.subscription);
    }

    function renderSubscription(sub) {
        if (!sub) {
            $('#sub-purchase, #sub-expiry, #sub-plan, #sub-qty, #sub-left, #sub-start, #sub-end').text('—');
            $('#sub-bar').css('width', '0%');
            return;
        }
        $('#sub-purchase').text(sub.purchaseDate || '—');
        $('#sub-expiry').text(sub.expiryDate || '—');
        $('#sub-plan').text(sub.name || sub.skuPartNumber || '—');
        $('#sub-qty').text(sub.totalLicenses ? (sub.totalLicenses + ' licenses') : '—');
        var days = sub.daysLeft;
        var left = '—';
        if (days != null && days !== '') {
            if (days < 0) {
                left = 'Expired';
            } else {
                var months = Math.round((days / 30) * 10) / 10;
                left = '~' + days + ' days left (~' + months + ' months)';
            }
        }
        $('#sub-left').text(left);
        $('#sub-bar').css('width', (sub.elapsedPercent || 0) + '%');
        $('#sub-start').text(sub.purchaseDate || '—');
        $('#sub-end').text(sub.expiryDate || '—');
    }

    function renderLicenses(list) {
        var tb = $('#m365-license-table tbody').empty();
        if (!list || !list.length) {
            tb.append('<tr><td colspan="4" class="m365-empty">No license SKUs returned.</td></tr>');
        } else {
            list.forEach(function (row) {
                tb.append(
                    '<tr><td>' + esc(row.name) +
                    '<div class="small text-muted">' + esc(row.skuPartNumber) + '</div></td>' +
                    '<td class="text-right">' + row.purchased + '</td>' +
                    '<td class="text-right">' + row.assigned + '</td>' +
                    '<td class="text-right">' + row.available + '</td></tr>'
                );
            });
        }
        var labels = (list || []).map(function (r) { return r.name; });
        var assigned = (list || []).map(function (r) { return r.assigned; });
        var available = (list || []).map(function (r) { return r.available; });
        if (licenseChart) licenseChart.destroy();
        licenseChart = new Chart(document.getElementById('m365-license-chart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Assigned', data: assigned, backgroundColor: '#0078d4' },
                    { label: 'Available', data: available, backgroundColor: '#a6d8ff' }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: { x: { ticks: { maxRotation: 40, minRotation: 0, font: { size: 10 } } }, y: { beginAtZero: true } }
            }
        });
    }

    function gb(bytes) {
        return Math.round((Number(bytes) || 0) / (1024 * 1024 * 1024) * 100) / 100;
    }

    function renderStorageChart(s) {
        if (storageChart) storageChart.destroy();
        storageChart = new Chart(document.getElementById('m365-storage-chart'), {
            type: 'bar',
            data: {
                labels: ['OneDrive', 'SharePoint', 'Mailbox'],
                datasets: [
                    { label: 'Used (GB)', data: [gb(s.onedriveUsedBytes), gb(s.sharepointUsedBytes), gb(s.mailboxUsedBytes)], backgroundColor: '#d83b01' },
                    { label: 'Allocated (GB)', data: [gb(s.onedriveAllocatedBytes), gb(s.sharepointAllocatedBytes), 0], backgroundColor: '#c8c8c8' }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    function renderTopStorage(rows) {
        var tb = $('#m365-top-storage tbody').empty();
        if (!rows || !rows.length) {
            tb.append('<tr><td colspan="3" class="m365-empty">No OneDrive usage yet. Reports.Read.All is required; usage can lag 24–48 hours.</td></tr>');
            return;
        }
        rows.forEach(function (u) {
            var pct = Number(u.onedrivePercent) || 0;
            var hot = pct >= 80 ? ' hot' : '';
            tb.append(
                '<tr><td><strong>' + esc(u.displayName) + '</strong><div class="small text-muted m365-clip" title="' + esc(u.userPrincipalName) + '">' + esc(u.userPrincipalName) + '</div></td>' +
                '<td class="text-nowrap">' + esc(u.onedriveUsed) + ' / ' + esc(u.onedriveAllocated) + '</td>' +
                '<td><div class="m365-progress' + hot + '"><span style="width:' + Math.min(100, pct) + '%"></span></div>' +
                '<div class="small text-muted mt-1">' + pct + '%</div></td></tr>'
            );
        });
    }

    function renderSites(rows) {
        var tb = $('#m365-sites tbody').empty();
        if (!rows || !rows.length) {
            tb.append('<tr><td colspan="4" class="m365-empty">No SharePoint site usage returned.</td></tr>');
            return;
        }
        rows.forEach(function (s) {
            var url = s.siteUrl || '';
            var label = 'Site';
            if (url) {
                label = url.replace(/\/+$/, '').split('/').pop() || url;
                try { label = decodeURIComponent(label); } catch (e) {}
            }
            var sub = s.owner || url;
            tb.append(
                '<tr><td><div class="font-weight-bold m365-clip" title="' + esc(url || label) + '">' + esc(label) + '</div>' +
                '<div class="small text-muted m365-clip" title="' + esc(sub) + '">' + esc(sub) + '</div></td>' +
                '<td class="text-nowrap">' + esc(s.used) + '</td><td>' + (s.files || 0) + '</td>' +
                '<td class="text-nowrap">' + esc(fmtWhen(s.lastActivity)) + '</td></tr>'
            );
        });
    }

    function renderUsers() {
        var q = ($('#m365-user-q').val() || '').toLowerCase();
        var lic = $('#m365-user-lic').val();
        var typ = $('#m365-user-type').val();
        var rows = (dash && dash.userList) ? dash.userList : [];
        var tb = $('#m365-users tbody').empty();
        var shown = 0;
        rows.forEach(function (u) {
            if (lic === 'licensed' && !u.licensed) return;
            if (lic === 'unlicensed' && u.licensed) return;
            if (typ !== 'all' && u.userType !== typ) return;
            var blob = ((u.displayName || '') + ' ' + (u.userPrincipalName || '') + ' ' + (u.mail || '')).toLowerCase();
            if (q && blob.indexOf(q) === -1) return;
            shown++;
            var chips = (u.licenses && u.licenses.length)
                ? u.licenses.map(function (n) { return '<span class="m365-lic-chip">' + esc(n) + '</span>'; }).join(' ')
                : '<span class="badge m365-badge-none">Unlicensed</span>';
            var sign = u.accountEnabled
                ? '<span class="badge m365-badge-ok">Allowed</span>'
                : '<span class="badge m365-badge-off">Blocked</span>';
            if (u.userType === 'Guest') {
                sign += ' <span class="badge m365-badge-guest">Guest</span>';
            }
            var od = (u.onedriveUsed || '0 B') + ' / ' + (u.onedriveAllocated || '0 B');
            var upn = u.userPrincipalName || '';
            tb.append(
                '<tr><td>' + esc(u.displayName) + '</td>' +
                '<td title="' + esc(upn) + '">' + esc(upn) + '</td>' +
                '<td>' + chips + '</td>' +
                '<td>' + esc(od) + '<div class="small text-muted">' + (u.onedrivePercent || 0) + '%</div></td>' +
                '<td>' + esc(u.mailboxUsed || '—') + '</td>' +
                '<td>' + esc(fmtWhen(u.lastActivity)) + '</td>' +
                '<td>' + sign + '</td></tr>'
            );
        });
        if (!shown) {
            tb.append('<tr><td colspan="7" class="m365-empty">No users match the current filter.</td></tr>');
        }
        $('#m365-user-count').text(shown + ' of ' + rows.length + ' users');
    }

    function renderSignins(si) {
        si = si || { success: 0, failed: 0, recent: [] };
        $('#si-ok').text(si.success || 0);
        $('#si-fail').text(si.failed || 0);
        var tb = $('#m365-signins tbody').empty();
        if (!si.recent || !si.recent.length) {
            tb.append('<tr><td colspan="7" class="m365-empty">No sign-in logs. Grant AuditLog.Read.All and admin consent, or Entra ID P1 may be required.</td></tr>');
            return;
        }
        si.recent.forEach(function (r) {
            var badge = r.success
                ? '<span class="badge m365-badge-ok">Success</span>'
                : '<span class="badge m365-badge-off">Failed</span>';
            var loc = [r.city, r.country].filter(Boolean).join(', ') || '—';
            var when = fmtWhen(r.when);
            tb.append(
                '<tr><td>' + esc(when) + '</td><td>' + esc(r.user) +
                '<div class="small text-muted">' + esc(r.upn) + '</div></td>' +
                '<td>' + badge + '</td><td>' + esc(loc) + '</td><td>' + esc(r.ip || '—') + '</td>' +
                '<td>' + esc((r.browser || '—') + ' / ' + (r.os || '—')) + '</td>' +
                '<td>' + esc(r.app || '—') + '</td></tr>'
            );
        });
    }

    function renderAll(payload) {
        dash = payload.data || {};
        renderKpis(dash);
        renderLicenses(dash.licenses || []);
        renderStorageChart(dash.storage || {});
        renderTopStorage(dash.topStorage || []);
        renderSites(dash.sharepointSites || []);
        renderUsers();
        renderSignins(dash.signIns);
        var warn = dash.warnings || [];
        if (warn.length) {
            $('#m365-warnings').show().html(warn.map(esc).join('<br>'));
        } else {
            $('#m365-warnings').hide().empty();
        }
        var stamp = payload.fetched_at ? payload.fetched_at.replace('T', ' ') : '';
        var cacheNote = payload.from_cache ? ' (cached)' : '';
        $('#m365-updated').text(stamp ? ('Updated ' + stamp + cacheNote) : 'Updated just now');
    }

    function loadDashboard(force) {
        loader(true, force ? 'Refreshing Microsoft Graph reports…' : 'Loading Microsoft 365 data…');
        api({
            action: 'dashboard',
            period: $('#m365-period').val(),
            force: force ? 1 : 0
        }).done(function (res) {
            if (!res || !res.success) {
                alert((res && res.message) || 'Could not load Microsoft 365 data.');
                if (res && res.configured === false) applyStatus({ configured: false });
                return;
            }
            applyStatus({ configured: true, tenant_id: $('#m365-tenant').val(), client_id: $('#m365-client').val(), secret_set: true });
            $('#m365-setup').hide();
            renderAll(res);
        }).fail(function () {
            alert('Request failed. Check PHP cURL and that kattegat/ragnar_ms365.php is reachable.');
        }).always(function () {
            loader(false);
        });
    }

    $('#m365-open-settings').on('click', function () {
        $('#m365SettingsModal').modal('show');
    });
    $('#m365-save-settings').on('click', function () {
        loader(true, 'Saving credentials and requesting a Graph token…');
        api({
            action: 'save_config',
            tenant_id: $('#m365-tenant').val(),
            client_id: $('#m365-client').val(),
            client_secret: $('#m365-secret').val()
        }).done(function (res) {
            if (!res || !res.success) {
                alert((res && res.message) || 'Save failed.');
                return;
            }
            applyStatus(res);
            $('#m365SettingsModal').modal('hide');
            loadDashboard(true);
        }).fail(function () {
            alert('Could not save settings.');
        }).always(function () {
            loader(false);
        });
    });
    $('#m365-refresh').on('click', function () { loadDashboard(true); });
    $('#m365-period').on('change', function () { loadDashboard(true); });
    $('#m365-user-q, #m365-user-lic, #m365-user-type').on('keyup change', renderUsers);
    $('#m365-export').on('click', function () {
        var rows = (dash && dash.userList) ? dash.userList : [];
        var csv = ['Display name,Username,Licenses,OneDrive used,OneDrive allocated,Usage %,Mailbox,Last activity,Sign-in,Type'];
        rows.forEach(function (u) {
            csv.push([
                u.displayName, u.userPrincipalName, (u.licenses || []).join('; '),
                u.onedriveUsed, u.onedriveAllocated, u.onedrivePercent,
                u.mailboxUsed, u.lastActivity, u.accountEnabled ? 'Allowed' : 'Blocked', u.userType
            ].map(function (v) {
                return '"' + String(v == null ? '' : v).replace(/"/g, '""') + '"';
            }).join(','));
        });
        var blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
        var a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'm365-users.csv';
        a.click();
    });

    loader(true, 'Checking Microsoft 365 connection…');
    api({ action: 'status' }).done(function (res) {
        var configured = applyStatus(res || {});
        if (configured) {
            loadDashboard(false);
        } else {
            loader(false);
        }
    }).fail(function () {
        loader(false);
        $('#m365-setup').show();
    });
})();
</script>
</body>
</html>
