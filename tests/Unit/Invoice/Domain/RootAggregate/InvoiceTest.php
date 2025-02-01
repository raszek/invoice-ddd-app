<?php

namespace Tests\Unit\Invoice\Domain\RootAggregate;

use Modules\Invoice\Domain\Enums\StatusEnum;
use Modules\Invoice\Domain\Exception\CannotSendInvoiceException;
use Modules\Invoice\Domain\RootAggregate\Invoice;
use Modules\Invoice\Domain\RootAggregate\Product;
use Modules\Invoice\Domain\ValueObject\CustomerEmail;
use Modules\Invoice\Domain\ValueObject\CustomerName;
use Modules\Shared\ValueObject\Uuid;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{

    #[Test]
    public function invoice_cannot_be_sent_if_status_is_other_than_draft()
    {
        $invoice = new Invoice(
            id: Uuid::create(),
            customerName: new CustomerName('John Doe'),
            customerEmail: new CustomerEmail('johndoe@example.com'),
            status: StatusEnum::Sending,
            products: [
                new Product(
                    name: 'Toothbrush',
                    quantity: 1,
                    price: 10
                )
            ]
        );

        $error = null;
        try {
            $invoice->send();
        } catch (CannotSendInvoiceException $e) {
            $error = $e->getMessage();
        }

        $this->assertNotNull($error);
        $this->assertEquals('Cannot send invoice with sending status', $error);
    }

    #[Test]
    public function invoice_cannot_be_sent_if_total_cast_is_not_bigger_than_0()
    {
        $invoice = new Invoice(
            id: Uuid::create(),
            customerName: new CustomerName('John Doe'),
            customerEmail: new CustomerEmail('johndoe@example.com'),
            status: StatusEnum::Draft,
            products: []
        );

        $error = null;
        try {
            $invoice->send();
        } catch (CannotSendInvoiceException $e) {
            $error = $e->getMessage();
        }

        $this->assertNotNull($error);
        $this->assertEquals('Cannot send invoice when total cost is not bigger than 0', $error);
    }

    #[Test]
    public function invoice_can_be_sent()
    {
        $invoice = new Invoice(
            id: Uuid::create(),
            customerName: new CustomerName('John Doe'),
            customerEmail: new CustomerEmail('johndoe@example.com'),
            status: StatusEnum::Draft,
            products: [
                new Product(
                    name: 'Toothbrush',
                    quantity: 1,
                    price: 10
                )
            ]
        );

        $invoice->send();

        $this->assertEquals('sending', $invoice->getStatus()->value);
    }

}
