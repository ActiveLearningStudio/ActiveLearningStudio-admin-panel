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

class ActivityItemController extends Controller
{
    use RequestTrait;

    protected $end_point = '/activity-items';

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
                ->editColumn('image', '<img src="{{validate_api_url($image)}}" style="max-width: 75px">')
                ->addColumn('action', function ($row) {
                    return view('activity-items.partials.action', ['activityItem' => $row])->render();
                })->addColumn('meta', function ($row) {
                    return view('activity-items.partials.meta', ['activityItem' => $row])->render();
                })
                ->rawColumns(['action', 'image', 'meta'])
                ->skipPaging() // already paginated response
                ->filter(function ($instance) {
                    return true;
                }) // to set the global search false as it involves relationship searching
                ->make(true);
        }
        return view('activity-items.index');
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('activity-items.create');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     * @throws \Throwable
     */
    public function edit($id)
    {
        $response = $this->getHTTP($this->end_point . '/' . $id);
        return view('activity-items.edit', ['response' => $response]);
    }

}
