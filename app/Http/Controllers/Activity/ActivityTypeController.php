<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Traits\RequestTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ActivityTypeController extends Controller
{
    use RequestTrait;
    protected $end_point = '/activity-types';

    /**
     * @param Request $request
     * @return Application|Factory|JsonResponse|View
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $response = $this->getHTTP($this->end_point, $request->all());
            return DataTables::custom($response['data'])
                ->setTotalRecords($response['meta']['total'])
                ->editColumn('image', '<img src="{{validate_api_url($image)}}" style="max-width: 75px">')
                ->addColumn('action', function ($row) {
                    return view('activity-types.partials.action', ['activityType' => $row])->render();
                })
                ->skipPaging() // already paginated response
                ->rawColumns(['action', 'image'])
                ->make(true);
        }
        return view('activity-types.index');
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('activity-types.create');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     * @throws \Throwable
     */
    public function edit($id)
    {
        $response = $this->getHTTP($this->end_point.'/'.$id);
        return view('activity-types.edit', ['response' => $response]);
    }

}
