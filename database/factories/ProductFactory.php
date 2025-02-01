<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Invoice\Infrastructure\Model\ProductModel;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<ProductModel>
 */
class ProductFactory extends Factory
{

    protected $model = ProductModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(0, 10),
            'quantity' => $this->faker->numberBetween(0, 10),
        ];
    }
}
