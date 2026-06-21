<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Faculty;
use App\Models\Department;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'faculty' => ['nom' => 'Faculté des Sciences et Techniques', 'code' => 'FST'],
                'departments' => [
                    ['nom' => 'Département Informatique', 'code' => 'INFO'],
                    ['nom' => 'Département Mathématiques', 'code' => 'MATH'],
                    ['nom' => 'Département Physique',      'code' => 'PHY'],
                ],
            ],
            [
                'faculty' => ['nom' => 'Faculté des Sciences Économiques', 'code' => 'FSEG'],
                'departments' => [
                    ['nom' => 'Département Économie', 'code' => 'ECO'],
                    ['nom' => 'Département Gestion',  'code' => 'GEST'],
                ],
            ],
            [
                'faculty' => ['nom' => 'Faculté de Droit', 'code' => 'FD'],
                'departments' => [
                    ['nom' => 'Département Droit Privé',  'code' => 'DP'],
                    ['nom' => 'Département Droit Public', 'code' => 'DPU'],
                ],
            ],
            [
                'faculty' => ['nom' => 'Faculté des Lettres et Sciences Humaines', 'code' => 'FLSH'],
                'departments' => [
                    ['nom' => 'Département Lettres Modernes', 'code' => 'LM'],
                    ['nom' => 'Département Histoire',         'code' => 'HIST'],
                ],
            ],
        ];

        foreach ($data as $item) {
            $faculty = Faculty::firstOrCreate(['code' => $item['faculty']['code']], $item['faculty']);
            foreach ($item['departments'] as $dept) {
                Department::firstOrCreate(
                    ['code' => $dept['code']],
                    array_merge($dept, ['faculty_id' => $faculty->id])
                );
            }
        }

        $this->command->info('Facultés et départements créés avec succès.');
    }
}