<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteRequest;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Notes",
 *     description="API Endpoints of Notes"
 * )
 *
 * @OA\Schema(
 *    schema="NoteSchema",
 *        @OA\Property(
 *            property="id",
 *            description="Note identifier",
 *            type="integer",
 *            nullable="false",
 *            example="1"
 *        ),
 *        @OA\Property(
 *            property="text",
 *            description="Note text",
 *            type="string",
 *            nullable="false",
 *            example="Note text"
 *        ),
 *        @OA\Property(
 *            property="user_id",
 *            description="User E-mail",
 *            type="integer",
 *            nullable="false",
 *            example="1"
 *        ),
 *        @OA\Property(
 *            property="updated_at",
 *            description="Note updated date",
 *            type="string",
 *            nullable="false",
 *            example="2023-11-15T05:34:17.000000Z"
 *        ),
 *        @OA\Property(
 *            property="created_at",
 *            description="Note created date",
 *            type="string",
 *            nullable="false",
 *            example="2023-11-15T05:34:17.000000Z"
 *        ),
 *    )
 * )
 */
class NoteController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *      path="/api/notes",
     *      operationId="getNotesList",
     *      tags={"Notes"},
     *      security={{"passport": {"*"}}},
     *      summary="Get list of notes",
     *      description="Returns list of notes",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  ref="#/components/schemas/NoteSchema"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      )
     * )
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
     * @OA\Post(
     *      path="/api/notes",
     *      operationId="storeNote",
     *      tags={"Notes"},
     *      security={{"passport": {"*"}}},
     *      summary="Store new note",
     *      description="Returns note data",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="text",
     *                     format="string",
     *                     type="string",
     *                     description="Note text",
     *                 ),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/NoteSchema"
     *         )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      )
     * )
     *
     * @param \App\Http\Requests\NoteRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
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
     * @OA\Get(
     *      path="/api/notes/{id}",
     *      operationId="getNoteById",
     *      tags={"Notes"},
     *      security={{"passport": {"*"}}},
     *      summary="Get note information",
     *      description="Returns note data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Note id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/NoteSchema"
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     *
     * @param \App\Models\Note $note
     *
     * @return \Illuminate\Http\JsonResponse
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
     * @OA\Put(
     *      path="/api/notes/{id}",
     *      operationId="updateNote",
     *      tags={"Notes"},
     *      security={{"passport": {"*"}}},
     *      summary="Update existing note",
     *      description="Returns updated note data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Note id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="text",
     *                     format="string",
     *                     type="string",
     *                     description="Note text",
     *                 ),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     *
     * @param \App\Http\Requests\NoteRequest $request
     * @param \App\Models\Note $note
     *
     * @return \Illuminate\Http\JsonResponse
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
     * @OA\Delete(
     *      path="/api/notes/{id}",
     *      operationId="deleteNote",
     *      tags={"Notes"},
     *      security={{"passport": {"*"}}},
     *      summary="Delete existing note",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Note id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     *
     * @param \App\Models\Note $note
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Note $note): JsonResponse
    {
        $this->authorize('update', $note);
        $note->delete();
        return response()->json(['message' => 'Note deleted']);
    }

}
