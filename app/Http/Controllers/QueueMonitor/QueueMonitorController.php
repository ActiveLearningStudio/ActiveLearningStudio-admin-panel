<?php

namespace App\Http\Controllers\QueueMonitor;

use App\Http\Controllers\Controller;
use App\Traits\RequestTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class QueueMonitorController extends Controller
{
    use RequestTrait;

    protected $end_point = '/queue-monitor';

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
                ->addColumn('status', function ($row){
                    return view('queue-monitor.partials.status_column', ['job' => $row])->render();
                })
                ->addColumn('detail', function ($row){
                    return view('queue-monitor.partials.detail_column', ['job' => $row])->render();
                })
                ->editColumn('job_id', function ($row){
                    return view('queue-monitor.partials.name_column', ['job' => $row])->render();
                })
                ->setTotalRecords($response['meta']['total'])
                ->skipPaging() // already paginated response
                ->rawColumns(['action' , 'status', 'detail', 'job_id'])
                ->make(true);
        }
        return view('queue-monitor.index');
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws \Throwable
     */
    public function jobs(Request $request){
        if ($request->ajax()) {
            $response = $this->getHTTP($this->end_point, $request->all());
            return DataTables::custom($response['data'])
                ->setTotalRecords($response['meta']['total'])
                ->skipPaging() // already paginated response
                ->rawColumns(['action' , 'status', 'detail', 'job_id'])
                ->make(true);
        }
        return view('queue-monitor.jobs');
    }
}
