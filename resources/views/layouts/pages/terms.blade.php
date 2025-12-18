@extends('layouts.app')

@section('title', 'Syarat & Ketentuan - Tunas Motor')

@section('content')
@include('layouts.partials.header', ['hideSearch' => true])

<section class="policy-section">
    <div class="container">
        <div class="policy-container">
            <div class="policy-header">
                <h1>Syarat & Ketentuan</h1>
                <p>Terakhir diperbarui: {{ date('d F Y') }}</p>
            </div>

            <div class="policy-content">
                <div class="policy-section-item">
                    <h2>1. Penerimaan Ketentuan</h2>
                    <p>Dengan mengakses dan menggunakan website Tunas Motor, Anda setuju untuk terikat oleh Syarat & Ketentuan ini. Jika Anda tidak setuju dengan ketentuan ini, mohon untuk tidak menggunakan layanan kami.</p>
                </div>

                <div class="policy-section-item">
                    <h2>2. Definisi</h2>
                    <ul>
                        <li><strong>"Tunas Motor"</strong> merujuk pada toko sparepart motor online beserta seluruh layanannya.</li>
                        <li><strong>"Pengguna"</strong> merujuk pada setiap orang yang mengakses atau menggunakan layanan kami.</li>
                        <li><strong>"Produk"</strong> merujuk pada sparepart motor dan aksesoris yang dijual di platform kami.</li>
                        <li><strong>"Pesanan"</strong> merujuk pada transaksi pembelian yang dilakukan melalui platform kami.</li>
                    </ul>
                </div>

                <div class="policy-section-item">
                    <h2>3. Akun Pengguna</h2>
                    <ul>
                        <li>Anda harus berusia minimal 17 tahun untuk membuat akun.</li>
                        <li>Anda bertanggung jawab menjaga kerahasiaan informasi akun Anda.</li>
                        <li>Satu orang hanya diperbolehkan memiliki satu akun.</li>
                        <li>Kami berhak menonaktifkan akun yang melanggar ketentuan.</li>
                    </ul>
                </div>

                <div class="policy-section-item">
                    <h2>4. Pemesanan dan Pembayaran</h2>
                    <h3>4.1 Proses Pemesanan</h3>
                    <ul>
                        <li>Semua pesanan harus melalui proses checkout di website kami.</li>
                        <li>Pesanan dianggap sah setelah pembayaran dikonfirmasi atau persetujuan COD.</li>
                        <li>Kami berhak menolak pesanan jika stok tidak tersedia.</li>
                    </ul>
                    
                    <h3>4.2 Metode Pembayaran</h3>
                    <ul>
                        <li><strong>Pembayaran Online:</strong> Transfer Bank, E-Wallet, Kartu Kredit, QRIS via Midtrans.</li>
                        <li><strong>COD (Cash on Delivery):</strong> Bayar saat barang diterima (tersedia untuk area tertentu).</li>
                    </ul>
                    
                    <h3>4.3 Harga</h3>
                    <ul>
                        <li>Semua harga dalam Rupiah Indonesia (IDR).</li>
                        <li>Harga dapat berubah tanpa pemberitahuan sebelumnya.</li>
                        <li>Diskon dan promosi berlaku sesuai ketentuan yang tercantum.</li>
                    </ul>
                </div>

                <div class="policy-section-item">
                    <h2>5. Pengiriman</h2>
                    <ul>
                        <li>Kami mengirimkan ke seluruh wilayah Indonesia.</li>
                        <li>Waktu pengiriman tergantung lokasi dan ketersediaan kurir.</li>
                        <li>Biaya pengiriman dihitung berdasarkan berat dan lokasi tujuan.</li>
                        <li>Risiko kerusakan atau kehilangan menjadi tanggung jawab kami hingga barang diterima.</li>
                        <li>Pastikan alamat pengiriman lengkap dan benar.</li>
                    </ul>
                </div>

                <div class="policy-section-item">
                    <h2>6. Kebijakan Pengembalian</h2>
                    <h3>6.1 Kondisi Pengembalian</h3>
                    <p>Produk dapat dikembalikan jika:</p>
                    <ul>
                        <li>Produk yang diterima tidak sesuai dengan pesanan.</li>
                        <li>Produk rusak atau cacat saat diterima.</li>
                        <li>Produk tidak berfungsi sebagaimana mestinya.</li>
                    </ul>
                    
                    <h3>6.2 Prosedur Pengembalian</h3>
                    <ul>
                        <li>Ajukan pengembalian dalam waktu 3x24 jam setelah barang diterima.</li>
                        <li>Sertakan bukti foto dan video kondisi produk.</li>
                        <li>Produk harus dalam kondisi asli dengan kemasan lengkap.</li>
                        <li>Biaya pengiriman pengembalian ditanggung oleh kami jika kesalahan dari pihak kami.</li>
                    </ul>
                    
                    <h3>6.3 Pengembalian Dana</h3>
                    <ul>
                        <li>Pengembalian dana diproses dalam 7-14 hari kerja.</li>
                        <li>Dana dikembalikan melalui metode pembayaran yang sama.</li>
                    </ul>
                </div>

                <div class="policy-section-item">
                    <h2>7. Garansi Produk</h2>
                    <ul>
                        <li>Garansi berlaku sesuai ketentuan masing-masing produk.</li>
                        <li>Garansi tidak berlaku untuk kerusakan akibat kesalahan penggunaan.</li>
                        <li>Klaim garansi harus disertai bukti pembelian dari Tunas Motor.</li>
                    </ul>
                </div>

                <div class="policy-section-item">
                    <h2>8. Larangan</h2>
                    <p>Pengguna dilarang:</p>
                    <ul>
                        <li>Menggunakan layanan untuk tujuan ilegal.</li>
                        <li>Membuat pesanan palsu atau curang.</li>
                        <li>Menyebarkan malware atau melakukan serangan cyber.</li>
                        <li>Menggunakan akun orang lain tanpa izin.</li>
                        <li>Melakukan tindakan yang merugikan pengguna lain atau Tunas Motor.</li>
                    </ul>
                </div>

                <div class="policy-section-item">
                    <h2>9. Batasan Tanggung Jawab</h2>
                    <ul>
                        <li>Kami tidak bertanggung jawab atas kerugian akibat penggunaan produk yang tidak sesuai.</li>
                        <li>Kami tidak menjamin ketersediaan website 100% setiap saat.</li>
                        <li>Kami tidak bertanggung jawab atas keterlambatan pengiriman di luar kendali kami.</li>
                    </ul>
                </div>

                <div class="policy-section-item">
                    <h2>10. Hak Kekayaan Intelektual</h2>
                    <p>Semua konten di website ini termasuk logo, teks, gambar, dan desain adalah milik Tunas Motor dan dilindungi oleh undang-undang hak cipta Indonesia.</p>
                </div>

                <div class="policy-section-item">
                    <h2>11. Perubahan Ketentuan</h2>
                    <p>Kami berhak mengubah Syarat & Ketentuan ini kapan saja. Perubahan akan berlaku segera setelah dipublikasikan di website. Penggunaan berkelanjutan setelah perubahan berarti Anda menerima ketentuan yang diperbarui.</p>
                </div>

                <div class="policy-section-item">
                    <h2>12. Hukum yang Berlaku</h2>
                    <p>Syarat & Ketentuan ini diatur oleh dan ditafsirkan sesuai dengan hukum Republik Indonesia. Setiap perselisihan akan diselesaikan melalui musyawarah atau pengadilan yang berwenang di Indonesia.</p>
                </div>

                <div class="policy-section-item">
                    <h2>13. Hubungi Kami</h2>
                    <p>Untuk pertanyaan tentang Syarat & Ketentuan ini, hubungi kami:</p>
                    <ul>
                        <li><strong>Email:</strong> info@tunasmotor.com</li>
                        <li><strong>Telepon:</strong> +(62) 1234 5678 90</li>
                        <li><strong>Alamat:</strong> Cirebon, Jawa Barat, Indonesia</li>
                    </ul>
                </div>
            </div>

            <div class="policy-footer">
                <a href="{{ route('home') }}" class="btn-back-home">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 10H5M5 10l5-5M5 10l5 5"/>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</section>

@include('layouts.partials.footer')
@endsection

