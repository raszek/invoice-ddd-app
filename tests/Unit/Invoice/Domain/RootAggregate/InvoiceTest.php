<?php

namespace Tests\Unit\Invoice\Domain\RootAggregate;

use Modules\Invoice\Domain\Enums\StatusEnum;
use Modules\Invoice\Domain\Exception\CannotAddProductException;
use Modules\Invoice\Domain\Exception\CannotMarkDeliveredException;
use Modules\Invoice\Domain\Exception\CannotSendInvoiceException;
use Modules\Invoice\Domain\RootAggregate\Invoice;
use Modules\Invoice\Domain\RootAggregate\Product;
use Modules\Invoice\Domain\ValueObject\CustomerEmail;
use Modules\Invoice\Domain\ValueObject\CustomerName;
use Modules\Invoice\Domain\ValueObject\ProductName;
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
                    id: Uuid::create(),
                    name: new ProductName('Toothbrush'),
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
                    id: Uuid::create(),
                    name: new ProductName('Toothbrush'),
                    quantity: 1,
                    price: 10
                )
            ]
        );

        $invoice->send();

        $this->assertEquals('sending', $invoice->getStatus()->value);
    }

    #[Test]
    public function invoice_cannot_be_marked_as_delivered_when_status_is_other_than_sending()
    {
        $invoiceId = Uuid::create();

        $invoice = new Invoice(
            id: $invoiceId,
            customerName: new CustomerName('John Doe'),
            customerEmail: new CustomerEmail('johndoe@example.com'),
            status: StatusEnum::Draft,
            products: [
                new Product(
                    id: Uuid::create(),
                    name: new ProductName('Toothbrush'),
                    quantity: 1,
                    price: 10,
                )
            ]
        );

        $error = null;
        try {
            $invoice->markDelivered();
        } catch (CannotMarkDeliveredException $e) {
            $error = $e->getMessage();
        }

        $this->assertNotNull($error);
        $this->assertEquals('Cannot mark as delivered when invoice status is other than sending', $error);
    }

    #[Test]
    public function invoice_can_be_marked_as_delivered()
    {
        $invoice = new Invoice(
            id: Uuid::create(),
            customerName: new CustomerName('John Doe'),
            customerEmail: new CustomerEmail('johndoe@example.com'),
            status: StatusEnum::Sending,
            products: [
                new Product(
                    id: Uuid::create(),
                    name: new ProductName('Toothbrush'),
                    quantity: 1,
                    price: 10
                )
            ]
        );

        $invoice->markDelivered();

        $this->assertEquals(StatusEnum::SentToClient, $invoice->getStatus());
    }

    #[Test]
    public function invoice_cannot_add_product_if_status_is_other_than_draft()
    {
        $invoice = new Invoice(
            id: Uuid::create(),
            customerName: new CustomerName('John Doe'),
            customerEmail: new CustomerEmail('johndoe@example.com'),
            status: StatusEnum::Sending,
            products: [
            ]
        );

        $error = null;
        try {
            $invoice->addNewProduct(new Product(
                id: Uuid::create(),
                name: new ProductName('Toothbrush'),
                quantity: 1,
                price: 10
            ));
        } catch (CannotAddProductException $e) {
            $error = $e->getMessage();
        }

        $this->assertNotNull($error);
        $this->assertEquals('Cannot add product when invoice status is other than draft', $error);
    }

    #[Test]
    public function invoice_cannot_add_product_if_there_already_product_with_same_name()
    {
        $invoice = new Invoice(
            id: Uuid::create(),
            customerName: new CustomerName('John Doe'),
            customerEmail: new CustomerEmail('johndoe@example.com'),
            status: StatusEnum::Draft,
            products: [
                new Product(
                    id: Uuid::create(),
                    name: new ProductName('Toothbrush'),
                    quantity: 1,
                    price: 10
                )
            ]
        );

        $error = null;
        try {
            $invoice->addNewProduct(new Product(
                id: Uuid::create(),
                name: new ProductName('Toothbrush'),
                quantity: 1,
                price: 10
            ));
        } catch (CannotAddProductException $e) {
            $error = $e->getMessage();
        }

        $this->assertNotNull($error);
        $this->assertEquals('Cannot add product. Product with name Toothbrush already exist in this invoice.', $error);
    }

}
