<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\RequestTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    use RequestTrait;

    protected $end_point = '/users';

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
                ->addColumn('action', function ($row) {
                    return view('users.partials.action', ['user' => $row])->render();
                })
                ->skipPaging() // already paginated response
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('users.index');
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     * @throws \Throwable
     */
    public function edit($id)
    {
        $response = $this->getHTTP($this->end_point . '/' . $id);
        return view('users.edit', ['response' => $response]);
    }

    /**
     * @param $id
     * @return Application|ResponseFactory|Response
     * @throws \Throwable
     */
    public function projectPreviewModal($id)
    {
        $response = $this->getHTTP('/projects/' . $id . '/load-shared');
        $html = view('users.partials.preview-modal', ['project' => $response['data']])->render();
        return response(['html' => $html], 200);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws \Throwable
     */
    public function reportBasic(Request $request){
        if ($request->ajax()) {
            $response = $this->getHTTP($this->end_point.'/report/basic', $request->all());
            return DataTables::custom($response['data'])
                ->setTotalRecords($response['meta']['total'] ?? $response['total'])
                ->make(true);
        }
        return view('users.reports.basic');
    }

    /**
     * @return Application|ResponseFactory|Response
     * @throws \Throwable
     */
    public function bulkImportModal(){
        $html = view('users.partials.bulk-import-modal')->render();
        return response(['html' => $html], 200);
    }
}
