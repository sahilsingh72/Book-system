<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challan extends Model
{
    use HasFactory;

    protected $fillable = ['book_request_id', 'challan_number', 'challan_date', 'remarks'];

    /**
     * Get the book request associated with the challan.
     */
    public function bookRequest()
    {
        return $this->belongsTo(BookRequest::class, 'book_request_id');
    }
}
