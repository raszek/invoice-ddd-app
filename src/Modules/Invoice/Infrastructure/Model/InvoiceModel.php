<?php

namespace Modules\Invoice\Infrastructure\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $customer_name
 * @property string $customer_email
 * @property string $status
 */
class InvoiceModel extends Model
{

    protected $table = 'invoices';

    protected $keyType = 'string';


    public $incrementing = false;


    public function products(): HasMany
    {
        return $this->hasMany(ProductModel::class, 'invoice_id');
    }
}
