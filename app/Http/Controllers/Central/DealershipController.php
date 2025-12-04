<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central;

use App\Enums\State;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDealershipRequest;
use App\Models\Dealership;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

use function App\Helpers\get_timezones;

final class DealershipController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Central/Dealership/Index');
    }

    public function create(): Response
    {
        return Inertia::render('Central/Dealership/Create', [
            'timezones' => get_timezones(),
            'states' => State::options(),
        ]);
    }

    public function store(StoreDealershipRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $dealership = Dealership::create([
                'name' => $validated['name'],
            ]);

            auth()->user()->dealerships()->attach($dealership);

            foreach ($validated['stores'] as $storeData) {
                $dealership->stores()->create([
                    'name' => $storeData['name'],
                    'address' => $storeData['address'],
                    'city' => $storeData['city'],
                    'state' => $storeData['state'],
                    'zip' => $storeData['zip'],
                    'phone' => $storeData['phone'],
                    'timezone' => $storeData['timezone'],
                ]);
            }
        });

        return redirect()->route('dealerships.index')
            ->with('success', 'Dealership created successfully.');
    }
}
