<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ViewAny:Announcement');
    }

    public function view(User $user, Announcement $announcement): bool
    {
        return $user->can('View:Announcement');
    }

    public function create(User $user): bool
    {
        return $user->can('Create:Announcement');
    }

    public function update(User $user, Announcement $announcement): bool
    {
        return $user->can('Update:Announcement');
    }

    public function delete(User $user, Announcement $announcement): bool
    {
        return $user->can('Delete:Announcement');
    }
}
