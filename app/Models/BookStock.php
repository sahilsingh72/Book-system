<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookStock extends Model
{
    protected $table = 'book_stock';
    protected $primaryKey = 'book_stock_id';

    protected $fillable = [
        'user_id',
        'book_id',
        'book_request_id',
        'stock_quantity',
        'stock_date',
        // 'isconfirmed',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
    
    public function bookRequests()
    {
        return $this->belongsTo(Book::class, 'book_request_id');
    }
}
