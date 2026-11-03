<?php
mysqli_report(MYSQLI_REPORT_OFF);
$host = '127.0.0.1';
$user = 'nausher';
$pass = 'ZX5#*,PPq$5;';
$db = 'stc_associate_go';
$con = mysqli_connect($host, $user, $pass, $db);
if (!$con) { echo "connect fail: ".mysqli_connect_error()."\n"; exit; }
echo "connected\n";
$t = mysqli_query($con, "SHOW TABLES LIKE 'stc_req_item_communicate'");
echo "table=" . ($t && mysqli_num_rows($t) ? 'yes' : 'no') . "\n";
if ($t && mysqli_num_rows($t)) {
  $c = mysqli_query($con, "SELECT COUNT(*) c FROM stc_req_item_communicate");
  echo "rows=" . mysqli_fetch_assoc($c)['c'] . "\n";
  $cols = mysqli_query($con, "SHOW COLUMNS FROM stc_req_item_communicate");
  while ($r = mysqli_fetch_assoc($cols)) echo " col ".$r['Field']."\n";
  $q = mysqli_query($con, "
    SELECT C.id, C.req_item_id, C.req_id, L.msg_count, P.stc_cust_project_title
    FROM stc_req_item_communicate C
    INNER JOIN (
      SELECT req_item_id, MAX(id) AS max_id, COUNT(*) AS msg_count
      FROM stc_req_item_communicate
      WHERE status = 1
      GROUP BY req_item_id
    ) L ON L.req_item_id=C.req_item_id AND L.max_id=C.id
    LEFT JOIN stc_cust_super_requisition_list_items I ON I.stc_cust_super_requisition_list_id=C.req_item_id
    LEFT JOIN stc_cust_super_requisition_list RL ON RL.stc_cust_super_requisition_list_id=C.req_id
    LEFT JOIN stc_cust_project P ON P.stc_cust_project_id=RL.stc_cust_super_requisition_list_project_id
    WHERE C.status=1
    ORDER BY C.id DESC
    LIMIT 5
  ");
  if (!$q) echo "query err=".mysqli_error($con)."\n";
  else {
    echo "sample=".mysqli_num_rows($q)."\n";
    while ($r = mysqli_fetch_assoc($q)) print_r($r);
  }
} else {
  echo "creating table...\n";
}
