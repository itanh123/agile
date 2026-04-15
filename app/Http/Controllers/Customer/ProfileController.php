<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('customer.profile.edit');
    }

    public function update(Request $request)
    {
        if ($request->user()->role?->slug === 'admin') {
            return back()->with('error', 'Admin accounts cannot be modified.');
        }

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $request->user()->update(array_merge($data, ['name' => $data['full_name']]));

        return back()->with('success', 'Profile updated.');
    }
}
