<?php 
                                          include_once("../../MCU/db.php");
                                          date_default_timezone_set('Asia/Kolkata');
                                          $day_array=array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
                                          $check_attendanceforsc=mysqli_query($con, "
                                            SELECT `stc_school_teacher_attendance_scheduleid` FROM `stc_school_teacher_attendance` 
                                            WHERE `stc_school_teacher_attendance_status`=1
                                            AND `stc_school_teacher_attendance_createdby`='".$_SESSION['stc_school_user_id']."'
                                          ");
                                          $att_counter=0;
                                          $schedule_id=0;
                                          if(mysqli_num_rows($check_attendanceforsc)>0){
                                            $att_counter++;
                                            foreach($check_attendanceforsc as $check_attendanceforscrow){
                                              $schedule_id=$check_attendanceforscrow['stc_school_teacher_attendance_scheduleid'];
                                            }
                                          }
                                          $odinclassqry=mysqli_query($con, "
                                            SELECT `stc_school_teacher_attendance_scheduleid` FROM `stc_school_teacher_attendance` 
                                            WHERE `stc_school_teacher_attendance_status`=1
                                            AND `stc_school_teacher_attendance_createdby`='".$_SESSION['stc_school_user_id']."'
                                          ");
                                          
                                          foreach($day_array as $day){
                                            $tr_color='';
                                            $counter=0;
                                            $periods='';
                                            $days=date("l");
                                            for($i=0;$i<7;$i++){
                                              $counter++;
                                              $query="
                                                SELECT
                                                  `stc_school_class_title`,
                                                  `stc_school_teacher_schedule_id`,
                                                  `stc_school_subject_title`,
                                                  `stc_school_teacher_schedule_day`,
                                                  `stc_school_teacher_schedule_classid`,
                                                  `stc_school_teacher_schedule_subjectid`,
                                                  `stc_school_teacher_schedule_begtime`,
                                                  `stc_school_teacher_schedule_endtime` 
                                                FROM `stc_school_teacher_schedule`
                                                LEFT JOIN `stc_school_class`
                                                ON `stc_school_teacher_schedule_classid`=`stc_school_class_id`
                                                LEFT JOIN `stc_school_subject`
                                                ON `stc_school_teacher_schedule_subjectid`=`stc_school_subject_id`
                                                WHERE `stc_school_teacher_schedule_day`='".$day."'
                                                AND `stc_school_teacher_schedule_teacherid`='".$_SESSION['stc_school_teacher_id']."'
                                                AND `stc_school_teacher_schedule_period`='".$counter."'
                                              ";
                                              $periodquery=mysqli_query($con, $query);
                                              if(mysqli_num_rows($periodquery)>0){
                                                foreach($periodquery as $period){
                                                  
                                                  $time=date('h:i');
                                                  $time_flag="n";
                                                  if($day==$days){
                                                    $tr_color="style='background:#fdff32;'";
                                                    $time_flag = ($time > date('h:i', strtotime($period['stc_school_teacher_schedule_begtime']))) && ($time < date('h:i', strtotime($period['stc_school_teacher_schedule_endtime']))) ? "y" : "n";
                                                  } 
                                                  if($att_counter>0){
                                                    if($time_flag=="y"){
                                                      $periods.='                                                        
                                                        <td class="text-center">
                                                          <a href="javascript:void(0);" class="stc-school-show-student-default"  data-toggle="modal" data-target="#exampleModal" id="'.$period['stc_school_teacher_schedule_id'].'" class-id="'.$period['stc_school_teacher_schedule_classid'].'" sub-id="'.$period['stc_school_teacher_schedule_subjectid'].'">
                                                              <b>
                                                                Class - '.$period['stc_school_class_title'].'<br>
                                                                '.$period['stc_school_subject_title'].'<br>
                                                                '.date('h:i', strtotime($period['stc_school_teacher_schedule_begtime'])).' - 
                                                                '.date('h:i', strtotime($period['stc_school_teacher_schedule_endtime'])).'
                                                              </b>
                                                          </a>
                                                        </td>
                                                      ';
                                                    }else{
                                                      $periods.='
                                                        <td class="text-center">
                                                          <b>
                                                            Class - '.$period['stc_school_class_title'].'<br>
                                                            '.$period['stc_school_subject_title'].'<br>
                                                            '.date('h:i a', strtotime($period['stc_school_teacher_schedule_begtime'])).' - 
                                                            '.date('h:i a', strtotime($period['stc_school_teacher_schedule_endtime'])).' 
                                                          </b>
                                                        </td>
                                                      ';
                                                    }
                                                  }else{
                                                    if($time_flag=="y"){
                                                      $periods.='
                                                        <td class="text-center"  style="background: #8cff32;font-weight: bold;">
                                                          <a href="javascript:void(0);" class="stc-school-show-student" data-toggle="modal" data-target="#exampleModal" id="'.$period['stc_school_teacher_schedule_id'].'" class-id="'.$period['stc_school_teacher_schedule_classid'].'" sub-id="'.$period['stc_school_teacher_schedule_subjectid'].'">
                                                              <b>
                                                                Class - '.$period['stc_school_class_title'].'<br>
                                                                '.$period['stc_school_subject_title'].'<br>
                                                                '.date('h:i', strtotime($period['stc_school_teacher_schedule_begtime'])).' - 
                                                                '.date('h:i', strtotime($period['stc_school_teacher_schedule_endtime'])).'
                                                              </b>
                                                          </a>
                                                        </td>
                                                      ';
                                                    }else{
                                                      $periods.='
                                                        <td class="text-center">
                                                          <b>
                                                            Class - '.$period['stc_school_class_title'].'<br>
                                                            '.$period['stc_school_subject_title'].'<br>
                                                            '.date('h:i a', strtotime($period['stc_school_teacher_schedule_begtime'])).' - 
                                                            '.date('h:i a', strtotime($period['stc_school_teacher_schedule_endtime'])).' 
                                                          </b>
                                                        </td>
                                                      ';
                                                    }
                                                  }
                                                }
                                              }else{
                                                $periods.='<td>NA</td>';
                                              }
                                            }
                                            
                                            echo '
                                              <tr '.$tr_color.'>
                                                <td>'.$day.'</td>
                                                '.$periods.'
                                              </tr>    
                                            ';
                                          }
                                        ?>

                                        
                              <!-- <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <table class="table table-hover table-bordered table-responsive">
                                      <thead>
                                        <tr>
                                          <td class="text-center"><b>Day</b></td>
                                          <td class="text-center"><b>1<span style="vertical-align: top;font-size: 11px;">st</span> Period</b></td>
                                          <td class="text-center"><b>2<span style="vertical-align: top;font-size: 11px;">nd</span> Period</b></td>
                                          <td class="text-center"><b>3<span style="vertical-align: top;font-size: 11px;">rd</span> Period</b></td>
                                          <td class="text-center"><b>4<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></td>
                                          <td class="text-center"><b>5<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></td>
                                          <td class="text-center"><b>6<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></td>
                                          <td class="text-center"><b>7<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></td>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      <?php 
                                          // include_once("../../MCU/db.php");
                                          // date_default_timezone_set('Asia/Kolkata');
                                          // $day_array=array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
                                          // $data='';

                                          // $check_attendanceforsc=mysqli_query($con, "
                                          //   SELECT `stc_school_teacher_attendance_scheduleid` FROM `stc_school_teacher_attendance` 
                                          //   WHERE `stc_school_teacher_attendance_status`=1
                                          //   AND `stc_school_teacher_attendance_createdby`='".$_SESSION['stc_school_user_id']."'
                                          // ");
                                          // $att_counter=0;
                                          // $schedule_id=0;
                                          // if(mysqli_num_rows($check_attendanceforsc)>0){
                                          //   $att_counter++;
                                          //   foreach($check_attendanceforsc as $check_attendanceforscrow){
                                          //     $schedule_id=$check_attendanceforscrow['stc_school_teacher_attendance_scheduleid'];
                                          //   }
                                          // }

                                          // foreach($day_array as $class_row){
                                          //   $tr_color='';
                                          //   $schedule_data='';
                                          //   $rev_counter=7;
                                          //   $schedule_sql=mysqli_query($con, "
                                          //     SELECT 
                                          //       `stc_school_class_title`,
                                          //       `stc_school_teacher_schedule_id`,
                                          //       `stc_school_subject_title`,
                                          //       `stc_school_teacher_schedule_day`,
                                          //       `stc_school_teacher_schedule_classid`,
                                          //       `stc_school_teacher_schedule_subjectid`,
                                          //       `stc_school_teacher_schedule_begtime`,
                                          //       `stc_school_teacher_schedule_endtime` 
                                          //     FROM 
                                          //       `stc_school_teacher_schedule` 
                                          //     LEFT JOIN 
                                          //       `stc_school_subject`
                                          //     ON 
                                          //       `stc_school_teacher_schedule_subjectid`=`stc_school_subject_id`
                                          //     LEFT JOIN 
                                          //       `stc_school_class`
                                          //     ON 
                                          //       `stc_school_teacher_schedule_classid`=`stc_school_class_id`
                                          //     WHERE 
                                          //       `stc_school_teacher_schedule_day`='".$class_row."' AND 
                                          //       `stc_school_teacher_schedule_teacherid`='".$_SESSION['stc_school_teacher_id']."'
                                          //     ORDER BY TIME(`stc_school_teacher_schedule_begtime`) ASC
                                          //   ");
                                          //   foreach($schedule_sql as $schedule_row){                                                
                                          //     $rev_counter--;
                                              
                                          //     $missed_class = '<span style="bfont-size: 110px; color: red; position: absolute; margin: 25px 5px 5px 0px;">X</span>';
                                          //     $completed_class = '<span style="font-size: 110px;color: green;position: absolute;margin: 28px 5px 5px -20px;">✔</span>';
                                          //     $day=date("l");
                                          //     if($att_counter>0){
                                          //       if($schedule_id==$schedule_row['stc_school_teacher_schedule_id']){
                                          //         $schedule_data.='
                                          //           <td class="text-center">
                                          //             <a href="javascript:void(0);" class="stc-school-show-student-default"  data-toggle="modal" data-target="#exampleModal" id="'.$schedule_row['stc_school_teacher_schedule_id'].'" class-id="'.$schedule_row['stc_school_teacher_schedule_classid'].'" sub-id="'.$schedule_row['stc_school_teacher_schedule_subjectid'].'">
                                          //                 <b>
                                          //                   Class - '.$schedule_row['stc_school_class_title'].'<br>
                                          //                   '.$schedule_row['stc_school_subject_title'].'<br>
                                          //                   '.date('h:i', strtotime($schedule_row['stc_school_teacher_schedule_begtime'])).' - 
                                          //                   '.date('h:i', strtotime($schedule_row['stc_school_teacher_schedule_endtime'])).'
                                          //                 </b>
                                          //             </a>
                                          //           </td>
                                          //         ';
                                          //       }else{
                                          //         $schedule_data.='
                                          //             <td class="text-center">
                                          //                 <b>
                                          //                   Class - '.$schedule_row['stc_school_class_title'].'<br>
                                          //                   '.$schedule_row['stc_school_subject_title'].'<br>
                                          //                   '.date('h:i', strtotime($schedule_row['stc_school_teacher_schedule_begtime'])).' - 
                                          //                   '.date('h:i', strtotime($schedule_row['stc_school_teacher_schedule_endtime'])).'
                                          //                 </b>
                                                      
                                          //             </td>
                                          //         ';
                                          //       }
                                          //     }else{
                                          //       if($class_row!=$day){
                                          //         $schedule_data.='
                                          //             <td class="text-center">
                                          //                 <b>
                                          //                   Class - '.$schedule_row['stc_school_class_title'].'<br>
                                          //                   '.$schedule_row['stc_school_subject_title'].'<br>
                                          //                   '.date('h:i', strtotime($schedule_row['stc_school_teacher_schedule_begtime'])).' - 
                                          //                   '.date('h:i', strtotime($schedule_row['stc_school_teacher_schedule_endtime'])).'
                                          //                 </b>
                                                      
                                          //             </td>
                                          //         ';
                                          //       }else{
                                          //         $tr_color="background:#fdff32;";
                                          //         if($schedule_row['stc_school_subject_title']=="NA"){
                                          //           $schedule_data.='
                                          //               <td class="text-center">
                                          //                   <b>
                                          //                     '.$schedule_row['stc_school_subject_title'].'
                                          //                   </b>
                                                        
                                          //               </td>
                                          //           ';
                                          //         }else{
                                          //           $cur_time=date('h:i');
                                          //           $beg_time=date('h:i', strtotime($schedule_row['stc_school_teacher_schedule_begtime']));
                                          //           $end_time=date('h:i', strtotime($schedule_row['stc_school_teacher_schedule_endtime']));
                                          //           if(($cur_time>$beg_time) && ($end_time>$cur_time)){
                                          //             $schedule_data.='
                                          //               <td class="text-center" style="background: #8cff32;font-weight: bold;">
                                          //                 <a href="javascript:void(0);" class="stc-school-show-student" data-toggle="modal" data-target="#exampleModal" id="'.$schedule_row['stc_school_teacher_schedule_id'].'" class-id="'.$schedule_row['stc_school_teacher_schedule_classid'].'" sub-id="'.$schedule_row['stc_school_teacher_schedule_subjectid'].'">
                                          //                     <b>
                                          //                       Class - '.$schedule_row['stc_school_class_title'].'<br>
                                          //                       '.$schedule_row['stc_school_subject_title'].'<br>
                                          //                       '.date('h:i', strtotime($schedule_row['stc_school_teacher_schedule_begtime'])).' - 
                                          //                       '.date('h:i', strtotime($schedule_row['stc_school_teacher_schedule_endtime'])).'
                                          //                     </b>
                                          //                 </a>
                                          //               </td>
                                          //             ';
                                          //           }else{
                                          //             $schedule_data.='
                                          //                 <td class="text-center">
                                          //                     <b>
                                          //                       Class - '.$schedule_row['stc_school_class_title'].'<br>
                                          //                       '.$schedule_row['stc_school_subject_title'].'<br>
                                          //                       '.date('h:i', strtotime($schedule_row['stc_school_teacher_schedule_begtime'])).' - 
                                          //                       '.date('h:i', strtotime($schedule_row['stc_school_teacher_schedule_endtime'])).'
                                          //                     </b>
                                                          
                                          //                 </td>
                                          //             ';
                                          //           }
                                          //         }
                                          //       }
                                          //     }
                                          //   }
                                          //   $hash_rec='';
                                          //   for($i = 0; $i<$rev_counter; $i++){
                                          //     $hash_rec.='<td class="text-center"><b>NA</b></td>';
                                          //   }
                                          //   $data.='
                                          //         <tr style="'.$tr_color.'">
                                          //           <td class="text-center">'.$class_row.'</td>
                                          //           '.$schedule_data.$hash_rec.'
                                          //         </tr>
                                          //   ';
                                          // }
                                          // echo $data;

                                        ?>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div> -->