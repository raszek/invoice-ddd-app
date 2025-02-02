<?php

namespace Modules\Invoice\Infrastructure\Repository;

use Modules\Invoice\Domain\Repository\ProductRepository;
use Modules\Invoice\Domain\RootAggregate\Product;
use Modules\Invoice\Infrastructure\Model\ProductModel;

class EloquentProductRepository implements ProductRepository
{

    public function create(Product $product, string $invoiceId): void
    {
        $productModel = new ProductModel();

        $productModel->id = $product->getId()->get();
        $productModel->name = $product->getName();
        $productModel->price = $product->getPrice();
        $productModel->quantity = $product->getQuantity();
        $productModel->invoice_id = $invoiceId;

        $productModel->save();
    }
}
