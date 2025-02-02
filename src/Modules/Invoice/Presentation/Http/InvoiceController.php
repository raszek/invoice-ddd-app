<?php

namespace Modules\Invoice\Presentation\Http;

use Illuminate\Http\JsonResponse;
use Modules\Invoice\Application\Action\AddProductAction;
use Modules\Invoice\Application\Action\CreateInvoice;
use Modules\Invoice\Application\Action\GetInvoice;
use Modules\Invoice\Application\Action\SendInvoice;
use Modules\Invoice\Application\Request\InvoiceRequest;
use Modules\Invoice\Application\Request\ProductRequest;
use Modules\Invoice\Domain\Exception\CannotSendInvoiceException;
use Modules\Invoice\Infrastructure\Request\AddInvoiceProductRequest;
use Modules\Invoice\Infrastructure\Request\CreateInvoiceRequest;
use Symfony\Component\HttpFoundation\Response;

class InvoiceController
{

    public function view(GetInvoice $getInvoice, string $id): Response
    {
        $response = $getInvoice->execute($id);

        return response()->json($response->toArray());
    }

    public function create(CreateInvoiceRequest $request, CreateInvoice $createInvoice): Response
    {
        $validated = $request->validated();

        $invoiceResponse = $createInvoice->execute(new InvoiceRequest(...$validated));

        return response()->json($invoiceResponse->toArray());
    }

    public function send(SendInvoice $sendInvoice, string $id): Response
    {
        try {
            $sendInvoice->execute($id);
        } catch (CannotSendInvoiceException $e) {
            abort(400, $e->getMessage());
        }

        return response()->noContent();
    }

    public function addProduct(
        AddProductAction $addProduct,
        AddInvoiceProductRequest $request,
        string $invoiceId
    ): JsonResponse
    {
        $validated = $request->validated();

        $productResponse = $addProduct->execute(new ProductRequest(
            name: $validated['name'],
            quantity: $validated['quantity'],
            price: $validated['price'],
            invoiceId: $invoiceId
        ));

        return response()
            ->json($productResponse->toArray())
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
