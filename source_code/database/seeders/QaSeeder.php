<?php

namespace Database\Seeders;

use App\Models\Qa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [

            [
                'pertanyaan' => 'Apa itu Labtek?',
                'jawaban' => 'Labtek adalah platform e-commerce dari PT Arkamaya Guna Saharsa yang menjual produk dalam negeri. Perusahaan ini juga memiliki e-commerce lain, Labserve, yang fokus pada produk luar negeri. PT Arkamaya Guna Saharsa sudah terverifikasi di e-katalog.',
            ],
            [
                'pertanyaan' => ' Bagaimana cara melakukan transaksi di Labtek?',
                'jawaban' => 'Untuk melakukan transaksi, Anda harus mendaftar atau login terlebih dahulu. Anda dapat login secara manual atau menggunakan akun Google. Setelah login, Anda harus mengisi data diri lengkap (seperti alamat, nama perusahaan, dan biodata) sebelum melanjutkan ke proses pembelian.',
            ],
            [
                'pertanyaan' => 'Apa yang dimaksud dengan produk nego dan produk tidak nego?',
                'jawaban' => 'Produk Nego: Produk ini memiliki harga yang bisa dinegosiasikan. Setelah checkout, status pesanan akan berubah menjadi -Menunggu Konfirmasi Admin untuk Negosiasi-. Jika admin setuju, status akan berubah menjadi -Negosiasi-, dan Anda akan diarahkan untuk menghubungi admin melalui WhatsApp atau live chat untuk menyelesaikan negosiasi. Setelah kesepakatan tercapai, status akan berubah menjadi -Diterima-. Produk Tidak Nego: Produk ini memiliki harga tetap yang tidak bisa dinegosiasikan. Setelah checkout, status pesanan akan berubah menjadi -Menunggu Konfirmasi Admin-. Jika admin setuju, status akan berubah menjadi -Diterima-.',
            ],
            [
                'pertanyaan' => 'Apa yang terjadi setelah pesanan saya diterima?',
                'jawaban' => 'Setelah pesanan Anda diterima oleh admin, akan muncul invoice yang mencakup total harga, PPN 10%, dan nomor rekening perusahaan. Anda harus mentransfer jumlah yang tercantum di invoice ke rekening tersebut dan mengupload bukti pembayaran. Admin akan memeriksa bukti pembayaran, dan jika valid, pesanan akan diproses ke tahap packing dan pengiriman.',
            ],
            [
                'pertanyaan' => 'Bagaimana cara melacak pesanan saya?',
                'jawaban' => 'Setelah pesanan Anda dikirim, admin akan memberikan nomor resi pengiriman. Anda dapat menggunakan nomor resi ini untuk melacak posisi barang yang Anda pesan.',
            ],
            [
                'pertanyaan' => 'Apa yang harus saya lakukan setelah menerima barang?',
                'jawaban' => 'Setelah barang sampai, Anda harus mengklik tombol "Diterima" di halaman pesanan Anda. Setelah itu, Anda juga dapat memberikan ulasan mengenai produk yang telah Anda beli (opsional).',
            ],
            [
                'pertanyaan' => 'Apa itu Big Sale di Labtek?',
                'jawaban' => 'Big Sale adalah acara khusus di Labtek di mana produk-produk pilihan akan mendapatkan diskon besar-besaran, mulai dari 20% hingga 90%. Produk hanya akan diskon selama Big Sale berlangsung.',
            ],
            [
                'pertanyaan' => 'Apakah saya bisa menghubungi admin untuk negosiasi atau pertanyaan lainnya?',
                'jawaban' => 'Ya, Anda bisa menghubungi admin melalui WhatsApp atau live chat di website. Live chat dapat diakses dari balon chat di sudut kanan bawah layar. Namun, untuk menjaga keamanan bersama, disarankan untuk menggunakan WhatsApp.',
            ],
            [
                'pertanyaan' => 'Bagaimana cara login menggunakan akun Google di Labtek?',
                'jawaban' => 'Anda dapat login menggunakan akun Google dengan mengklik tombol "Login dengan Google" di halaman login. Setelah itu, Anda akan diarahkan untuk memasukkan kredensial akun Google Anda. Setelah login berhasil, Anda dapat melanjutkan ke proses transaksi seperti biasa.',
            ],
            [
                'pertanyaan' => 'Apakah saya bisa membatalkan pesanan setelah melakukan checkout?',
                'jawaban' => 'Pembatalan pesanan setelah checkout dapat dilakukan jika status pesanan masih -Menunggu Konfirmasi Admin-. Namun, setelah admin mengonfirmasi pesanan, pembatalan tidak dapat dilakukan. Jika Anda ingin membatalkan pesanan, segera hubungi admin sebelum pesanan dikonfirmasi.',
            ], [
                'pertanyaan' => 'Apa keuntungan mendaftar di Labtek?',
                'jawaban' => 'Dengan mendaftar di Labtek, Anda dapat menikmati berbagai fitur seperti penyimpanan data pesanan, akses ke promosi eksklusif, dan kemudahan dalam melacak pesanan Anda. Selain itu, Anda juga akan mendapatkan notifikasi tentang Big Sale dan diskon lainnya langsung ke email Anda.',
            ],

        ];
        DB::table('qas')->insert($faqs);


    }
}
