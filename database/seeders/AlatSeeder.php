<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert alat (master table)
        DB::table('alat')->insert([
            ['nama_alat' => 'Kamera'],
            ['nama_alat' => 'Proyektor'],
        ]);
        
        // Get alat IDs
        $kameraId = DB::table('alat')->where('nama_alat', 'Kamera')->value('id');
        $proyektorId = DB::table('alat')->where('nama_alat', 'Proyektor')->value('id');
        
        // Insert peminjaman_alat with timestamps
        $now = now();
        DB::table('peminjaman_alat')->insert([
            [
                'kode_peminjaman' => 'PM00000001',
                'nama_peminjam' => 'Rina Kurnia',
                'nomor_identitas' => 'MHS-2201001',
                'jumlah_alat' => 2,
                'alat_id' => $kameraId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode_peminjaman' => 'PM00000002',
                'nama_peminjam' => 'Dedi Pranata',
                'nomor_identitas' => 'MHS-2201002',
                'jumlah_alat' => 1,
                'alat_id' => $proyektorId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode_peminjaman' => 'PM00000003',
                'nama_peminjam' => 'Mira Ananda',
                'nomor_identitas' => 'MHS-2201003',
                'jumlah_alat' => 3,
                'alat_id' => $kameraId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
