<?php
session_start();
require "../functions.php";
require "../session.php";
if ($role !== 'Admin') {
  header("location:../login.php");
}

$lapangan = query("SELECT COUNT(id_lapangan) AS jml_lapangan FROM lapangan")[0];
$pesanan = query("SELECT COUNT(id_bayar) AS jml_sewa FROM bayar")[0];
$user = query("SELECT COUNT(id_user) AS jml_user FROM user")[0];

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Dashboard | BP Futsal</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
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
              <li class="nav-item active">
                <a
                  href="beranda.php"
                  class="collapsed"
                  aria-expanded="false"
                >
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
                <img
                  src="assets/img/kaiadmin/logo_light.svg"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                />
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
          <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
            <div class="container-fluid">

              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar-sm">
                      <img
                        src="../img/<?= $_SESSION['foto']; ?>"
                        alt="Foto Profil"
                        class="avatar-img rounded-circle"
                      />
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
                            <img
                              src="../img/<?= $_SESSION['foto']; ?>"
                              alt="Foto Profil"
                              class="avatar-img rounded"
                            />
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
        <h3 class="fw-bold mb-3">Dashboard</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-primary bubble-shadow-small">
                  <i class="fas fa-users"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Total Pengguna</p>
                  <h4 class="card-title"><?= $lapangan["jml_lapangan"]; ?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                  <i class="far fa-check-circle"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Total Lapangan</p>
                  <h4 class="card-title"><?= $pesanan["jml_sewa"]; ?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-success bubble-shadow-small">
                  <i class="fas fa-luggage-cart"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Total Pesanan</p>
                  <h4 class="card-title"><?= $user["jml_user"]; ?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Grafik Batang Total Pesanan per User -->
    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Total Pesanan per User</h4>
            <canvas id="userOrdersChart"></canvas>
          </div>
        </div>
      </div>
    </div>
    <!-- End Grafik Batang -->
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
  var ctx = document.getElementById('userOrdersChart').getContext('2d');
  var userOrdersChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: [
        <?php
        // Ambil nama user untuk label grafik
        $userLabels = query("SELECT u.nama FROM user u JOIN bayar b ON u.id_user = b.id_user GROUP BY u.id_user ORDER BY COUNT(b.id_user) DESC");
        foreach ($userLabels as $label) {
          echo '"' . $label['nama'] . '",';
        }
        ?>
      ],
      datasets: [{
        label: 'Total Pesanan',
        data: [
          <?php
          // Ambil total pesanan untuk setiap user
          $userOrders = query("SELECT COUNT(b.id_user) AS total_pesanan FROM user u JOIN bayar b ON u.id_user = b.id_user GROUP BY u.id_user ORDER BY total_pesanan DESC");
          foreach ($userOrders as $order) {
            echo $order['total_pesanan'] . ',';
          }
          ?>
        ],
        backgroundColor: 'rgba(75, 192, 192, 0.6)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>

</body>
</html>
