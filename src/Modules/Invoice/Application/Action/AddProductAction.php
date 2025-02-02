<?php

namespace Modules\Invoice\Application\Action;

use Modules\Invoice\Application\Request\ProductRequest;
use Modules\Invoice\Application\Response\ProductResponse;
use Modules\Invoice\Domain\Exception\CannotAddProductException;
use Modules\Invoice\Domain\Exception\InvoiceNotExistException;
use Modules\Invoice\Domain\Repository\InvoiceRepository;
use Modules\Invoice\Domain\Repository\ProductRepository;
use Modules\Invoice\Domain\RootAggregate\Product;
use Modules\Invoice\Domain\ValueObject\ProductName;
use Modules\Shared\ValueObject\Uuid;

readonly class AddProductAction
{

    public function __construct(
        private InvoiceRepository $invoiceRepository,
        private ProductRepository $productRepository
    ) {
    }

    /**
     * @throws InvoiceNotExistException
     * @throws CannotAddProductException
     */
    public function execute(ProductRequest $productRequest): ProductResponse
    {
        $invoice = $this->invoiceRepository->find($productRequest->invoiceId);

        if (!$invoice) {
            throw new InvoiceNotExistException('Invoice not found');
        }

        $newProduct = new Product(
            id: Uuid::create(),
            name: new ProductName($productRequest->name),
            quantity: $productRequest->quantity,
            price: $productRequest->price,
        );

        $invoice->addNewProduct($newProduct);

        $this->productRepository->create($newProduct, $invoice->getId());

        return new ProductResponse(
            name: $newProduct->getName(),
            quantity: $newProduct->getQuantity(),
            price: $newProduct->getPrice(),
        );

    }
}
