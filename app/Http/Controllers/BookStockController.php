<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\BookStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookStockController extends Controller
{
    // public function index()
    // {
    //     // Get the logged-in user
    //     $user = Auth::user();

    //     // Fetch book stock for the current DLC or ALC user
    //     $bookStocks = BookStock::where('user_id', $user->id)
    //         ->with('book')
    //         ->get();

    //     // Pass the book stocks to the view
    //     return view('backend.pages.book_stock.index', compact('bookStocks'));
    // }

    public function viewStock()
{
    $userId = Auth::id(); // Get the logged-in user's ID
    $role = Auth::user()->role; // Get the user's role
    if($role=='okcl'){
        $role ='dlc';
        $user=Admin::where('role', $role)->get(['id','name']);

    }elseif($role=='dlc'){
        $role ='alc';
        $user=Admin::where('role', $role)->where('assign_under', Auth::id())->get(['id','name']);

    }
    //dd($user);
    $book_data=[];
    foreach($user as $u){
        // dd($u);
        $id=23;
        $book_list = BookStock::where('user_id',23)->get();
        dd($book_list);
        $u->totalbook = $book_list->count();
        $u->totalsum = $book_list->sum('stock_quantity');
        $book_data[] =$u;
    }
     dd($book_data,12);
    // if ($role === 'okcl') {
    //     // OKCL can see all DLC stocks
    //     $stocks = BookStock::with('book', 'admin')
    //     ->get();
    // } elseif ($role === 'dlc') {
    //     // DLC can see their own stock and ALC stocks under them
    //     $stocks = BookStock::with('book')
    //         ->where('user_id', $userId)
    //         ->orWhereIn('user_id', function($query) use ($userId) {
    //             $query->select('id')->from('admins')->where('assign_under', $userId); // ALCs under the DLC
    //         })
    //         ->get();
    // } elseif ($role === 'alc') {
    //     // ALC can see only their own stock
    //     $stocks = BookStock::with('book')->where('user_id', $userId)->get();
    // } else {
    //     $stocks = collect(); // Return an empty collection for other roles
    // }

    return view('backend.pages.book_stock.index', compact('book_data'));
}
}
