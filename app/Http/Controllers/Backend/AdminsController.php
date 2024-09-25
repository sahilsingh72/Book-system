<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;

class AdminsController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(Auth::user(), ['admin.view']);

        return view('backend.pages.admins.index', [
            'admins' => Admin::all(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(Auth::user(), ['admin.create']);

        return view('backend.pages.admins.create', [
            'roles' => Role::all(),
            'admins' => Admin::whereIn('role', ['OKCL', 'DLC'])->get(),
        ]);
    }

    public function store(AdminRequest $request): RedirectResponse
    {
        $this->checkAuthorization(Auth::user(), ['admin.create']);
       //dd(gettype($request->roles),$request->roles[0]);
        $admin = new Admin();
        $admin->name = $request->name;
        $admin->username = $request->username;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->role = $request->roles[0];
        $admin->assign_under = $request->assign_under;
        $admin->save();

        if ($request->roles) {
            $admin->assignRole($request->roles);
        }

        session()->flash('success', __('Admin has been created.'));
        return redirect()->route('admin.admins.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(Auth::user(), ['admin.edit']);

        $admin = Admin::findOrFail($id);
        return view('backend.pages.admins.edit', [
            'admin' => $admin,
            'roles' => Role::all(),
            'admins' => Admin::whereIn('role', ['OKCL', 'DLC'])->get(),

        ]);
    }

    public function update(AdminRequest $request, int $id): RedirectResponse
    {

        //dd($request->all());
        $this->checkAuthorization(Auth::user(), ['admin.edit']);

        $admin = Admin::findOrFail($id);
        $admin->name = $request->name;
        $admin->email = $request->email;
        // $admin->username = $request->username;
        $admin->role = $request->roles[0];
        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }
        // $admin->assign_under = $request->assign_under;
        $admin->save();

        $admin->roles()->detach();
        if ($request->roles) {
            $admin->assignRole($request->roles);
        }

        session()->flash('success', 'Admin has been updated.');
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(Auth::user(), ['admin.delete']);

        $admin = Admin::findOrFail($id);
        $admin->delete();
        session()->flash('success', 'Admin has been deleted.');
        return back();
    }

    public function profile()
    {
        
        $admin = Auth::guard('admin')->user(); // Fetch the currently authenticated admin
        return view('backend.pages.admins.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        // Fetch the authenticated admin
        $admin = Auth::guard('admin')->user();

        if (!($admin instanceof Admin)) {
            return redirect()->back()->with('error', 'User not found');
        }

        // Update the admin's profile information
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->username = $request->username;

        // Update the password only if a new password is provided
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save(); // Save the changes to the database

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully!');
    }

   
}
