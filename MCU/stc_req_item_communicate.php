<?php
date_default_timezone_set('Asia/Kolkata');

if (!function_exists('stc_req_comm_ensure_table')) {
    function stc_req_comm_ensure_table($db){
        static $done = false;
        if($done) return;
        @mysqli_query($db, "
            CREATE TABLE IF NOT EXISTS `stc_req_item_communicate` (
              `id` INT NOT NULL AUTO_INCREMENT,
              `req_item_id` INT NOT NULL,
              `req_id` INT NOT NULL DEFAULT 0,
              `message` TEXT NOT NULL,
              `image_url` VARCHAR(500) NOT NULL DEFAULT '',
              `issue_type` VARCHAR(32) NOT NULL DEFAULT 'general',
              `username` VARCHAR(150) NOT NULL DEFAULT '',
              `user_type` VARCHAR(32) NOT NULL DEFAULT '',
              `created_by` INT NOT NULL DEFAULT 0,
              `created_date` DATETIME NOT NULL,
              `status` TINYINT NOT NULL DEFAULT 1,
              PRIMARY KEY (`id`),
              KEY `idx_req_item_date` (`req_item_id`, `created_date`),
              KEY `idx_req_comm_user` (`user_type`, `created_by`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        @mysqli_query($db, "
            CREATE TABLE IF NOT EXISTS `stc_req_item_communicate_seen` (
              `id` INT NOT NULL AUTO_INCREMENT,
              `req_item_id` INT NOT NULL,
              `user_type` VARCHAR(32) NOT NULL DEFAULT '',
              `created_by` INT NOT NULL DEFAULT 0,
              `last_seen_id` INT NOT NULL DEFAULT 0,
              `last_seen_date` DATETIME NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `uniq_req_comm_seen` (`req_item_id`, `user_type`, `created_by`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        $done = true;
    }
}

if (!function_exists('stc_req_comm_actor')) {
    function stc_req_comm_actor($forceType = ''){
        $forceType = strtolower(trim((string)$forceType));
        $candidates = array(
            'subagent' => array(
                'id' => isset($_SESSION['stc_agent_sub_id']) ? (int)$_SESSION['stc_agent_sub_id'] : 0,
                'name' => trim((string)($_SESSION['stc_agent_sub_name'] ?? '')),
            ),
            'agent' => array(
                'id' => isset($_SESSION['stc_agent_id']) ? (int)$_SESSION['stc_agent_id'] : 0,
                'name' => trim((string)($_SESSION['stc_agent_name'] ?? '')),
            ),
            'vikings' => array(
                'id' => isset($_SESSION['stc_empl_id']) ? (int)$_SESSION['stc_empl_id'] : 0,
                'name' => trim((string)($_SESSION['stc_empl_name'] ?? '')),
            ),
        );
        $order = $forceType !== '' && isset($candidates[$forceType])
            ? array($forceType)
            : array('subagent', 'agent', 'vikings');
        foreach($order as $type){
            if($candidates[$type]['id'] > 0){
                $name = $candidates[$type]['name'] !== '' ? $candidates[$type]['name'] : ucfirst($type);
                return array(
                    'user_type' => $type,
                    'created_by' => $candidates[$type]['id'],
                    'username' => $name,
                );
            }
        }
        return null;
    }
}

if (!function_exists('stc_req_comm_issue_label')) {
    function stc_req_comm_issue_label($type){
        if($type === 'wrong_material') return 'Wrong Material';
        if($type === 'fault_material') return 'Fault Material';
        return '';
    }
}

if (!function_exists('stc_req_comm_list')) {
    function stc_req_comm_list($db, $reqItemId, $actor){
        stc_req_comm_ensure_table($db);
        $reqItemId = (int)$reqItemId;
        $out = array();
        $q = mysqli_query($db, "
            SELECT `id`, `req_item_id`, `req_id`, `message`, `image_url`, `issue_type`,
                   `username`, `user_type`, `created_by`, `created_date`
            FROM `stc_req_item_communicate`
            WHERE `req_item_id`='".$reqItemId."' AND `status`=1
            ORDER BY `id` DESC
        ");
        if($q){
            while($row = mysqli_fetch_assoc($q)){
                $mine = (strcasecmp((string)$row['user_type'], (string)$actor['user_type']) === 0)
                    && ((int)$row['created_by'] === (int)$actor['created_by']);
                $out[] = array(
                    'id' => (int)$row['id'],
                    'req_item_id' => (int)$row['req_item_id'],
                    'message' => (string)$row['message'],
                    'image_url' => (string)$row['image_url'],
                    'issue_type' => (string)$row['issue_type'],
                    'issue_label' => stc_req_comm_issue_label($row['issue_type']),
                    'username' => (string)$row['username'],
                    'user_type' => (string)$row['user_type'],
                    'created_date' => date('d-m-Y h:i A', strtotime($row['created_date'])),
                    'mine' => $mine,
                );
            }
        }
        return $out;
    }
}

if (!function_exists('stc_req_comm_mark_seen')) {
    function stc_req_comm_mark_seen($db, $reqItemId, $actor){
        stc_req_comm_ensure_table($db);
        $reqItemId = (int)$reqItemId;
        if($reqItemId <= 0 || empty($actor['created_by'])) return;
        $maxQ = mysqli_query($db, "SELECT MAX(`id`) AS max_id FROM `stc_req_item_communicate` WHERE `req_item_id`='".$reqItemId."' AND `status`=1");
        $maxRow = $maxQ ? mysqli_fetch_assoc($maxQ) : array();
        $maxId = (int)($maxRow['max_id'] ?? 0);
        $now = date('Y-m-d H:i:s');
        $userType = mysqli_real_escape_string($db, (string)$actor['user_type']);
        $createdBy = (int)$actor['created_by'];
        $exists = mysqli_query($db, "
            SELECT `id` FROM `stc_req_item_communicate_seen`
            WHERE `req_item_id`='".$reqItemId."' AND `user_type`='".$userType."' AND `created_by`='".$createdBy."'
            LIMIT 1
        ");
        if($exists && mysqli_num_rows($exists) > 0){
            mysqli_query($db, "
                UPDATE `stc_req_item_communicate_seen`
                SET `last_seen_id`='".$maxId."', `last_seen_date`='".$now."'
                WHERE `req_item_id`='".$reqItemId."' AND `user_type`='".$userType."' AND `created_by`='".$createdBy."'
            ");
        }else{
            mysqli_query($db, "
                INSERT INTO `stc_req_item_communicate_seen`(
                    `req_item_id`, `user_type`, `created_by`, `last_seen_id`, `last_seen_date`
                ) VALUES (
                    '".$reqItemId."', '".$userType."', '".$createdBy."', '".$maxId."', '".$now."'
                )
            ");
        }
    }
}

if (!function_exists('stc_req_comm_inbox')) {
    function stc_req_comm_inbox($db, $actor, $opts = array()){
        stc_req_comm_ensure_table($db);
        $userType = mysqli_real_escape_string($db, (string)$actor['user_type']);
        $createdBy = (int)$actor['created_by'];
        $isAdmin = (strcasecmp((string)$actor['user_type'], 'vikings') === 0);
        $showAll = $isAdmin && !empty($opts['all']);
        $limit = isset($opts['limit']) ? (int)$opts['limit'] : ($showAll ? 15 : 40);
        $offset = isset($opts['offset']) ? (int)$opts['offset'] : 0;
        if($limit < 1){ $limit = 15; }
        if($limit > 50){ $limit = 50; }
        if($offset < 0){ $offset = 0; }

        $unreadWhere = "
            WHERE C.`status`=1
              AND (S.`id` IS NULL OR S.`last_seen_id` < C.`id`)
              AND NOT (C.`user_type`='".$userType."' AND C.`created_by`='".$createdBy."')
        ";
        $unreadCnt = 0;
        $uq = mysqli_query($db, "
            SELECT COUNT(*) AS c
            FROM `stc_req_item_communicate` C
            INNER JOIN (
                SELECT `req_item_id`, MAX(`id`) AS max_id
                FROM `stc_req_item_communicate`
                WHERE `status`=1
                GROUP BY `req_item_id`
            ) L ON L.`req_item_id`=C.`req_item_id` AND L.`max_id`=C.`id`
            LEFT JOIN `stc_req_item_communicate_seen` S
                ON S.`req_item_id`=C.`req_item_id`
                AND S.`user_type`='".$userType."'
                AND S.`created_by`='".$createdBy."'
            ".$unreadWhere."
        ");
        if($uq && ($ur = mysqli_fetch_assoc($uq))){
            $unreadCnt = (int)($ur['c'] ?? 0);
        }

        $listWhere = $showAll
            ? "WHERE C.`status`=1"
            : $unreadWhere;
        $total = $showAll ? 0 : $unreadCnt;
        if($showAll){
            $tq = mysqli_query($db, "
                SELECT COUNT(*) AS c
                FROM `stc_req_item_communicate` C
                INNER JOIN (
                    SELECT `req_item_id`, MAX(`id`) AS max_id
                    FROM `stc_req_item_communicate`
                    WHERE `status`=1
                    GROUP BY `req_item_id`
                ) L ON L.`req_item_id`=C.`req_item_id` AND L.`max_id`=C.`id`
                WHERE C.`status`=1
            ");
            if($tq && ($tr = mysqli_fetch_assoc($tq))){
                $total = (int)($tr['c'] ?? 0);
            }
        }

        $out = array();
        $q = mysqli_query($db, "
            SELECT
                C.`id`,
                C.`req_item_id`,
                C.`req_id`,
                C.`message`,
                C.`image_url`,
                C.`issue_type`,
                C.`username`,
                C.`user_type`,
                C.`created_by`,
                C.`created_date`,
                S.`id` AS seen_id,
                S.`last_seen_id`,
                I.`stc_cust_super_requisition_list_items_title` AS item_title,
                P.`stc_cust_project_title` AS project_title
            FROM `stc_req_item_communicate` C
            INNER JOIN (
                SELECT `req_item_id`, MAX(`id`) AS max_id
                FROM `stc_req_item_communicate`
                WHERE `status`=1
                GROUP BY `req_item_id`
            ) L ON L.`req_item_id`=C.`req_item_id` AND L.`max_id`=C.`id`
            LEFT JOIN `stc_req_item_communicate_seen` S
                ON S.`req_item_id`=C.`req_item_id`
                AND S.`user_type`='".$userType."'
                AND S.`created_by`='".$createdBy."'
            LEFT JOIN `stc_cust_super_requisition_list_items` I
                ON I.`stc_cust_super_requisition_list_id`=C.`req_item_id`
            LEFT JOIN `stc_cust_super_requisition_list` RL
                ON RL.`stc_cust_super_requisition_list_id`=C.`req_id`
            LEFT JOIN `stc_cust_project` P
                ON P.`stc_cust_project_id`=RL.`stc_cust_super_requisition_list_project_id`
            ".$listWhere."
            ORDER BY C.`id` DESC
            LIMIT ".$limit." OFFSET ".$offset."
        ");
        if($q){
            while($row = mysqli_fetch_assoc($q)){
                $mine = (strcasecmp((string)$row['user_type'], (string)$actor['user_type']) === 0)
                    && ((int)$row['created_by'] === (int)$actor['created_by']);
                $isNew = !$mine && (
                    empty($row['seen_id']) || ((int)$row['last_seen_id'] < (int)$row['id'])
                );
                $out[] = array(
                    'id' => (int)$row['id'],
                    'req_item_id' => (int)$row['req_item_id'],
                    'req_id' => (int)$row['req_id'],
                    'message' => (string)$row['message'],
                    'image_url' => (string)$row['image_url'],
                    'issue_type' => (string)$row['issue_type'],
                    'issue_label' => stc_req_comm_issue_label($row['issue_type']),
                    'username' => (string)$row['username'],
                    'item_title' => (string)($row['item_title'] ?? ''),
                    'project_title' => (string)($row['project_title'] ?? ''),
                    'created_date' => date('d-m-Y h:i A', strtotime($row['created_date'])),
                    'is_new' => $isNew ? 1 : 0,
                );
            }
        }
        return array(
            'mode' => $showAll ? 'all' : 'new',
            'is_admin' => $isAdmin ? 1 : 0,
            'unread_count' => $unreadCnt,
            'total' => $total,
            'limit' => $limit,
            'offset' => $offset,
            'has_more' => ($offset + count($out)) < $total ? 1 : 0,
            'items' => $out,
        );
    }
}

if (!function_exists('stc_req_comm_upload_image')) {
    function stc_req_comm_upload_image($reqItemId, $file){
        if(empty($file) || !isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])){
            return array('ok' => true, 'public_url' => '');
        }
        if(!empty($file['error']) && (int)$file['error'] !== UPLOAD_ERR_OK){
            return array('ok' => false, 'error' => 'Image upload failed.');
        }
        if((int)($file['size'] ?? 0) > 8 * 1024 * 1024){
            return array('ok' => false, 'error' => 'Image must be 8MB or smaller.');
        }
        $r2Path = __DIR__ . '/product_r2_upload.php';
        if(is_file($r2Path)){
            require_once $r2Path;
        }
        $name = (string)($file['name'] ?? '');
        if(function_exists('stc_r2_product_upload_configured') && stc_r2_product_upload_configured()
            && function_exists('stc_r2_upload_safety_image_from_path')){
            $r2 = stc_r2_upload_safety_image_from_path($file['tmp_name'], 'reqcomm', (int)$reqItemId, '', $name);
            if(!empty($r2['ok']) && !empty($r2['public_url'])){
                return array('ok' => true, 'public_url' => $r2['public_url']);
            }
            return array('ok' => false, 'error' => $r2['error'] ?? 'Cloudflare upload failed.');
        }
        return array('ok' => false, 'error' => 'Cloudflare image upload is not configured.');
    }
}

if (!function_exists('stc_req_comm_write_item_log')) {
    function stc_req_comm_write_item_log($db, $reqItemId, $title, $message, $createdBy){
        @mysqli_query($db, "
            INSERT INTO `stc_cust_super_requisition_list_items_log`(
                `item_id`, `title`, `message`, `status`, `created_by`
            ) VALUES (
                '".mysqli_real_escape_string($db, (string)$reqItemId)."',
                '".mysqli_real_escape_string($db, $title)."',
                '".mysqli_real_escape_string($db, $message)."',
                '1',
                '".mysqli_real_escape_string($db, (string)$createdBy)."'
            )
        ");
    }
}

if (!function_exists('stc_req_comm_user_email')) {
    function stc_req_comm_user_email($db, $userType, $userId){
        $userId = (int)$userId;
        if($userId <= 0) return '';
        $sql = '';
        if($userType === 'subagent'){
            $sql = "SELECT `stc_cust_pro_supervisor_email` AS email FROM `stc_cust_pro_supervisor` WHERE `stc_cust_pro_supervisor_id`='".$userId."' LIMIT 1";
        }elseif($userType === 'agent'){
            $sql = "SELECT `stc_agents_email` AS email FROM `stc_agents` WHERE `stc_agents_id`='".$userId."' LIMIT 1";
        }elseif($userType === 'vikings'){
            $sql = "SELECT `stc_user_email` AS email FROM `stc_user` WHERE `stc_user_id`='".$userId."' LIMIT 1";
        }
        if($sql === '') return '';
        $q = mysqli_query($db, $sql);
        if(!$q || mysqli_num_rows($q) === 0) return '';
        $row = mysqli_fetch_assoc($q);
        $email = trim((string)($row['email'] ?? ''));
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : '';
    }
}

if (!function_exists('stc_req_comm_notify_recipients')) {
    function stc_req_comm_notify_recipients($db, $reqItemId, $item, $actor){
        $emails = array();
        $add = function($email) use (&$emails){
            $email = strtolower(trim((string)$email));
            if($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)){
                $emails[$email] = true;
            }
        };

        $add($item['supervisor_email'] ?? '');

        $agentIds = array();
        if((int)($item['supervisor_created_by'] ?? 0) > 0){
            $agentIds[] = (int)$item['supervisor_created_by'];
        }
        if((int)($item['project_created_by'] ?? 0) > 0){
            $agentIds[] = (int)$item['project_created_by'];
        }
        if($agentIds){
            $aq = mysqli_query($db, "
                SELECT `stc_agents_email`
                FROM `stc_agents`
                WHERE `stc_agents_id` IN (".implode(',', array_unique($agentIds)).")
            ");
            if($aq){
                while($ar = mysqli_fetch_assoc($aq)){
                    $add($ar['stc_agents_email'] ?? '');
                }
            }
        }

        $pq = mysqli_query($db, "
            SELECT DISTINCT `user_type`, `created_by`
            FROM `stc_req_item_communicate`
            WHERE `req_item_id`='".(int)$reqItemId."' AND `status`=1
        ");
        if($pq){
            while($pr = mysqli_fetch_assoc($pq)){
                $add(stc_req_comm_user_email($db, strtolower((string)$pr['user_type']), (int)$pr['created_by']));
            }
        }

        $senderEmail = strtolower(stc_req_comm_user_email($db, (string)$actor['user_type'], (int)$actor['created_by']));
        if($senderEmail !== ''){
            unset($emails[$senderEmail]);
        }
        return array_keys($emails);
    }
}

if (!function_exists('stc_req_comm_notify_email')) {
    function stc_req_comm_notify_email($db, $reqItemId, $reqId, $item, $message, $issueType, $imageUrl, $actor){
        $recipients = stc_req_comm_notify_recipients($db, $reqItemId, $item, $actor);
        if(!$recipients) return;

        $reqNo = (int)$reqId;
        $itemTitle = trim((string)($item['item_title'] ?? ''));
        $project = trim((string)($item['project_title'] ?? ''));
        $label = stc_req_comm_issue_label($issueType);
        if($label === '') $label = 'Message';
        $sender = trim((string)$actor['username']);
        $when = date('d-m-Y h:i A');
        $safeTitle = htmlspecialchars($itemTitle !== '' ? $itemTitle : 'Requisition item', ENT_QUOTES, 'UTF-8');
        $safeProject = htmlspecialchars($project !== '' ? $project : '-', ENT_QUOTES, 'UTF-8');
        $safeSender = htmlspecialchars($sender !== '' ? $sender : 'A user', ENT_QUOTES, 'UTF-8');
        $safeMsg = $message !== '' ? nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) : '<em>No text. A picture was shared.</em>';
        $photoHtml = $imageUrl !== ''
            ? '<p style="margin:16px 0 0;"><a href="'.htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8').'" style="color:#075e54;">View attached picture</a></p>'
            : '';

        $portal = 'https://stcassociate.com/stc_sub_agent47/stc-requisition.php?page=requisition';
        $from = 'info@stcassociate.com';
        $host = isset($_SERVER['SERVER_NAME']) ? (string)$_SERVER['SERVER_NAME'] : '';
        if(strpos($host, 'agent') !== false){
            $from = 'info@agent.stcassociate.com';
            $portal = 'https://stcassociate.com/stc_agent47/stc-supervisor-balance-reports.php?page=reports';
        }elseif(strpos($host, 'viking') !== false){
            $portal = 'https://stcassociate.com/stc_vikings/daily-requisition.php';
        }

        $subject = 'You have gained a message for your requisition #'.$reqNo;
        $body = '
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Requisition message</title></head>
<body style="margin:0;padding:0;background:#f2f2f2;">
  <table role="presentation" style="width:100%;border:none;border-spacing:0;background:#f2f2f2;">
    <tr>
      <td align="center" style="padding:24px 10px;">
        <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;background:#ffffff;font-family:Arial,sans-serif;color:#333;">
          <tr>
            <td style="padding:24px 28px;text-align:center;background:#075e54;">
              <img src="https://stcassociate.com/stc_symbiote/img/stc_logo.png" width="90" alt="STC" style="border-radius:50%;">
              <h1 style="margin:12px 0 0;font-size:20px;color:#fff;">STC Associates</h1>
            </td>
          </tr>
          <tr>
            <td style="padding:28px;">
              <p style="margin:0 0 12px;font-size:16px;">You have gained a message for your requisition.</p>
              <p style="margin:0 0 8px;"><b>Requisition No:</b> '.$reqNo.'</p>
              <p style="margin:0 0 8px;"><b>Project:</b> '.$safeProject.'</p>
              <p style="margin:0 0 8px;"><b>Item:</b> '.$safeTitle.'</p>
              <p style="margin:0 0 8px;"><b>Type:</b> '.htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'</p>
              <p style="margin:0 0 16px;"><b>From:</b> '.$safeSender.' &nbsp; <span style="color:#777;">'.$when.'</span></p>
              <div style="padding:12px 14px;background:#ece5dd;border-radius:8px;font-size:15px;line-height:22px;">'.$safeMsg.'</div>
              '.$photoHtml.'
              <p style="margin:22px 0 0;text-align:center;">
                <a href="'.$portal.'" style="display:inline-block;background:#075e54;color:#fff;text-decoration:none;padding:10px 18px;border-radius:4px;">Open communication</a>
              </p>
            </td>
          </tr>
          <tr>
            <td style="padding:16px 28px;background:#404040;color:#ccc;font-size:12px;text-align:center;">
              This is a system generated mail. Please do not reply.
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>';

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= 'From: STC Associates <'.$from.">\r\n";
        foreach($recipients as $to){
            @mail($to, $subject, $body, $headers);
        }
    }
}

if (!function_exists('stc_req_comm_send')) {
    function stc_req_comm_send($db, $reqItemId, $reqId, $message, $issueType, $file, $actor){
        stc_req_comm_ensure_table($db);
        $reqItemId = (int)$reqItemId;
        $reqId = (int)$reqId;
        $message = trim((string)$message);
        $issueType = strtolower(trim((string)$issueType));
        if(!in_array($issueType, array('general', 'wrong_material', 'fault_material'), true)){
            $issueType = 'general';
        }
        if($reqItemId <= 0){
            return array('ok' => false, 'error' => 'Invalid item.');
        }
        $chk = mysqli_query($db, "
            SELECT
                I.`stc_cust_super_requisition_list_id`,
                I.`stc_cust_super_requisition_list_items_req_id`,
                I.`stc_cust_super_requisition_list_items_title` AS item_title,
                L.`stc_cust_super_requisition_list_super_id`,
                P.`stc_cust_project_title` AS project_title,
                P.`stc_cust_project_createdby` AS project_created_by,
                S.`stc_cust_pro_supervisor_email` AS supervisor_email,
                S.`stc_cust_pro_supervisor_created_by` AS supervisor_created_by
            FROM `stc_cust_super_requisition_list_items` I
            LEFT JOIN `stc_cust_super_requisition_list` L
                ON I.`stc_cust_super_requisition_list_items_req_id` = L.`stc_cust_super_requisition_list_id`
            LEFT JOIN `stc_cust_project` P
                ON L.`stc_cust_super_requisition_list_project_id` = P.`stc_cust_project_id`
            LEFT JOIN `stc_cust_pro_supervisor` S
                ON L.`stc_cust_super_requisition_list_super_id` = S.`stc_cust_pro_supervisor_id`
            WHERE I.`stc_cust_super_requisition_list_id`='".$reqItemId."'
            LIMIT 1
        ");
        if(!$chk || mysqli_num_rows($chk) === 0){
            return array('ok' => false, 'error' => 'Requisition item not found.');
        }
        $item = mysqli_fetch_assoc($chk);
        if($reqId <= 0){
            $reqId = (int)($item['stc_cust_super_requisition_list_items_req_id'] ?? 0);
        }
        $img = stc_req_comm_upload_image($reqItemId, $file);
        if(empty($img['ok'])){
            return array('ok' => false, 'error' => $img['error'] ?? 'Image upload failed.');
        }
        $imageUrl = trim((string)($img['public_url'] ?? ''));
        if($message === '' && $imageUrl === ''){
            return array('ok' => false, 'error' => 'Enter a message or attach a picture.');
        }
        $now = date('Y-m-d H:i:s');
        $ok = mysqli_query($db, "
            INSERT INTO `stc_req_item_communicate`(
                `req_item_id`, `req_id`, `message`, `image_url`, `issue_type`,
                `username`, `user_type`, `created_by`, `created_date`, `status`
            ) VALUES (
                '".$reqItemId."',
                '".$reqId."',
                '".mysqli_real_escape_string($db, $message)."',
                '".mysqli_real_escape_string($db, $imageUrl)."',
                '".mysqli_real_escape_string($db, $issueType)."',
                '".mysqli_real_escape_string($db, $actor['username'])."',
                '".mysqli_real_escape_string($db, $actor['user_type'])."',
                '".(int)$actor['created_by']."',
                '".$now."',
                1
            )
        ");
        if(!$ok){
            return array('ok' => false, 'error' => 'Could not save message.');
        }
        $label = stc_req_comm_issue_label($issueType);
        if($label !== ''){
            $logMsg = $label.' reported by '.$actor['username'].' on '.date('d-m-Y h:i A');
            if($message !== '') $logMsg .= '<br/>'.$message;
            if($imageUrl !== '') $logMsg .= '<br/>Photo: '.$imageUrl;
            stc_req_comm_write_item_log($db, $reqItemId, $label, $logMsg, $actor['created_by']);
        }
        stc_req_comm_notify_email($db, $reqItemId, $reqId, $item, $message, $issueType, $imageUrl, $actor);
        return array('ok' => true);
    }
}

if (!function_exists('stc_req_comm_button')) {
    function stc_req_comm_button($reqItemId, $reqId = 0, $itemTitle = ''){
        $reqItemId = (int)$reqItemId;
        $reqId = (int)$reqId;
        return '<a href="#" class="btn btn-warning btn-sm stc-req-comm-open" title="Wrong / Fault material"'
            .' data-req-item-id="'.$reqItemId.'"'
            .' data-req-id="'.$reqId.'"'
            .' data-item-title="'.htmlspecialchars((string)$itemTitle, ENT_QUOTES, 'UTF-8').'">'
            .'<i class="fa fa-comments"></i>'
            .'</a>';
    }
}

if (!function_exists('stc_req_comm_inbox_button')) {
    function stc_req_comm_inbox_button($label = 'Messages'){
        $label = htmlspecialchars((string)$label, ENT_QUOTES, 'UTF-8');
        return '<div class="stc-req-comm-inbox-wrap" id="stcReqCommInbox">'
            .'<button type="button" class="stc-req-comm-inbox-btn" id="stcReqCommInboxBtn" title="Requisition messages">'
            .'<i class="fa fa-bell"></i><span>'.$label.'</span>'
            .'<em class="stc-req-comm-inbox-badge" id="stcReqCommInboxBadge" style="display:none;">0</em>'
            .'</button>'
            .'<div class="stc-req-comm-inbox-panel" id="stcReqCommInboxPanel" style="display:none;"></div>'
            .'</div>';
    }
}

if (!function_exists('stc_req_comm_handle_request')) {
    function stc_req_comm_handle_request($db, $forceType = ''){
        header('Content-Type: application/json; charset=utf-8');
        $actor = stc_req_comm_actor($forceType);
        if(!$actor){
            echo json_encode(array('ok' => false, 'error' => 'Please sign in again.'));
            exit;
        }
        $action = isset($_POST['stc_req_comm_action']) ? strtolower(trim((string)$_POST['stc_req_comm_action'])) : '';
        $reqItemId = isset($_POST['req_item_id']) ? (int)$_POST['req_item_id'] : 0;
        if($action === 'inbox'){
            $isAdmin = (strcasecmp((string)$actor['user_type'], 'vikings') === 0);
            $opts = array(
                'all' => $isAdmin ? 1 : 0,
                'limit' => isset($_POST['limit']) ? (int)$_POST['limit'] : ($isAdmin ? 15 : 40),
                'offset' => isset($_POST['offset']) ? (int)$_POST['offset'] : 0,
            );
            $inbox = stc_req_comm_inbox($db, $actor, $opts);
            echo json_encode(array(
                'ok' => true,
                'count' => (int)$inbox['unread_count'],
                'unread_count' => (int)$inbox['unread_count'],
                'total' => (int)$inbox['total'],
                'has_more' => (int)$inbox['has_more'],
                'mode' => (string)$inbox['mode'],
                'is_admin' => (int)$inbox['is_admin'],
                'limit' => (int)$inbox['limit'],
                'offset' => (int)$inbox['offset'],
                'items' => $inbox['items'],
            ));
            exit;
        }
        if($action === 'list'){
            $messages = stc_req_comm_list($db, $reqItemId, $actor);
            stc_req_comm_mark_seen($db, $reqItemId, $actor);
            echo json_encode(array('ok' => true, 'messages' => $messages));
            exit;
        }
        if($action === 'send'){
            $reqId = isset($_POST['req_id']) ? (int)$_POST['req_id'] : 0;
            $message = isset($_POST['message']) ? (string)$_POST['message'] : '';
            $issueType = isset($_POST['issue_type']) ? (string)$_POST['issue_type'] : 'general';
            $file = isset($_FILES['image']) ? $_FILES['image'] : array();
            $res = stc_req_comm_send($db, $reqItemId, $reqId, $message, $issueType, $file, $actor);
            if(!empty($res['ok'])){
                stc_req_comm_mark_seen($db, $reqItemId, $actor);
                echo json_encode(array('ok' => true, 'messages' => stc_req_comm_list($db, $reqItemId, $actor)));
            }else{
                echo json_encode(array('ok' => false, 'error' => $res['error'] ?? 'Failed.'));
            }
            exit;
        }
        echo json_encode(array('ok' => false, 'error' => 'Unknown action.'));
        exit;
    }
}

if (!function_exists('stc_req_comm_render_ui')) {
    function stc_req_comm_render_ui($apiUrl){
        $apiUrl = htmlspecialchars((string)$apiUrl, ENT_QUOTES, 'UTF-8');
        ?>
<div class="modal fade" id="stcReqCommModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content stc-req-comm-box">
      <div class="modal-header" style="background:#075e54;color:#fff;">
        <h5 class="modal-title"><i class="fa fa-comments"></i> Item Communication <span id="stcReqCommTitle" style="font-weight:400;font-size:13px;"></span></h5>
        <button type="button" class="close stc-req-comm-close" aria-label="Close" style="color:#fff;opacity:1;"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" style="padding:0;background:#ece5dd;">
        <div id="stcReqCommThread" class="stc-req-comm-thread"></div>
        <form id="stcReqCommForm" enctype="multipart/form-data">
          <input type="hidden" name="stc_req_comm_action" value="send">
          <input type="hidden" name="req_item_id" id="stcReqCommItemId" value="">
          <input type="hidden" name="req_id" id="stcReqCommReqId" value="">
          <div class="stc-req-comm-form">
            <select name="issue_type" id="stcReqCommIssue" class="form-control">
              <option value="wrong_material">Wrong Material</option>
              <option value="fault_material">Fault Material</option>
              <option value="general">Message</option>
            </select>
            <textarea name="message" id="stcReqCommMessage" class="form-control" rows="2" placeholder="Type a message..."></textarea>
            <label class="stc-req-comm-file">
              <i class="fa fa-camera"></i>
              <input type="file" name="image" id="stcReqCommImage" accept="image/*">
            </label>
            <button type="submit" class="btn btn-success" id="stcReqCommSend"><i class="fa fa-paper-plane"></i></button>
          </div>
          <div id="stcReqCommFileName" class="stc-req-comm-filename"></div>
        </form>
      </div>
    </div>
  </div>
</div>
<style>
  #stcReqCommModal.modal{z-index:20060 !important;}
  #stcReqCommModal.modal.show,
  #stcReqCommModal.modal.in{opacity:1;}
  #stcReqCommModal .modal-dialog{z-index:20061;margin:40px auto;transform:none !important;}
  body.stc-req-comm-open > .modal-backdrop,
  .stc-req-comm-backdrop{z-index:20040 !important;opacity:0.45;}
  .stc-req-comm-close{cursor:pointer;}
  .stc-req-comm-thread{height:420px;overflow-y:auto;padding:14px 12px;background:#ece5dd;}
  .stc-req-comm-row{display:flex;margin-bottom:10px;}
  .stc-req-comm-row.in{justify-content:flex-start;}
  .stc-req-comm-row.out{justify-content:flex-end;}
  .stc-req-comm-bubble{max-width:78%;padding:8px 10px;border-radius:8px;box-shadow:0 1px 1px rgba(0,0,0,.08);word-break:break-word;}
  .stc-req-comm-row.in .stc-req-comm-bubble{background:#fff;}
  .stc-req-comm-row.out .stc-req-comm-bubble{background:#dcf8c6;}
  .stc-req-comm-meta{font-size:11px;color:#667781;margin-bottom:3px;}
  .stc-req-comm-tag{display:inline-block;background:#f39c12;color:#fff;border-radius:3px;padding:1px 6px;font-size:10px;margin-bottom:4px;}
  .stc-req-comm-bubble img{max-width:220px;max-height:220px;display:block;margin-top:6px;border-radius:6px;}
  .stc-req-comm-form{display:flex;align-items:center;gap:8px;padding:10px;background:#f0f0f0;}
  .stc-req-comm-form select{max-width:160px;}
  .stc-req-comm-form textarea{flex:1;resize:none;}
  .stc-req-comm-file{margin:0;cursor:pointer;font-size:20px;color:#54656f;}
  .stc-req-comm-file input{display:none;}
  .stc-req-comm-filename{padding:0 12px 8px;font-size:12px;color:#555;background:#f0f0f0;}
  .stc-req-comm-empty{text-align:center;color:#667781;padding:40px 10px;}
  a.stc-req-comm-open{margin-left:4px;}
  a.stc-req-comm-open.has-unread{box-shadow:0 0 0 2px #e74c3c;}
  .stc-req-comm-headbar{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:12px;min-height:38px;}
  .stc-req-comm-headbar h5{grid-column:2;margin:0;text-align:center;white-space:nowrap;}
  .stc-req-comm-inbox-wrap{grid-column:3;justify-self:end;position:relative;z-index:2;}
  .stc-req-comm-inbox-btn{
    display:inline-flex;align-items:center;gap:8px;
    height:38px;padding:0 14px;border:1px solid #d9dee3;border-radius:8px;
    background:#fff;color:#2d3748;font-weight:600;font-size:13px;line-height:1;
    box-shadow:0 1px 2px rgba(16,24,40,.06);cursor:pointer;
  }
  .stc-req-comm-inbox-btn:hover{border-color:#c5ccd3;background:#f8fafc;}
  .stc-req-comm-inbox-btn i{color:#e67e22;}
  .stc-req-comm-inbox-badge{
    display:none;min-width:18px;height:18px;padding:0 5px;border-radius:9px;
    background:#e74c3c;color:#fff;font-style:normal;font-weight:700;font-size:11px;
    line-height:18px;text-align:center;
  }
  .stc-req-comm-inbox-panel{
    position:fixed;width:420px;max-width:calc(100vw - 24px);max-height:520px;
    overflow:hidden;z-index:20070;background:#fff;border:1px solid #e5e7eb;
    border-radius:12px;box-shadow:0 16px 40px rgba(15,23,42,.18);
    white-space:normal;text-align:left;font-family:inherit;
    display:flex;flex-direction:column;
  }
  .stc-req-comm-inbox-head{
    padding:12px 14px;background:#075e54;color:#fff;font-weight:700;font-size:14px;flex-shrink:0;
  }
  .stc-req-comm-inbox-list{max-height:400px;overflow-y:auto;-webkit-overflow-scrolling:touch;}
  .stc-req-comm-inbox-item{
    display:flex;align-items:flex-start;gap:10px;padding:12px 14px;
    border-bottom:1px solid #f1f5f9;color:#1f2937;text-decoration:none;background:#fff;
  }
  .stc-req-comm-inbox-item.is-new{background:#fff8f0;}
  .stc-req-comm-inbox-item:last-child{border-bottom:0;}
  .stc-req-comm-inbox-item:hover{background:#f0fdf8;color:#1f2937;text-decoration:none;}
  .stc-req-comm-inbox-ico{
    width:34px;height:34px;border-radius:50%;background:#fff4e5;color:#e67e22;
    display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;
  }
  .stc-req-comm-inbox-body{min-width:0;flex:1;}
  .stc-req-comm-inbox-title{font-size:13px;font-weight:700;line-height:1.35;word-break:break-word;}
  .stc-req-comm-inbox-title .stc-req-comm-new-badge{
    display:inline-block;vertical-align:middle;margin-left:6px;
    background:#e74c3c;color:#fff;border-radius:3px;padding:1px 6px;
    font-size:10px;font-weight:700;letter-spacing:.3px;line-height:1.4;
  }
  .stc-req-comm-inbox-meta,.stc-req-comm-inbox-msg,.stc-req-comm-inbox-foot{
    display:block;font-size:12px;color:#667781;margin-top:3px;word-break:break-word;
  }
  .stc-req-comm-inbox-msg{color:#334155;}
  .stc-req-comm-inbox-tag{
    display:inline-block;background:#f39c12;color:#fff;border-radius:3px;
    padding:1px 6px;font-size:10px;font-weight:700;margin-right:6px;
  }
  .stc-req-comm-inbox-empty{padding:28px 16px;text-align:center;color:#7b8794;font-size:13px;}
  .stc-req-comm-inbox-more{
    display:block;width:100%;border:0;border-top:1px solid #eef2f7;background:#f8fafc;
    color:#075e54;font-weight:700;font-size:13px;padding:12px 14px;cursor:pointer;flex-shrink:0;
  }
  .stc-req-comm-inbox-more:hover{background:#eef8f5;}
  .stc-req-comm-inbox-more[disabled]{opacity:.6;cursor:wait;}
  .stc-req-comm-inbox-btn.stc-req-comm-has-new{border-color:#f5c6cb;background:#fff5f5;}
  .stc-req-comm-inbox-btn.stc-req-comm-has-new i{color:#e74c3c;}
</style>
<script>
(function(){
  var apiUrl = <?php echo json_encode((string)$apiUrl); ?>;
  function jq(){ return window.jQuery; }
  function esc(s){ return jq()('<div>').text(s == null ? '' : String(s)).html(); }
  function renderMessages(list){
    var $ = jq();
    var html = '';
    if(!list || !list.length){
      html = '<div class="stc-req-comm-empty">No messages yet. Report wrong or fault material here.</div>';
    }else{
      list.forEach(function(m){
        var side = m.mine ? 'out' : 'in';
        var tag = m.issue_label ? '<div class="stc-req-comm-tag">'+esc(m.issue_label)+'</div>' : '';
        var img = m.image_url ? '<a href="'+esc(m.image_url)+'" target="_blank"><img src="'+esc(m.image_url)+'" alt="photo"></a>' : '';
        var text = m.message ? '<div>'+esc(m.message).replace(/\n/g,'<br>')+'</div>' : '';
        html += '<div class="stc-req-comm-row '+side+'"><div class="stc-req-comm-bubble">'
          + '<div class="stc-req-comm-meta"><b>'+esc(m.username)+'</b> · '+esc(m.created_date)+'</div>'
          + tag + text + img + '</div></div>';
      });
    }
    $('#stcReqCommThread').html(html);
  }
  function loadMessages(){
    var $ = jq();
    var itemId = $('#stcReqCommItemId').val();
    $('#stcReqCommThread').html('<div class="stc-req-comm-empty">Loading...</div>');
    $.post(apiUrl, { stc_req_comm_action:'list', req_item_id:itemId }, function(res){
      if(res && res.ok){ renderMessages(res.messages); refreshInbox(); }
      else { $('#stcReqCommThread').html('<div class="stc-req-comm-empty">'+(res && res.error ? esc(res.error) : 'Could not load messages.')+'</div>'); }
    }, 'json').fail(function(){
      $('#stcReqCommThread').html('<div class="stc-req-comm-empty">Could not load messages.</div>');
    });
  }
  var inboxState = { items: [], offset: 0, limit: 15, hasMore: 0, mode: 'new', unread: 0, loading: false };
  function inboxItemHtml(it){
    var preview = it.message ? it.message : (it.image_url ? 'Photo attached' : 'New message');
    var tag = it.issue_label ? '<span class="stc-req-comm-inbox-tag">'+esc(it.issue_label)+'</span>' : '';
    var isNew = parseInt(it.is_new, 10) === 1;
    var newBadge = isNew ? ' <span class="stc-req-comm-new-badge">NEW</span>' : '';
    return '<a href="#" class="stc-req-comm-inbox-item stc-req-comm-open'+(isNew ? ' is-new' : '')+'"'
      + ' data-req-item-id="'+esc(it.req_item_id)+'"'
      + ' data-req-id="'+esc(it.req_id)+'"'
      + ' data-item-title="'+esc(it.item_title || '')+'">'
      + '<div class="stc-req-comm-inbox-ico"><i class="fa fa-comments"></i></div>'
      + '<div class="stc-req-comm-inbox-body">'
      + '<div class="stc-req-comm-inbox-title">'+esc(it.item_title || ('Item #'+it.req_item_id))+newBadge+'</div>'
      + '<div class="stc-req-comm-inbox-meta">Req# '+esc(it.req_id)+' · '+esc(it.project_title || '-')+'</div>'
      + '<div class="stc-req-comm-inbox-msg">'+esc(preview)+'</div>'
      + '<div class="stc-req-comm-inbox-foot">'+tag+esc(it.username)+' · '+esc(it.created_date)+'</div>'
      + '</div></a>';
  }
  function syncUnreadMarks(items){
    var $ = jq();
    $('.stc-req-comm-open').removeClass('has-unread');
    (items || []).forEach(function(it){
      if(parseInt(it.is_new, 10) === 1){
        $('.stc-req-comm-open[data-req-item-id="'+it.req_item_id+'"]').addClass('has-unread');
      }
    });
  }
  function paintInboxPanel(keepScroll){
    var $ = jq();
    var $panel = $('#stcReqCommInboxPanel');
    if(!$panel.length) return;
    var $list = $panel.find('.stc-req-comm-inbox-list');
    var scrollTop = keepScroll && $list.length ? $list.scrollTop() : 0;
    var items = inboxState.items || [];
    var head = inboxState.mode === 'all' ? 'All requisition messages' : 'New requisition messages';
    var html = '<div class="stc-req-comm-inbox-head">'+head+'</div><div class="stc-req-comm-inbox-list">';
    if(!items.length){
      html += '<div class="stc-req-comm-inbox-empty">'+(inboxState.mode === 'all' ? 'No requisition messages yet.' : 'No new requisition messages.')+'</div>';
    }else{
      items.forEach(function(it){ html += inboxItemHtml(it); });
    }
    html += '</div>';
    if(inboxState.mode === 'all' && parseInt(inboxState.hasMore, 10) === 1){
      html += '<button type="button" class="stc-req-comm-inbox-more" id="stcReqCommInboxMore">Show more</button>';
    }
    $panel.html(html);
    if(keepScroll){
      $panel.find('.stc-req-comm-inbox-list').scrollTop(scrollTop);
    }
    syncUnreadMarks(items);
  }
  function renderInbox(res, append){
    var $ = jq();
    var $badge = $('#stcReqCommInboxBadge');
    var $btn = $('#stcReqCommInboxBtn');
    if(!$badge.length) return;
    var items = (res && res.items) ? res.items : [];
    var unread = parseInt((res && (res.unread_count != null ? res.unread_count : res.count)) || 0, 10) || 0;
    inboxState.mode = (res && res.mode) ? res.mode : 'new';
    inboxState.limit = parseInt((res && res.limit) || inboxState.limit, 10) || 15;
    inboxState.hasMore = parseInt((res && res.has_more) || 0, 10) || 0;
    inboxState.unread = unread;
    if(append){
      inboxState.items = (inboxState.items || []).concat(items);
      inboxState.offset = inboxState.items.length;
    }else{
      inboxState.items = items;
      inboxState.offset = items.length;
    }
    if(unread > 0){
      $badge.text(unread > 99 ? '99+' : unread).show();
      $btn.addClass('stc-req-comm-has-new');
    }else{
      $badge.hide();
      $btn.removeClass('stc-req-comm-has-new');
    }
    paintInboxPanel(!!append);
  }
  function refreshInbox(append){
    var $ = jq();
    if(!$('#stcReqCommInbox').length || inboxState.loading) return;
    var offset = append ? (inboxState.offset || 0) : 0;
    var limit = inboxState.limit || 15;
    inboxState.loading = true;
    if(append){ $('#stcReqCommInboxMore').prop('disabled', true).text('Loading...'); }
    $.post(apiUrl, { stc_req_comm_action:'inbox', limit: limit, offset: offset }, function(res){
      inboxState.loading = false;
      if(res && res.ok){ renderInbox(res, !!append); }
      else if(append){ $('#stcReqCommInboxMore').prop('disabled', false).text('Show more'); }
    }, 'json').fail(function(){
      inboxState.loading = false;
      if(append){ $('#stcReqCommInboxMore').prop('disabled', false).text('Show more'); }
    });
  }
  function openCommModal(){
    var $ = jq();
    var $modal = $('#stcReqCommModal');
    if($modal.parent()[0] !== document.body){
      $modal.appendTo('body');
    }
    $('body').addClass('stc-req-comm-open modal-open');
    $modal.stop(true, true).addClass('show in').css({ display: 'block', opacity: 1 }).attr('aria-hidden', 'false');
    if(!$('.stc-req-comm-backdrop').length){
      $('<div class="modal-backdrop fade show in stc-req-comm-backdrop"></div>').css({ zIndex: 20040, opacity: 0.45 }).appendTo('body');
    }
  }
  function closeCommModal(){
    var $ = jq();
    var $modal = $('#stcReqCommModal');
    if(!$modal.length || $modal.data('stcClosing')) return;
    $modal.data('stcClosing', 1);
    var $backdrop = $('.stc-req-comm-backdrop');
    $modal.attr('aria-hidden', 'true').removeClass('show in');
    $modal.animate({ opacity: 0 }, 120, function(){
      $modal.hide().css('opacity', '');
      $modal.data('stcClosing', 0);
    });
    $backdrop.animate({ opacity: 0 }, 120, function(){ $backdrop.remove(); });
    $('body').removeClass('stc-req-comm-open');
    if(!$('.modal.show:visible, .modal.in:visible').not($modal).length){
      $('body').removeClass('modal-open').css({ 'padding-right': '', 'overflow': '' });
      $('.modal-backdrop').not('.stc-req-comm-backdrop').remove();
    }
  }
  function bindCommUi(){
    var $ = jq();
    if(!$ || $.stcReqCommBound){ return; }
    $.stcReqCommBound = true;
    $(document).on('click', '.stc-req-comm-open', function(e){
      e.preventDefault();
      e.stopPropagation();
      var $btn = $(this);
      $('#stcReqCommItemId').val($btn.attr('data-req-item-id') || $btn.data('req-item-id') || '');
      $('#stcReqCommReqId').val($btn.attr('data-req-id') || $btn.data('req-id') || 0);
      var title = $btn.attr('data-item-title') || $btn.data('item-title') || '';
      $('#stcReqCommTitle').text(title ? ' — '+title : '');
      $('#stcReqCommMessage').val('');
      $('#stcReqCommImage').val('');
      $('#stcReqCommFileName').text('');
      $('#stcReqCommIssue').val('wrong_material');
      openCommModal();
      loadMessages();
    });
    function placeInboxPanel(){
      var $btn = $('#stcReqCommInboxBtn');
      var $panel = $('#stcReqCommInboxPanel');
      if(!$btn.length || !$panel.length) return;
      $panel.appendTo('body');
      var r = $btn[0].getBoundingClientRect();
      var width = Math.min(420, window.innerWidth - 24);
      var left = Math.min(window.innerWidth - width - 12, Math.max(12, r.right - width));
      $panel.css({
        position: 'fixed',
        top: (r.bottom + 8) + 'px',
        left: left + 'px',
        right: 'auto',
        width: width + 'px',
        display: 'flex'
      });
    }
    $(document).on('click', '#stcReqCommInboxBtn', function(e){
      e.preventDefault();
      e.stopPropagation();
      var $panel = $('#stcReqCommInboxPanel');
      if($panel.is(':visible')){
        $panel.hide();
      }else{
        refreshInbox(false);
        placeInboxPanel();
      }
    });
    $(document).on('click', '#stcReqCommInboxMore', function(e){
      e.preventDefault();
      e.stopPropagation();
      refreshInbox(true);
    });
    $(document).on('click', function(e){
      if(!$(e.target).closest('#stcReqCommInbox, #stcReqCommInboxPanel').length){
        $('#stcReqCommInboxPanel').hide();
      }
    });
    $(document).on('click', '#stcReqCommInboxPanel .stc-req-comm-open', function(){
      $('#stcReqCommInboxPanel').hide();
    });
    $(window).on('resize scroll', function(){
      if($('#stcReqCommInboxPanel').is(':visible')){
        placeInboxPanel();
      }
    });
    $(document).on('click', '.stc-req-comm-close, .stc-req-comm-backdrop', function(e){
      e.preventDefault();
      e.stopPropagation();
      closeCommModal();
      refreshInbox();
    });
    $(document).on('keydown.stcReqComm', function(e){
      if((e.key === 'Escape' || e.keyCode === 27) && $('#stcReqCommModal').hasClass('show')){
        closeCommModal();
        refreshInbox();
      }
    });
    $(document).on('click', '#stcReqCommModal', function(e){
      if(e.target === this){
        closeCommModal();
        refreshInbox();
      }
    });
    $(document).on('change', '#stcReqCommImage', function(){
      var f = this.files && this.files[0] ? this.files[0].name : '';
      $('#stcReqCommFileName').text(f);
    });
    $(document).on('submit', '#stcReqCommForm', function(e){
      e.preventDefault();
      var $btn = $('#stcReqCommSend').prop('disabled', true);
      var fd = new FormData(this);
      $.ajax({
        url: apiUrl,
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(res){
          $btn.prop('disabled', false);
          if(res && res.ok){
            $('#stcReqCommMessage').val('');
            $('#stcReqCommImage').val('');
            $('#stcReqCommFileName').text('');
            renderMessages(res.messages);
            refreshInbox();
          }else{
            alert((res && res.error) ? res.error : 'Could not send.');
          }
        },
        error: function(){
          $btn.prop('disabled', false);
          alert('Could not send.');
        }
      });
    });
    window.stcReqCommRefreshInbox = refreshInbox;
    refreshInbox();
    setInterval(refreshInbox, 45000);
  }
  if(window.jQuery){ bindCommUi(); }
  else {
    document.addEventListener('DOMContentLoaded', function(){
      if(window.jQuery){ bindCommUi(); }
    });
  }
})();
</script>
        <?php
    }
}
