<?php
require_once "../includes/db.php";

// Ambil data saldo
$sql_saldo = "SELECT * FROM saldo WHERE id = 1 LIMIT 1";
$result_saldo = $conn->query($sql_saldo);
$saldo = $result_saldo->fetch_assoc();

// Ambil semua pesanan
$sql = "SELECT * FROM transactions ORDER BY date DESC";
$result = $conn->query($sql);

$transactions = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <title>Pemasukan - D’ajib Creative House</title>
    <link rel="stylesheet" href="../assets/css/style-saldo.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  </head>
  <body>
    <div class="sidebar">
      <div class="logo"><img src="../assets/img/logo.png" alt="logo" /></div>
      <ul class="menu">
        <li><a href="dashboard.html">Dashboard</a></li>
        <li><a href="pesanan.php">Pesanan</a></li>
        <li class="active">Info Saldo</li>
        <li><a href="laporan.php">Laporan</a></li>
      </ul>
      <div class="logout">Log Out</div>
    </div>

    <div class="main">
       <div class="sidebar-overlay"></div>
      <div class="header">
        <div class="hamburger-menu">
      <span></span>
      <span></span>
      <span></span>
  </div>
        <h2>TOKO SAYA</h2>
        <div class="profile">Nama Admin<br /><small>Admin</small></div>
      </div>

      <div class="saldo-content">
        <div class="saldo-row saldo-top-section">
          <!-- Left Column -->
          <div class="saldo-left-panel">
            <!-- Balance Information -->
            <div class="saldo-card saldo-info-card">
              <h3>Saldo Keseluruhan</h3>
              <div class="saldo-amount">Rp. <?php echo number_format($saldo['total_balance'], 0, ',', '.'); ?></div>
            </div>

            <!-- Balance by Platform -->
            <div class="saldo-card saldo-platform-card">
              <div class="saldo-platform-list">
                <div class="saldo-platform-entry">
                  <span>Shopee</span>
                  <strong>Rp. <?php echo number_format($saldo['shopee_balance'], 0, ',', '.'); ?></strong>
                </div>
                <div class="saldo-platform-entry">
                  <span>TikTok</span>
                  <strong>Rp. <?php echo number_format($saldo['tiktok_balance'], 0, ',', '.'); ?></strong>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div class="saldo-right-panel">
            <div class="saldo-card saldo-profit-capital">
              <div class="saldo-sub-card saldo-profit">
                <h4>Keuntungan</h4>
                <strong>Rp. <?php echo number_format($saldo['profit'], 0, ',', '.'); ?></strong>
              </div>
              <div class="saldo-sub-card saldo-capital">
                <h4>Modal</h4>
                <strong>Rp. <?php echo number_format($saldo['capital'], 0, ',', '.'); ?></strong>
              </div>
            </div>
          </div>
        </div>

        <!-- Bottom Section: Recent Transactions Full Width -->
        <div class="content-wrapper">
          <h3 class="sub-title">Daftar Transaksi</h3>

          <!-- Header kolom -->
          <div class="transaksi-header">
            <div>Order ID</div>
            <div>Deskripsi</div>
            <div>Type</div>
            <div>Tanggal</div>
            <div>Harga</div>
            <div>Keterangan</div>
          </div>

          <div id="transaction-container">
            <?php foreach ($transactions as $i => $trans): ?>
              <?php
                $jenis = strtolower($trans['type']) === 'masuk' ? 'Masuk' : 'Keluar';
                $warna = $jenis === 'Masuk' ? 'trans-in' : 'trans-out';
              ?>
              <div class="transaksi-card <?php echo $warna; ?> <?php echo $i > 1 ? 'hidden' : ''; ?>">
                <div><?php echo htmlspecialchars($trans['order_id'] ?? '-'); ?></div>
                <div><?php echo ucfirst($trans['description']); ?></div>
                <div><?php echo $jenis; ?></div>
                <div><?php echo htmlspecialchars($trans['date']); ?></div>
                <div>Rp <?php echo number_format($trans['amount'], 0, ',', '.'); ?></div>
                <div><?php echo htmlspecialchars($trans['keterangan'] ?? '-'); ?></div>
              </div>
            <?php endforeach; ?>
          </div>
          <!-- Tombol View More -->
          <div style="text-align: center; margin-top: 10px;">
            <span id="toggle-transaksi" class="view-more-text">Lihat semua transaksi</span>
          </div>
        </div>

      </div>
    </div>
    <script>
  document.addEventListener('DOMContentLoaded', function() {
    // --- FUNGSI UNTUK TOMBOL "LIHAT SEMUA" ---
    const toggleLink = document.getElementById('toggle-transaksi');
    if (toggleLink) {
      let isShowingAll = false;
      toggleLink.addEventListener('click', function () {
        const hiddenCards = document.querySelectorAll('.transaksi-card');
        hiddenCards.forEach((card, index) => {
          if (index >= 2) {
            card.classList.toggle('hidden');
          }
        });
        isShowingAll = !isShowingAll;
        this.textContent = isShowingAll ? 'Sembunyikan transaksi' : 'Lihat semua transaksi';
      });
    }

    // --- FUNGSI UNTUK SIDEBAR TOGGLE ---
    const hamburger = document.querySelector('.hamburger-menu');
    const sidebar = document.querySelector('.sidebar'); // 'sidebar' sudah ada di sini
    const overlay = document.querySelector('.sidebar-overlay');

    function toggleSidebar() {
      sidebar.classList.toggle('active');
      overlay.classList.toggle('active');
    }

    if (hamburger) {
      hamburger.addEventListener('click', toggleSidebar);
    }
    if (overlay) {
      overlay.addEventListener('click', toggleSidebar);
    }

    // ========================================================
    // === FUNGSI BARU UNTUK MEMPERBAIKI TINGGI SIDEBAR ===
    // ========================================================
    function setSidebarHeight() {
      if (sidebar) {
        // Gunakan window.innerHeight yang memberikan tinggi area terlihat secara akurat
        sidebar.style.height = window.innerHeight + 'px';
      }
    }

    // Panggil saat halaman pertama kali dimuat
    setSidebarHeight();

    // Panggil lagi saat ukuran jendela berubah (misal: rotasi ponsel atau address bar hilang)
    window.addEventListener('resize', setSidebarHeight);
    // ========================================================
  });
</script>

  </body>
</html>
