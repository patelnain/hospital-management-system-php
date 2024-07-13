<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid=$_SESSION['ad_id'];
if(isset($_GET['deleteRequest']))
{
    $id=intval($_GET['deleteRequest']);
    $adn="DELETE FROM his_pwdresets WHERE  id = ?";
    $stmt= $mysqli->prepare($adn);
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $stmt->close();	 

    if($stmt)
    {
        $success = "Deleted";
    }
    else
    {
        $err = "Try Again Later";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php');?>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include('assets/inc/nav.php');?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include("assets/inc/sidebar.php");?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Password Resets</a></li>
                                        <li class="breadcrumb-item active">Manage</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Accounts Requesting For Password Resets</h4>
                            </div>
                        </div>
                    </div>     
                    <!-- end page title --> 

                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">
                                <h4 class="header-title"></h4>
                                <div class="mb-2">
                                    <div class="row">
                                        <div class="col-12 text-sm-center form-inline" >
                                            <div class="form-group mr-2" style="display:none">
                                                <select id="demo-foo-filter-status" class="custom-select custom-select-sm">
                                                    <option value="">Show all</option>
                                                    <option value="Discharged">Discharged</option>
                                                    <option value="OutPatients">OutPatients</option>
                                                    <option value="InPatients">InPatients</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <input id="demo-foo-search" type="text" placeholder="Search" class="form-control form-control-sm" autocomplete="on">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
    <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
        <thead>
            <tr>
                <th>#</th>
                <th data-toggle="true">Email</th>
                <th data-hide="phone">Password Reset Token</th>
                <th data-hide="phone">Date Requested</th>
                <th data-hide="phone">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $ret = "SELECT * FROM his_pwdresets";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute();
            $res = $stmt->get_result();
            $cnt = 1;
            while ($row = $res->fetch_object()) {
                // Trim timestamp to DD-MMM-YYYY HH:MM format
                $requestedtime = isset($row->created_at) ? date('d-M-Y H:i', strtotime($row->created_at)) : '';

                if ($row->status == 'Pending') {
                    $action = "<td><a href='his_admin_update_doc_password.php?email=" . urlencode($row->email) . "&pwd=" . urlencode($row->pwd) . "' class='badge badge-danger'><i class='fas fa-edit'></i>Reset Password</a></td>";
                } else {
                    $emailSubject = "Password Reset Request";
                    $emailBody = "Token: $row->token\nNew Password: $row->pwd";
                    $encodedEmailSubject = rawurlencode($emailSubject);
                    $encodedEmailBody = rawurlencode($emailBody);
                    $action = "<td><a href='#' onclick='openMail(\"$row->email\", \"$encodedEmailSubject\", \"$encodedEmailBody\")' class='badge badge-success'><i class='fas fa-envelope'></i>Send Mail</a></td>";
                }
                ?>
                <script>
                    function openMail(email, subject, body) {
                        window.open('mailto:' + email + '?subject=' + subject + '&body=' + body);
                    }
                </script>

                <tr>
                    <td><?php echo $cnt; ?></td>
                    <td><?php echo $row->email; ?></td>
                    <td><?php echo $row->token; ?></td>
                    <td><?php echo $requestedtime; ?></td>
                    <?php echo $action; ?>
                </tr>

            <?php
                $cnt = $cnt + 1;
            } ?>
        </tbody>
        <tfoot>
            <tr class="active">
                <td colspan="8">
                    <div class="text-right">
                        <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
</div> <!-- end .table-responsive-->

                            </div> <!-- end card-box -->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include('assets/inc/footer.php');?>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->


    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- Footable js -->
    <script src="assets/libs/footable/footable.all.min.js"></script>

    <!-- Init js -->
    <script src="assets/js/pages/foo-tables.init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>
    
</body>

</html>