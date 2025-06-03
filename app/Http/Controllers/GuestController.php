<?php
namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $query = Guest::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('tel')) {
            $query->where('tel', 'like', '%' . $request->tel . '%');
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $guests = $query->latest()->paginate(10)->withQueryString();

        return view('guests.index', compact('guests'));
    }

    public function create()
    {
        return view('guests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'nullable|email',
            'tel' => 'nullable|string',
            'gender' => 'nullable|in:Male,Female',
        ]);

        Guest::create([
            'guest_code' => Str::uuid(),
            'name' => $request->name,
            'email' => $request->email,
            'tel' => $request->tel,
            'gender' => $request->gender,
            'created_by' => 1,
        ]);

        return redirect()->route('guests.index')->with('success', 'Guest added successfully.');
    }

    public function show(Guest $guest)
    {
        return view('guests.show', compact('guest'));
    }

    public function edit(Guest $guest)
    {
        return view('guests.edit', compact('guest'));
    }

    public function update(Request $request, Guest $guest)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'nullable|email',
            'tel' => 'nullable|string',
            'gender' => 'nullable|in:Male,Female',
        ]);

        $guest->update([
            'name' => $request->name,
            'email' => $request->email,
            'tel' => $request->tel,
            'gender' => $request->gender,
            'modified_by' => 1,
        ]);

        return redirect()->route('guests.index')->with('success', 'Guest updated successfully.');
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();
        return redirect()->route('guests.index')->with('success', 'Guest deleted.');
    }
}
