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
            SELECT `stc_cust_super_requisition_list_id`, `stc_cust_super_requisition_list_items_req_id`
            FROM `stc_cust_super_requisition_list_items`
            WHERE `stc_cust_super_requisition_list_id`='".$reqItemId."'
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
        if($action === 'list'){
            echo json_encode(array('ok' => true, 'messages' => stc_req_comm_list($db, $reqItemId, $actor)));
            exit;
        }
        if($action === 'send'){
            $reqId = isset($_POST['req_id']) ? (int)$_POST['req_id'] : 0;
            $message = isset($_POST['message']) ? (string)$_POST['message'] : '';
            $issueType = isset($_POST['issue_type']) ? (string)$_POST['issue_type'] : 'general';
            $file = isset($_FILES['image']) ? $_FILES['image'] : array();
            $res = stc_req_comm_send($db, $reqItemId, $reqId, $message, $issueType, $file, $actor);
            if(!empty($res['ok'])){
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;opacity:1;"><span>&times;</span></button>
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
</style>
<script>
(function($){
  var apiUrl = <?php echo json_encode((string)$apiUrl); ?>;
  function esc(s){ return $('<div>').text(s == null ? '' : String(s)).html(); }
  function renderMessages(list){
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
    var itemId = $('#stcReqCommItemId').val();
    $('#stcReqCommThread').html('<div class="stc-req-comm-empty">Loading...</div>');
    $.post(apiUrl, { stc_req_comm_action:'list', req_item_id:itemId }, function(res){
      if(res && res.ok){ renderMessages(res.messages); }
      else { $('#stcReqCommThread').html('<div class="stc-req-comm-empty">'+(res && res.error ? esc(res.error) : 'Could not load messages.')+'</div>'); }
    }, 'json').fail(function(){
      $('#stcReqCommThread').html('<div class="stc-req-comm-empty">Could not load messages.</div>');
    });
  }
  $(document).on('click', '.stc-req-comm-open', function(e){
    e.preventDefault();
    e.stopPropagation();
    var $btn = $(this);
    $('#stcReqCommItemId').val($btn.data('req-item-id') || '');
    $('#stcReqCommReqId').val($btn.data('req-id') || 0);
    var title = $btn.data('item-title') || '';
    $('#stcReqCommTitle').text(title ? ' — '+title : '');
    $('#stcReqCommMessage').val('');
    $('#stcReqCommImage').val('');
    $('#stcReqCommFileName').text('');
    $('#stcReqCommIssue').val('wrong_material');
    $('#stcReqCommModal').modal('show');
    loadMessages();
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
})(jQuery);
</script>
        <?php
    }
}
