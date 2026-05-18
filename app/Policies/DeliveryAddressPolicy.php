<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DeliveryAddress;

class DeliveryAddressPolicy
{
    public function view(User $user, DeliveryAddress $address): bool
    {
        return $user->id === $address->user_id || $user->isAdmin();
    }

    public function update(User $user, DeliveryAddress $address): bool
    {
        return $user->id === $address->user_id || $user->isAdmin();
    }

    public function delete(User $user, DeliveryAddress $address): bool
    {
        return $user->id === $address->user_id || $user->isAdmin();
    }
}
