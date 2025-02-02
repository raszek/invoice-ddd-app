<?php

declare(strict_types=1);

namespace Tests\Feature\Notification\Http;

use Database\Factories\InvoiceFactory;
use Database\Factories\ProductFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Invoice\Domain\Enums\StatusEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        $this->setUpFaker();

        parent::setUp();
    }

    #[Test]
    public function hook_can_mark_messages_as_delivered(): void
    {
        $invoice = InvoiceFactory::new()->create([
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'status' => StatusEnum::Sending->value
        ]);

        ProductFactory::new()->create([
            'invoice_id' => $invoice->id,
            'quantity' => 5,
            'price' => 100
        ]);

        $uri = route('notification.hook', [
            'action' => 'delivered',
            'reference' => $invoice->id,
        ]);

        $this->post($uri)->assertNoContent();

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => StatusEnum::SentToClient->value
        ]);
    }

    #[DataProvider('hookActionProvider')]
    public function testHook(string $action): void
    {
        $uri = route('notification.hook', [
            'action' => $action,
            'reference' => $this->faker->uuid,
        ]);

        $this->post($uri)->assertNoContent();
    }

    public function testInvalid(): void
    {
        $params = [
            'action' => 'dummy',
            'reference' => $this->faker->numberBetween(),
        ];

        $uri = route('notification.hook', $params);
        $this->post($uri)->assertNotFound();
    }

    public static function hookActionProvider(): array
    {
        return [
            ['delivered'],
            ['dummy'],
        ];
    }
}
