<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Domain;

class DomainFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Domain::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'client' => $this->faker->word(),
            'site_url' => $this->faker->word(),
            'service' => $this->faker->word(),
            'host' => $this->faker->word(),
            'invoice' => $this->faker->dateTime(),
            'google_drive' => $this->faker->text(),
            'start_date' => $this->faker->dateTime(),
            'end_date' => $this->faker->dateTime(),
        ];
    }
}
