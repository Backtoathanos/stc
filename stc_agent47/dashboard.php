<?php
session_start();

// Set session and cookie parameters
$session_duration = 24 * 60; // 24 minutes (cPanel limit)
$cookie_duration = 7 * 24 * 60 * 60; // 7 days in seconds

// If session exists
if(isset($_SESSION["stc_agent_id"])) {
    // Check if remember cookie exists, if not set it
    if(!isset($_COOKIE["stc_agent_remember"])) {
        setcookie("stc_agent_remember", $_SESSION["stc_agent_id"], time() + $cookie_duration, "/");
        setcookie("stc_agent_name", $_SESSION["stc_agent_name"], time() + $cookie_duration, "/");
        setcookie("stc_agent_role", $_SESSION["stc_agent_role"], time() + $cookie_duration, "/");
    }
} 
// If session doesn't exist but cookie does
elseif(isset($_COOKIE["stc_agent_remember"])) {
    // Restore session from cookie
    $_SESSION["stc_agent_id"] = $_COOKIE["stc_agent_remember"];
    $_SESSION["stc_agent_name"] = $_COOKIE["stc_agent_name"];
    $_SESSION["stc_agent_role"] = $_COOKIE["stc_agent_role"];
    // Optionally refresh the cookie
    setcookie("stc_agent_remember", $_COOKIE["stc_agent_remember"], time() + $cookie_duration, "/");
    setcookie("stc_agent_name", $_COOKIE["stc_agent_name"], time() + $cookie_duration, "/");
    setcookie("stc_agent_role", $_COOKIE["stc_agent_role"], time() + $cookie_duration, "/");
}
// Neither session nor cookie exists
else {
    header("Location: index.html");
    exit();
}
$stc_agent_name = isset($_SESSION['stc_agent_name']) ? $_SESSION['stc_agent_name'] : 'Associate';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Dashboard - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .stc-dash-welcome {
            background: #ffffff;
            border: 1px solid #e5eaf3;
            border-left: 6px solid #3f6ad8;
            border-radius: 14px;
            padding: 22px 26px;
            color: #1f2937;
            margin-bottom: 22px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
        }
        .stc-dash-welcome h4 {
            margin: 0 0 6px;
            font-weight: 700;
            font-size: 22px;
            color: #1f2937 !important;
        }
        .stc-dash-welcome p {
            margin: 0;
            color: #4b5563 !important;
            opacity: 1;
        }
        .stc-dash-section-title {
            font-size: 15px;
            font-weight: 700;
            color: #3d4465;
            margin: 8px 0 14px;
            letter-spacing: .02em;
            text-transform: uppercase;
        }
        .stc-dash-card,
        .stc-dash-req-card {
            display: block;
            background: #fff;
            border: 1px solid #eef1f6;
            border-radius: 14px;
            text-decoration: none !important;
            color: inherit;
            height: 100%;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
            transition: transform .16s ease, box-shadow .16s ease;
        }
        .stc-dash-card:hover,
        .stc-dash-req-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.1);
        }
        .stc-dash-card {
            padding: 20px 22px;
        }
        .stc-dash-card-top {
            display: flex;
            align-items: center;
        }
        .stc-dash-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-right: 16px;
            flex-shrink: 0;
        }
        .stc-dash-label {
            font-size: 13px;
            color: #6b7280;
            font-weight: 600;
        }
        .stc-dash-number {
            font-size: 30px;
            line-height: 1.15;
            font-weight: 700;
            color: #1f2937;
        }
        .stc-dash-note {
            margin-top: 10px;
            font-size: 12px;
            color: #94a3b8;
        }
        .stc-dash-req-card {
            padding: 16px 18px 18px;
            position: relative;
            overflow: hidden;
        }
        .stc-dash-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .stc-dash-req-label {
            font-size: 13px;
            color: #6b7280;
            font-weight: 600;
        }
        .stc-dash-req-number {
            font-size: 26px;
            font-weight: 700;
            color: #1f2937;
            margin-top: 2px;
        }
        .stc-dash-card,
        .stc-dash-req-card {
            cursor: pointer;
            border: 0;
            width: 100%;
            text-align: left;
        }
        .stc-dash-modal.modal {
            z-index: 2050;
        }
        .stc-dash-modal.modal.fade.in,
        .stc-dash-modal.modal.fade.show {
            opacity: 1;
        }
        .stc-dash-modal .modal-dialog {
            max-width: 980px;
            margin: 80px auto;
        }
        .stc-dash-modal .modal-content {
            border-radius: 14px;
            border: 0;
            background: #fff;
            opacity: 1;
            box-shadow: 0 18px 48px rgba(15, 23, 42, 0.22);
        }
        .modal-backdrop {
            z-index: 2040;
        }
        .modal-backdrop.fade.in,
        .modal-backdrop.show {
            opacity: .5;
        }
        .stc-dash-modal .table th {
            white-space: nowrap;
            background: #f8fafc;
        }
        .stc-dash-empty {
            color: #94a3b8;
            padding: 24px 0;
        }
        .stc-dash-chart-card {
            background: #fff;
            border: 1px solid #eef1f6;
            border-radius: 14px;
            padding: 18px 18px 10px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
            height: 100%;
        }
        .stc-dash-chart-card h6 {
            font-weight: 700;
            color: #3d4465;
            margin-bottom: 12px;
        }
        .stc-dash-chart-wrap {
            position: relative;
            height: 320px;
        }
    </style>
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php include_once("header-nav.php");?>
        <?php include_once("ui-setting.php");?>        
        <div class="app-main">
                <?php include_once("sidebar-nav.php");?>                   
                <div class="app-main__outer">
                    <div class="app-main__inner">
                        <div class="stc-dash-welcome">
                            <h4>Welcome back, <?php echo htmlspecialchars($stc_agent_name, ENT_QUOTES, 'UTF-8'); ?></h4>
                            <p>Overview of projects, safety, procurement, requisitions and job status.</p>
                        </div>

                        <div class="stc-dash-section-title">Overview</div>
                        <div class="row">
                            <div class="col-md-4 col-xl-4 col-sm-12 mb-3">
                                <button type="button" class="stc-dash-card stc-dash-open" data-type="projects" data-title="Projects">
                                    <div class="stc-dash-card-top">
                                        <div class="stc-dash-icon" style="background:#3f6ad8;"><i class="fa fa-briefcase"></i></div>
                                        <div class="stc-dash-meta">
                                            <div class="stc-dash-label">Projects</div>
                                            <div class="stc-dash-number" data-count="projects"><i class="fa fa-spinner fa-spin"></i></div>
                                        </div>
                                    </div>
                                    <div class="stc-dash-note">Own and collaborated projects</div>
                                </button>
                            </div>
                            <div class="col-md-4 col-xl-4 col-sm-12 mb-3">
                                <button type="button" class="stc-dash-card stc-dash-open" data-type="supervisors" data-title="Supervisors">
                                    <div class="stc-dash-card-top">
                                        <div class="stc-dash-icon" style="background:#16aaff;"><i class="fa fa-user"></i></div>
                                        <div class="stc-dash-meta">
                                            <div class="stc-dash-label">Users</div>
                                            <div class="stc-dash-number" data-count="supervisors"><i class="fa fa-spinner fa-spin"></i></div>
                                        </div>
                                    </div>
                                    <div class="stc-dash-note">Users created by you</div>
                                </button>
                            </div>
                            <div class="col-md-4 col-xl-4 col-sm-12 mb-3">
                                <button type="button" class="stc-dash-card stc-dash-open" data-type="collab_supervisors" data-title="Collaborated Supervisors">
                                    <div class="stc-dash-card-top">
                                        <div class="stc-dash-icon" style="background:#794c8a;"><i class="fa fa-users"></i></div>
                                        <div class="stc-dash-meta">
                                            <div class="stc-dash-label">Collaborated Users</div>
                                            <div class="stc-dash-number" data-count="collab_supervisors"><i class="fa fa-spinner fa-spin"></i></div>
                                        </div>
                                    </div>
                                    <div class="stc-dash-note">Users shared with you</div>
                                </button>
                            </div>
                        </div>

                        <div class="stc-dash-section-title" style="margin-top:10px;">Requisitions</div>
                        <div class="row">
                            <div class="col-md-3 col-xl-3 col-sm-6 mb-3">
                                <button type="button" class="stc-dash-req-card stc-dash-open" data-type="req_total" data-title="Total Requisitions">
                                    <span class="stc-dash-dot" style="background:#343a40;"></span>
                                    <div class="stc-dash-req-label">Total Requisitions</div>
                                    <div class="stc-dash-req-number" data-count="req_total"><i class="fa fa-spinner fa-spin"></i></div>
                                </button>
                            </div>
                            <div class="col-md-3 col-xl-3 col-sm-6 mb-3">
                                <button type="button" class="stc-dash-req-card stc-dash-open" data-type="req_ordered" data-title="Ordered Requisitions">
                                    <span class="stc-dash-dot" style="background:#3498db;"></span>
                                    <div class="stc-dash-req-label">Ordered</div>
                                    <div class="stc-dash-req-number" data-count="req_ordered"><i class="fa fa-spinner fa-spin"></i></div>
                                </button>
                            </div>
                            <div class="col-md-3 col-xl-3 col-sm-6 mb-3">
                                <button type="button" class="stc-dash-req-card stc-dash-open" data-type="req_approved" data-title="Approved Requisitions">
                                    <span class="stc-dash-dot" style="background:#2ecc71;"></span>
                                    <div class="stc-dash-req-label">Approved</div>
                                    <div class="stc-dash-req-number" data-count="req_approved"><i class="fa fa-spinner fa-spin"></i></div>
                                </button>
                            </div>
                            <div class="col-md-3 col-xl-3 col-sm-6 mb-3">
                                <button type="button" class="stc-dash-req-card stc-dash-open" data-type="req_accepted" data-title="Accepted Requisitions">
                                    <span class="stc-dash-dot" style="background:#27ae60;"></span>
                                    <div class="stc-dash-req-label">Accepted</div>
                                    <div class="stc-dash-req-number" data-count="req_accepted"><i class="fa fa-spinner fa-spin"></i></div>
                                </button>
                            </div>
                            <div class="col-md-3 col-xl-3 col-sm-6 mb-3">
                                <button type="button" class="stc-dash-req-card stc-dash-open" data-type="req_dispatched" data-title="Dispatched Requisitions">
                                    <span class="stc-dash-dot" style="background:#f39c12;"></span>
                                    <div class="stc-dash-req-label">Dispatched</div>
                                    <div class="stc-dash-req-number" data-count="req_dispatched"><i class="fa fa-spinner fa-spin"></i></div>
                                </button>
                            </div>
                            <div class="col-md-3 col-xl-3 col-sm-6 mb-3">
                                <button type="button" class="stc-dash-req-card stc-dash-open" data-type="req_received" data-title="Received Requisitions">
                                    <span class="stc-dash-dot" style="background:#16a085;"></span>
                                    <div class="stc-dash-req-label">Received</div>
                                    <div class="stc-dash-req-number" data-count="req_received"><i class="fa fa-spinner fa-spin"></i></div>
                                </button>
                            </div>
                            <div class="col-md-3 col-xl-3 col-sm-6 mb-3">
                                <button type="button" class="stc-dash-req-card stc-dash-open" data-type="req_pending" data-title="Pending Requisitions">
                                    <span class="stc-dash-dot" style="background:#ff2f2f;"></span>
                                    <div class="stc-dash-req-label">Pending</div>
                                    <div class="stc-dash-req-number" data-count="req_pending"><i class="fa fa-spinner fa-spin"></i></div>
                                </button>
                            </div>
                            <div class="col-md-3 col-xl-3 col-sm-6 mb-3">
                                <button type="button" class="stc-dash-req-card stc-dash-open" data-type="req_canceled" data-title="Canceled / Rejected Requisitions">
                                    <span class="stc-dash-dot" style="background:#95a5a6;"></span>
                                    <div class="stc-dash-req-label">Canceled / Rejected</div>
                                    <div class="stc-dash-req-number" data-count="req_canceled"><i class="fa fa-spinner fa-spin"></i></div>
                                </button>
                            </div>
                        </div>

                        <div class="stc-dash-section-title" style="margin-top:10px;">Safety</div>
                        <div class="row">
                            <div class="col-md-4 col-xl-4 col-sm-12 mb-3">
                                <button type="button" class="stc-dash-card stc-dash-open" data-type="safety_tbm" data-title="Tool Box Meetings">
                                    <div class="stc-dash-card-top">
                                        <div class="stc-dash-icon" style="background:#0ea5e9;"><i class="fa fa-users"></i></div>
                                        <div class="stc-dash-meta">
                                            <div class="stc-dash-label">TBT / TBM</div>
                                            <div class="stc-dash-number" data-count="safety_tbm"><i class="fa fa-spinner fa-spin"></i></div>
                                        </div>
                                    </div>
                                    <div class="stc-dash-note">Tool box meetings</div>
                                </button>
                            </div>
                            <div class="col-md-4 col-xl-4 col-sm-12 mb-3">
                                <button type="button" class="stc-dash-card stc-dash-open" data-type="safety_ppec" data-title="PPEC">
                                    <div class="stc-dash-card-top">
                                        <div class="stc-dash-icon" style="background:#14b8a6;"><i class="fa fa-shield"></i></div>
                                        <div class="stc-dash-meta">
                                            <div class="stc-dash-label">PPEC</div>
                                            <div class="stc-dash-number" data-count="safety_ppec"><i class="fa fa-spinner fa-spin"></i></div>
                                        </div>
                                    </div>
                                    <div class="stc-dash-note">PPE checklists</div>
                                </button>
                            </div>
                            <div class="col-md-4 col-xl-4 col-sm-12 mb-3">
                                <button type="button" class="stc-dash-card stc-dash-open" data-type="safety_attendance" data-title="Attendance">
                                    <div class="stc-dash-card-top">
                                        <div class="stc-dash-icon" style="background:#6366f1;"><i class="fa fa-calendar-check-o"></i></div>
                                        <div class="stc-dash-meta">
                                            <div class="stc-dash-label">Attendance</div>
                                            <div class="stc-dash-number" data-count="safety_attendance"><i class="fa fa-spinner fa-spin"></i></div>
                                        </div>
                                    </div>
                                    <div class="stc-dash-note">E-permit enrolled employees</div>
                                </button>
                            </div>
                        </div>

                        <div class="stc-dash-section-title" style="margin-top:10px;">Procurement Tracker</div>
                        <div class="row">
                            <div class="col-md-4 col-xl-4 col-sm-12 mb-3">
                                <button type="button" class="stc-dash-card stc-dash-open" data-type="proc_tracker" data-title="Procurement Tracker">
                                    <div class="stc-dash-card-top">
                                        <div class="stc-dash-icon" style="background:#f59e0b;"><i class="fa fa-truck"></i></div>
                                        <div class="stc-dash-meta">
                                            <div class="stc-dash-label">Procurement</div>
                                            <div class="stc-dash-number" data-count="proc_tracker"><i class="fa fa-spinner fa-spin"></i></div>
                                        </div>
                                    </div>
                                    <div class="stc-dash-note">Tracker items</div>
                                </button>
                            </div>
                            <div class="col-md-4 col-xl-4 col-sm-12 mb-3">
                                <button type="button" class="stc-dash-card stc-dash-open" data-type="proc_tools" data-title="Tools & Tackles">
                                    <div class="stc-dash-card-top">
                                        <div class="stc-dash-icon" style="background:#ea580c;"><i class="fa fa-wrench"></i></div>
                                        <div class="stc-dash-meta">
                                            <div class="stc-dash-label">Tools</div>
                                            <div class="stc-dash-number" data-count="proc_tools"><i class="fa fa-spinner fa-spin"></i></div>
                                        </div>
                                    </div>
                                    <div class="stc-dash-note">Hand tool lists</div>
                                </button>
                            </div>
                            <div class="col-md-4 col-xl-4 col-sm-12 mb-3">
                                <button type="button" class="stc-dash-card stc-dash-open" data-type="proc_returnable" data-title="Returnable Materials">
                                    <div class="stc-dash-card-top">
                                        <div class="stc-dash-icon" style="background:#be185d;"><i class="fa fa-refresh"></i></div>
                                        <div class="stc-dash-meta">
                                            <div class="stc-dash-label">Returnable</div>
                                            <div class="stc-dash-number" data-count="proc_returnable"><i class="fa fa-spinner fa-spin"></i></div>
                                        </div>
                                    </div>
                                    <div class="stc-dash-note">Tools &amp; tackles requisitions</div>
                                </button>
                            </div>
                        </div>

                        <div class="stc-dash-section-title" style="margin-top:10px;">Requisition Movement</div>
                        <div class="row">
                            <div class="col-md-6 col-xl-6 col-sm-12 mb-3">
                                <div class="stc-dash-chart-card">
                                    <h6>Monthly requisitions <span id="stcDashMonthYear"></span></h6>
                                    <div class="stc-dash-chart-wrap"><canvas id="stcDashMonthChart"></canvas></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-6 col-sm-12 mb-3">
                                <div class="stc-dash-chart-card">
                                    <h6>Yearly requisitions</h6>
                                    <div class="stc-dash-chart-wrap"><canvas id="stcDashYearChart"></canvas></div>
                                </div>
                            </div>
                        </div>

                        <div class="stc-dash-section-title" style="margin-top:10px;">Job Status Down List</div>
                        <div class="row">
                            <div class="col-md-3 col-xl-3 col-sm-6 mb-3">
                                <button type="button" class="stc-dash-req-card stc-dash-open" data-type="sdl_total" data-title="All Job Status">
                                    <span class="stc-dash-dot" style="background:#343a40;"></span>
                                    <div class="stc-dash-req-label">All Jobs</div>
                                    <div class="stc-dash-req-number" data-count="sdl_total"><i class="fa fa-spinner fa-spin"></i></div>
                                </button>
                            </div>
                            <div class="col-md-3 col-xl-3 col-sm-6 mb-3">
                                <button type="button" class="stc-dash-req-card stc-dash-open" data-type="sdl_planning" data-title="Planning Jobs">
                                    <span class="stc-dash-dot" style="background:#00f9b4;"></span>
                                    <div class="stc-dash-req-label">Planning</div>
                                    <div class="stc-dash-req-number" data-count="sdl_planning"><i class="fa fa-spinner fa-spin"></i></div>
                                </button>
                            </div>
                            <div class="col-md-3 col-xl-3 col-sm-6 mb-3">
                                <button type="button" class="stc-dash-req-card stc-dash-open" data-type="sdl_down" data-title="Down Jobs">
                                    <span class="stc-dash-dot" style="background:#e91919;"></span>
                                    <div class="stc-dash-req-label">Down</div>
                                    <div class="stc-dash-req-number" data-count="sdl_down"><i class="fa fa-spinner fa-spin"></i></div>
                                </button>
                            </div>
                            <div class="col-md-3 col-xl-3 col-sm-6 mb-3">
                                <button type="button" class="stc-dash-req-card stc-dash-open" data-type="sdl_wip" data-title="Work-in-progress Jobs">
                                    <span class="stc-dash-dot" style="background:#f6f900;"></span>
                                    <div class="stc-dash-req-label">WIP</div>
                                    <div class="stc-dash-req-number" data-count="sdl_wip"><i class="fa fa-spinner fa-spin"></i></div>
                                </button>
                            </div>
                            <div class="col-md-3 col-xl-3 col-sm-6 mb-3">
                                <button type="button" class="stc-dash-req-card stc-dash-open" data-type="sdl_done" data-title="Work-done Jobs">
                                    <span class="stc-dash-dot" style="background:#2aef00;"></span>
                                    <div class="stc-dash-req-label">Work Done</div>
                                    <div class="stc-dash-req-number" data-count="sdl_done"><i class="fa fa-spinner fa-spin"></i></div>
                                </button>
                            </div>
                            <div class="col-md-3 col-xl-3 col-sm-6 mb-3">
                                <button type="button" class="stc-dash-req-card stc-dash-open" data-type="sdl_complete" data-title="Work-complete Jobs">
                                    <span class="stc-dash-dot" style="background:#16a34a;"></span>
                                    <div class="stc-dash-req-label">Complete</div>
                                    <div class="stc-dash-req-number" data-count="sdl_complete"><i class="fa fa-spinner fa-spin"></i></div>
                                </button>
                            </div>
                            <div class="col-md-3 col-xl-3 col-sm-6 mb-3">
                                <button type="button" class="stc-dash-req-card stc-dash-open" data-type="sdl_close" data-title="Closed Jobs">
                                    <span class="stc-dash-dot" style="background:#6b7280;"></span>
                                    <div class="stc-dash-req-label">Closed</div>
                                    <div class="stc-dash-req-number" data-count="sdl_close"><i class="fa fa-spinner fa-spin"></i></div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <div class="modal fade stc-dash-modal" id="stcDashModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stcDashModalTitle">Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="stcDashSearch" placeholder="Search...">
                    </div>
                    <p class="text-muted small mb-2" id="stcDashSummary">Loading...</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead id="stcDashThead"></thead>
                            <tbody id="stcDashTbody">
                                <tr><td class="text-center stc-dash-empty">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3" id="stcDashPager"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
    <!-- <script src="http://maps.google.com/maps/api/js?sensor=true"></script> -->
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function(){
            $('body').delegate('.search-icon', 'click', function(e){
                e.preventDefault();
                $(this).hide();
                $('.search-icon-2').show();
                // var pd_title=$('.agent-pro-search').val();
                // window.location.href="stc-product.php?pd_name="+pd_title;
            });

            $('body').delegate('.search-icon-2', 'click', function(e){
                var pd_title=$('.agent-pro-search').val();
                if(pd_title!=""){
                    window.location.href="stc-product.php?pd_name="+pd_title;
                }
            });

            $(".home").addClass("mm-active");

            var dashApi = 'api/dashboard.php';
            var dashState = { type: '', title: '', page: 1, search: '', timer: null };

            function dashEscape(value){
                return $('<div/>').text(value == null ? '' : String(value)).html();
            }

            function dashFormat(num){
                return (parseInt(num, 10) || 0).toLocaleString('en-IN');
            }

            var monthChart = null;
            var yearChart = null;

            function renderBarChart(canvasId, existing, payload){
                var ctx = document.getElementById(canvasId);
                if(!ctx || !payload){ return existing; }
                if(existing){ existing.destroy(); }
                return new Chart(ctx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: payload.labels || [],
                        datasets: payload.datasets || []
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' }
                        },
                        scales: {
                            x: { stacked: false },
                            y: { beginAtZero: true, ticks: { precision: 0 } }
                        }
                    }
                });
            }

            function loadCharts(){
                $.ajax({
                    url: dashApi,
                    method: 'POST',
                    dataType: 'json',
                    data: { action: 'charts' },
                    success: function(res){
                        if(!res || !res.ok){ return; }
                        if(res.monthly && res.monthly.year){
                            $('#stcDashMonthYear').text('('+res.monthly.year+')');
                        }
                        monthChart = renderBarChart('stcDashMonthChart', monthChart, res.monthly);
                        yearChart = renderBarChart('stcDashYearChart', yearChart, res.yearly);
                    }
                });
            }

            function loadSummary(){
                $.ajax({
                    url: dashApi,
                    method: 'POST',
                    dataType: 'json',
                    data: { action: 'summary' },
                    success: function(res){
                        if(!res || !res.ok || !res.counts){
                            $('[data-count]').text('0');
                            return;
                        }
                        $.each(res.counts, function(key, val){
                            $('[data-count="'+key+'"]').text(dashFormat(val));
                        });
                    },
                    error: function(){
                        $('[data-count]').text('0');
                    }
                });
            }

            function renderPager(page, pages){
                var html = '';
                if(pages <= 1){
                    $('#stcDashPager').html('');
                    return;
                }
                if(page > 1){
                    html += '<button type="button" class="btn btn-sm btn-default stc-dash-page" data-page="'+(page-1)+'">Prev</button> ';
                }
                html += '<span class="mx-2">Page '+page+' of '+pages+'</span>';
                if(page < pages){
                    html += ' <button type="button" class="btn btn-sm btn-default stc-dash-page" data-page="'+(page+1)+'">Next</button>';
                }
                $('#stcDashPager').html(html);
            }

            function loadList(){
                $('#stcDashTbody').html('<tr><td class="text-center stc-dash-empty" colspan="8"><i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>');
                $.ajax({
                    url: dashApi,
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'list',
                        type: dashState.type,
                        page: dashState.page,
                        search: dashState.search,
                        per_page: 15
                    },
                    success: function(res){
                        if(!res || !res.ok){
                            $('#stcDashTbody').html('<tr><td class="text-center text-danger">Could not load details.</td></tr>');
                            $('#stcDashSummary').text('');
                            return;
                        }
                        var head = '<tr>';
                        $.each(res.columns || [], function(_, col){
                            head += '<th class="text-center">'+dashEscape(col)+'</th>';
                        });
                        head += '</tr>';
                        $('#stcDashThead').html(head);

                        if(!res.rows || !res.rows.length){
                            $('#stcDashTbody').html('<tr><td class="text-center stc-dash-empty" colspan="'+(res.columns.length || 1)+'">No records found.</td></tr>');
                        }else{
                            var body = '';
                            $.each(res.rows, function(_, row){
                                body += '<tr>';
                                $.each(row, function(_, cell){
                                    body += '<td>'+dashEscape(cell)+'</td>';
                                });
                                body += '</tr>';
                            });
                            $('#stcDashTbody').html(body);
                        }
                        $('#stcDashSummary').text('Showing page '+res.page+' of '+res.pages+' • '+dashFormat(res.total)+' records');
                        renderPager(res.page, res.pages);
                    },
                    error: function(){
                        $('#stcDashTbody').html('<tr><td class="text-center text-danger">Could not load details.</td></tr>');
                    }
                });
            }

            $('body').delegate('.stc-dash-open', 'click', function(e){
                e.preventDefault();
                dashState.type = $(this).data('type');
                dashState.title = $(this).data('title');
                dashState.page = 1;
                dashState.search = '';
                $('#stcDashModalTitle').text(dashState.title);
                $('#stcDashSearch').val('');
                $('#stcDashThead').empty();
                $('#stcDashPager').empty();
                $('#stcDashModal').modal('show').addClass('show');
                loadList();
            });

            $('#stcDashModal').on('hidden.bs.modal', function(){
                $(this).removeClass('show');
            });

            $('body').delegate('.stc-dash-page', 'click', function(){
                dashState.page = parseInt($(this).data('page'), 10) || 1;
                loadList();
            });

            $('#stcDashSearch').on('keyup', function(){
                var value = $(this).val();
                clearTimeout(dashState.timer);
                dashState.timer = setTimeout(function(){
                    dashState.search = value;
                    dashState.page = 1;
                    loadList();
                }, 350);
            });

            loadSummary();
            loadCharts();
        });
    </script>
</body>
</html>
