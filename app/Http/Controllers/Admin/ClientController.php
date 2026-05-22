<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

class ClientController extends Controller
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
        if (!in_array($sortField, ['id', 'name', 'email', 'phone'])) {
            $sortField = 'id';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $clients = Client::where('name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orWhere('phone', 'like', '%' . $search . '%')
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage)
            ->appends($request->all());

        return view('admin.clients.index', compact('clients', 'search', 'perPage', 'sortField', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('clients', 'email')],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|max:2048',
        ]);

        $client = DB::transaction(function () use ($request, $validated) {
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
                $request->file('photo')->move(public_path('images/clients'), $photoName);
                $photoPath = 'images/clients/' . $photoName;
            }

            // Create User (Auth entity)
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make('password'), // Default password
                'photo' => $photoPath,
            ]);

            // Assign Role (Cliente) - Assume role ID 3 is Client, or find by name
            $role = Role::firstOrCreate(['name' => 'Cliente']);
            $user->role_id = $role->id;
            $user->save();

            // Create Client (Profile entity)
            return Client::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'photo' => $photoPath,
            ]);
        });

        return redirect()->route('admin.clients.edit', $client->id)->with('swal', [
            'title' => 'Cliente Registrado',
            'text' => 'El nuevo cliente ha sido dado de alta. Completa su información aquí.',
            'icon' => 'success',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('clients', 'email')->ignore($client->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request, $validated, $client) {
            $photoPath = $client->photo;
            if ($request->hasFile('photo')) {
                if ($client->photo && File::exists(public_path($client->photo))) {
                    File::delete(public_path($client->photo));
                }
                
                $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
                $request->file('photo')->move(public_path('images/clients'), $photoName);
                $photoPath = 'images/clients/' . $photoName;
            }

            // Update Client profile
            $client->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'photo' => $photoPath,
            ]);

            // Update underlying User if exists
            if ($client->user_id) {
                User::where('id', $client->user_id)->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'photo' => $photoPath,
                ]);
            }
        });

        return redirect()->route('admin.clients.index')->with('swal', [
            'title' => 'Cliente Actualizado',
            'text' => 'Los datos del cliente se guardaron con éxito.',
            'icon' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        DB::transaction(function () use ($client) {
            if ($client->user_id) {
                User::where('id', $client->user_id)->delete();
            }
            $client->delete();
        });

        return redirect()->route('admin.clients.index')->with('swal', [
            'title' => 'Cliente Eliminado',
            'text' => 'El registro ha sido eliminado exitosamente.',
            'icon' => 'success',
        ]);
    }
}
