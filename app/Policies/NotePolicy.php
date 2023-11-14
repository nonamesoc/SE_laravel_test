<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;

class NotePolicy
{

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function index(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Note $note
     *
     * @return bool
     */
    public function update(User $user, Note $note): bool
    {
        return $user->id === $note->user_id;
    }

    /**
     * Perform pre-authorization checks.
     *
     * @param \App\Models\User $user
     * @param string $ability
     *
     * @return bool|null
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }

        return null;
    }

}
