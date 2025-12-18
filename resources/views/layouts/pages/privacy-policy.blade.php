@extends('layouts.app')

@section('title', 'Kebijakan Privasi - Tunas Motor')

@section('content')
@include('layouts.partials.header', ['hideSearch' => true])

<section class="policy-section">
    <div class="container">
        <div class="policy-container">
            <div class="policy-header">
                <h1>Kebijakan Privasi</h1>
                <p>Terakhir diperbarui: {{ date('d F Y') }}</p>
            </div>

            <div class="policy-content">
                <div class="policy-section-item">
                    <h2>1. Pendahuluan</h2>
                    <p>Tunas Motor ("kami", "kita", atau "perusahaan") menghargai privasi Anda dan berkomitmen untuk melindungi data pribadi Anda. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan, dan melindungi informasi pribadi Anda saat Anda menggunakan layanan kami.</p>
                </div>

                <div class="policy-section-item">
                    <h2>2. Informasi yang Kami Kumpulkan</h2>
                    <p>Kami mengumpulkan beberapa jenis informasi, termasuk:</p>
                    <ul>
                        <li><strong>Informasi Pribadi:</strong> Nama lengkap, alamat email, nomor telepon, alamat pengiriman.</li>
                        <li><strong>Informasi Transaksi:</strong> Riwayat pembelian, metode pembayaran, detail pesanan.</li>
                        <li><strong>Informasi Teknis:</strong> Alamat IP, jenis browser, perangkat yang digunakan.</li>
                        <li><strong>Informasi Penggunaan:</strong> Halaman yang dikunjungi, waktu kunjungan, produk yang dilihat.</li>
                    </ul>
                </div>

                <div class="policy-section-item">
                    <h2>3. Cara Kami Menggunakan Informasi</h2>
                    <p>Kami menggunakan informasi yang dikumpulkan untuk:</p>
                    <ul>
                        <li>Memproses dan mengirimkan pesanan Anda.</li>
                        <li>Mengelola akun pengguna Anda.</li>
                        <li>Mengirimkan konfirmasi pesanan dan pembaruan pengiriman.</li>
                        <li>Memberikan layanan pelanggan dan dukungan.</li>
                        <li>Mengirimkan newsletter dan promosi (dengan persetujuan Anda).</li>
                        <li>Meningkatkan layanan dan pengalaman pengguna.</li>
                        <li>Mencegah penipuan dan menjaga keamanan platform.</li>
                    </ul>
                </div>

                <div class="policy-section-item">
                    <h2>4. Pembagian Informasi</h2>
                    <p>Kami dapat membagikan informasi Anda kepada:</p>
                    <ul>
                        <li><strong>Mitra Pengiriman:</strong> Untuk memastikan pesanan sampai ke alamat Anda.</li>
                        <li><strong>Penyedia Pembayaran:</strong> Untuk memproses transaksi pembayaran (Midtrans).</li>
                        <li><strong>Pihak Berwenang:</strong> Jika diwajibkan oleh hukum atau peraturan yang berlaku.</li>
                    </ul>
                    <p>Kami tidak menjual atau menyewakan informasi pribadi Anda kepada pihak ketiga untuk tujuan pemasaran.</p>
                </div>

                <div class="policy-section-item">
                    <h2>5. Keamanan Data</h2>
                    <p>Kami menerapkan langkah-langkah keamanan yang sesuai untuk melindungi informasi Anda dari akses tidak sah, perubahan, pengungkapan, atau penghancuran. Ini termasuk:</p>
                    <ul>
                        <li>Enkripsi SSL untuk semua transmisi data.</li>
                        <li>Penyimpanan data yang aman dengan akses terbatas.</li>
                        <li>Pemantauan keamanan secara berkala.</li>
                    </ul>
                </div>

                <div class="policy-section-item">
                    <h2>6. Cookie dan Teknologi Pelacakan</h2>
                    <p>Kami menggunakan cookie dan teknologi serupa untuk:</p>
                    <ul>
                        <li>Mengingat preferensi dan pengaturan Anda.</li>
                        <li>Menjaga sesi login Anda.</li>
                        <li>Menganalisis penggunaan website.</li>
                    </ul>
                    <p>Anda dapat mengatur browser untuk menolak cookie, namun beberapa fitur mungkin tidak berfungsi dengan baik.</p>
                </div>

                <div class="policy-section-item">
                    <h2>7. Hak-Hak Anda</h2>
                    <p>Anda memiliki hak untuk:</p>
                    <ul>
                        <li>Mengakses informasi pribadi yang kami simpan tentang Anda.</li>
                        <li>Meminta koreksi data yang tidak akurat.</li>
                        <li>Meminta penghapusan akun dan data Anda.</li>
                        <li>Menarik persetujuan untuk menerima komunikasi pemasaran.</li>
                    </ul>
                </div>

                <div class="policy-section-item">
                    <h2>8. Retensi Data</h2>
                    <p>Kami menyimpan informasi pribadi Anda selama diperlukan untuk tujuan yang dijelaskan dalam kebijakan ini, atau sebagaimana diwajibkan oleh hukum. Data transaksi disimpan untuk keperluan akuntansi dan hukum.</p>
                </div>

                <div class="policy-section-item">
                    <h2>9. Perubahan Kebijakan</h2>
                    <p>Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Perubahan signifikan akan diberitahukan melalui email atau pemberitahuan di website kami.</p>
                </div>

                <div class="policy-section-item">
                    <h2>10. Hubungi Kami</h2>
                    <p>Jika Anda memiliki pertanyaan tentang Kebijakan Privasi ini atau ingin menggunakan hak-hak Anda, silakan hubungi kami melalui:</p>
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

