<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ViewAny:Article');
    }

    public function view(User $user, Article $article): bool
    {
        return $user->can('View:Article');
    }

    public function create(User $user): bool
    {
        return $user->can('Create:Article');
    }

    public function update(User $user, Article $article): bool
    {
        return $user->can('Update:Article');
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->can('Delete:Article');
    }

    public function restore(User $user, Article $article): bool
    {
        return $user->can('Restore:Article');
    }

    public function forceDelete(User $user, Article $article): bool
    {
        return $user->can('ForceDelete:Article');
    }
}
