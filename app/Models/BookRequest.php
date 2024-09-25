<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
    use HasFactory;

    protected $table = 'book_requests'; 

    protected $fillable = [
        'requested_by',
        'requested_from',
        'book_id',
        'quantity',
        'status',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(Admin::class, 'requested_by');
    }

    public function requestedFrom()
    {
        return $this->belongsTo(Admin::class, 'requested_from');
    }
    
    public function challan()
    {
        return $this->hasOne(Challan::class, 'book_request_id');
    }
    public function requested_By()          //challan
{
    return $this->belongsTo(Admin::class, 'requested_by'); // assuming requested_by is the admin's ID
}
}
