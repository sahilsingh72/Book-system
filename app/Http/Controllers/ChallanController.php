<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Challan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ChallanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user(); // Get the currently logged-in DLC user
        $filter = $request->query('filter');

        // Fetch the challans based on the selected filter
        if ($filter === 'alc') {
            // Fetch challans requested from ALC
            $challans = Challan::whereHas('bookRequest', function($query) use ($user) {
                $query->where('requested_from', $user->id); // Challans for ALC
            })->get();
        } elseif ($filter === 'okcl') {
            // Fetch challans requested to OKCL
            $challans = Challan::whereHas('bookRequest', function($query) use ($user) {
                $query->where('requested_by', $user->id); // Challans for OKCL
            })->get();
        } else {
            // Default: fetch all challans
            $challans = Challan::whereHas('bookRequest', function($query) use ($user) {
                $query->where('book_requests.requested_by', $user->id)
                  ->orWhere('book_requests.requested_from', $user->id); // challan alc + OKCL
        })->with('bookRequest')->get();
        }

        return view('backend.pages.challans.index', compact('challans'));
    }

    public function generatePdf(){
        $pdf = App::make('dompdf.wrapper');
        // $pdf->loadHTML('<h1>challan</h1>');
        $pdf = Pdf::loadView('backend.pages.challans.challan-pdf')->setPaper('a4', 'potrait');
        // return $pdf->download('invoice.pdf');
        return $pdf->stream('challan' . time() . '.pdf');
    }
 
    public function mail()
        {
            $data["email"] = "sahilsinghgbss1@gmail.com";
            $data["title"] = "From OKCL.org";
            $data["body"] = "This is Demo";
      
            $pdf = PDF::loadView('backend.pages.challans.challan-pdf', $data);
      
            Mail::send('backend.pages.challans.challan-pdf', $data, function($message)use($data, $pdf) {
                $message->to($data["email"])
                        ->subject($data["title"])
                        ->attachData($pdf->output(), "challan.pdf");
            });
      
            return redirect()->route('challans.index');
        }



}
