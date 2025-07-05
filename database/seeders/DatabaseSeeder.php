<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Nasabah;
use App\Models\Rekening;
use App\Models\Pembiayaan;
use App\Models\Investasi;
use App\Models\Ziswaf;
use App\Models\FinancingCard;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\Document;
use App\Models\ActivityLog;
use App\Models\SystemSetting;
use App\Models\NisbahSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Define the current Faker instance.
     *
     * @var \Faker\Generator
     */
    protected $faker; // Deklarasikan properti faker

    /**
     * Run the application's database seeds.
     */
    public function run(): void
    {
        // Inisialisasi Faker di awal metode run()
        $this->faker = \Faker\Factory::create('id_ID');

        // === 1. Buat User Admin Utama ===
        User::factory()->create([
            'nama' => 'Super Admin Bank Syariah',
            'email' => 'admin@banksyariah.com',
            'password' => Hash::make('password'), // Password default untuk admin
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        // === 2. Buat User dengan Berbagai Peran (selain nasabah) ===
        User::factory()->count(5)->role('admin_keuangan')->create();
        User::factory()->count(5)->role('admin_cs')->create();
        User::factory()->count(5)->role('teller')->create();
        User::factory()->count(2)->role('manager')->create();
        User::factory()->count(2)->role('auditor')->create();

        // === 3. Buat Data Nasabah beserta User Akunnya ===
        Nasabah::factory()->count(50)->create()->each(function ($nasabah) {
            // Untuk setiap nasabah, buatkan User account dengan role 'nasabah'
            $user = User::factory()->forNasabah()->create([
                'email' => $nasabah->email, // Gunakan email nasabah untuk login
                'nama' => $nasabah->nama_lengkap, // Gunakan nama lengkap nasabah untuk nama user
            ]);
            $nasabah->user_id = $user->id; // Kaitkan user_id ke nasabah
            $nasabah->save(); // Simpan perubahan pada nasabah
        });

        // === 4. Buat Nasabah Tanpa Akun User (misal: nasabah lama/offline) ===
        Nasabah::factory()->count(10)->create([
            'user_id' => null,
            'email' => null, // Email bisa null jika tidak ada akun user
        ]);

        // === 5. Buat Data Rekening ===
        // Untuk setiap nasabah yang sudah ada, buat beberapa rekening
        Nasabah::all()->each(function ($nasabah) {
            // Gunakan $this->faker yang sudah diinisialisasi
            Rekening::factory()->count($this->faker->numberBetween(1, 3))->create([
                'nasabah_id' => $nasabah->id,
            ]);
        });

        // === 6. Buat Data Nisbah Settings ===
        NisbahSetting::factory()->count(10)->create(); // Buat 10 pengaturan nisbah acak

        // === 7. Buat Data Pembiayaan ===
        Nasabah::all()->each(function ($nasabah) {
            // Gunakan $this->faker yang sudah diinisialisasi
            Pembiayaan::factory()->count($this->faker->numberBetween(0, 2))->create([ // 0-2 pembiayaan per nasabah
                'nasabah_id' => $nasabah->id,
            ]);
        });

        // === 8. Buat Data Investasi ===
        Nasabah::all()->each(function ($nasabah) {
            // Gunakan $this->faker yang sudah diinisialisasi
            Investasi::factory()->count($this->faker->numberBetween(0, 2))->create([ // 0-2 investasi per nasabah
                'nasabah_id' => $nasabah->id,
            ]);
        });

        // === 9. Buat Data ZISWAF ===
        // Buat ZISWAF yang dilakukan oleh Nasabah
        Nasabah::all()->each(function ($nasabah) {
            // Gunakan $this->faker yang sudah diinisialisasi
            Ziswaf::factory()->count($this->faker->numberBetween(0, 3))->create([
                'nasabah_id' => $nasabah->id,
                'nama_donatur' => null, // Kosongkan nama donatur jika nasabah_id terisi
            ]);
        });
        // Buat ZISWAF yang dilakukan oleh Non-Nasabah
        Ziswaf::factory()->count(20)->create([
            'nasabah_id' => null,
            'nama_donatur' => $this->faker->name(), // Gunakan $this->faker
        ]);


        // === 10. Buat Data Financing Cards ===
        // Untuk setiap pembiayaan yang berstatus Aktif/Disetujui, buat kartu
        Pembiayaan::whereIn('status', ['Aktif', 'Disetujui'])->get()->each(function ($pembiayaan) {
            FinancingCard::factory()->create([
                'nasabah_id' => $pembiayaan->nasabah_id,
                'pembiayaan_id' => $pembiayaan->id,
                'nama_pada_kartu' => $pembiayaan->nasabah->nama_lengkap, // Nama di kartu dari nama nasabah
            ]);
        });

        // === 11. Buat Data Transactions ===
        // Buat transaksi untuk setiap rekening
        Rekening::all()->each(function ($rekening) {
            // Gunakan $this->faker yang sudah diinisialisasi
            Transaction::factory()->count($this->faker->numberBetween(5, 20))->create([
                'rekening_id' => $rekening->id,
                'financing_card_id' => null, // Default null, akan diisi jika ada transaksi kartu
                'saldo_sebelum_transaksi' => $rekening->saldo, // Ambil saldo saat ini
                'saldo_setelah_transaksi' => $rekening->saldo + $this->faker->randomFloat(2, -1000000, 1000000) // Dummy perubahan saldo
            ]);
        });
        // Buat transaksi untuk setiap kartu pembiayaan
        FinancingCard::all()->each(function ($card) {
            // Gunakan $this->faker yang sudah diinisialisasi
            Transaction::factory()->count($this->faker->numberBetween(3, 10))->create([
                'rekening_id' => null, // Transaksi kartu tidak selalu terkait rekening langsung
                'financing_card_id' => $card->id,
                'tipe_transaksi' => $this->faker->randomElement(['Debit', 'Pembayaran', 'Pembayaran_Kartu_Pembiayaan']),
                'saldo_sebelum_transaksi' => $card->limit_tersedia,
                'saldo_setelah_transaksi' => $card->limit_tersedia + $this->faker->randomFloat(2, -500000, 500000)
            ]);
        });


        // === 12. Buat Data Notifications ===
        User::all()->each(function ($user) {
            // Gunakan $this->faker yang sudah diinisialisasi
            Notification::factory()->count($this->faker->numberBetween(3, 15))->create([
                'user_id' => $user->id,
            ]);
        });

        // === 13. Buat Data Documents ===
        // Dokumen terkait Nasabah
        Nasabah::all()->each(function ($nasabah) {
            // Gunakan $this->faker yang sudah diinisialisasi
            Document::factory()->count($this->faker->numberBetween(1, 3))->create([
                'nasabah_id' => $nasabah->id,
                'pembiayaan_id' => null,
                'diupload_oleh_user_id' => User::inRandomOrder()->first()->id, // Admin random
            ]);
        });
        // Dokumen terkait Pembiayaan
        Pembiayaan::all()->each(function ($pembiayaan) {
            // Gunakan $this->faker yang sudah diinisialisasi
            Document::factory()->count($this->faker->numberBetween(1, 2))->create([
                'nasabah_id' => $pembiayaan->nasabah_id,
                'pembiayaan_id' => $pembiayaan->id,
                'diupload_oleh_user_id' => User::inRandomOrder()->first()->id, // Admin random
            ]);
        });


        // === 14. Buat Data Activity Logs ===
        User::all()->each(function ($user) {
            // Gunakan $this->faker yang sudah diinisialisasi
            ActivityLog::factory()->count($this->faker->numberBetween(5, 20))->create([
                'user_id' => $user->id,
            ]);
        });
        // Beberapa activity log oleh sistem (user_id null)
        ActivityLog::factory()->count(10)->create([
            'user_id' => null,
            'tipe_aktivitas' => 'Sistem Otomatis',
            'deskripsi' => $this->faker->sentence(), // Gunakan $this->faker
        ]);


        // === 15. Buat Data System Settings ===
        // Pastikan hanya membuat satu set setting atau mengupdate yang sudah ada
        $adminUser = User::where('role', 'super_admin')->first();
        SystemSetting::factory()->create([
            'key_setting' => 'nama_bank',
            'value_setting' => 'Bank Syariah Berkah Nusantara',
            'tipe_value' => 'string',
            'deskripsi' => 'Nama resmi bank',
            'updated_by_user_id' => $adminUser->id ?? null,
        ]);
        SystemSetting::factory()->create([
            'key_setting' => 'limit_transfer_harian_nasabah',
            'value_setting' => '25000000',
            'tipe_value' => 'integer',
            'deskripsi' => 'Batas transfer harian untuk nasabah',
            'updated_by_user_id' => $adminUser->id ?? null,
        ]);
        // Gunakan $this->faker yang sudah diinisialisasi
        SystemSetting::factory()->count(5)->create([
            'updated_by_user_id' => $adminUser->id ?? null,
        ]);
    }
}
