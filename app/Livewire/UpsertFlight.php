<?php

namespace App\Livewire;

use App\Models\Flight;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UpsertFlight extends Component
{
    public ?string $flightId = null;

    // Flight Information
    public string $trip_type = 'air';
    public ?string $vehicle_type = null;
    // public ?string $operator_name = null;
    public string $airline = '';
    public ?string $aircraft_type = null;
    public string $departure_airport = '';
    public ?string $departure_terminal = null;
    public string $arrival_airport = '';
    public ?string $arrival_terminal = null;
    public string $departure_city = '';
    public string $arrival_city = '';
    public string $departure_time = '';
    public string $arrival_time = '';
    public ?float $base_price = null;
    public ?int $total_seats = null;
    public array $seat_classes = ['economy'];
    public array $pricing_tiers = [];
    public bool $is_active = true;
    public ?string $notes = null;
    public ?int $branch_id = null;

    public function mount(?string $flightId = null): void
    {
        $this->flightId = $flightId;

        if ($flightId) {
            $flight = Flight::findOrFail($flightId);
            $this->fill([
                'trip_type' => $flight->trip_type ?? 'air',
                'vehicle_type' => $flight->vehicle_type,
                // 'operator_name' => $flight->operator_name,
                'airline' => $flight->airline,
                'aircraft_type' => $flight->aircraft_type,
                'departure_airport' => $flight->departure_airport,
                'departure_terminal' => $flight->departure_terminal,
                'arrival_airport' => $flight->arrival_airport,
                'arrival_terminal' => $flight->arrival_terminal,
                'departure_city' => $flight->departure_city,
                'arrival_city' => $flight->arrival_city,
                'departure_time' => $flight->departure_time->format('Y-m-d\TH:i'),
                'arrival_time' => $flight->arrival_time->format('Y-m-d\TH:i'),
                'base_price' => $flight->base_price,
                'total_seats' => $flight->total_seats,
                'seat_classes' => $flight->seat_classes ?? ['economy'],
                'pricing_tiers' => $flight->pricing_tiers ?? ['economy' => $flight->base_price],
                'is_active' => $flight->is_active,
                'notes' => $flight->notes,
            ]);
            $this->branch_id = $flight->branch_id;
        }
        $user = Auth::user();
        if ($user && $user->isBranchManager()) {
            $this->branch_id = $user->branch_id;
        }
    }

    protected function rules(): array
    {
        $id = $this->flightId;

        return [
            'trip_type' => ['required', 'in:air,land,sea'],
            'vehicle_type' => ['nullable', 'string', 'max:100'],
            // 'operator_name' => ['nullable', 'string', 'max:100'],
            'airline' => ['required', 'string', 'max:100'],
            'aircraft_type' => ['nullable', 'string', 'max:50'],
            'departure_airport' => ['required', 'string', 'max:10'],
            'departure_terminal' => ['nullable', 'string', 'max:100'],
            'arrival_airport' => ['required', 'string', 'max:10'],
            'arrival_terminal' => ['nullable', 'string', 'max:100'],
            'departure_city' => ['required', 'string', 'max:100'],
            'arrival_city' => ['required', 'string', 'max:100'],
            'departure_time' => ['required', 'date', 'after:now'],
            'arrival_time' => ['required', 'date', 'after:departure_time'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'total_seats' => ['required', 'integer', 'min:1', 'max:1000'],
            'seat_classes' => ['nullable', 'array'],
            'pricing_tiers' => ['nullable', 'array'],
            'is_active' => ['boolean'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function save()
    {
        $data = $this->validate();

        // Calculate trip duration
        $departure = Carbon::parse($data['departure_time']);
        $arrival = Carbon::parse($data['arrival_time']);
        $durationMinutes = $departure->diffInMinutes($arrival);

        $payload = [
            'trip_type' => $data['trip_type'],
            'vehicle_type' => $data['vehicle_type'],
            // 'operator_name' => $data['operator_name'],
            'airline' => $data['airline'],
            'aircraft_type' => $data['aircraft_type'],
            'departure_airport' => $data['departure_airport'],
            'departure_terminal' => $data['departure_terminal'],
            'arrival_airport' => $data['arrival_airport'],
            'arrival_terminal' => $data['arrival_terminal'],
            'departure_city' => $data['departure_city'],
            'arrival_city' => $data['arrival_city'],
            'departure_time' => $data['departure_time'],
            'arrival_time' => $data['arrival_time'],
            'duration_minutes' => $durationMinutes,
            'base_price' => $data['base_price'],
            'total_seats' => $data['total_seats'],
            'seat_classes' => $data['seat_classes'] ?? ['economy'],
            'pricing_tiers' => $data['pricing_tiers'] ?? ['economy' => $data['base_price']],
            'is_active' => $data['is_active'],
            'notes' => $data['notes'],
        ];

        $user = Auth::user();
        if ($user && $user->isBranchManager()) {
            $payload['branch_id'] = $user->branch_id;
        } else if ($this->branch_id) {
            $payload['branch_id'] = $this->branch_id;
        }

        if ($this->flightId) {
            // Update flight
            $flight = Flight::findOrFail($this->flightId);
            
            // Check that the new seat count is not less than booked seats
            $bookedSeats = $flight->total_seats - $flight->available_seats;
            if ($data['total_seats'] < $bookedSeats) {
                $this->addError('total_seats', t('Cannot reduce seat count below booked seats (:booked).', ['booked' => $bookedSeats]));
                return;
            }
            
            $payload['available_seats'] = $data['total_seats'] - $bookedSeats;
            $flight->update($payload);
        } else {
            // Create new flight
            $payload['available_seats'] = $data['total_seats'];
            Flight::create($payload);
        }

        session()->flash('success', $this->flightId ? t('Flight updated successfully') : t('Flight created successfully'));
        return redirect()->route('dashboard.flights.index');
    }

    public function updatedSeatClasses($value)
    {
        // When seat classes change, ensure pricing_tiers only contains selected classes
        $newTiers = [];
        foreach ($this->seat_classes as $class) {
            // Keep existing price if it exists, otherwise use base price as default
            $newTiers[$class] = $this->pricing_tiers[$class] ?? $this->base_price;
        }
        $this->pricing_tiers = $newTiers;
    }

    public function render()
    {
        return view('livewire.upsert-flight');
    }
}
