<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsletterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Newsletter::create([
            'title' => 'Selamat Datang di Newsletter Tunas Motor!',
            'excerpt' => 'Berita terbaru dan promo menarik dari Tunas Motor untuk Anda.',
            'content' => '
                <h2>Selamat Datang!</h2>
                <p>Terima kasih telah berlangganan newsletter Tunas Motor. Kami akan mengirimkan informasi terbaru tentang:</p>
                <ul>
                    <li>Produk motor terbaru dan terbaik</li>
                    <li>Promo dan diskon spesial</li>
                    <li>Tips perawatan motor</li>
                    <li>Berita industri otomotif</li>
                    <li>Event dan acara khusus</li>
                </ul>

                <h3>Promo Spesial untuk Subscriber Baru</h3>
                <p>Dapatkan diskon 10% untuk pembelian sparepart pertama Anda dengan menunjukkan email newsletter ini.</p>

                <blockquote>
                    "Tunas Motor - Solusi Terpercaya untuk Kebutuhan Otomotif Anda"
                </blockquote>

                <p>Jangan lupa untuk mengunjungi showroom kami di Cirebon atau hubungi customer service kami di +(62) 1234 5678 90.</p>
            ',
            'status' => 'published',
        ]);

        \App\Models\Newsletter::create([
            'title' => 'Tips Perawatan Motor di Musim Hujan',
            'excerpt' => 'Pelajari cara merawat motor Anda agar tetap prima selama musim hujan.',
            'content' => '
                <h2>Musim Hujan Tiba!</h2>
                <p>Musim hujan merupakan tantangan tersendiri bagi pengendara motor. Berikut beberapa tips untuk menjaga motor Anda tetap dalam kondisi prima:</p>

                <h3>1. Periksa Kondisi Ban</h3>
                <p>Pastikan tekanan angin ban sesuai rekomendasi dan teliti adanya retak atau aus yang tidak normal.</p>

                <h3>2. Sistem Kelistrikan</h3>
                <p>Periksa kondisi aki, kabel, dan sistem kelistrikan lainnya. Kelembaban dapat mempengaruhi performa aki.</p>

                <h3>3. Rem dan Sistem Pengereman</h3>
                <p>Pastikan rem masih berfungsi dengan baik. Jarak pengereman yang lebih panjang di jalan basah memerlukan perhatian ekstra.</p>

                <h3>4. Bersihkan Motor Setelah Digunakan</h3>
                <p>Selalu bersihkan motor dari air dan kotoran setelah digunakan di hujan untuk mencegah karat.</p>

                <p><strong>Kunjungi bengkel resmi Tunas Motor untuk servis berkala dan dapatkan garansi resmi!</strong></p>
            ',
            'status' => 'published',
        ]);

        \App\Models\Newsletter::create([
            'title' => 'Grand Opening Showroom Baru Tunas Motor',
            'excerpt' => 'Showroom baru Tunas Motor hadir dengan fasilitas modern dan koleksi lengkap.',
            'content' => '
                <h2>Showroom Baru Tunas Motor - Grand Opening!</h2>
                <p>Dengan bangga kami umumkan pembukaan showroom baru Tunas Motor yang berlokasi di pusat kota Cirebon.</p>

                <h3>Fasilitas Modern</h3>
                <ul>
                    <li>Area display yang luas dan modern</li>
                    <li>Cafe dan area istirahat</li>
                    <li>Free WiFi</li>
                    <li>Parking area yang luas</li>
                    <li>Customer service 24/7</li>
                </ul>

                <h3>Spesial Grand Opening</h3>
                <p>Dapatkan diskon hingga 25% untuk semua produk dan potongan harga khusus untuk test drive motor impian Anda.</p>

                <p><em>Tanggal: 25 Desember 2025</em><br>
                <em>Waktu: 09.00 - 17.00 WIB</em><br>
                <em>Lokasi: Jl. Pahlawan No. 123, Cirebon</em></p>

                <p>Jangan lewatkan kesempatan emas ini! Kami tunggu kedatangan Anda.</p>
            ',
            'status' => 'draft',
        ]);
    }
}
