<?php

namespace App\Http\Controllers\LmsSetting;

use App\Http\Controllers\Controller;
use App\Traits\RequestTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class LmsSettingController extends Controller
{
    use RequestTrait;
    protected $end_point = '/lms-settings';

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
                    return view('lms-settings.partials.action', ['setting' => $row])->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('lms-settings.index');
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('lms-settings.create');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     * @throws \Throwable
     */
    public function edit($id)
    {
        $response = $this->getHTTP($this->end_point.'/'.$id.'/edit');
        return view('lms-settings.edit', ['response' => $response]);
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $response = $this->postHttp($this->end_point, $request->only('email', 'password', 'name', 'first_name', 'last_name', 'organization_name', 'job_title'));

        // if validations fails
        if ($response instanceof RedirectResponse){
            return $response;
        }

        return view('lms-settings.index');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     * @throws \Throwable
     */
    public function destroy($id)
    {
        $this->deleteHTTP($this->end_point.'/'.$id);
        return view('lms-settings.index');
    }
}
