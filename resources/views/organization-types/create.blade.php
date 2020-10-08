@extends('adminlte::page')

@section('title', 'Organization Type')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                @if(isset($orgType))
                    <h1 class="m-0 text-dark">Edit Organization Type</h1>
                @else
                    <h1 class="m-0 text-dark">Create Organization Type</h1>
                @endif
            </div>
        </div>
    </div>
@stop

@section('content')
<form method="POST" action="{{ url('admin/organization-types/save') }}">
    @csrf
    @if(isset($orgType))
        <input type="hidden" name="type_id" value="{{ isset($orgType) ? $orgType['id'] : '' }}"/>
    @endif
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="card card-info">
                <div class="card-header">
                    @if(isset($orgType))
                        <h3 class="card-title">Edit Organization Type Form</h3>
                    @else
                        <h3 class="card-title">Create Organization Type Form</h3>
                    @endif
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" value="{{ isset($orgType) ? $orgType['name'] : '' }}">
                    </div>
                    <div class="form-group">
                        <label>Label</label>
                        <input type="text" class="form-control" name="label" value="{{ isset($orgType) ? $orgType['label'] : '' }}">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
@stop