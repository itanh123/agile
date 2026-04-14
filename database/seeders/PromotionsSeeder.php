<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\BuildsTableRows;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromotionsSeeder extends Seeder
{
    use BuildsTableRows;

    public function run(): void
    {
        $table = 'promotions';
        if (! $this->tableExists($table)) {
            return;
        }

        $faker = fake();
        $codes = ['WELCOME10', 'SPA20', 'PETLOVE15'];

        foreach ($codes as $code) {
            $id = DB::table($table)->insertGetId(
                $this->withTimestamps($table, [
                    'code' => $code,
                    'description' => $faker->sentence(),
                    'discount_type' => 'percent',
                    'discount_value' => $faker->randomElement([10, 15, 20]),
                    'starts_at' => $faker->dateTimeBetween('-15 days', 'now'),
                    'ends_at' => $faker->dateTimeBetween('+10 days', '+60 days'),
                    'is_active' => true,
                ])
            );

            SeederState::$promotionIds[] = $id;
        }
    }
}
