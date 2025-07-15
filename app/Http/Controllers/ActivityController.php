<?php

namespace App\Http\Controllers;

use App\Models\Activity;
// use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = Activity::where('user_id', auth()->id())->get();
        return view('activities.index', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('activities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
    'date' => 'required|date',
    'description' => 'required|string',
    'photo' => 'nullable|image|max:5120',
]);

$data = $request->only(['date', 'description']);
$data['user_id'] = Auth::id(); // Tambahkan otomatis user yang login


        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('activity_photos', 'public');
        }

        Activity::create($data);

        return redirect()->route('activities.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        return view('activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        return view('activities.edit', compact('activity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string',
            'photo' => 'nullable|image|max:5120',
        ]);

        $data = $request->only(['date', 'description']);

        if ($request->hasFile('photo')) {
            if ($activity->photo) {
                Storage::disk('public')->delete($activity->photo);
            }
            $data['photo'] = $request->file('photo')->store('activity_photos', 'public');
        }

        $activity->update($data);

        return redirect()->route('activities.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        if ($activity->photo) {
            Storage::disk('public')->delete($activity->photo);
        }

        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}
