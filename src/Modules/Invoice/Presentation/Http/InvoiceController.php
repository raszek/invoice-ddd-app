<?php

namespace Modules\Invoice\Presentation\Http;

use Modules\Invoice\Application\Action\GetInvoice;
use Symfony\Component\HttpFoundation\Response;

class InvoiceController
{

    public function view(GetInvoice $getInvoice, string $id): Response
    {
        $response = $getInvoice->execute($id);

        return response()->json($response->toArray());
    }

}
