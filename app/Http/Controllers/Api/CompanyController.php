<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Company;

class CompanyController extends Controller
{
    use CanLoadRelationships;

    /**
     * relations
     *
     * @var array
     */
    private array $relations = ['comments'];

    /**
     * __construct
     */
    public function __construct()
    {
        // rate limit
        $this->middleware('throttle:api');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = $this->loadRelationships(
            Company::query()->withAvgRating()
        );

        $sort = \request()->query('sort');

        if (isset($sort)) {
            $sorts = \array_map('trim', \explode(',', $sort));

            foreach ($sorts as $sort) {
                switch ($sort) {
                    case 'name':
                        $query->orderBy('name', 'desc');
                        break;
                    case 'comments_max_created_at':
                        $query->withLatestComment()
                            ->orderBy('comments_max_created_at', 'desc');
                        break;
                    case 'comments_avg_rating':
                        $query->orderBy('comments_avg_rating', 'desc');
                        break;
                }
            }
        }

        $search = \request()->query('search');

        if (isset($search)) {
            $query->name($search);
        }

        return CompanyResource::collection(
            $query->paginate()
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return new CompanyResource(
            $this->loadRelationships(
                Company::query()->withAvgRating()
            )->find($id)
        );
    }
}
