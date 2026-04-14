<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\PetBreed;
use App\Models\PetCategory;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index()
    {
        $pets = auth()->user()->pets()->with(['category', 'breed'])->latest()->paginate(10);

        return view('customer.pets.index', compact('pets'));
    }

    public function create()
    {
        $categories = PetCategory::all();
        $breeds = PetBreed::all();

        return view('customer.pets.create', compact('categories', 'breeds'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:pet_categories,id',
            'breed_id' => 'required|exists:pet_breeds,id',
            'gender' => 'required|in:male,female,unknown',
            'color' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'health_status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $data['user_id'] = auth()->id();
        auth()->user()->pets()->create($data);

        return redirect()->route('customer.pets.index')->with('success', 'Pet created.');
    }

    public function show(Pet $pet)
    {
        abort_unless($pet->user_id === auth()->id(), 403);

        return view('customer.pets.show', compact('pet'));
    }

    public function edit(Pet $pet)
    {
        abort_unless($pet->user_id === auth()->id(), 403);
        $categories = PetCategory::all();
        $breeds = PetBreed::all();

        return view('customer.pets.edit', compact('pet', 'categories', 'breeds'));
    }

    public function update(Request $request, Pet $pet)
    {
        abort_unless($pet->user_id === auth()->id(), 403);
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:pet_categories,id',
            'breed_id' => 'required|exists:pet_breeds,id',
            'gender' => 'required|in:male,female,unknown',
            'color' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'health_status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $pet->update($data);

        return redirect()->route('customer.pets.index')->with('success', 'Pet updated.');
    }

    public function destroy(Pet $pet)
    {
        abort_unless($pet->user_id === auth()->id(), 403);
        $pet->delete();

        return back()->with('success', 'Pet deleted.');
    }
}
