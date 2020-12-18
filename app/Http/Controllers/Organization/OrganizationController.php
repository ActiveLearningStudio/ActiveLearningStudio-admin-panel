<?php

namespace App\Http\Controllers\Organization;

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

class OrganizationController extends Controller
{
    use RequestTrait;

    protected $end_point = '/organizations';

    /**
     * @param Request $request
     * @return Application|Factory|JsonResponse|View
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $response = $this->getHTTP($this->end_point, $request->all());
            // dd($response);
            return DataTables::custom($response['data'])
                ->setTotalRecords($response['meta']['total'])
                ->editColumn('image', '<img src="{{validate_api_url($image)}}" style="max-width: 75px">')
                ->addColumn('parent', function($organization) {
                    return view('organizations.partials.parent_column', ['organization' => $organization])->render();
                })
                ->addColumn('action', function ($organization) {
                    return view('organizations.partials.action', ['organization' => $organization])->render();
                })
                ->skipPaging() // already paginated response
                ->rawColumns(['action', 'image'])
                ->make(true);
        }
        return view('organizations.index');
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('organizations.create');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     * @throws \Throwable
     */
    public function edit($id)
    {
        $response = $this->getHTTP($this->end_point . '/' . $id);
        return view('organizations.edit', ['response' => $response]);
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