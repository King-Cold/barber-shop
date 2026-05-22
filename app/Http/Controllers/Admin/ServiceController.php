<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'desc');

        // Whitelist sort fields
        if (!in_array($sortField, ['id', 'name', 'price', 'duration'])) {
            $sortField = 'id';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $services = Service::where('name', 'like', '%' . $search . '%')
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage)
            ->appends($request->all());

        return view('admin.services.index', compact('services', 'search', 'perPage', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
        ]);

        Service::create($validated);

        return redirect()->route('admin.services.index')->with('swal', [
            'title' => 'Servicio Creado',
            'text' => 'El nuevo servicio ha sido agregado al catálogo.',
            'icon' => 'success',
        ]);
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
        ]);

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('swal', [
            'title' => 'Servicio Actualizado',
            'text' => 'El servicio se modificó correctamente.',
            'icon' => 'success',
        ]);
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('swal', [
            'title' => 'Servicio Eliminado',
            'text' => 'El servicio se ha eliminado correctamente.',
            'icon' => 'success',
        ]);
    }
}
