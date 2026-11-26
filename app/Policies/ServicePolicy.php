<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ViewAny:Service');
    }

    public function view(User $user, Service $service): bool
    {
        return $user->can('View:Service');
    }

    public function create(User $user): bool
    {
        return $user->can('Create:Service');
    }

    public function update(User $user, Service $service): bool
    {
        return $user->can('Update:Service');
    }

    public function delete(User $user, Service $service): bool
    {
        return $user->can('Delete:Service');
    }
}
