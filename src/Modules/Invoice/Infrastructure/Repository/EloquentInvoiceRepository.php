<?php

namespace Modules\Invoice\Infrastructure\Repository;

use Modules\Invoice\Domain\Enums\StatusEnum;
use Modules\Invoice\Domain\Repository\InvoiceRepository;
use Modules\Invoice\Domain\RootAggregate\Invoice;
use Modules\Invoice\Domain\RootAggregate\Product;
use Modules\Invoice\Domain\ValueObject\CustomerEmail;
use Modules\Invoice\Domain\ValueObject\CustomerName;
use Modules\Invoice\Domain\ValueObject\ProductName;
use Modules\Invoice\Infrastructure\Model\InvoiceModel;
use Modules\Invoice\Infrastructure\Model\ProductModel;
use Modules\Shared\ValueObject\Uuid;

class EloquentInvoiceRepository implements InvoiceRepository
{

    public function find(string $id): ?Invoice
    {
        /**
         * @var InvoiceModel $modelInvoice
         */
        $modelInvoice = InvoiceModel::find($id);

        if (!$modelInvoice) {
            return null;
        }

        $products = $modelInvoice->products()->chunkMap(function (ProductModel $productModel) {
            return new Product(
                id: new Uuid($productModel->id),
                name: new ProductName($productModel->name),
                quantity: $productModel->quantity,
                price: $productModel->price,
            );
        });

        return new Invoice(
            id: new Uuid($modelInvoice->id),
            customerName: new CustomerName($modelInvoice->customer_name),
            customerEmail: new CustomerEmail($modelInvoice->customer_email),
            status: StatusEnum::tryFrom($modelInvoice->status),
            products: $products->toArray(),
        );
    }

    public function create(Invoice $createdInvoice): void
    {
        $newInvoice = new InvoiceModel();

        $newInvoice->id = $createdInvoice->getId();
        $newInvoice->customer_name = $createdInvoice->getCustomerName();
        $newInvoice->customer_email = $createdInvoice->getCustomerEmail();
        $newInvoice->status = $createdInvoice->getStatus()->value;

        $newInvoice->save();
    }

    public function update(Invoice $updatedInvoice): void
    {
        $modelInvoice = InvoiceModel::find($updatedInvoice->getId());

        $modelInvoice->customer_name = $updatedInvoice->getCustomerName();
        $modelInvoice->customer_email = $updatedInvoice->getCustomerEmail();
        $modelInvoice->status = $updatedInvoice->getStatus()->value;

        $modelInvoice->save();
    }
}
