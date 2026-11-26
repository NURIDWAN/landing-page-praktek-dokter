<?php

namespace App\Policies;

use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ViewAny:Media');
    }

    public function view(User $user, Media $media): bool
    {
        return $user->can('View:Media');
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Media $media): bool
    {
        return false;
    }

    public function delete(User $user, Media $media): bool
    {
        return $user->can('Delete:Media');
    }
}
