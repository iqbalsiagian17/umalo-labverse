<?php

namespace Database\Seeders;

use App\Models\Qa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [

            [
                'question' => 'Apa itu Labtek?',
                'answer' => 'Labtek adalah platform e-commerce dari PT Arkamaya Guna Saharsa yang menjual Product dalam negeri. Perusahaan ini juga memiliki e-commerce lain, Labserve, yang fokus pada Product luar negeri. PT Arkamaya Guna Saharsa sudah terverifikasi di e-katalog.',
            ],
            [
                'question' => ' Bagaimana cara melakukan transaksi di Labtek?',
                'answer' => 'Untuk melakukan transaksi, Anda harus mendaftar atau login terlebih dahulu. Anda dapat login secara manual atau menggunakan akun Google. Setelah login, Anda harus mengisi data diri lengkap (seperti alamat, nama perusahaan, dan biodata) sebelum melanjutkan ke proses pembelian.',
            ],
            [
                'question' => 'Apa yang dimaksud dengan Product nego dan Product tidak nego?',
                'answer' => 'Product Nego: Product ini memiliki harga yang bisa dinegosiasikan. Setelah checkout, status pesanan akan berubah menjadi -Menunggu Konfirmasi Admin untuk Negosiasi-. Jika admin setuju, status akan berubah menjadi -Negosiasi-, dan Anda akan diarahkan untuk menghubungi admin melalui WhatsApp atau live chat untuk menyelesaikan negosiasi. Setelah kesepakatan tercapai, status akan berubah menjadi -Diterima-. Product Tidak Nego: Product ini memiliki harga tetap yang tidak bisa dinegosiasikan. Setelah checkout, status pesanan akan berubah menjadi -Menunggu Konfirmasi Admin-. Jika admin setuju, status akan berubah menjadi -Diterima-.',
            ],
            [
                'question' => 'Apa yang terjadi setelah pesanan saya diterima?',
                'answer' => 'Setelah pesanan Anda diterima oleh admin, akan muncul invoice yang mencakup total harga, PPN 10%, dan nomor rekening perusahaan. Anda harus mentransfer jumlah yang tercantum di invoice ke rekening tersebut dan mengupload bukti pembayaran. Admin akan memeriksa bukti pembayaran, dan jika valid, pesanan akan diproses ke tahap packing dan pengiriman.',
            ],
            [
                'question' => 'Bagaimana cara melacak pesanan saya?',
                'answer' => 'Setelah pesanan Anda dikirim, admin akan memberikan nomor resi pengiriman. Anda dapat menggunakan nomor resi ini untuk melacak posisi barang yang Anda pesan.',
            ],
            [
                'question' => 'Apa yang harus saya lakukan setelah menerima barang?',
                'answer' => 'Setelah barang sampai, Anda harus mengklik tombol "Diterima" di halaman pesanan Anda. Setelah itu, Anda juga dapat memberikan ulasan mengenai Product yang telah Anda beli (opsional).',
            ],
            [
                'question' => 'Apa itu Big Sale di Labtek?',
                'answer' => 'Big Sale adalah acara khusus di Labtek di mana Product-Product pilihan akan mendapatkan diskon besar-besaran, mulai dari 20% hingga 90%. Product hanya akan diskon selama Big Sale berlangsung.',
            ],
            [
                'question' => 'Apakah saya bisa menghubungi admin untuk negosiasi atau pertanyaan lainnya?',
                'answer' => 'Ya, Anda bisa menghubungi admin melalui WhatsApp atau live chat di website. Live chat dapat diakses dari balon chat di sudut kanan bawah layar. Namun, untuk menjaga keamanan bersama, disarankan untuk menggunakan WhatsApp.',
            ],
            [
                'question' => 'Bagaimana cara login menggunakan akun Google di Labtek?',
                'answer' => 'Anda dapat login menggunakan akun Google dengan mengklik tombol "Login dengan Google" di halaman login. Setelah itu, Anda akan diarahkan untuk memasukkan kredensial akun Google Anda. Setelah login berhasil, Anda dapat melanjutkan ke proses transaksi seperti biasa.',
            ],
            [
                'question' => 'Apakah saya bisa membatalkan pesanan setelah melakukan checkout?',
                'answer' => 'Pembatalan pesanan setelah checkout dapat dilakukan jika status pesanan masih -Menunggu Konfirmasi Admin-. Namun, setelah admin mengonfirmasi pesanan, pembatalan tidak dapat dilakukan. Jika Anda ingin membatalkan pesanan, segera hubungi admin sebelum pesanan dikonfirmasi.',
            ], [
                'question' => 'Apa keuntungan mendaftar di Labtek?',
                'answer' => 'Dengan mendaftar di Labtek, Anda dapat menikmati berbagai fitur seperti penyimpanan data pesanan, akses ke promosi eksklusif, dan kemudahan dalam melacak pesanan Anda. Selain itu, Anda juga akan mendapatkan notifikasi tentang Big Sale dan diskon lainnya langsung ke email Anda.',
            ],

        ];
        DB::table('t_faq')->insert($faqs);


    }
}
