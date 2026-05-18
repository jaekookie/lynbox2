<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeliveryAddressRequest;
use App\Models\DeliveryAddress;
use Illuminate\Http\Request;

class DeliveryAddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()->deliveryAddresses;

        return view('addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('addresses.edit');
    }

    public function edit(DeliveryAddress $address)
    {
        $this->authorize('update', $address);
        return view('addresses.edit', compact('address'));
    }

    public function store(StoreDeliveryAddressRequest $request)
    {
        $address = auth()->user()->deliveryAddresses()->create($request->validated());

        if ($request->set_as_default) {
            $address->setAsDefault();
        }

        return redirect()->route('addresses.index')->with('success', 'Adresse ajoutée avec succès.');
    }

    public function update(DeliveryAddress $address, StoreDeliveryAddressRequest $request)
    {
        $this->authorize('update', $address);

        $address->update($request->validated());

        return redirect()->route('addresses.index')->with('success', 'Adresse mise à jour avec succès.');
    }

    public function setDefault(DeliveryAddress $address)
    {
        $this->authorize('update', $address);

        $address->setAsDefault();

        return redirect()->route('addresses.index')->with('success', 'Adresse définie par défaut.');
    }

    public function destroy(DeliveryAddress $address)
    {
        $this->authorize('delete', $address);

        $address->delete();

        return redirect()->route('addresses.index')->with('success', 'Adresse supprimée avec succès.');
    }
}
