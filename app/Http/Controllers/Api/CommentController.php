<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Comment;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    use CanLoadRelationships;

    /**
     * relations
     *
     * @var array
     */
    private array $relations = ['user', 'company'];

    /**
     * __construct
     */
    public function __construct()
    {
        // authentication
        $this->middleware('auth:sanctum')->except(['index', 'show']);

        // rate limit
        $this->middleware('throttle:api');

        // authorization
        $this->authorizeResource(Comment::class, 'comment');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Company $company)
    {
        $comments = $this->loadRelationships(
            $company->comments()->latest()
        );

        return CommentResource::collection(
            $comments->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Company $company)
    {
        $comment = Comment::create([
            ...$request->validate([
                'comment' => 'required|string|max:255',
                'rating' => 'required|integer|between:1,10',
            ]),
            'company_id' => $company->id,
            'user_id' => $request->user()->id,
        ]);

        return new CommentResource(
            $this->loadRelationships($comment)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company, Comment $comment)
    {
        return new CommentResource(
            $this->loadRelationships($comment)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company, Comment $comment)
    {
        // if (Gate::denies('update-comment', $comment)) {
        //     abort(403, 'You are not authorized to update this event.');
        // }
        // $this->authorize('update-comment', $comment);

        $comment->update(
            $request->validate([
                'comment' => 'required|string|max:255',
                'rating' => 'required|integer|between:1,10',
            ])
        );

        return new CommentResource(
            $this->loadRelationships($comment)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company, Comment $comment)
    {
        // $this->authorize('update-comment', $comment);

        $comment->delete();

        return \response(status: 204);
    }
}
