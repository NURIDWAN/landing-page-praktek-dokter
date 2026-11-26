<?php

namespace App\Policies;

use App\Models\ArticleCategory;
use App\Models\User;

class ArticleCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ViewAny:ArticleCategory');
    }

    public function view(User $user, ArticleCategory $category): bool
    {
        return $user->can('View:ArticleCategory');
    }

    public function create(User $user): bool
    {
        return $user->can('Create:ArticleCategory');
    }

    public function update(User $user, ArticleCategory $category): bool
    {
        return $user->can('Update:ArticleCategory');
    }

    public function delete(User $user, ArticleCategory $category): bool
    {
        return $user->can('Delete:ArticleCategory');
    }
}
