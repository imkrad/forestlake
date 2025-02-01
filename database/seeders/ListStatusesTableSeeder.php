<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ListStatusesTableSeeder extends Seeder
{
    /**
     * Auto generated seeder file.
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('list_statuses')->delete();
        
        \DB::table('list_statuses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Available',
                'type' => 'Lot',
                'color' => 'bg-success',
                'others' => 'text-success',
                'step' => 0,
                'is_active' => 1,
                'created_at' => '2024-08-18 15:57:50',
                'updated_at' => '2024-08-18 15:57:50',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Reserved',
                'type' => 'Lot',
                'color' => 'bg-warning',
                'others' => 'text-warning',
                'step' => 0,
                'is_active' => 1,
                'created_at' => '2024-08-18 15:57:50',
                'updated_at' => '2024-08-18 15:57:50',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Sold',
                'type' => 'Lot',
                'color' => 'bg-danger',
                'others' => 'text-danger',
                'step' => 1,
                'is_active' => 1,
                'created_at' => '2024-08-18 15:57:50',
                'updated_at' => '2024-08-18 15:57:50',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Occupied',
                'type' => 'Lot',
                'color' => 'bg-dark',
                'others' => 'text-dark',
                'step' => 2,
                'is_active' => 1,
                'created_at' => '2024-08-18 15:57:50',
                'updated_at' => '2024-08-18 15:57:50',
            ),
        ));

        
    }
}