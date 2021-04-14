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
                        'admin_id' => 'required|integer',
                        'image' => 'required',
                        'domain' => 'required|max:255',
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
                            {{ Aire::input('domain', 'Domain')->id('domain')->addClass('form-control')->required() }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::file('image', 'Image')->id('image')->required() }}
                            <p></p>
                            <img id="image-preview" src="" alt="Uploaded Image" onerror="this.style.display='none'"
                                 style="max-width: 150px"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::select([], 'parent_id', 'Parent')->id('parent_id')->addClass('form-control') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::select([], 'admin_id', 'Admin')->id('admin_id')->addClass('form-control')->required() }}
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
        let url = api_url + api_v + "/admin/users";
        initializeSelect2("#admin_id", url, ["email"]);

        url = api_url + api_v + "/admin/organizations";
        initializeSelect2("#parent_id", url, ["name"]);

        multiPartFormSubmission("#organization-form", url, function (response){
            $("#image-preview").hide();
            $("select").val(null);
            $("#parent_id").empty().trigger('change');
            $("#admin_id").empty().trigger('change')
        });
    </script>
@endsection
