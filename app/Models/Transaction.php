<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'isbn',
        'issn',
        'title',
        'categories',
        'bookID',
        'borrowerID',
        'borrower',
        'borrowerAddress',
        'borrowerContactNumber',
        'borrowerCourseAndYear',
        'borrowerDepartment',
        'librarian',
        'dateBorrowed',
        'dueDate',
        'bookPrice',
        'status',
        'purpose',
        'dateReturned',
        'remarks',
    ];
}
