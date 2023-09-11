<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductQtyModel extends Model
{
    protected $table = 'product_qty';
    protected $fillable = [
        'product_id',
        'warehouse',
        'qty',
    ];
}
