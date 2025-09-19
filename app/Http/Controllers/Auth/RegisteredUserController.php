<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\City;
use App\Models\Participant;
use App\Models\User;
use App\Services\ParticipantService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(
        private ParticipantService $participantService
    ) {}

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $cities = City::orderBy('name')->get();
        return view('auth.register', compact('cities'));
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Create or get participant
        $participant = $this->participantService->createOrGetParticipant([
            'first_name' => $validated['name'],
            'last_name' => '', // We'll use the name as first_name for now
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'document_type' => $validated['document_type'],
            'document_number' => $validated['document_number'],
            'city_id' => $validated['city_id'],
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'participant_id' => $participant->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect to city form
        $city = City::find($validated['city_id']);
        return redirect()->route('public.forms.show', [
            'city' => $city->name
        ]);
    }
}