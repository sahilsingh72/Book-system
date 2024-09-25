<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\Admin;
use App\Models\BookStock;
use Illuminate\Http\Request;

use App\Models\Challan;   
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request as HttpRequest; // Alias for the HTTP Request

class BookRequestController extends Controller
{
    public function index(Request $request)
    {   
        $user = Auth::user(); // Get the currently logged-in user
        $role = $user->role;  // Check the role of the logged-in user
        
        $status = $request->query('status', 'pending'); // Default is 'pending'


        if($role === 'okcl') {
            // okcl role: show requests coming from DLC to okcl
            $bookRequests = BookRequest::select('book_requests.*','admins.name','books.title')
            ->leftJoin('admins', 'book_requests.requested_by', '=', 'admins.id')
            ->leftJoin('books', 'book_requests.book_id', '=', 'books.id')
            ->where('book_requests.requested_from', $user->id)
            // ->where('book_requests.status', '=' , 'pending')
            ->where('book_requests.status', $status)
            ->get();
        } 
        elseif($role ==='dlc'){
            // DLC role: show requests coming from ALC to DLC
            $bookRequests = BookRequest::select('book_requests.*', 'admins.name', 'books.title')
            ->leftJoin('admins', 'book_requests.requested_by', '=', 'admins.id')
            ->leftJoin('books', 'book_requests.book_id', '=', 'books.id')
            ->where('book_requests.requested_from', $user->id) // DLC is the recipient
            ->where('book_requests.status',  $status)
            ->get();
        }
        else {
        // If neither role, return an empty collection or handle accordingly
        $bookRequests = collect();
        }
              
        // Pass the $bookRequests variable to the view
        return view('backend.pages.book-requests.index', compact('bookRequests'));
    }

    // Show the form to create a new book request
    public function create() {
        $books = Book::all(); // Get all available books
        return view('backend.pages.book-requests.create', compact('books'));
    }

    // Store a new book request
    public function store(HttpRequest $request) 
    {
        $user = Auth::user();
        $requestfrom = $this->getRequestedFrom();
        try{
            $bookRequest = new BookRequest(); // Use the Request model
            $bookRequest->requested_by = Auth::id(); // ID of the logged-in admin (ALC or DLC)
            $bookRequest->requested_from = $requestfrom; // Logic to find out if requesting from DLC or OKCL
            $bookRequest->book_id = $request->book_id;
            $bookRequest->quantity = $request->quantity;
            $bookRequest->status = 'pending';
            $bookRequest->save();
        
            return redirect()->route('book-requests.view')->with('success', 'Book request created successfully.');
        }
        catch (\Exception $e) {
            Log::error('Failed to save book request: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create book request.');
        }
    }

    private function getRequestedFrom()
    {
        $user = Auth::user(); 

        if ($user->role == 'alc') {
            // If the user is an ALC, request should go to their DLC
            $dlc = Admin::where('id', $user->assign_under)->where('role', 'dlc')->first(); 
            if ($dlc) {
                return $dlc->id;
            } else {
                Log::error("No DLC found for ALC with ID: {$user->id}");
                return null;
            }
        }

        if ($user->role == 'dlc') {
            // If the user is a DLC, request should go to OKCL
            $okcl = Admin::where('role', 'okcl')->first();
            if ($okcl) {
                return $okcl->id;
            } else {
                Log::error("No OKCL admin found for DLC with ID: {$user->id}");
                return null;
            }
        }

        // Log an error for unrecognized roles
        Log::error("Unrecognized role for user ID: {$user->id} with role: {$user->role}");
        return null;
    }

    // Logic to approve or decline a book request
    public function updateStatus($id, $status) {
        $bookRequest = BookRequest::find($id);

        if (!$bookRequest) {
            return redirect()->back()->with('error', 'Book request not found.');
        }

        $bookRequest->status = $status;
        $bookRequest->save();

        return redirect()->back()->with('success', 'Book request ' . $status . ' successfully.');
    }

    // Other methods as required (index, show, etc.)
    public function request_view(){
        $user = Auth::id();

        // Fetch book requests where the requested_by or requested_from is the authenticated user
        $bookRequests = BookRequest::select('book_requests.*','admins.name','books.title')
            //where('requested_by', $userId)
            ->leftJoin('admins', 'book_requests.requested_from', '=', 'admins.id')
            ->leftJoin('books', 'book_requests.book_id', '=', 'books.id')
            ->where('book_requests.requested_by', $user)
            ->get();
            //dd($bookRequests);
        
        return view('backend.pages.book-requests.request_book_list', compact('bookRequests'));
    }
    public function updateStatusapprove($id){

        $bookRequests = BookRequest::find($id);

        if (!$bookRequests) {
            return redirect()->back()->with('error', 'Book request not found.');
        }
        $bookRequests->status ='Approved';
        $bookRequests->update();

        // Check if stock already exists for the user (DLC or ALC)
            $existingStock = BookStock::where('user_id', $bookRequests->requested_from)
            ->where('book_id', $bookRequests->book_id)
            ->first();

        if ($existingStock) {
        // If stock exists, update the quantity
        $existingStock->stock_quantity += $bookRequests->quantity;
        $existingStock->isconfirmed = true; // Confirm stock
        $existingStock->stock_date = now(); // Set stock date to current
        $existingStock->book_request_id = $bookRequests->id;
        $existingStock->save();
        } else {
        // If no stock exists, create a new stock entry
        BookStock::create([
        'user_id' => $bookRequests->requested_from,
        'book_id' => $bookRequests->book_id,
        'stock_quantity' => $bookRequests->quantity,
        'isconfirmed' => true, // Mark as confirmed
        'stock_date' => now(),
        'book_request_id' => $bookRequests->id, 
        ]);
        }

        // Update book quantities
        $book = $bookRequests->book;
        $book->quantity -= $bookRequests->quantity;
        $book->save();

        // Generate Challan after approval
            $this->generateChallan($bookRequests);

            return redirect()->back()->with('success', 'Request approved and challan generated successfully.');
        }
    public function updateStatusdecline($id){
        $bookRequests = BookRequest::find($id);
        $bookRequests->status ='Declined';
        $bookRequests->update();
        return redirect()->back()->with('success', 'Request Declined!.');
        }

    public function generateChallan($bookRequests)
    {
        
    
        // Generate a unique challan number
        $challanNumber = 'CHLN-' . strtoupper(uniqid());
    
        // Create a new challan record
        $challan = new Challan();
        $challan->book_request_id = $bookRequests->id;
        $challan->challan_number = $challanNumber;
        $challan->challan_date = now();
        $challan->remarks = 'Challan generated for approved book request.';
        $challan->save();
    
        return redirect()->back()->with('success', 'Challan generated successfully with number: ' . $challanNumber);
    }
    
    public function showAlcChallans()
    {
        // Show challans related to ALC only
        $challans = BookRequest::where('requested_by', 'alc') // Assuming 'alc' user type is stored
            ->where('status', 'Approved')
            ->with('book')
            ->get();

        return view('backend.pages.challans.index', compact('challans'));
    }

    public function showOkclChallans()
    {
        // Show challans related to OKCL only
        $challans = BookRequest::where('requested_by', 'okcl') // Assuming 'okcl' user type is stored
            ->where('status', 'Approved')
            ->with('book')
            ->get();

        return view('backend.pages.challans.index', compact('challans'));
    }   
}
