<?php
session_start();
require "../session.php";
require "../functions.php";

if ($role !== 'Admin') {
  header("location:/login.php");
}

$saran = query("SELECT saran.id_saran, saran.saran, user.nama_lengkap
        FROM saran
        JOIN user ON saran.id_user = user.id_user");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Saran | BP Futsal</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="../img/logo-bpfutsalcenter.png" type="image" />
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["assets/css/fonts.min.css"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="assets/css/demo.css" />
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <a href="beranda.php" class="logo" style="color: #ffffff; font-weight:bold;">
                        <img src="../img/logo-bpfutsalcenter.png" alt="navbar brand" class="navbar-brand"
                            height="40" />
                        SI | BP FUTSAL
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                    <button class="topbar-toggler more">
                        <i class="gg-more-vertical-alt"></i>
                    </button>
                </div>
                <!-- End Logo Header -->
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        <li class="nav-item">
                            <a href="beranda.php" class="collapsed" aria-expanded="false">
                                <i class="fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">MENU</h4>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#pengguna">
                                <i class="fas fa-users"></i>
                                <p>Data Pengguna</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="pengguna">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="admin.php">
                                            <span class="sub-item">Admin</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="pengguna.php">
                                            <span class="sub-item">Pengguna</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#pesan">
                                <i class="fas fa-envelope"></i>
                                <p>Pemesanan</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="pesan">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="pesan.php">
                                            <span class="sub-item">Belum Bayar</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="bayar.php">
                                            <span class="sub-item">Sudah Bayar</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="lapangan.php">
                                <i class="fas fa-futbol"></i>
                                <p>Kelola Lapangan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="event.php">
                                <i class="fas fa-tags"></i>
                                <p>Kelola Event & Berita</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="chat.php">
                                <i class="fas fa-comments"></i>
                                <p>Chat</p>
                            </a>
                        </li>
                        <li class="nav-item active">
                            <a href="saran.php">
                                <i class="fas fa-receipt"></i>
                                <p>Saran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="metode.php">
                                <i class="fas fa-money-check-alt"></i>
                                <p>Metode Pembayaran</p>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="index.html" class="logo">
                            <img src="assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand"
                                height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">

                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                                    aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="../img/<?= $_SESSION['foto']; ?>" alt="Foto Profil"
                                            class="avatar-img rounded-circle" />
                                    </div>
                                    <span class="profile-username">
                                        <span class="op-7">Hi,</span>
                                        <span class="fw-bold"><?= $_SESSION["nama"]; ?></span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="avatar-lg">
                                                    <img src="../img/<?= $_SESSION['foto']; ?>" alt="Foto Profil"
                                                        class="avatar-img rounded" />
                                                </div>
                                                <div class="u-text">
                                                    <h4><?= $_SESSION["nama"]; ?></h4>
                                                    <p class="text-muted"><?= $_SESSION["email"]; ?></p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="../logout.php">Logout</a>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>

            <div class="container">
                <div class="page-inner">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Data Saran</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <h4 class="card-title">Saran Dari Pengguna</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="add-row" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Pengguna</th>
                                                    <th>Saran</th>
                                                    <th style="width: 10%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                    <?php $i = 1; ?>
                                                    <?php foreach ($saran as $row) : ?>
                                                        <tr>
                                                        <th scope="row"><?= $i++; ?></th>
                                                        <td><?= $row["nama_lengkap"]; ?></td>
                                                        <td><?= $row["saran"]; ?></td>
                                                        <td>
                                                            <div class="form-button-action">
                                                                <button type="button" title=""
                                                                    class="btn btn-link btn-danger">
                                                                    <a
                                                                        href="./controller/hapusSaran.php?id=<?= $row["id_saran"]; ?>"><i
                                                                            class="fa fa-times"></i></a>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="footer">
                    <div class="container-fluid d-flex justify-content-between">
                        <div class="copyright">
                        @ 2024, dikembangkan oleh
                        <a href="https://pcr.ac.id/">Politeknik Caltex Riau</a>
                        </div>
                        <div>
                        Design by 
                        <a target="_blank" href="https://www.themekita.com/demo-kaiadmin-lite-bootstrap-dashboard/livepreview/demo1/">ThemeKita</a>.
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <script src="assets/js/core/jquery-3.7.1.min.js"></script>
        <script src="assets/js/core/popper.min.js"></script>
        <script src="assets/js/core/bootstrap.min.js"></script>
        <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
        <script src="assets/js/plugin/chart.js/chart.min.js"></script>
        <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>
        <script src="assets/js/plugin/chart-circle/circles.min.js"></script>
        <script src="assets/js/plugin/datatables/datatables.min.js"></script>
        <script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
        <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
        <script src="assets/js/plugin/jsvectormap/world.js"></script>
        <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>
        <script src="assets/js/kaiadmin.min.js"></script>
        <script>
            $(document).ready(function () {
                $("#basic-datatables").DataTable({});

                $("#multi-filter-select").DataTable({
                pageLength: 5,
                initComplete: function () {
                    this.api()
                    .columns()
                    .every(function () {
                        var column = this;
                        var select = $(
                        '<select class="form-select"><option value=""></option></select>'
                        )
                        .appendTo($(column.footer()).empty())
                        .on("change", function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());

                            column
                            .search(val ? "^" + val + "$" : "", true, false)
                            .draw();
                        });

                        column
                        .data()
                        .unique()
                        .sort()
                        .each(function (d, j) {
                            select.append(
                            '<option value="' + d + '">' + d + "</option>"
                            );
                        });
                    });
                },
                });

                // Add Row
                $("#add-row").DataTable({
                pageLength: 5,
                });

                var action =
                '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';
            });
        </script>
</body>

</html>