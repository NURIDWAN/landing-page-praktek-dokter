<?php

namespace App\Policies;

use App\Models\Page;
use App\Models\User;

class PagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ViewAny:Page');
    }

    public function view(User $user, Page $page): bool
    {
        return $user->can('View:Page');
    }

    public function create(User $user): bool
    {
        return $user->can('Create:Page');
    }

    public function update(User $user, Page $page): bool
    {
        return $user->can('Update:Page');
    }

    public function delete(User $user, Page $page): bool
    {
        return $user->can('Delete:Page');
    }
}
