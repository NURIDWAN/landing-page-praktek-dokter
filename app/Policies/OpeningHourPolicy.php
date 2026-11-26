<?php

namespace App\Policies;

use App\Models\OpeningHour;
use App\Models\User;

class OpeningHourPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ViewAny:OpeningHour');
    }

    public function view(User $user, OpeningHour $openingHour): bool
    {
        return $user->can('View:OpeningHour');
    }

    public function create(User $user): bool
    {
        return $user->can('Create:OpeningHour');
    }

    public function update(User $user, OpeningHour $openingHour): bool
    {
        return $user->can('Update:OpeningHour');
    }

    public function delete(User $user, OpeningHour $openingHour): bool
    {
        return $user->can('Delete:OpeningHour');
    }
}
