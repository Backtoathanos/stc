<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STC Associates | {{!empty($page_title) ? $page_title : ''}}</title>
  @include('layouts.head')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  
  @include('layouts.nav')

  @include('layouts.aside')
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Site Users</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">User</a></li>
              <li class="breadcrumb-item active"><a href="#">{{!empty($page_title) ? $page_title : ''}}</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-12">
                <p>@include('layouts._message')</p>
            </div>
        </div>
        <div class="row">          
        <div class="col-lg-12 col-12"><a href="{{ url('/users/siteusers/create') }}" class="btn btn-block btn-primary btn-md">Create Site User</a></div>
          <div class="col-lg-12 col-12">
            <div class="card">
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="text-center">Id</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Contact</th>
                        <th class="text-center">Whatsapp</th>
                        <th class="text-center">UID</th>
                        <th class="text-center">Address</th>
                        <th class="text-center">City</th>
                        <th class="text-center">State</th>
                        <th class="text-center">Pincode</th>
                        <th class="text-center">Password</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Token</th>
                        <th class="text-center">Created By</th>
                        <th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($getRecord as $value)
                        <tr>
                            <td class="text-center">{{$value->stc_cust_pro_supervisor_id}}</td>
                            <td>{{$value->stc_cust_pro_supervisor_fullname}}</td>
                            <td>{{$value->stc_cust_pro_supervisor_email}}</td>
                            <td>{{$value->stc_cust_pro_supervisor_contact}}</td>
                            <td>{{$value->stc_cust_pro_supervisor_whatsapp}}</td>
                            <td>{{$value->stc_cust_pro_supervisor_uid}}</td>
                            <td>{{$value->stc_cust_pro_supervisor_address}}</td>
                            <td>{{$value->stc_city_name}}</td>
                            <td>{{$value->stc_state_name}}</td>
                            <td>{{$value->stc_cust_pro_supervisor_pincode}}</td>
                            <td>{{$value->stc_cust_pro_supervisor_password}}</td>
                            <td class="text-center">{{ ($value->stc_cust_pro_supervisor_status == 1) ? 'Active' : 'In-active'}}</td>
                            <td>{{$value->stc_cust_pro_supervisor_category}}</td>
                            <td>{{$value->stc_cust_pro_supervisor_token}}</td>
                            <td>{{$value->stc_agents_name}}</td>
                            <td class="text-center">
                                <a href="{{url('/users/siteusers/delete/'.$value->stc_cust_pro_supervisor_id)}}" class="btn btn-danger btn-sm" ><i class='fas fa-trash'></i></a>
                                <a href="{{url('/users/siteusers/edit/'.$value->stc_cust_pro_supervisor_id)}}" class="btn btn-danger btn-sm" ><i class='fas fa-edit'></i></a>
                                <?php     

                                  $server = $_SERVER['SERVER_NAME']=="localhost" ? "http://localhost/stc" : "https://stcassociate.com";
                                  $user_id = $value->stc_cust_pro_supervisor_id;
                                  $user_type = "siteuser";
                                  
                                  
                                  $simple_string =$user_id;
                                  $ciphering = "AES-128-CTR";
                                  $iv_length = openssl_cipher_iv_length($ciphering);
                                  $options = 0;
                                  $encryption_iv = '1234567891011121';
                                  $encryption_key = "stc_associate_go";
                                  $encryption = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv);
                                  
                                  $url = $server.'/usercredentials.php?user_id='.$encryption.'&user_type='.$user_type;
                                ?>
                                <a href="<?php echo $url;?>" target="_blank" class="btn btn-danger btn-sm" ><i class='fas fa-arrow-right'></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <th class="text-center">Id</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Contact</th>
                        <th class="text-center">Whatsapp</th>
                        <th class="text-center">UID</th>
                        <th class="text-center">Address</th>
                        <th class="text-center">City</th>
                        <th class="text-center">State</th>
                        <th class="text-center">Pincode</th>
                        <th class="text-center">Password</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Token</th>
                        <th class="text-center">Created By</th>
                        <th class="text-center">Actions</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
 
  @include('layouts.footer')

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
  <!-- ./wrapper -->
  @include('layouts.foot')

</body>
</html>
