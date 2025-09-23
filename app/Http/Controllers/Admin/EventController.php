<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Event::query();

        // Filtros de búsqueda
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('year', 'like', "%{$search}%");
            });
        }

        // Filtro por año
        if ($request->filled('year')) {
            $query->where('year', $request->get('year'));
        }

        // Filtro por ciudad
        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->get('city')}%");
        }

        // Ordenar por año descendente, luego por nombre
        $events = $query->withCount('forms')
                       ->orderBy('year', 'desc')
                       ->orderBy('name', 'asc')
                       ->paginate(15);

        // Obtener años únicos para el filtro
        $years = Event::select('year')
                     ->distinct()
                     ->orderBy('year', 'desc')
                     ->pluck('year');

        // Obtener ciudades únicas para el filtro
        $cities = Event::select('city')
                      ->distinct()
                      ->orderBy('city', 'asc')
                      ->pluck('city');

        return view('admin.events.index', compact('events', 'years', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 10),
        ], [
            'name.required' => 'El nombre del evento es obligatorio.',
            'name.max' => 'El nombre del evento no puede tener más de 255 caracteres.',
            'city.required' => 'La ciudad es obligatoria.',
            'city.max' => 'La ciudad no puede tener más de 255 caracteres.',
            'year.required' => 'El año es obligatorio.',
            'year.integer' => 'El año debe ser un número entero.',
            'year.min' => 'El año debe ser mayor o igual a 2000.',
            'year.max' => 'El año no puede ser mayor a ' . (date('Y') + 10) . '.',
        ]);

        Event::create($validated);

        return redirect()->route('admin.events.index')
                        ->with('success', 'Evento creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): View
    {
        $event->load(['forms' => function ($query) {
            $query->withCount('formSubmissions');
        }, 'cycles']);
        
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 10),
        ], [
            'name.required' => 'El nombre del evento es obligatorio.',
            'name.max' => 'El nombre del evento no puede tener más de 255 caracteres.',
            'city.required' => 'La ciudad es obligatoria.',
            'city.max' => 'La ciudad no puede tener más de 255 caracteres.',
            'year.required' => 'El año es obligatorio.',
            'year.integer' => 'El año debe ser un número entero.',
            'year.min' => 'El año debe ser mayor o igual a 2000.',
            'year.max' => 'El año no puede ser mayor a ' . (date('Y') + 10) . '.',
        ]);

        $event->update($validated);

        return redirect()->route('admin.events.index')
                        ->with('success', 'Evento actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): RedirectResponse
    {
        // Verificar si el evento tiene formularios asociados
        if ($event->forms()->count() > 0) {
            return redirect()->route('admin.events.index')
                            ->with('error', 'No se puede eliminar el evento porque tiene formularios asociados. Elimina primero los formularios.');
        }

        // Verificar si el evento tiene ciclos asociados
        if ($event->cycles()->count() > 0) {
            return redirect()->route('admin.events.index')
                            ->with('error', 'No se puede eliminar el evento porque tiene ciclos asociados. Elimina primero los ciclos.');
        }

        $event->delete();

        return redirect()->route('admin.events.index')
                        ->with('success', 'Evento eliminado exitosamente.');
    }
}
