<?php

namespace App\Observers;

use App\Models\Note;
use App\Models\User;
use App\Notifications\NoteCreated;
use Illuminate\Support\Facades\Notification;

class NoteObserver
{

    /**
     * Handle the Note "created" event.
     *
     * @param \App\Models\Note $note
     */
    public function created(Note $note): void
    {
        $admins = User::where('role', 'admin')->get();
        $delay = now()->addMinutes(2);
        Notification::send($admins, (new NoteCreated($note))->delay($delay));
    }

}
