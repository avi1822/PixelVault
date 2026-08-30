<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Seed Admin User
        \App\Models\User::updateOrCreate(
            ['user_name' => 'admin'],
            [
                'first_name' => 'Admin',
                'last_name' => 'System',
                'user_name' => 'admin',
                'phone_number' => '0712345678',
                'address' => 'PixelVault HQ',
                'propic' => 1,
                'email' => 'pixelvault1011@gmail.com',
                'password' => \Hash::make('admin123'),
                'isadmin' => 1,
            ]
        );

        // 2. Seed Computers (30 Computers)
        for ($i = 1; $i <= 30; $i++) {
            \App\Models\Computer::updateOrCreate(
                ['cid' => $i],
                [
                    'cid' => $i,
                    'spec1' => 'Intel Core i9-13900K',
                    'spec2' => 'NVIDIA GeForce RTX 4080 16GB',
                    'spec3' => '32GB DDR5 6000MHz RAM',
                    'spec4' => '2TB NVMe M.2 SSD',
                    'spec5' => '27" 240Hz IPS Gaming Monitor',
                    'spec6' => 'RGB Mechanical Keyboard & Gaming Mouse',
                    'spec7' => '7.1 Surround Sound Headset',
                ]
            );
        }

        // 3. Seed Games
        $gamesList = [
            ['name' => 'Cyberpunk 2077', 'path' => 'https://t4.ftcdn.net/jpg/04/33/31/33/360_F_433313379_H0YcLl0UsKdGf7Jv9pkjuzboos17OMWW.jpg'],
            ['name' => 'Valorant', 'path' => 'https://t3.ftcdn.net/jpg/01/63/91/94/360_F_163919432_qiG1V2wEiNMsqaHT0g11EBmBQpih6Czm.jpg'],
            ['name' => 'Grand Theft Auto V', 'path' => 'https://t3.ftcdn.net/jpg/04/29/97/24/360_F_429972422_idgQSEcP8Ur9ky1ZXXUlrGwx39wUjyqH.jpg'],
            ['name' => 'Call of Duty: Warzone', 'path' => 'https://t4.ftcdn.net/jpg/04/33/31/33/360_F_433313379_H0YcLl0UsKdGf7Jv9pkjuzboos17OMWW.jpg'],
            ['name' => 'Counter-Strike 2', 'path' => 'https://t3.ftcdn.net/jpg/01/63/91/94/360_F_163919432_qiG1V2wEiNMsqaHT0g11EBmBQpih6Czm.jpg'],
            ['name' => 'Apex Legends', 'path' => 'https://t3.ftcdn.net/jpg/04/29/97/24/360_F_429972422_idgQSEcP8Ur9ky1ZXXUlrGwx39wUjyqH.jpg'],
            ['name' => 'Dota 2', 'path' => 'https://t4.ftcdn.net/jpg/04/33/31/33/360_F_433313379_H0YcLl0UsKdGf7Jv9pkjuzboos17OMWW.jpg'],
            ['name' => 'League of Legends', 'path' => 'https://t3.ftcdn.net/jpg/01/63/91/94/360_F_163919432_qiG1V2wEiNMsqaHT0g11EBmBQpih6Czm.jpg'],
            ['name' => 'Fortnite', 'path' => 'https://t3.ftcdn.net/jpg/04/29/97/24/360_F_429972422_idgQSEcP8Ur9ky1ZXXUlrGwx39wUjyqH.jpg'],
            ['name' => 'Minecraft', 'path' => 'https://t4.ftcdn.net/jpg/04/33/31/33/360_F_433313379_H0YcLl0UsKdGf7Jv9pkjuzboos17OMWW.jpg'],
            ['name' => 'God of War', 'path' => 'https://t3.ftcdn.net/jpg/01/63/91/94/360_F_163919432_qiG1V2wEiNMsqaHT0g11EBmBQpih6Czm.jpg'],
            ['name' => 'EA SPORTS FC 24', 'path' => 'https://t3.ftcdn.net/jpg/04/29/97/24/360_F_429972422_idgQSEcP8Ur9ky1ZXXUlrGwx39wUjyqH.jpg'],
        ];

        foreach ($gamesList as $g) {
            \App\Models\Game::updateOrCreate(
                ['name' => $g['name']],
                ['name' => $g['name'], 'path' => $g['path']]
            );
        }

        // 4. Seed Packages
        $packagesList = [
            ['package_id' => 1, 'package_name' => 'Standard Hourly Pass', 'package_time' => 1, 'package_price' => 150],
            ['package_id' => 2, 'package_name' => 'Pro Gamer 3-Hour Pass', 'package_time' => 3, 'package_price' => 400],
            ['package_id' => 3, 'package_name' => 'Night Owl 6-Hour Pass', 'package_time' => 6, 'package_price' => 750],
        ];

        foreach ($packagesList as $p) {
            \App\Models\Package::updateOrCreate(
                ['package_id' => $p['package_id']],
                $p
            );
        }

    }
}
