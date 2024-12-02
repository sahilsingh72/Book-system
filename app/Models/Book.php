<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Request;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'title', 
        'author', 
        'isbn', 
        'published_date', 
        'quantity', 
        'description'
    ];  
    public function requests()
    {
        return $this->hasMany(BookRequest::class);
    }
    public function bookRequests()
    {
        return $this->hasMany(BookRequest::class);
    }
    protected $table = 'books';

    
}
