<?php

namespace Modules\Invoice\Infrastructure\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $name
 * @property int $price
 * @property int $quantity
 * @property string $invoice_id
 */
class ProductModel extends Model
{

    protected $table = 'invoice_product_lines';

    protected $keyType = 'string';

    public $incrementing = false;


}
