<?php

declare(strict_types=1);

namespace Tests\Feature\Invoice\Http;

use Database\Factories\InvoiceFactory;
use Database\Factories\ProductFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Invoice\Domain\Enums\StatusEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InvoiceControllerTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        $this->setUpFaker();

        parent::setUp();
    }

    #[Test]
    public function user_can_view_invoice(): void
    {
        $invoice = InvoiceFactory::new()->create([
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'status' => StatusEnum::Draft->value
        ]);

        $uri = route('invoices.view', [
            'id' => $invoice->id,
        ]);

        $this->getJson($uri)
            ->assertOk()
            ->assertJson([
                'customerName' => 'John Doe',
                'customerEmail' => 'john@example.com',
                'status' => StatusEnum::Draft->value
            ]);
    }

    #[Test]
    public function user_can_create_invoice(): void
    {
        $uri = route('invoices.create');

        $response = $this->post($uri, [
            'customerName' => 'John Doe',
            'customerEmail' => 'john@example.com',
        ]);

        $response->assertOk()
            ->assertJson([
                'customerName' => 'John Doe',
                'customerEmail' => 'john@example.com',
                'status' => StatusEnum::Draft->value
            ]);

        $this->assertDatabaseHas('invoices', [
            'customer_email' => 'john@example.com',
        ]);
    }

    #[Test]
    public function user_can_send_invoice(): void
    {
        $invoice = InvoiceFactory::new()->create([
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'status' => StatusEnum::Draft->value
        ]);

        ProductFactory::new()->create([
            'invoice_id' => $invoice->id,
            'quantity' => 5,
            'price' => 100
        ]);

        $uri = route('invoices.send', [
            'id' => $invoice->id,
        ]);

        $this->post($uri)->assertStatus(204);

        $this->assertDatabaseHas('invoices', [
            'customer_email' => 'john@example.com',
            'status' => StatusEnum::Sending->value
        ]);
    }

}
