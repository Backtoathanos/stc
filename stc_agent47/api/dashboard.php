<?php
session_start();

$cookie_duration = 7 * 24 * 60 * 60;
if (isset($_SESSION['stc_agent_id'])) {
    if (!isset($_COOKIE['stc_agent_remember'])) {
        setcookie('stc_agent_remember', $_SESSION['stc_agent_id'], time() + $cookie_duration, '/');
        setcookie('stc_agent_name', $_SESSION['stc_agent_name'], time() + $cookie_duration, '/');
        setcookie('stc_agent_role', $_SESSION['stc_agent_role'], time() + $cookie_duration, '/');
    }
} elseif (isset($_COOKIE['stc_agent_remember'])) {
    $_SESSION['stc_agent_id'] = $_COOKIE['stc_agent_remember'];
    $_SESSION['stc_agent_name'] = $_COOKIE['stc_agent_name'];
    $_SESSION['stc_agent_role'] = $_COOKIE['stc_agent_role'];
} else {
    header('Content-Type: application/json');
    echo json_encode(array('ok' => false, 'message' => 'Please login again.'));
    exit();
}

include_once('../../MCU/db.php');
header('Content-Type: application/json');

$agent_id = mysqli_real_escape_string($con, (string) $_SESSION['stc_agent_id']);
$agent_role = isset($_SESSION['stc_agent_role']) ? (int) $_SESSION['stc_agent_role'] : 0;
$action = isset($_POST['action']) ? trim((string) $_POST['action']) : 'summary';

function stc_api_int($con, $sql) {
    $q = mysqli_query($con, $sql);
    if ($q && ($row = mysqli_fetch_row($q))) {
        return (int) $row[0];
    }
    return 0;
}

function stc_api_project_ids($con, $agent_id, $agent_role) {
    if ($agent_role === 3) {
        $sql = "
            SELECT DISTINCT `stc_cust_project_id`
            FROM `stc_cust_project`
            INNER JOIN `stc_agent_requested_customer`
                ON `stc_agent_requested_customer_cust_id`=`stc_cust_project_cust_id`
            INNER JOIN `stc_agents`
                ON `stc_agent_requested_customer_agent_id`=`stc_agents_id`
            WHERE `stc_agents_id`='".$agent_id."'
        ";
    } else {
        $sql = "
            SELECT DISTINCT `stc_cust_project_id`
            FROM `stc_cust_project`
            LEFT JOIN `stc_cust_project_collaborate`
                ON `stc_cust_project_collaborate_projectid`=`stc_cust_project_id`
            WHERE `stc_cust_project_createdby`='".$agent_id."'
            OR `stc_cust_project_collaborate_teamid`='".$agent_id."'
        ";
    }
    $ids = array();
    $q = mysqli_query($con, $sql);
    if ($q) {
        while ($row = mysqli_fetch_assoc($q)) {
            $ids[] = (int) $row['stc_cust_project_id'];
        }
    }
    return $ids;
}

function stc_api_status_label($status) {
    $map = array(
        1 => 'Ordered',
        2 => 'Approved',
        3 => 'Accepted',
        4 => 'Dispatched',
        5 => 'Received',
        6 => 'Rejected',
        7 => 'Canceled',
        8 => 'Returned',
        9 => 'Pending',
    );
    $status = (int) $status;
    return isset($map[$status]) ? $map[$status] : 'Closed';
}

