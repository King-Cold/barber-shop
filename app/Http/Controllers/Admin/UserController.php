<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'desc');

        // Whitelist sort fields
        if (!in_array($sortField, ['id', 'name', 'email', 'role_id'])) {
            $sortField = 'id';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $users = User::with('role')
            ->where(function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage)
            ->appends($request->all());

        return view('admin.users.index', compact('users', 'search', 'perPage', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id' => 'required|integer|in:1,2,3,4',
            'photo' => 'nullable|image|max:2048',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('images/users'), $photoName);
            $photoPath = 'images/users/' . $photoName;
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'photo' => $photoPath,
        ]);

        // Sync Barbers and Clients
        if ($user->role_id == 3) {
            \App\Models\Client::where('user_id', $user->id)->update(['user_id' => null]);
            $barber = \App\Models\Barber::where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();
            if ($barber) {
                $barber->update([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo,
                ]);
            } else {
                $barber = \App\Models\Barber::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'specialty' => 'General',
                    'photo' => $user->photo,
                ]);
            }
            
            return redirect()->route('admin.barbers.edit', $barber->id)->with('swal', [
                'title' => 'Barbero Creado',
                'text' => 'El perfil se enlazó automáticamente. Completa su información aquí.',
                'icon' => 'success',
            ]);
        } elseif ($user->role_id == 4) {
            \App\Models\Barber::where('user_id', $user->id)->update(['user_id' => null]);
            $client = \App\Models\Client::where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();
            if ($client) {
                $client->update([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo,
                ]);
            } else {
                $client = \App\Models\Client::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo,
                ]);
            }

            return redirect()->route('admin.clients.edit', $client->id)->with('swal', [
                'title' => 'Cliente Creado',
                'text' => 'El perfil se enlazó automáticamente. Completa su información aquí.',
                'icon' => 'success',
            ]);
        } else {
            \App\Models\Barber::where('user_id', $user->id)->update(['user_id' => null]);
            \App\Models\Client::where('user_id', $user->id)->update(['user_id' => null]);
        }

        return redirect()->route('admin.users.index')->with('swal', [
            'title' => 'Usuario Creado',
            'text' => 'El nuevo usuario ha sido registrado.',
            'icon' => 'success',
        ]);
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role_id' => 'required|integer|in:1,2,3,4',
            'photo' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $photoPath = $user->photo;
        if ($request->hasFile('photo')) {
            if ($user->photo && File::exists(public_path($user->photo))) {
                File::delete(public_path($user->photo));
            }
            $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('images/users'), $photoName);
            $photoPath = 'images/users/' . $photoName;
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
            'photo' => $photoPath,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        // Sync Barbers and Clients
        if ($user->role_id == 3) {
            \App\Models\Client::where('user_id', $user->id)->update(['user_id' => null]);
            $barber = \App\Models\Barber::where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();
            if ($barber) {
                $barber->update([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo,
                ]);
            } else {
                $barber = \App\Models\Barber::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'specialty' => 'General',
                    'photo' => $user->photo,
                ]);
            }
        } elseif ($user->role_id == 4) {
            \App\Models\Barber::where('user_id', $user->id)->update(['user_id' => null]);
            $client = \App\Models\Client::where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();
            if ($client) {
                $client->update([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo,
                ]);
            } else {
                $client = \App\Models\Client::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo,
                ]);
            }
        } else {
            \App\Models\Barber::where('user_id', $user->id)->update(['user_id' => null]);
            \App\Models\Client::where('user_id', $user->id)->update(['user_id' => null]);
        }

        return redirect()->route('admin.users.index')->with('swal', [
            'title' => 'Usuario Actualizado',
            'text' => 'Los datos se actualizaron correctamente.',
            'icon' => 'success',
        ]);
    }

    public function destroy(User $user)
    {
        // Security Rules
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('swal', [
                'title' => 'Acción denegada',
                'text' => 'No puedes eliminar tu propia cuenta.',
                'icon' => 'error',
            ]);
        }

        if ($user->id === 1) {
            return redirect()->route('admin.users.index')->with('swal', [
                'title' => 'Acción denegada',
                'text' => 'La cuenta del Super Administrador Principal (ID 1) está protegida.',
                'icon' => 'error',
            ]);
        }

        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            return redirect()->route('admin.users.index')->with('swal', [
                'title' => 'Acceso Denegado',
                'text' => 'Solo un Super Administrador puede gestionar a otros Super Administradores.',
                'icon' => 'error',
            ]);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('swal', [
            'title' => '¡Eliminado!',
            'text' => "El usuario ha sido eliminado correctamente.",
            'icon' => 'success',
        ]);
    }
}
