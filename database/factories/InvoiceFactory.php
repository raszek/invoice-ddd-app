<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Invoice\Domain\Enums\StatusEnum;
use Modules\Invoice\Infrastructure\Model\InvoiceModel;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<InvoiceModel>
 */
class InvoiceFactory extends Factory
{

    protected $model = InvoiceModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->unique()->safeEmail(),
            'status' => StatusEnum::Draft->value
        ];
    }

}
