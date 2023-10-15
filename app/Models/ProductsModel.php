<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductsModel extends Model
{
    use SoftDeletes;
    protected $table = 'products';
    protected $fillable = [
        'name',
        'description',
        'price',
        'unit',
        'category_id',
        'qty',
    ];
}
