document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("orderForm");

    // Jika form tidak tampil, tidak ada interaksi yang perlu diproses.
    if (!form) return;

    const nama = document.getElementById("nama");
    const kontak = document.getElementById("kontak");
    const produk = document.getElementById("produk");
    const jumlah = document.getElementById("jumlah");
    const totalLabel = document.getElementById("liveTotal");
    const discountHint = document.getElementById("discountHint");

    // Mengubah angka menjadi format Rupiah.
    function formatRupiah(angka) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            maximumFractionDigits: 0
        }).format(angka);
    }

    // Menghitung estimasi berdasarkan produk dan jumlah.
    function hitungEstimasi() {
        const option = produk.options[produk.selectedIndex];
        const harga = Number(option.dataset.price || 0);
        const qty = Number(jumlah.value || 0);

        const subtotal = harga * qty;

        const diskon = subtotal >= 100000
            ? subtotal * 0.05
            : 0;

        const total = Math.max(0, subtotal - diskon);

        totalLabel.textContent = formatRupiah(total);

        if (diskon > 0) {
            discountHint.textContent =
                "Diskon 5%: " + formatRupiah(diskon);
        } else {
            discountHint.textContent =
                "Diskon 5% untuk subtotal minimal Rp100.000.";
        }
    }

    // Menampilkan pesan kesalahan di dekat input.
    function tampilkanError(id, pesan) {
        document.getElementById(id).textContent = pesan;
    }

    // Memperbarui total saat produk atau jumlah berubah.
    produk.addEventListener("change", hitungEstimasi);
    jumlah.addEventListener("input", hitungEstimasi);

    // Memilih produk dari kartu katalog.
    document.querySelectorAll("[data-product]").forEach(function (link) {
        link.addEventListener("click", function () {
            produk.value = link.dataset.product;
            hitungEstimasi();
        });
    });

    // Validasi awal sebelum form dikirim.
    form.addEventListener("submit", function (event) {
        let valid = true;

        tampilkanError("namaError", "");
        tampilkanError("kontakError", "");
        tampilkanError("produkError", "");
        tampilkanError("jumlahError", "");

        if (nama.value.trim().length < 2) {
            tampilkanError(
                "namaError",
                "Nama minimal 2 karakter."
            );
            valid = false;
        }

        if (!/^[0-9+\-\s]{8,18}$/.test(kontak.value.trim())) {
            tampilkanError(
                "kontakError",
                "Periksa kembali nomor kontak."
            );
            valid = false;
        }

        if (produk.value === "") {
            tampilkanError(
                "produkError",
                "Silakan pilih produk."
            );
            valid = false;
        }

        const qty = Number(jumlah.value);

        if (!Number.isInteger(qty) || qty < 1 || qty > 100) {
            tampilkanError(
                "jumlahError",
                "Jumlah harus bilangan bulat 1–100."
            );
            valid = false;
        }

        if (!valid) {
            event.preventDefault();
        }

        // Jika valid, browser mengirim form menggunakan POST.
        // PHP akan memvalidasi ulang dan menghitung hasil akhir.
    });

    // Hitung total pertama kali halaman dibuka.
    hitungEstimasi();
});