<?php

namespace App\Console\Commands;

use App\Models\Menu;
use Database\Seeders\CategorySeeder;
use Database\Seeders\MenuSeeder;
use Illuminate\Console\Command;

class SeedCatalogIfEmpty extends Command
{
    protected $signature = 'catalog:seed-if-empty';

    protected $description = 'Isi katalog contoh hanya jika belum ada menu.';

    public function handle(): int
    {
        if (Menu::query()->exists()) {
            $this->info('Katalog sudah berisi menu; tidak ada perubahan.');

            return self::SUCCESS;
        }

        foreach ([CategorySeeder::class, MenuSeeder::class] as $seeder) {
            $exitCode = $this->call('db:seed', [
                '--class' => $seeder,
                '--force' => true,
            ]);

            if ($exitCode !== self::SUCCESS) {
                return $exitCode;
            }
        }

        $this->info('Katalog awal berhasil dibuat.');

        return self::SUCCESS;
    }
}
