<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Book;
use App\Models\BookRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        
        $this->checkAuthorization(Auth::user(), ['dashboard.view']);

        $user = Auth::guard('admin')->user();
        $role = $user->role;

        $totalRequest = 0;
        if ($role === 'okcl') {
            // Count requests sent to OKCL
            $totalRequest = BookRequest::where('requested_from', $user->id)
            ->where('status', 'pending')
            ->count();
        } elseif ($role === 'dlc') {
            // Count requests sent to DLC
            $totalRequest = BookRequest::where('requested_from', $user->id)
            ->where('status', 'pending')
            ->count();
        }

        return view(
            'backend.pages.dashboard.index',
            [
                'total_admins' => Admin::count(),
                'total_roles' => Role::count(),
                'total_permissions' => Permission::count(),
                'total_books' => Book::count(),
                'total_quantity'=> Book::sum('quantity'),
                'total_request'=> $totalRequest,
            ]
        );
    }
 
    
}

