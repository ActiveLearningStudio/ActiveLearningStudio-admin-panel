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
    protected $end_point = '/organization-type';

    public function index()
    {
        $orgTypes = $this->getHTTP($this->end_point);
        return view('organization-types.index', ['orgTypes'=>$orgTypes]);
    }

    public function create()
    {
        return view('organization-types.create');
    }

    public function edit($id)
    {
        $orgType = $this->getHTTP($this->end_point.'/'.$id);
        return view('organization-types.create', ['orgType' => $orgType]);
    }

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

    public function delete($id) {
        $result = $this->deleteHTTP($this->end_point.'/'.$id);
        return redirect('admin/organization-types');
    }

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
