<?php

namespace Modules\Invoice\Infrastructure\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $customer_name
 * @property string $customer_email
 * @property string $status
 */
class ModelInvoice extends Model
{

    protected $table = 'invoices';

    protected $keyType = 'string';


    public $incrementing = false;


}
