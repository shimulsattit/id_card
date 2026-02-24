<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'Principal',
            'Asst. Headmaster',
            'Headmaster',
            'Assistant Teacher',
            'Computer Operator',
            'Aya',
            'Night Guard'
        ];

        foreach ($defaults as $name) {
            Designation::firstOrCreate([
                'name' => $name,
                'school_id' => null
            ]);
        }
    }
}
