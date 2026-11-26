<?php

namespace App\Policies;

use App\Models\ContactMessage;
use App\Models\User;

class ContactMessagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ViewAny:ContactMessage');
    }

    public function view(User $user, ContactMessage $contactMessage): bool
    {
        return $user->can('View:ContactMessage');
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, ContactMessage $contactMessage): bool
    {
        return $user->can('Update:ContactMessage');
    }

    public function delete(User $user, ContactMessage $contactMessage): bool
    {
        return $user->can('Delete:ContactMessage');
    }
}
