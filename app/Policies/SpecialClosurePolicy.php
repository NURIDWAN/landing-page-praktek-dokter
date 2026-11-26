<?php

namespace App\Policies;

use App\Models\SpecialClosure;
use App\Models\User;

class SpecialClosurePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ViewAny:SpecialClosure');
    }

    public function view(User $user, SpecialClosure $specialClosure): bool
    {
        return $user->can('View:SpecialClosure');
    }

    public function create(User $user): bool
    {
        return $user->can('Create:SpecialClosure');
    }

    public function update(User $user, SpecialClosure $specialClosure): bool
    {
        return $user->can('Update:SpecialClosure');
    }

    public function delete(User $user, SpecialClosure $specialClosure): bool
    {
        return $user->can('Delete:SpecialClosure');
    }
}
