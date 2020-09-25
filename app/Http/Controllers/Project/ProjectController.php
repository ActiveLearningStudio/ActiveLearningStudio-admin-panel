<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Traits\RequestTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
{
    use RequestTrait;

    protected $end_point = '/projects';

    /**
     * @param Request $request
     * @return Application|Factory|JsonResponse|View
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $response = $this->getHTTP($this->end_point, $request->all());
            return DataTables::of($response['data'])
                ->setTotalRecords($response['meta']['total'])
                ->setFilteredRecords($response['meta']['total'])
                ->editColumn('starter_project',
                    function($project) {
                        return view('projects.partials.starter_project', ['project' => $project])->render();
                    })
                ->rawColumns(['starter_project'])
                ->skipPaging() // already paginated response
                ->make(true);
        }
        return view('projects.index');
    }
}