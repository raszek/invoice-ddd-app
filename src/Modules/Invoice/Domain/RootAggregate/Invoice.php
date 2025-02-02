<?php

namespace Modules\Invoice\Domain\RootAggregate;

use Modules\Invoice\Domain\Enums\StatusEnum;
use Modules\Invoice\Domain\Event\InvoiceSentDomainEvent;
use Modules\Invoice\Domain\Event\InvoiceSentProduct;
use Modules\Invoice\Domain\Exception\CannotAddProductException;
use Modules\Invoice\Domain\Exception\CannotMarkDeliveredException;
use Modules\Invoice\Domain\Exception\CannotSendInvoiceException;
use Modules\Invoice\Domain\ValueObject\CustomerEmail;
use Modules\Invoice\Domain\ValueObject\CustomerName;
use Modules\Shared\Domain\Aggregate\RootAggregate;
use Modules\Shared\Helper\ArrayHelper;
use Modules\Shared\ValueObject\Uuid;

class Invoice extends RootAggregate
{

    public function __construct(
        private readonly Uuid $id,
        private readonly CustomerName $customerName,
        private readonly CustomerEmail $customerEmail,
        private StatusEnum $status,
        /**
         * @var Product[]
         */
        private array $products,
    ) {
        $this->setProducts($this->products);
    }

    public static function create(
        string $customerName,
        string $customerEmail,
    ): static
    {
        return new static(
            id: Uuid::create(),
            customerName: new CustomerName($customerName),
            customerEmail: new CustomerEmail($customerEmail),
            status: StatusEnum::Draft,
            products: []
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function getStatus(): StatusEnum
    {
        return $this->status;
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    public function getTotalPrice(): int
    {
        $totalPrice = 0;
        foreach ($this->getProducts() as $product) {
            $totalPrice += $product->getTotalUnitPrice();
        }

        return $totalPrice;
    }

    public function addNewProduct(Product $newProduct): void
    {
        if ($this->status !== StatusEnum::Draft) {
            throw new CannotAddProductException(
                'Cannot add product when invoice status is other than draft'
            );
        }

        foreach ($this->getProducts() as $product) {
            if ($product->getName() === $newProduct->getName()) {
                throw new CannotAddProductException(
                    sprintf(
                        'Cannot add product. Product with name %s already exist in this invoice.',
                        $newProduct->getName()
                    )
                );
            }
        }
    }

    public function send(): void
    {
        if ($this->status !== StatusEnum::Draft) {
            throw new CannotSendInvoiceException(
                sprintf('Cannot send invoice with %s status', $this->status->value)
            );
        }

        if ($this->getTotalPrice() <= 0) {
            throw new CannotSendInvoiceException('Cannot send invoice when total cost is not bigger than 0');
        }

        $this->status = StatusEnum::Sending;

        $this->record(new InvoiceSentDomainEvent(
            id: $this->id,
            destinationEmail: $this->customerEmail,
            name: $this->customerName,
            totalCost: $this->getTotalPrice(),
            products: ArrayHelper::map($this->products, fn(Product $product) => new InvoiceSentProduct(
                productName: $product->getName(),
                cost: $product->getTotalUnitPrice(),
            )),
        ));
    }

    public function markDelivered(): void
    {
        if ($this->status !== StatusEnum::Sending) {
            throw new CannotMarkDeliveredException('Cannot mark as delivered when invoice status is other than sending');
        }

        $this->status = StatusEnum::SentToClient;
    }

    private function setProducts(array $products): void
    {
        $this->products = [];
        $this->addProducts($products);
    }

    private function addProducts(array $products): void
    {
        foreach ($products as $product) {
            $this->addProduct($product);
        }
    }

    private function addProduct(Product $product): void
    {
        $this->products[] = $product;
    }

}
