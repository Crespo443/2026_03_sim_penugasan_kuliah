<?php
namespace Database\Factories;

use App\Models\MataKuliah;
use Illuminate\Database\Eloquent\Factories\Factory;

class MataKuliahFactory extends Factory
{
    protected $model = MataKuliah::class;

    public function definition(): array
    {
        return [
            'kode' => $this->faker->unique()->bothify('MK###'),
            'nama' => $this->faker->words(2, true),
            'dosen' => $this->faker->name(),
            'ruangan' => $this->faker->bothify('Ruang-##'),
            'hari' => $this->faker->randomElement(['Senin','Selasa','Rabu','Kamis','Jumat']),
            'jam_mulai' => $this->faker->time('H:i'),
            'jam_selesai' => $this->faker->time('H:i'),
        ];
    }
}
