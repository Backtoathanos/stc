<?php 
    include "../../MCU/db.php";
    $get_reqqry=mysqli_query($con, "
        SELECT
            `stc_cust_super_requisition_list_id`,
            `stc_cust_super_requisition_list_date`,
            `stc_cust_super_requisition_list_status`
        FROM
            `stc_cust_super_requisition_list`
        WHERE
            (
                DATE(`stc_cust_super_requisition_list_date`) BETWEEN '2023-12-2' AND '2023-12-12'
            ) 
        AND `stc_cust_super_requisition_list_status`=1
    ");
    if(mysqli_num_rows($get_reqqry)>0){
        echo "Executed query handled with ".mysqli_num_rows($get_reqqry). " no of requisitions.</br>";
        $counter=0;
        foreach($get_reqqry as $get_reqrow){
            echo $counter." Requisiton found!</br>";
            $reqid=$get_reqrow['stc_cust_super_requisition_list_id'];
            $get_reqitemqry=mysqli_query($con, "
                SELECT
                    `stc_cust_super_requisition_list_id`,
                    `stc_cust_super_requisition_list_items_status`
                FROM
                    `stc_cust_super_requisition_list_items`
                WHERE
                     `stc_cust_super_requisition_list_items_req_id`=".$reqid
            );
            foreach($get_reqitemqry as $get_reqitemrow){
                echo $counter." Requisiton items found!</br>";
                if($get_reqitemrow['stc_cust_super_requisition_list_items_status']==2){
                    $counter++;
                    $update_reqqry=mysqli_query($con, "UPDATE `stc_cust_super_requisition_list_items` SET `stc_cust_super_requisition_list_items_status`=1 WHERE `stc_cust_super_requisition_list_items_approved_qty`<>0 AND `stc_cust_super_requisition_list_items_req_id`=".$reqid);
                    $update_reqqry=mysqli_query($con, "UPDATE `stc_cust_super_requisition_list` SET `stc_cust_super_requisition_list_status`=2 WHERE `stc_cust_super_requisition_list_id`=".$reqid);
                }
            }
        }
        echo "Algorithm executed successfully. No of ".$counter. " Requisitions items handled.";
    }
?>