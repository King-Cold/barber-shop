<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

class BarberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'desc');

        // Whitelist sort fields
        if (!in_array($sortField, ['id', 'name', 'email', 'phone', 'specialty'])) {
            $sortField = 'id';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $barbers = Barber::where('name', 'like', '%' . $search . '%')
            ->orWhere('specialty', 'like', '%' . $search . '%')
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage)
            ->appends($request->all());

        return view('admin.barbers.index', compact('barbers', 'search', 'perPage', 'sortField', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.barbers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => ['required', 'email', Rule::unique('barbers', 'email')],
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|max:2048',
        ]);

        $barber = DB::transaction(function () use ($request, $validated) {
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
                $request->file('photo')->move(public_path('images/barbers'), $photoName);
                $photoPath = 'images/barbers/' . $photoName;
            }

            // Create User (Auth entity)
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make('password'), // Default password
                'photo' => $photoPath,
            ]);

            // Assign Role (Barbero) - Assume role ID 4 is Barber, or find by name
            $role = Role::firstOrCreate(['name' => 'Barbero']);
            $user->role_id = $role->id;
            $user->save();

            // Create Barber (Profile entity)
            return Barber::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'specialty' => $validated['specialty'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'photo' => $photoPath,
            ]);
        });

        return redirect()->route('admin.barbers.edit', $barber->id)->with('swal', [
            'title' => 'Barbero Registrado',
            'text' => 'El nuevo barbero ha sido dado de alta. Completa su información aquí.',
            'icon' => 'success',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barber $barber)
    {
        return view('admin.barbers.edit', compact('barber'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barber $barber)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => ['required', 'email', Rule::unique('barbers', 'email')->ignore($barber->id)],
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request, $validated, $barber) {
            $photoPath = $barber->photo;
            if ($request->hasFile('photo')) {
                if ($barber->photo && File::exists(public_path($barber->photo))) {
                    File::delete(public_path($barber->photo));
                }
                
                $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
                $request->file('photo')->move(public_path('images/barbers'), $photoName);
                $photoPath = 'images/barbers/' . $photoName;
            }

            // Update Barber profile
            $barber->update([
                'name' => $validated['name'],
                'specialty' => $validated['specialty'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'photo' => $photoPath,
            ]);

            // Update underlying User if exists
            if ($barber->user_id) {
                User::where('id', $barber->user_id)->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'photo' => $photoPath,
                ]);
            }
        });

        return redirect()->route('admin.barbers.index')->with('swal', [
            'title' => 'Barbero Actualizado',
            'text' => 'Los datos profesionales se guardaron con éxito.',
            'icon' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barber $barber)
    {
        DB::transaction(function () use ($barber) {
            if ($barber->user_id) {
                User::where('id', $barber->user_id)->delete();
            }
            $barber->delete();
        });

        return redirect()->route('admin.barbers.index')->with('swal', [
            'title' => 'Barbero Eliminado',
            'text' => 'El registro ha sido eliminado exitosamente.',
            'icon' => 'success',
        ]);
    }
}
