<?php

namespace App\Repositories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

class EventRepository
{
    /**
     * Find an event by name (case insensitive).
     */
    public function findByNameInsensitive(string $name): ?Event
    {
        return Event::byNameInsensitive($name)->first();
    }

    /**
     * Find an event by name, city and year.
     */
    public function findByNameCityYear(string $name, string $city, int $year): ?Event
    {
        return Event::byNameInsensitive($name)
            ->byCityInsensitive($city)
            ->byYear($year)
            ->first();
    }

    /**
     * Get all events.
     */
    public function getAll(): Collection
    {
        return Event::orderBy('name')->orderBy('city')->orderBy('year')->get();
    }

    /**
     * Get events by year.
     */
    public function getByYear(int $year): Collection
    {
        return Event::byYear($year)->orderBy('name')->orderBy('city')->get();
    }

    /**
     * Get events by city.
     */
    public function getByCity(string $city): Collection
    {
        return Event::byCityInsensitive($city)->orderBy('name')->orderBy('year')->get();
    }

    /**
     * Find an event by ID.
     */
    public function findById(int $id): ?Event
    {
        return Event::find($id);
    }

    /**
     * Create a new event.
     */
    public function create(array $data): Event
    {
        return Event::create($data);
    }

    /**
     * Update an event.
     */
    public function update(Event $event, array $data): Event
    {
        $event->update($data);
        return $event->fresh();
    }

    /**
     * Delete an event.
     */
    public function delete(Event $event): bool
    {
        return $event->delete();
    }

    /**
     * Search events by name.
     */
    public function searchByName(string $query): Collection
    {
        return Event::where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->orderBy('city')
            ->orderBy('year')
            ->get();
    }

    /**
     * Search events by city.
     */
    public function searchByCity(string $query): Collection
    {
        return Event::where('city', 'like', "%{$query}%")
            ->orderBy('city')
            ->orderBy('name')
            ->orderBy('year')
            ->get();
    }

    /**
     * Get events with forms.
     */
    public function getEventsWithForms(): Collection
    {
        return Event::whereHas('forms')
            ->with(['forms' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->orderBy('city')
            ->orderBy('year')
            ->get();
    }

    /**
     * Get events with active forms.
     */
    public function getEventsWithActiveForms(): Collection
    {
        return Event::whereHas('activeForms')
            ->with('activeForms')
            ->orderBy('name')
            ->orderBy('city')
            ->orderBy('year')
            ->get();
    }

    /**
     * Get event statistics.
     */
    public function getEventStatistics(Event $event): array
    {
        $totalForms = $event->forms()->count();
        $activeForms = $event->activeForms()->count();
        $totalParticipants = $event->participants()->count();
        $totalSubmissions = $event->forms()
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
