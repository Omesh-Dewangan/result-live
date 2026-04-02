<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1001; $i <= 1030; $i++) {
            $s1 = rand(30, 95);
            $s2 = rand(30, 95);
            $s3 = rand(30, 95);
            $s4 = rand(30, 95);
            $s5 = rand(30, 95);
            $total = $s1 + $s2 + $s3 + $s4 + $s5;
            $status = ($s1 > 33 && $s2 > 33 && $s3 > 33 && $s4 > 33 && $s5 > 33) ? 'Pass' : 'Fail';

            \App\Models\Result::create([
                'roll_number' => (string)$i,
                'name' => "Student $i",
                'father_name' => "Father $i",
                'course' => 'B.Tech',
                'subject1' => $s1,
                'subject2' => $s2,
                'subject3' => $s3,
                'subject4' => $s4,
                'subject5' => $s5,
                'total' => $total,
                'result_status' => $status,
            ]);
        }
    }
}
