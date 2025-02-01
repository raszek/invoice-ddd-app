<?php

namespace Modules\Invoice\Presentation\Http;

use Modules\Invoice\Application\Action\CreateInvoice;
use Modules\Invoice\Application\Action\GetInvoice;
use Modules\Invoice\Application\Action\SendInvoice;
use Modules\Invoice\Application\Request\InvoiceRequest;
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
        $sendInvoice->execute($id);

        return response()->noContent();
    }
}
