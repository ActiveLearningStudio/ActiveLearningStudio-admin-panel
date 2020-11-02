@extends('adminlte::page')

@section('title', 'Add Organization')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Add Organization</h1>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Create Organization Form</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                {{ Aire::open()->class('form-horizontal')->post()->id('organization-form')
                    ->rules([
                        'name' => 'required|max:255',
                        'description' => 'required|max:255',
                        'parent_id' => 'integer',
                        ])
                    }}
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::input('name', 'Name')->id('name')->addClass('form-control')->required() }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::input('description', 'Description')->id('description')->addClass('form-control')->required() }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::select([], 'parent_id', 'Parent')->id('parent_id')->addClass('form-control') }}
                        </div>
                    </div>
                </div>

                <!-- /.card-body -->
                <div class="card-footer">
                    {{Aire::submit('Create Organization')->addClass('btn btn-info')}}
                    {{Aire::submit('Cancel')->addClass('btn btn-default float-right cancel')->data('redirect', route('admin.organizations.index'))}}
                </div>
                <!-- /.card-footer -->
                {{ Aire::close() }}
            </div>
        </div>
    </div>
@stop
@section('js')
    <script type="text/javascript">
        // form submit
        let url = api_url + api_v + "/admin/organizations";
        initializeSelect2("#parent_id", url, ["name"]);
        multiPartFormSubmission("#organization-form", url, function (response){
            $("select").val(null);
            $("#parent_id").empty().trigger('change')
        });
    </script>
@endsection
