<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        "isbn",
        "issn",
        "title",
        "publisher",
        "deweyDecimal",
        "description",
        "categories",
        "publishedDate",
        "author",
        "type",
        "price",
        "quantity",
        "status",
        "remarks",
    ];
}
