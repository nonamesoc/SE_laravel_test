<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteRequest;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        if ($request->user()->can('index', Note::class)) {
            $notes = Note::all();
        } else {
            $notes = Note::where('user_id', Auth::id())->get();
        }

        return response()->json($notes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\NoteRequest $request
     */
    public function store(NoteRequest $request): JsonResponse
    {
        $attributes = $request->all();
        $attributes['user_id'] = Auth::id();
        $note = Note::create($attributes);
        return response()->json($note);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Note $note
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Note $note): JsonResponse
    {
        $this->authorize('update', $note);
        return response()->json($note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\NoteRequest $request
     * @param \App\Models\Note $note
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(NoteRequest $request, Note $note): JsonResponse
    {
        $this->authorize('update', $note);
        $note->update($request->all());
        return response()->json(['message' => 'Note updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Note $note
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Note $note): JsonResponse
    {
        $this->authorize('update', $note);
        $note->delete();
        return response()->json(['message' => 'Note deleted']);
    }
}
