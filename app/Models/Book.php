<?php

namespace App\Models;

use App\Models\Category;
use App\Models\BorrowDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'category_id',
        'title',
        'author',
        'publisher',
        'year',
        'isbn',
        'jumlah_halaman',
        'lokasi_buku',
        'stock',
        'image',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowDetails()
    {
        return $this->hasMany(BorrowDetail::class);
    }
}
