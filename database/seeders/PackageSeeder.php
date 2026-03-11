<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\PackageFeature;

class PackageSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // --- PAKET INSTAGRAM ---
            [
                'name' => 'Instagram Lite',
                'type' => 'Instagram',
                'price' => 1000000,
                'features' => [
                    '14 feed (2 carousel)',
                    '1 reels (15-30 detik)',
                    '5 story',
                    '1x revisi per konten',
                    'Admin posting',
                    'Optimasi akun',
                    'Privat group',
                    'Laporan 1x (Edukasi)'
                ]
            ],
            [
                'name' => 'Instagram Starter',
                'type' => 'Instagram',
                'price' => 2500000,
                'features' => [
                    '18 feed plus 5 carousel (Total 23 template)',
                    '3 reels (15-30 detik)',
                    '12 story',
                    '1x revisi per konten',
                    'Admin posting',
                    'Optimasi akun',
                    'Privat grup',
                    'Laporan 3x (Kecuali minggu pertama)'
                ]
            ],
            [
                'name' => 'Instagram Growth',
                'type' => 'Instagram',
                'price' => 5000000,
                'features' => [
                    '24 feed (Custom carousel)',
                    '6 reels (5 free 1)',
                    '20 story',
                    '3x revisi konten pilar',
                    '2x revisi per konten',
                    'Admin posting',
                    'Optimasi akun',
                    'Privat grup konsul',
                    'Full laporan 4x (Edukasi, Kinerja, Solusi)',
                    'Ads 3%'
                ]
            ],

            // --- PAKET TIKTOK ---
            [
                'name' => 'TikTok Lite',
                'type' => 'TikTok',
                'price' => 1500000,
                'features' => [
                    '10 Video Konten (15-60 detik)',
                    'Optimasi Profil & Bio',
                    'Pembuatan Ide & Scripting Dasar',
                    'Riset Tren & Sound Viral',
                    'Admin Posting & Penjadwalan',
                    '1x Revisi per Video (Minor)',
                    'Laporan Kinerja Bulanan'
                ]
            ],
            [
                'name' => 'TikTok Starter',
                'type' => 'TikTok',
                'price' => 3500000,
                'features' => [
                    '15 Video Konten (Fokus jualan/hook)',
                    'Setup TikTok Shop Dasar (10-15 SKU)',
                    'Optimasi Profil & SEO TikTok',
                    'Pembuatan Ide, Content Pillar & Scripting',
                    'Riset Tren & Sound Viral',
                    'Admin Posting & Manajemen Audiens',
                    '2x Revisi per Video (Minor)',
                    'Laporan Kinerja & Evaluasi Bulanan'
                ]
            ],
            [
                'name' => 'TikTok Growth',
                'type' => 'TikTok',
                'price' => 7500000,
                'features' => [
                    '25 Video Konten',
                    'Setup TikTok Shop Lengkap',
                    'Bonus: Pendampingan/Scripting Live',
                    'Setup TikTok Ads Dasar (Saldo klien)',
                    'Manajemen Komen & DM',
                    '2x Revisi per Video',
                    'Laporan Mingguan & Evaluasi Bulanan'
                ]
            ],

            // --- PAKET YOUTUBE ---
            [
                'name' => 'YouTube Shorts',
                'type' => 'YouTube',
                'price' => 1500000,
                'features' => [
                    '15 Video YouTube Shorts (max 60 detik)',
                    'Repurpose konten dari TikTok/IG',
                    'Optimasi Profil Channel',
                    'Riset Keyword & SEO Dasar',
                    'Admin Posting & Penjadwalan',
                    'Laporan Kinerja Bulanan'
                ]
            ],
            [
                'name' => 'YouTube Standard',
                'type' => 'YouTube',
                'price' => 3500000,
                'features' => [
                    '4 Video Long-Form (max 10 menit)',
                    '4 Custom Thumbnail Keren',
                    'Bonus: 8 Video Shorts (Potongan video panjang)',
                    'Riset Ide Konten & Content Calendar',
                    'Optimasi SEO YouTube Lengkap',
                    '2x Revisi per Video (Minor)',
                    'Laporan & Evaluasi Bulanan'
                ]
            ],
            [
                'name' => 'YouTube Growth',
                'type' => 'YouTube',
                'price' => 6500000,
                'features' => [
                    '8 Video Long-Form (max 15 menit)',
                    '8 Custom Thumbnail Premium',
                    'Bonus: 15 Video Shorts (Potongan video panjang)',
                    'Riset Ide Konten, Kompetitor & Scripting',
                    'Optimasi SEO YouTube Tingkat Lanjut',
                    'Setup YouTube Ads Dasar (Opsional)',
                    '2x Revisi per Video',
                    'Laporan & Konsultasi Strategi Bulanan'
                ]
            ],

            // --- PAKET BUNDLE ALL-IN-ONE (OMNI CHANNEL) ---
            [
                'name' => 'Omni Lite (Small Business)',
                'type' => 'All-in-One',
                'price' => 8500000,
                'features' => [
                    'Instagram: 12 Feed & 2 Reels',
                    'TikTok: 8 Video Konten',
                    'YouTube: 10 Shorts (Repurpose)',
                    'Admin Posting All Platform',
                    'Optimasi Profil Semua Akun',
                    '1x Revisi per Konten',
                    'Laporan Kinerja Bulanan Terpadu'
                ]
            ],
            [
                'name' => 'Omni Starter (Professional)',
                'type' => 'All-in-One',
                'price' => 18000000,
                'features' => [
                    'Instagram: 20 Feed, 5 Reels & 15 Story',
                    'TikTok: 20 Video Konten',
                    'YouTube: 4 Video Panjang & 15 Shorts',
                    'Setup TikTok Shop & Optimasi SEO',
                    'Ide Konten & Scripting Premium',
                    'Privat Group Konsultasi 24/7',
                    '2x Revisi per Konten',
                    'Laporan Detail & Evaluasi Strategi'
                ]
            ],
            [
                'name' => 'Omni Executive (Corporate)',
                'type' => 'All-in-One',
                'price' => 35000000,
                'features' => [
                    'Instagram & TikTok: Daily Posting (Full)',
                    'YouTube: 8 Video Panjang & 30 Shorts',
                    'Full Management TikTok Shop & Ads',
                    'Manajemen DM & Komentar (CS All Channel)',
                    'Bonus: 1x Professional Product Photoshoot',
                    '3x Revisi per Konten',
                    'Meeting Evaluasi Mingguan & Strategi Marketing',
                    'Priority Support (Manager Account Khusus)'
                ]
            ],
        ];

        foreach ($data as $item) {
            $package = Package::create([
                'name' => $item['name'],
                'type' => $item['type'],
                'price' => $item['price'],
                'is_active' => true,
            ]);

            foreach ($item['features'] as $feature) {
                PackageFeature::create([
                    'package_id' => $package->id,
                    'feature_name' => $feature,
                ]);
            }
        }
    }
}