function stc_api_supervisor_ids($con, $agent_id) {
    $ids = array();
    $q = mysqli_query($con, "
        SELECT DISTINCT `stc_cust_pro_supervisor_id` AS sid
        FROM `stc_cust_pro_supervisor`
        LEFT JOIN `stc_cust_pro_supervisor_collaborate`
            ON `stc_cust_pro_supervisor_collaborate_userid`=`stc_cust_pro_supervisor_id`
        WHERE `stc_cust_pro_supervisor_created_by`='".$agent_id."'
        OR (`stc_cust_pro_supervisor_collaborate_teamid`='".$agent_id."' AND `stc_cust_pro_supervisor_collaborate_status`=1)
    ");
    if ($q) {
        while ($row = mysqli_fetch_assoc($q)) {
            $ids[] = (int) $row['sid'];
        }
    }
    return array_values(array_unique($ids));
}

function stc_api_sdl_label($status) {
    $map = array(
        1 => 'Planning',
        2 => 'Down',
        3 => 'Work-in-progress',
        4 => 'Work-done',
        5 => 'Work-complete',
        6 => 'Close',
    );
    $status = (int) $status;
    return isset($map[$status]) ? $map[$status] : 'Close';
}

function stc_api_status_filter($type) {
    $filters = array(
        'req_total' => '',
        'req_ordered' => ' AND I.`stc_cust_super_requisition_list_items_status`=1 ',
        'req_approved' => ' AND I.`stc_cust_super_requisition_list_items_status`=2 ',
        'req_accepted' => ' AND I.`stc_cust_super_requisition_list_items_status`=3 ',
        'req_dispatched' => ' AND I.`stc_cust_super_requisition_list_items_status`=4 ',
        'req_received' => ' AND I.`stc_cust_super_requisition_list_items_status`=5 ',
        'req_pending' => ' AND I.`stc_cust_super_requisition_list_items_status`=9 ',
        'req_canceled' => ' AND I.`stc_cust_super_requisition_list_items_status` IN (6,7) ',
    );
    return isset($filters[$type]) ? $filters[$type] : '';
}

$project_ids = stc_api_project_ids($con, $agent_id, $agent_role);
$supervisor_ids = stc_api_supervisor_ids($con, $agent_id);
$project_in = !empty($project_ids) ? implode(',', $project_ids) : '0';
$supervisor_in = !empty($supervisor_ids) ? implode(',', $supervisor_ids) : '0';

if ($action === 'summary') {
    $req_status = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0);
    $req_total = 0;
    if (!empty($project_ids)) {
        $id_list = implode(',', $project_ids);
        $req_q = mysqli_query($con, "
            SELECT I.`stc_cust_super_requisition_list_items_status` AS st, COUNT(*) AS c
            FROM `stc_cust_super_requisition_list_items` I
            INNER JOIN `stc_cust_super_requisition_list` L
                ON I.`stc_cust_super_requisition_list_items_req_id`=L.`stc_cust_super_requisition_list_id`
            WHERE L.`stc_cust_super_requisition_list_project_id` IN (".$id_list.")
            GROUP BY I.`stc_cust_super_requisition_list_items_status`
        ");
        if ($req_q) {
            while ($row = mysqli_fetch_assoc($req_q)) {
                $st = (int) $row['st'];
                $c = (int) $row['c'];
                $req_total += $c;
                if (isset($req_status[$st])) {
                    $req_status[$st] = $c;
                }
            }
        }
    }

    echo json_encode(array(
        'ok' => true,
        'counts' => array(
            'projects' => count($project_ids),
            'supervisors' => stc_api_int($con, "
                SELECT COUNT(DISTINCT `stc_cust_pro_supervisor_id`)
                FROM `stc_cust_pro_supervisor`
                WHERE `stc_cust_pro_supervisor_created_by`='".$agent_id."'
            "),
            'collab_supervisors' => stc_api_int($con, "
                SELECT COUNT(DISTINCT `stc_cust_pro_supervisor_collaborate_userid`)
                FROM `stc_cust_pro_supervisor_collaborate`
                WHERE `stc_cust_pro_supervisor_collaborate_teamid`='".$agent_id."'
                AND `stc_cust_pro_supervisor_collaborate_status`=1
            "),
            'req_total' => $req_total,
            'req_ordered' => $req_status[1],
            'req_approved' => $req_status[2],
            'req_accepted' => $req_status[3],
            'req_dispatched' => $req_status[4],
            'req_received' => $req_status[5],
            'req_pending' => $req_status[9],
            'req_canceled' => $req_status[6] + $req_status[7],
            'safety_tbm' => stc_api_int($con, "SELECT COUNT(*) FROM `stc_safetytbm` WHERE `stc_safetytbm_created_by` IN (".$supervisor_in.")"),
            'safety_ppec' => stc_api_int($con, "SELECT COUNT(*) FROM `stc_safetyppec` WHERE `stc_safetyppec_createdby` IN (".$supervisor_in.")"),
            'safety_attendance' => stc_api_int($con, "
                SELECT COUNT(DISTINCT ee.`emp_id`)
                FROM `stc_epermit_enrollment` ee
                LEFT JOIN `stc_status_down_list_department` d
                    ON ee.`dep_id`=d.`stc_status_down_list_department_id`
                WHERE ee.`emp_id`<>0
                AND (
                    ee.`location` IN (".$project_in.")
                    OR d.`stc_status_down_list_department_loc_id` IN (".$project_in.")
                )
            "),
            'proc_tracker' => stc_api_int($con, "
                SELECT COUNT(*) FROM `stc_cust_procurement_tracker`
                WHERE `stc_cust_procurement_tracker_created_by`='".$agent_id."'
                OR `stc_cust_procurement_tracker_project_id` IN (".$project_in.")
            "),
            'proc_tools' => stc_api_int($con, "SELECT COUNT(*) FROM `stc_safetytoolslist` WHERE `stc_safetytoolslist_createdby` IN (".$supervisor_in.")"),
            'proc_returnable' => stc_api_int($con, "
                SELECT COUNT(*)
                FROM `stc_cust_super_requisition_list_items` I
                INNER JOIN `stc_cust_super_requisition_list` L
                    ON I.`stc_cust_super_requisition_list_items_req_id`=L.`stc_cust_super_requisition_list_id`
                WHERE L.`stc_cust_super_requisition_list_project_id` IN (".$project_in.")
                AND I.`stc_cust_super_requisition_items_type`='Tools & Tackles'
            "),
            'sdl_total' => stc_api_int($con, "SELECT COUNT(*) FROM `stc_status_down_list` WHERE `stc_status_down_list_location` IN (".$project_in.")"),
            'sdl_planning' => stc_api_int($con, "SELECT COUNT(*) FROM `stc_status_down_list` WHERE `stc_status_down_list_location` IN (".$project_in.") AND `stc_status_down_list_status`=1"),
            'sdl_down' => stc_api_int($con, "SELECT COUNT(*) FROM `stc_status_down_list` WHERE `stc_status_down_list_location` IN (".$project_in.") AND `stc_status_down_list_status`=2"),
            'sdl_wip' => stc_api_int($con, "SELECT COUNT(*) FROM `stc_status_down_list` WHERE `stc_status_down_list_location` IN (".$project_in.") AND `stc_status_down_list_status`=3"),
            'sdl_done' => stc_api_int($con, "SELECT COUNT(*) FROM `stc_status_down_list` WHERE `stc_status_down_list_location` IN (".$project_in.") AND `stc_status_down_list_status`=4"),
            'sdl_complete' => stc_api_int($con, "SELECT COUNT(*) FROM `stc_status_down_list` WHERE `stc_status_down_list_location` IN (".$project_in.") AND `stc_status_down_list_status`=5"),
            'sdl_close' => stc_api_int($con, "SELECT COUNT(*) FROM `stc_status_down_list` WHERE `stc_status_down_list_location` IN (".$project_in.") AND `stc_status_down_list_status`=6"),
        ),
    ));
    exit();
}

if ($action === 'charts') {
    $year = (int) date('Y');
    $month_labels = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
    $series_keys = array(1 => 'Ordered', 2 => 'Approved', 4 => 'Dispatched', 5 => 'Received', 9 => 'Pending');
    $monthly = array();
    $yearly = array();
    foreach ($series_keys as $sid => $label) {
        $monthly[$sid] = array_fill(1, 12, 0);
    }
    $years = array();
    for ($y = $year - 4; $y <= $year; $y++) {
        $years[] = $y;
        foreach ($series_keys as $sid => $label) {
            $yearly[$y][$sid] = 0;
        }
    }
    if (!empty($project_ids)) {
        $q = mysqli_query($con, "
            SELECT
                YEAR(L.`stc_cust_super_requisition_list_date`) AS y,
                MONTH(L.`stc_cust_super_requisition_list_date`) AS m,
                I.`stc_cust_super_requisition_list_items_status` AS st,
                COUNT(*) AS c
            FROM `stc_cust_super_requisition_list_items` I
            INNER JOIN `stc_cust_super_requisition_list` L
                ON I.`stc_cust_super_requisition_list_items_req_id`=L.`stc_cust_super_requisition_list_id`
            WHERE L.`stc_cust_super_requisition_list_project_id` IN (".$project_in.")
            AND YEAR(L.`stc_cust_super_requisition_list_date`) BETWEEN '".($year - 4)."' AND '".$year."'
            GROUP BY YEAR(L.`stc_cust_super_requisition_list_date`), MONTH(L.`stc_cust_super_requisition_list_date`), I.`stc_cust_super_requisition_list_items_status`
        ");
        if ($q) {
            while ($row = mysqli_fetch_assoc($q)) {
                $y = (int) $row['y'];
                $m = (int) $row['m'];
                $st = (int) $row['st'];
                $c = (int) $row['c'];
                if ($y === $year && isset($monthly[$st][$m])) {
                    $monthly[$st][$m] += $c;
                }
                if (isset($yearly[$y][$st])) {
                    $yearly[$y][$st] += $c;
                }
            }
        }
    }
    $month_datasets = array();
    $year_datasets = array();
    $colors = array(
        1 => '#3498db',
        2 => '#2ecc71',
        4 => '#f39c12',
        5 => '#16a085',
        9 => '#ff2f2f',
    );
    foreach ($series_keys as $sid => $label) {
        $month_datasets[] = array(
            'label' => $label,
            'backgroundColor' => $colors[$sid],
            'data' => array_values($monthly[$sid]),
        );
        $ydata = array();
        foreach ($years as $y) {
            $ydata[] = isset($yearly[$y][$sid]) ? $yearly[$y][$sid] : 0;
        }
        $year_datasets[] = array(
            'label' => $label,
            'backgroundColor' => $colors[$sid],
            'data' => $ydata,
        );
    }
    echo json_encode(array(
        'ok' => true,
        'monthly' => array('labels' => $month_labels, 'datasets' => $month_datasets, 'year' => $year),
        'yearly' => array('labels' => $years, 'datasets' => $year_datasets),
    ));
    exit();
}

if ($action !== 'list') {
    echo json_encode(array('ok' => false, 'message' => 'Invalid request.'));
    exit();
}

$type = isset($_POST['type']) ? trim((string) $_POST['type']) : '';
$search = isset($_POST['search']) ? trim((string) $_POST['search']) : '';
$page = max(1, (int) (isset($_POST['page']) ? $_POST['page'] : 1));
$per_page = max(10, min(50, (int) (isset($_POST['per_page']) ? $_POST['per_page'] : 15)));
$offset = ($page - 1) * $per_page;
$search_esc = mysqli_real_escape_string($con, $search);

$columns = array();
$rows = array();
$total = 0;

if ($type === 'projects') {
    $columns = array('Project', 'Reference', 'Address', 'Status');
    $where = $agent_role === 3
        ? " INNER JOIN `stc_agent_requested_customer` ON `stc_agent_requested_customer_cust_id`=`stc_cust_project_cust_id`
            INNER JOIN `stc_agents` ON `stc_agent_requested_customer_agent_id`=`stc_agents_id`
            WHERE `stc_agents_id`='".$agent_id."' "
        : " LEFT JOIN `stc_cust_project_collaborate` ON `stc_cust_project_collaborate_projectid`=`stc_cust_project_id`
            WHERE (`stc_cust_project_createdby`='".$agent_id."' OR `stc_cust_project_collaborate_teamid`='".$agent_id."') ";
    if ($search_esc !== '') {
        $where .= " AND (`stc_cust_project_title` LIKE '%".$search_esc."%' OR `stc_cust_project_refr` LIKE '%".$search_esc."%' OR `stc_cust_project_address` LIKE '%".$search_esc."%') ";
    }
    $total = stc_api_int($con, "SELECT COUNT(DISTINCT `stc_cust_project_id`) FROM `stc_cust_project` ".$where);
    $q = mysqli_query($con, "
        SELECT DISTINCT
            `stc_cust_project_title`,
            `stc_cust_project_refr`,
            `stc_cust_project_address`,
            `stc_cust_project_status`
        FROM `stc_cust_project`
        ".$where."
        ORDER BY `stc_cust_project_title` ASC
        LIMIT ".$offset.", ".$per_page."
    ");
    if ($q) {
        while ($row = mysqli_fetch_assoc($q)) {
            $rows[] = array(
                (string) $row['stc_cust_project_title'],
                (string) $row['stc_cust_project_refr'],
                (string) $row['stc_cust_project_address'],
                ((int) $row['stc_cust_project_status'] === 1 ? 'Live' : 'Closed'),
            );
        }
    }
} elseif ($type === 'supervisors' || $type === 'collab_supervisors') {
    $columns = array('Supervisor', 'Contact');
    if ($type === 'supervisors') {
        $from = "
            FROM `stc_cust_pro_supervisor`
            WHERE `stc_cust_pro_supervisor_created_by`='".$agent_id."'
        ";
    } else {
        $from = "
            FROM `stc_cust_pro_supervisor`
            INNER JOIN `stc_cust_pro_supervisor_collaborate`
                ON `stc_cust_pro_supervisor_collaborate_userid`=`stc_cust_pro_supervisor_id`
            WHERE `stc_cust_pro_supervisor_collaborate_teamid`='".$agent_id."'
            AND `stc_cust_pro_supervisor_collaborate_status`=1
        ";
    }
    if ($search_esc !== '') {
        $from .= " AND (`stc_cust_pro_supervisor_fullname` LIKE '%".$search_esc."%' OR `stc_cust_pro_supervisor_contact` LIKE '%".$search_esc."%') ";
    }
    $total = stc_api_int($con, "SELECT COUNT(DISTINCT `stc_cust_pro_supervisor_id`) ".$from);
    $q = mysqli_query($con, "
        SELECT DISTINCT
            `stc_cust_pro_supervisor_fullname`,
            `stc_cust_pro_supervisor_contact`
        ".$from."
        ORDER BY `stc_cust_pro_supervisor_fullname` ASC
        LIMIT ".$offset.", ".$per_page."
    ");
    if ($q) {
        while ($row = mysqli_fetch_assoc($q)) {
            $rows[] = array(
                (string) $row['stc_cust_pro_supervisor_fullname'],
                (string) $row['stc_cust_pro_supervisor_contact'],
            );
        }
    }
} elseif (strpos($type, 'req_') === 0) {
    $columns = array('Date', 'PR No', 'Order Number', 'Project', 'Item', 'Qty', 'Status');
    if (empty($project_ids)) {
        $total = 0;
    } else {
        $id_list = implode(',', $project_ids);
        $status_sql = stc_api_status_filter($type);
        $search_sql = '';
        if ($search_esc !== '') {
            $search_sql = "
                AND (
                    I.`stc_cust_super_requisition_list_items_title` LIKE '%".$search_esc."%'
                    OR L.`stc_cust_super_requisition_list_id` LIKE '%".$search_esc."%'
                    OR L.`stc_cust_super_requisition_list_order_number` LIKE '%".$search_esc."%'
                    OR P.`stc_cust_project_title` LIKE '%".$search_esc."%'
                )
            ";
        }
        $from = "
            FROM `stc_cust_super_requisition_list_items` I
            INNER JOIN `stc_cust_super_requisition_list` L
                ON I.`stc_cust_super_requisition_list_items_req_id`=L.`stc_cust_super_requisition_list_id`
            LEFT JOIN `stc_cust_project` P
                ON P.`stc_cust_project_id`=L.`stc_cust_super_requisition_list_project_id`
            WHERE L.`stc_cust_super_requisition_list_project_id` IN (".$id_list.")
            ".$status_sql.$search_sql."
        ";
        $total = stc_api_int($con, "SELECT COUNT(*) ".$from);
        $q = mysqli_query($con, "
            SELECT
                DATE(L.`stc_cust_super_requisition_list_date`) AS req_date,
                L.`stc_cust_super_requisition_list_id` AS pr_no,
                IFNULL(L.`stc_cust_super_requisition_list_order_number`,'') AS order_number,
                IFNULL(P.`stc_cust_project_title`,'') AS project_title,
                I.`stc_cust_super_requisition_list_items_title` AS item_title,
                I.`stc_cust_super_requisition_list_items_reqqty` AS req_qty,
                I.`stc_cust_super_requisition_list_items_status` AS item_status
            ".$from."
            ORDER BY L.`stc_cust_super_requisition_list_date` DESC, L.`stc_cust_super_requisition_list_id` DESC
            LIMIT ".$offset.", ".$per_page."
        ");
        if ($q) {
            while ($row = mysqli_fetch_assoc($q)) {
                $rows[] = array(
                    date('d-m-Y', strtotime($row['req_date'])),
                    (string) $row['pr_no'],
                    trim((string) $row['order_number']) !== '' ? (string) $row['order_number'] : '-',
                    (string) $row['project_title'],
                    (string) $row['item_title'],
                    number_format((float) $row['req_qty'], 2),
                    stc_api_status_label($row['item_status']),
                );
            }
        }
    }
} elseif ($type === 'safety_tbm') {
    $columns = array('Date', 'Place', 'Supervisor');
    $from = "
        FROM `stc_safetytbm`
        LEFT JOIN `stc_cust_pro_supervisor` ON `stc_cust_pro_supervisor_id`=`stc_safetytbm_created_by`
        WHERE `stc_safetytbm_created_by` IN (".$supervisor_in.")
    ";
    if ($search_esc !== '') {
        $from .= " AND (`stc_safetytbm_place` LIKE '%".$search_esc."%' OR `stc_cust_pro_supervisor_fullname` LIKE '%".$search_esc."%') ";
    }
    $total = stc_api_int($con, "SELECT COUNT(*) ".$from);
    $q = mysqli_query($con, "SELECT DATE(`stc_safetytbm_date`) AS d, `stc_safetytbm_place`, IFNULL(`stc_cust_pro_supervisor_fullname`,'') AS sname ".$from." ORDER BY `stc_safetytbm_date` DESC LIMIT ".$offset.", ".$per_page);
    if ($q) {
        while ($row = mysqli_fetch_assoc($q)) {
            $rows[] = array(date('d-m-Y', strtotime($row['d'])), (string) $row['stc_safetytbm_place'], (string) $row['sname']);
        }
    }
} elseif ($type === 'safety_ppec') {
    $columns = array('Date', 'Site', 'Supervisor');
    $from = "
        FROM `stc_safetyppec`
        LEFT JOIN `stc_cust_pro_supervisor` ON `stc_cust_pro_supervisor_id`=`stc_safetyppec_createdby`
        WHERE `stc_safetyppec_createdby` IN (".$supervisor_in.")
    ";
    if ($search_esc !== '') {
        $from .= " AND (`stc_safetyppec_sitename` LIKE '%".$search_esc."%' OR `stc_cust_pro_supervisor_fullname` LIKE '%".$search_esc."%') ";
    }
    $total = stc_api_int($con, "SELECT COUNT(*) ".$from);
    $q = mysqli_query($con, "SELECT DATE(`stc_safetyppec_date`) AS d, `stc_safetyppec_sitename`, IFNULL(`stc_cust_pro_supervisor_fullname`,'') AS sname ".$from." ORDER BY `stc_safetyppec_date` DESC LIMIT ".$offset.", ".$per_page);
    if ($q) {
        while ($row = mysqli_fetch_assoc($q)) {
            $rows[] = array(date('d-m-Y', strtotime($row['d'])), (string) $row['stc_safetyppec_sitename'], (string) $row['sname']);
        }
    }
} elseif ($type === 'safety_attendance') {
    $columns = array('Employee', 'Location', 'Department', 'Last Date');
    $from = "
        FROM `stc_epermit_enrollment` ee
        LEFT JOIN `stc_status_down_list_department` d ON ee.`dep_id`=d.`stc_status_down_list_department_id`
        WHERE ee.`emp_id`<>0
        AND (ee.`location` IN (".$project_in.") OR d.`stc_status_down_list_department_loc_id` IN (".$project_in."))
    ";
    if ($search_esc !== '') {
        $from .= " AND (ee.`emp_name` LIKE '%".$search_esc."%' OR d.`stc_status_down_list_department_location` LIKE '%".$search_esc."%' OR d.`stc_status_down_list_department_dept` LIKE '%".$search_esc."%') ";
    }
    $total = stc_api_int($con, "SELECT COUNT(DISTINCT ee.`emp_id`) ".$from);
    $q = mysqli_query($con, "
        SELECT ee.`emp_name`, IFNULL(d.`stc_status_down_list_department_location`,'') AS loc, IFNULL(d.`stc_status_down_list_department_dept`,'') AS dept, MAX(DATE(ee.`created_date`)) AS last_date
        ".$from."
        GROUP BY ee.`emp_id`, ee.`emp_name`, d.`stc_status_down_list_department_location`, d.`stc_status_down_list_department_dept`
        ORDER BY ee.`emp_name` ASC
        LIMIT ".$offset.", ".$per_page."
    ");
    if ($q) {
        while ($row = mysqli_fetch_assoc($q)) {
            $rows[] = array((string) $row['emp_name'], (string) $row['loc'], (string) $row['dept'], date('d-m-Y', strtotime($row['last_date'])));
        }
    }
} elseif ($type === 'proc_tracker') {
    $columns = array('Date', 'Project', 'Item', 'Qty', 'Buyer');
    $from = "
        FROM `stc_cust_procurement_tracker`
        LEFT JOIN `stc_cust_project` ON `stc_cust_project_id`=`stc_cust_procurement_tracker_project_id`
        WHERE (`stc_cust_procurement_tracker_created_by`='".$agent_id."' OR `stc_cust_procurement_tracker_project_id` IN (".$project_in."))
    ";
    if ($search_esc !== '') {
        $from .= " AND (`stc_cust_procurement_tracker_item_title` LIKE '%".$search_esc."%' OR `stc_cust_project_title` LIKE '%".$search_esc."%' OR `stc_cust_procurement_tracker_buyer` LIKE '%".$search_esc."%') ";
    }
    $total = stc_api_int($con, "SELECT COUNT(*) ".$from);
    $q = mysqli_query($con, "
        SELECT DATE(`stc_cust_procurement_tracker_date`) AS d, IFNULL(`stc_cust_project_title`,'') AS project_title, `stc_cust_procurement_tracker_item_title`, `stc_cust_procurement_tracker_qty`, IFNULL(`stc_cust_procurement_tracker_buyer`,'') AS buyer
        ".$from."
        ORDER BY `stc_cust_procurement_tracker_id` DESC
        LIMIT ".$offset.", ".$per_page."
    ");
    if ($q) {
        while ($row = mysqli_fetch_assoc($q)) {
            $rows[] = array(date('d-m-Y', strtotime($row['d'])), (string) $row['project_title'], (string) $row['stc_cust_procurement_tracker_item_title'], number_format((float) $row['stc_cust_procurement_tracker_qty'], 2), (string) $row['buyer']);
        }
    }
} elseif ($type === 'proc_tools') {
    $columns = array('Date', 'Site', 'Supervisor');
    $from = "
        FROM `stc_safetytoolslist`
        LEFT JOIN `stc_cust_pro_supervisor` ON `stc_cust_pro_supervisor_id`=`stc_safetytoolslist_createdby`
        WHERE `stc_safetytoolslist_createdby` IN (".$supervisor_in.")
    ";
    if ($search_esc !== '') {
        $from .= " AND (`stc_safetytoolslist_sitename` LIKE '%".$search_esc."%' OR `stc_cust_pro_supervisor_fullname` LIKE '%".$search_esc."%') ";
    }
    $total = stc_api_int($con, "SELECT COUNT(*) ".$from);
    $q = mysqli_query($con, "SELECT DATE(`stc_safetytoolslist_date`) AS d, `stc_safetytoolslist_sitename`, IFNULL(`stc_cust_pro_supervisor_fullname`,'') AS sname ".$from." ORDER BY `stc_safetytoolslist_date` DESC LIMIT ".$offset.", ".$per_page);
    if ($q) {
        while ($row = mysqli_fetch_assoc($q)) {
            $rows[] = array(date('d-m-Y', strtotime($row['d'])), (string) $row['stc_safetytoolslist_sitename'], (string) $row['sname']);
        }
    }
} elseif ($type === 'proc_returnable') {
    $columns = array('Date', 'PR No', 'Project', 'Item', 'Qty', 'Status');
    $from = "
        FROM `stc_cust_super_requisition_list_items` I
        INNER JOIN `stc_cust_super_requisition_list` L
            ON I.`stc_cust_super_requisition_list_items_req_id`=L.`stc_cust_super_requisition_list_id`
        LEFT JOIN `stc_cust_project` P ON P.`stc_cust_project_id`=L.`stc_cust_super_requisition_list_project_id`
        WHERE L.`stc_cust_super_requisition_list_project_id` IN (".$project_in.")
        AND I.`stc_cust_super_requisition_items_type`='Tools & Tackles'
    ";
    if ($search_esc !== '') {
        $from .= " AND (I.`stc_cust_super_requisition_list_items_title` LIKE '%".$search_esc."%' OR P.`stc_cust_project_title` LIKE '%".$search_esc."%') ";
    }
    $total = stc_api_int($con, "SELECT COUNT(*) ".$from);
    $q = mysqli_query($con, "
        SELECT DATE(L.`stc_cust_super_requisition_list_date`) AS d, L.`stc_cust_super_requisition_list_id` AS pr_no, IFNULL(P.`stc_cust_project_title`,'') AS project_title, I.`stc_cust_super_requisition_list_items_title` AS item_title, I.`stc_cust_super_requisition_list_items_reqqty` AS qty, I.`stc_cust_super_requisition_list_items_status` AS st
        ".$from."
        ORDER BY L.`stc_cust_super_requisition_list_date` DESC
        LIMIT ".$offset.", ".$per_page."
    ");
    if ($q) {
        while ($row = mysqli_fetch_assoc($q)) {
            $rows[] = array(date('d-m-Y', strtotime($row['d'])), (string) $row['pr_no'], (string) $row['project_title'], (string) $row['item_title'], number_format((float) $row['qty'], 2), stc_api_status_label($row['st']));
        }
    }
} elseif (strpos($type, 'sdl_') === 0) {
    $columns = array('Date', 'Project', 'Sub Location', 'Reason', 'Status');
    $status_map = array(
        'sdl_planning' => 1,
        'sdl_down' => 2,
        'sdl_wip' => 3,
        'sdl_done' => 4,
        'sdl_complete' => 5,
        'sdl_close' => 6,
    );
    $status_sql = isset($status_map[$type]) ? " AND `stc_status_down_list_status`=".(int) $status_map[$type]." " : '';
    $from = "
        FROM `stc_status_down_list`
        LEFT JOIN `stc_cust_project` ON `stc_cust_project_id`=`stc_status_down_list_location`
        WHERE `stc_status_down_list_location` IN (".$project_in.")
        ".$status_sql."
    ";
    if ($search_esc !== '') {
        $from .= " AND (`stc_cust_project_title` LIKE '%".$search_esc."%' OR `stc_status_down_list_sub_location` LIKE '%".$search_esc."%' OR `stc_status_down_list_reason` LIKE '%".$search_esc."%') ";
    }
    $total = stc_api_int($con, "SELECT COUNT(*) ".$from);
    $q = mysqli_query($con, "
        SELECT DATE(`stc_status_down_list_date`) AS d, IFNULL(`stc_cust_project_title`,'') AS project_title, `stc_status_down_list_sub_location`, `stc_status_down_list_reason`, `stc_status_down_list_status`
        ".$from."
        ORDER BY `stc_status_down_list_date` DESC
        LIMIT ".$offset.", ".$per_page."
    ");
    if ($q) {
        while ($row = mysqli_fetch_assoc($q)) {
            $rows[] = array(date('d-m-Y', strtotime($row['d'])), (string) $row['project_title'], (string) $row['stc_status_down_list_sub_location'], (string) $row['stc_status_down_list_reason'], stc_api_sdl_label($row['stc_status_down_list_status']));
        }
    }
} else {
    echo json_encode(array('ok' => false, 'message' => 'Unknown list type.'));
    exit();
}

$pages = $total > 0 ? (int) ceil($total / $per_page) : 1;
$page = min($page, max(1, $pages));

echo json_encode(array(
    'ok' => true,
    'columns' => $columns,
    'rows' => $rows,
    'total' => $total,
    'page' => $page,
    'pages' => $pages,
    'per_page' => $per_page,
));
