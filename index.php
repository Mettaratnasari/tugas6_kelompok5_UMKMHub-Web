<?php
// DATA PRODUK UMKM
$produk = [
    "keripik" => [
        "nama" => "Keripik Pisang",
        "harga" => 18000
    ],
    "kopi" => [
        "nama" => "Kopi Lokal",
        "harga" => 45000
    ],
    "sambal" => [
        "nama" => "Sambal Rumahan",
        "harga" => 25000
    ],
    "tas" => [
        "nama" => "Tas Anyaman",
        "harga" => 65000
    ]
];

// FUNGSI FORMAT RUPIAH
function rupiah($angka) {
    return "Rp" . number_format($angka, 0, ",", ".");
}

// FUNGSI PENGAMAN OUTPUT HTML
function aman($teks) {
    return htmlspecialchars(
        (string)$teks,
        ENT_QUOTES,
        "UTF-8"
    );
}

// VARIABEL FORM
$nama = "";
$kontak = "";
$pilihan = "";
$jumlah = "";
$pembayaran = "";
$catatan = "";

$errors = [];
$hasil = null;

// MEMERIKSA APAKAH FORM DIKIRIM
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // MEMBACA DATA DARI FORM POST
    $nama = trim($_POST["nama"] ?? "");
    $kontak = trim($_POST["kontak"] ?? "");
    $pilihan = $_POST["produk"] ?? "";
    $jumlah = trim($_POST["jumlah"] ?? "");
    $pembayaran = $_POST["pembayaran"] ?? "";
    $catatan = trim($_POST["catatan"] ?? "");

    // VALIDASI NAMA
    if (strlen($nama) < 2) {
        $errors[] =
            "Nama wajib diisi minimal 2 karakter.";
    }

    // VALIDASI KONTAK
    if (!preg_match('/^[0-9+\-\s]{8,18}$/', $kontak)) {
        $errors[] =
            "Nomor kontak harus berisi 8–18 karakter angka, +, atau -.";
    }

    // VALIDASI PRODUK
    if (!isset($produk[$pilihan])) {
        $errors[] = "Silakan pilih produk yang tersedia.";
    }

    // VALIDASI JUMLAH
    if (
        filter_var($jumlah, FILTER_VALIDATE_INT) === false
        || (int)$jumlah < 1
        || (int)$jumlah > 100
    ) {
        $errors[] =
            "Jumlah pesanan harus berupa bilangan bulat 1–100.";
    }

    // VALIDASI PEMBAYARAN
    $metodeValid = [
        "Transfer Bank",
        "QRIS",
        "Bayar di Tempat"
    ];

    if (!in_array($pembayaran, $metodeValid, true)) {
        $errors[] = "Silakan pilih metode pembayaran.";
    }

    // VALIDASI CATATAN
    if (strlen($catatan) > 250) {
        $errors[] = "Catatan maksimal 250 karakter.";
    }

    // PROSES BISNIS JIKA VALIDASI LOLOS
    if (empty($errors)) {
        $item = $produk[$pilihan];

        $subtotal = $item["harga"] * (int)$jumlah;

        // Diskon 5% untuk subtotal minimal Rp100.000
        $diskon = ($subtotal >= 100000)
            ? $subtotal * 0.05
            : 0;

        $total = $subtotal - $diskon;

        $hasil = [
            "nama" => $nama,
            "kontak" => $kontak,
            "produk" => $item["nama"],
            "harga" => $item["harga"],
            "jumlah" => (int)$jumlah,
            "subtotal" => $subtotal,
            "diskon" => $diskon,
            "total" => $total,
            "pembayaran" => $pembayaran,
            "catatan" => $catatan
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>UMKMHub - Produk Lokal Indonesia</title>

    <!-- Menghubungkan file CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Menghubungkan file JavaScript -->
    <script src="js/script.js" defer></script>
</head>

<body>

<header class="navbar">
    <a href="index.php" class="brand">
        <span class="brand-logo">U</span>
        <span>UMKM<span class="accent">Hub</span></span>
    </a>

    <nav>
        <a href="#produk">Produk</a>
        <a href="#pesan">Pemesanan</a>
    </nav>
</header>

<main>

    <!-- BAGIAN BERANDA -->
    <section class="hero">
        <div>
            <span class="label">DUKUNG USAHA LOKAL</span>

            <h1>
                Produk lokal hebat,
                <span>semua dalam satu hub.</span>
            </h1>

            <p>
                Temukan produk pilihan UMKM Indonesia.
                Belanja lebih mudah dan dukung usaha lokal
                agar terus berkembang.
            </p>

            <a href="#produk" class="btn">
                Jelajahi Produk ↓
            </a>
        </div>

        <div class="hero-art">
            <div class="hero-icon">🛍️</div>
            <h3>Bangga Produk Lokal</h3>
            <p>Produk pilihan, karya anak bangsa.</p>
        </div>
    </section>

    <!-- BAGIAN KATALOG PRODUK -->
    <section class="section" id="produk">
        <span class="label">KATALOG PRODUK</span>
        <h2>Favorit dari UMKM</h2>

        <div class="product-grid">

            <article class="product-card">
                <div class="product-image">
    <img
        src="assets/keripik-pisang.jpg"
        alt="Keripik Pisang">
</div>
                <h3>Keripik Pisang</h3>
                <p>Snack renyah buatan lokal.</p>
                <strong>Rp18.000</strong>
                <a href="#pesan"
                   class="product-link"
                   data-product="keripik">
                    Pilih Produk →
                </a>
            </article>

            <article class="product-card">
                <div class="product-image">
    <img
        src="assets/kopi-lokal.jpg"
        alt="Kopi Lokal">
</div>
                <h3>Kopi Lokal</h3>
                <p>Kopi pilihan dengan aroma khas.</p>
                <strong>Rp45.000</strong>
                <a href="#pesan"
                   class="product-link"
                   data-product="kopi">
                    Pilih Produk →
                </a>
            </article>

            <article class="product-card">
                <div class="product-image">
    <img
        src="assets/sambal-rumahan.jpg"
        alt="Sambal Rumahan">
</div>
                <h3>Sambal Rumahan</h3>
                <p>Sambal pedas dengan resep rumahan.</p>
                <strong>Rp25.000</strong>
                <a href="#pesan"
                   class="product-link"
                   data-product="sambal">
                    Pilih Produk →
                </a>
            </article>

            <article class="product-card">
               <div class="product-image">
    <img
        src="assets/tas-anyaman.jpg"
        alt="Tas Anyaman">
</div>
                <h3>Tas Anyaman</h3>
                <p>Kerajinan tangan khas UMKM.</p>
                <strong>Rp65.000</strong>
                <a href="#pesan"
                   class="product-link"
                   data-product="tas">
                    Pilih Produk →
                </a>
            </article>

        </div>
    </section>

    <!-- BAGIAN FORM PEMESANAN -->
    <section class="order-section" id="pesan">

        <div class="order-intro">
            <span class="label">FORM PEMESANAN</span>
            <h2>Pesan produk favoritmu.</h2>

            <p>
                Isi formulir untuk menghitung estimasi
                biaya pesanan. Data akan diproses oleh PHP
                setelah formulir dikirim.
            </p>
        </div>

        <div class="form-panel">

            <?php if (!empty($errors)): ?>
                <div class="error-box" role="alert">
                    <strong>Periksa kembali data kamu:</strong>

                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= aman($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($hasil !== null): ?>

                <!-- HASIL PEMROSESAN PHP -->
                <div class="success-box" role="status">
                    Pesanan berhasil dihitung!
                </div>

                <h3>Ringkasan Pesanan</h3>

                <p>
                    Nama:
                    <strong><?= aman($hasil["nama"]) ?></strong>
                </p>

                <p>
                    Kontak:
                    <?= aman($hasil["kontak"]) ?>
                </p>

                <p>
                    Produk:
                    <?= aman($hasil["produk"]) ?>
                </p>

                <p>
                    Harga satuan:
                    <?= rupiah($hasil["harga"]) ?>
                </p>

                <p>
                    Jumlah:
                    <?= aman($hasil["jumlah"]) ?>
                </p>

                <p>
                    Subtotal:
                    <?= rupiah($hasil["subtotal"]) ?>
                </p>

                <p>
                    Diskon:
                    <?= rupiah($hasil["diskon"]) ?>
                </p>

                <h3 class="total">
                    Total:
                    <?= rupiah($hasil["total"]) ?>
                </h3>

                <p>
                    Pembayaran:
                    <?= aman($hasil["pembayaran"]) ?>
                </p>

                <?php if ($hasil["catatan"] !== ""): ?>
                    <p>
                        Catatan:
                        <?= aman($hasil["catatan"]) ?>
                    </p>
                <?php endif; ?>

                <a href="index.php#pesan" class="btn">
                    Buat Pesanan Baru
                </a>

            <?php else: ?>

                <h3>Detail Pesanan</h3>

                <form method="POST"
                      action="index.php#pesan"
                      id="orderForm">

                    <label for="nama">Nama Lengkap *</label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        minlength="2"
                        maxlength="80"
                        required
                        value="<?= aman($nama) ?>"
                        placeholder="Masukkan nama">

                    <small id="namaError" class="field-error"></small>

                    <label for="kontak">Nomor Kontak *</label>
                    <input
                        type="tel"
                        id="kontak"
                        name="kontak"
                        required
                        value="<?= aman($kontak) ?>"
                        placeholder="Contoh: 081234567890">

                    <small id="kontakError" class="field-error"></small>

                    <label for="produk">Pilih Produk *</label>
                    <select id="produk" name="produk" required>
                        <option value="">-- Pilih Produk --</option>

                        <?php foreach ($produk as $kode => $item): ?>
                            <option
                                value="<?= aman($kode) ?>"
                                data-price="<?= $item["harga"] ?>"
                                <?= $pilihan === $kode ? "selected" : "" ?>>

                                <?= aman($item["nama"]) ?>
                                — <?= rupiah($item["harga"]) ?>

                            </option>
                        <?php endforeach; ?>
                    </select>

                    <small id="produkError" class="field-error"></small>

                    <label for="jumlah">Jumlah Pesanan *</label>
                    <input
                        type="number"
                        id="jumlah"
                        name="jumlah"
                        min="1"
                        max="100"
                        step="1"
                        required
                        value="<?= aman($jumlah !== "" ? $jumlah : "1") ?>">

                    <small id="jumlahError" class="field-error"></small>

                    <label for="pembayaran">Metode Pembayaran *</label>
                    <select id="pembayaran" name="pembayaran" required>
                        <option value="">-- Pilih Pembayaran --</option>

                        <?php foreach (
                            ["Transfer Bank", "QRIS", "Bayar di Tempat"]
                            as $metode
                        ): ?>
                            <option
                                value="<?= aman($metode) ?>"
                                <?= $pembayaran === $metode ? "selected" : "" ?>>

                                <?= aman($metode) ?>

                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="catatan">Catatan (Opsional)</label>
                    <textarea
                        id="catatan"
                        name="catatan"
                        maxlength="250"
                        rows="3"
                        placeholder="Catatan untuk penjual"><?= aman($catatan) ?></textarea>

                    <div class="estimate">
                        <span>Estimasi Total</span>
                        <strong id="liveTotal">Rp0</strong>
                        <small id="discountHint">
                            Diskon 5% untuk subtotal minimal Rp100.000.
                        </small>
                    </div>

                    <button type="submit" class="submit-btn">
                        Kirim & Hitung Pesanan →
                    </button>

                    <p class="form-note">
                        Estimasi di browser dibantu JavaScript.
                        Hasil akhir divalidasi dan dihitung oleh PHP.
                    </p>

                </form>

            <?php endif; ?>

        </div>
    </section>

</main>

<footer>
    <strong>UMKMHub</strong>
    <p>Produk lokal, dampak besar.</p>
    <small>Project pembelajaran PHP — 2026</small>
</footer>

</body>
</html>