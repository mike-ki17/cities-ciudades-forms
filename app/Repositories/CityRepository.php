<?php

namespace App\Repositories;

use App\Models\City;
use Illuminate\Database\Eloquent\Collection;

class CityRepository
{
    /**
     * Find a city by name (case insensitive).
     */
    public function findByNameInsensitive(string $name): ?City
    {
        return City::byNameInsensitive($name)->first();
    }

    /**
     * Get all cities.
     */
    public function getAll(): Collection
    {
        return City::orderBy('name')->get();
    }

    /**
     * Get active cities.
     */
    public function getActive(): Collection
    {
        return City::whereNull('deleted_at')->orderBy('name')->get();
    }

    /**
     * Find a city by ID.
     */
    public function findById(int $id): ?City
    {
        return City::find($id);
    }

    /**
     * Create a new city.
     */
    public function create(array $data): City
    {
        return City::create($data);
    }

    /**
     * Update a city.
     */
    public function update(City $city, array $data): City
    {
        $city->update($data);
        return $city->fresh();
    }

    /**
     * Delete a city (soft delete).
     */
    public function delete(City $city): bool
    {
        return $city->delete();
    }

    /**
     * Search cities by name.
     */
    public function searchByName(string $query): Collection
    {
        return City::where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->get();
    }

    /**
     * Get cities with forms.
     */
    public function getCitiesWithForms(): Collection
    {
        return City::whereHas('forms')
            ->with(['forms' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get cities with active forms.
     */
    public function getCitiesWithActiveForms(): Collection
    {
        return City::whereHas('activeForms')
            ->with('activeForms')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get city statistics.
     */
    public function getCityStatistics(City $city): array
    {
        $totalForms = $city->forms()->count();
        $activeForms = $city->activeForms()->count();
        $totalParticipants = $city->participants()->count();
        $totalSubmissions = $city->forms()
            ->withCount('formSubmissions')
            ->get()
            ->sum('form_submissions_count');

        return [
            'total_forms' => $totalForms,
            'active_forms' => $activeForms,
            'total_participants' => $totalParticipants,
            'total_submissions' => $totalSubmissions,
        ];
    }
}
