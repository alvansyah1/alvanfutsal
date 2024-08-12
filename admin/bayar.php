<?php
session_start();
require "../session.php";
require "../functions.php";

if ($role !== 'Admin') {
    header("location:../login.php");
}

$pesan = query("SELECT sewa.id_sewa,user.nama_lengkap,sewa.tanggal_pesan,sewa.jam_pesan,sewa.jam_mulai,sewa.lama_sewa,sewa.jam_habis,sewa.total,bayar.bukti,bayar.konfirmasi,bayar.status,bayar.bayar_dp,bayar.kekurangan,lapangan.nama
FROM sewa
JOIN user ON sewa.id_user = user.id_user
JOIN bayar ON sewa.id_sewa = bayar.id_sewa
JOIN lapangan ON sewa.id_lapangan = lapangan.id_lapangan
ORDER BY CASE
    WHEN bayar.konfirmasi = 'Sudah Bayar' THEN 1
    ELSE 2
  END, sewa.tanggal_pesan DESC, sewa.jam_pesan DESC, sewa.jam_mulai DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Pembayaran | BP Futsal</title>
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
    <style>
        .modal-body img {
            max-width: 100%;
            height: auto;
        }

        .clickable-img {
            cursor: pointer;
        }

        .image-container {
            width: 100%;
            height: 100%;
            position: relative;
            display: inline-block;
        }

        .image-container img {
            width: 100%;
            height: auto;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }

        .image-container:hover .overlay {
            opacity: 1;
        }
    </style>
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
                        <li class="nav-item active submenu">
                            <a data-bs-toggle="collapse" href="#pesan">
                                <i class="fas fa-envelope"></i>
                                <p>Pemesanan</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse show" id="pesan">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="pesan.php">
                                            <span class="sub-item">Belum Bayar</span>
                                        </a>
                                    </li>
                                    <li class="active">
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
                        <li class="nav-item">
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
                            <h3 class="fw-bold mb-3">Data Pembayaran</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <h4 class="card-title">Konfirmasi Pembayaran</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="add-row" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Pemesan</th>
                                                    <th>Lapangan</th>
                                                    <th>Tanggal Pesan</th>
                                                    <th>Jam Pesan</th>
                                                    <th>Tanggal Main</th>
                                                    <th>Lama</th>
                                                    <th>Tanggal Selesai</th>
                                                    <th>Total</th>
                                                    <th>Status</th>
                                                    <th>DP</th>
                                                    <th>Kekurangan</th>
                                                    <th>Bukti</th>
                                                    <th>Status</th>
                                                    <th style="width: 10%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                <?php foreach ($pesan as $row): ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row["nama_lengkap"]; ?></td>
                                                        <td><?= $row["nama"]; ?></td>
                                                        <td><?= date("d-m-Y", strtotime($row["tanggal_pesan"])); ?></td>
                                                        <td><?= date("H:i", strtotime($row['jam_pesan'])) ?></td>
                                                        <td><?= date("H:i d-m-Y", strtotime($row["jam_mulai"])); ?></td>
                                                        <td><?= $row["lama_sewa"]; ?></td>
                                                        <td><?= date("H:i d-m-Y", strtotime($row["jam_habis"])); ?></td>
                                                        <td><?= $row["total"]; ?></td>
                                                        <td><?= $row["status"]; ?></td>
                                                        <td><?= $row["bayar_dp"]; ?></td>
                                                        <td><?= $row["kekurangan"]; ?></td>
                                                        <td>
                                                            <div class="image-container">
                                                                <img src="../img/<?= $row["bukti"]; ?>"
                                                                    class="clickable-img" data-bs-toggle="modal"
                                                                    data-bs-target="#imageModal<?= $row["id_sewa"]; ?>"
                                                                    data-action="zoom">
                                                                <div class="overlay" data-bs-toggle="modal"
                                                                    data-bs-target="#imageModal<?= $row["id_sewa"]; ?>">
                                                                    Lihat</div>
                                                            </div>
                                                        </td>
                                                        <td><?= $row["konfirmasi"]; ?></td>
                                                        <td>
                                                            <?php
                                                            $id_sewa = $row["id_sewa"];
                                                            if ($row["konfirmasi"] == "Terkonfirmasi") {
                                                                echo '';
                                                            } else {
                                                                echo ' <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#konfirmasiModal' . $id_sewa . '">
                                                                            Konfirmasi
                                                                        </button>
                                                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#hapusModal' . $id_sewa . '">
                                                                            Hapus
                                                                        </button>
                                                                        ';
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <!-- Modal Konfirmasi -->
                                                    <div class="modal fade" id="konfirmasiModal<?= $row["id_sewa"]; ?>"
                                                        tabindex="-1" aria-labelledby="konfirmasiModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="konfirmasiModalLabel">
                                                                        Konfirmasi Pesanan <?= $row["nama_lengkap"]; ?></h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Anda yakin ingin mengkonfirmasi pesanan ini?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                    <a href="./controller/konfirmasiBayar.php?id=<?= $row["id_sewa"]; ?>"
                                                                        class="btn btn-primary">Konfirmasi</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Modal Konfirmasi -->

                                                    <!-- Modal Hapus -->
                                                    <div class="modal fade" id="hapusModal<?= $row["id_sewa"]; ?>"
                                                        tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="hapusModalLabel">Hapus
                                                                        Pesanan <?= $row["nama_lengkap"]; ?></h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Anda yakin ingin menghapus pesanan ini?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                    <a href="./controller/hapusBayar.php?id=<?= $row["id_sewa"]; ?>"
                                                                        class="btn btn-danger">Hapus</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Modal Hapus -->

                                                    <!-- Modal untuk gambar -->
                                                    <div class="modal fade" id="imageModal<?= $row["id_sewa"]; ?>"
                                                        tabindex="-1"
                                                        aria-labelledby="imageModalLabel<?= $row["id_sewa"]; ?>"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="imageModalLabel<?= $row["id_sewa"]; ?>">Bukti
                                                                        Pembayaran</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    <img src="../img/<?= $row["bukti"]; ?>"
                                                                        data-action="zoom">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Modal untuk gambar -->
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