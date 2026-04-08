<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusOperatorRequest;
use App\Http\Requests\UpdateBusOperatorRequest;
use App\Models\BusOperator;
use App\Services\BusOperatorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BusOperatorController extends Controller
{
    public function __construct(
        protected BusOperatorService $operators
    ) {
    }

    public function index(): View
    {
        return view('bus-booking.operators.index', [
            'operators' => $this->operators->paginate(),
        ]);
    }

    public function create(): View
    {
        return view('bus-booking.operators.create');
    }

    public function store(StoreBusOperatorRequest $request): RedirectResponse
    {
        $this->operators->create([
            ...$request->validated(),
            'slug' => $request->validated('slug') ?: Str::slug($request->validated('name')),
        ]);

        return redirect()->route('bus-booking.operators.index');
    }

    public function edit(BusOperator $operator): View
    {
        return view('bus-booking.operators.edit', compact('operator'));
    }

    public function show(BusOperator $operator): View
    {
        return view('bus-booking.operators.show', [
            'operator' => $operator->loadCount(['routes', 'buses']),
        ]);
    }

    public function update(UpdateBusOperatorRequest $request, BusOperator $operator): RedirectResponse
    {
        $this->operators->update($operator, [
            ...$request->validated(),
            'slug' => $request->validated('slug') ?: Str::slug($request->validated('name')),
        ]);

        return redirect()->route('bus-booking.operators.index');
    }

    public function destroy(BusOperator $operator): RedirectResponse
    {
        $this->operators->delete($operator);

        return redirect()->route('bus-booking.operators.index');
    }
}
