<?php  
session_start(); 
if(isset($_SESSION["stc_empl_id"]) && ($_SESSION["stc_empl_role"]>0)){ 
}else{ 
    header("Location:index.html"); 
} 
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
    <title>Summary - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <link rel = "icon" type = "image/png" href="images/stc_logo_title.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        /* Existing styles... */
        
        /* New workflow styles */
        .workflow-container {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            padding: 20px 0;
            margin-bottom: 30px;
        }
        
        .workflow-step {
            display: flex;
            align-items: center;
        }
        
        .workflow-box {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px 20px;
            margin: 0 5px;
            min-width: 120px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .workflow-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .workflow-box.active {
            background: #4caf50;
            color: white;
            border-color: #4caf50;
        }
        
        .workflow-box .step-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .workflow-box .step-subtitle {
            font-size: 12px;
            color: #666;
        }
        
        .workflow-box.active .step-subtitle {
            color: #e0e0e0;
        }
        
        .workflow-arrow {
            font-size: 20px;
            color: #999;
            margin: 0 5px;
        }
        
        @media (max-width: 768px) {
            .workflow-container {
                flex-direction: column;
                align-items: stretch;
            }
            
            .workflow-step {
                margin-bottom: 10px;
            }
            
            .workflow-arrow {
                transform: rotate(90deg);
                margin: 5px 0;
            }
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
                    <!-- Workflow Visualization -->
                    <div class="workflow-container">
                        <div class="workflow-step">
                            <div class="workflow-box active">
                                <div class="step-title">DCP</div>
                                <div class="step-subtitle">Defective Corrective Preventive</div>
                            </div>
                        </div>
                        
                        <div class="workflow-arrow">→</div>
                        
                        <div class="workflow-step">
                            <div class="workflow-box">
                                <div class="step-title">TBM</div>
                                <div class="step-subtitle">Tools Box Meetings</div>
                            </div>
                        </div>
                        
                        <div class="workflow-arrow">→</div>
                        
                        <div class="workflow-step">
                            <div class="workflow-box">
                                <div class="step-title">Requisitions</div>
                                <div class="step-subtitle">Purchase Requests</div>
                            </div>
                        </div>
                        
                        <div class="workflow-arrow">→</div>
                        
                        <div class="workflow-step">
                            <div class="workflow-box">
                                <div class="step-title">Dispatches</div>
                                <div class="step-subtitle">Material Dispatch & Receipts</div>
                            </div>
                        </div>
                        
                        <div class="workflow-arrow">→</div>
                        
                        <div class="workflow-step">
                            <div class="workflow-box">
                                <div class="step-title">Receivings</div>
                                <div class="step-subtitle">Material Receivings</div>
                            </div>
                        </div>
                        
                        <div class="workflow-arrow">→</div>
                        
                        <div class="workflow-step">
                            <div class="workflow-box">
                                <div class="step-title">Consumptions</div>
                                <div class="step-subtitle">Consumed Materials</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rest of your content -->
                    <div class="app-page-title">
                        <!-- Your existing page content here -->
                    </div>
                </div>  
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="./assets/scripts/jquery.table2excel.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/floatthead/2.2.3/jquery.floatThead.min.js"></script>
    
    <script>
        // Optional: Add interactivity to workflow steps
        $(document).ready(function() {
            $('.workflow-box').click(function() {
                // Remove active class from all boxes
                $('.workflow-box').removeClass('active');
                // Add active class to clicked box
                $(this).addClass('active');
                
                // Here you could add code to load content for the selected step
                // For example: loadStepContent($(this).find('.step-title').text());
            });
        });
    </script>
</body>
</html>