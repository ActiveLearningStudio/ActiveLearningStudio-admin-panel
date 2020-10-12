<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Traits\RequestTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class OrganizationTypesController extends Controller
{
    use RequestTrait;
    protected $end_point = '/organization-types';

    /**
     * View lists existing organization types
     * @return View
     * @throws \Throwable
     */
    public function index()
    {
        $orgTypes = $this->getHTTP($this->end_point);
        return view('organization-types.index', ['orgTypes'=>$orgTypes['data']]);
    }

    /**
     * View provides interface to create a new organization type
     * @return View
     */
    public function create()
    {
        return view('organization-types.create');
    }

    /**
     * View provides an interface to edit an existing organization type
     * @param int $id
     * @return View
     * @throws \Throwable
     */
    public function edit($id)
    {
        $orgType = $this->getHTTP($this->end_point.'/'.$id);
        return view('organization-types.create', ['orgType' => $orgType]);
    }

    /**
     * Saves organization type information from create and edit
     * @return Redirect
     * @throws \Throwable
     */
    public function save(Request $req)
    {
        if($req->filled('type_id')){
            $result = $this->putHTTP(
                $this->end_point.'/'.$req->type_id, 
                [
                    'name'=>$req->name,
                    'label'=>$req->label,
                ]
            );
        } else {
            $result = $this->postHTTP(
                $this->end_point, 
                [
                    'name'=>$req->name,
                    'label'=>$req->label,
                ]
            );
        }
        return redirect('admin/organization-types');
    }

    /**
     * Deletes existing organization type
     * @return Redirect
     * @throws \Throwable
     */
    public function delete($id) {
        $result = $this->deleteHTTP($this->end_point.'/'.$id);
        return redirect('admin/organization-types');
    }

    /**
     * Changes the display order for organization types.
     * It can move a single type up or down
     * @param int $type_id The id of the org type to move
     * @param string $direction Move up or down
     * @return Redirect
     * @throws \Throwable
     */
    public function change_order($type_id, $direction){
        $orgType = $this->getHTTP($this->end_point.'/'.$type_id);
        $result = $this->putHTTP(
            $this->end_point.'/'.$orgType['id'], 
            [
                'name'=>$orgType['name'],
                'label'=>$orgType['label'],
                'order'=> $direction == 'up' ? intval($orgType['order']) - 1 : intval($orgType['order']) + 1 ,
            ]
        );
        return redirect('admin/organization-types');
    }
}
