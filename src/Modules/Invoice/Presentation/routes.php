<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Invoice\Presentation\Http\InvoiceController;


Route::get('/invoices/{id}', [InvoiceController::class, 'view'])->name('invoices.view');
Route::post('/invoices', [InvoiceController::class, 'create'])->name('invoices.create');
