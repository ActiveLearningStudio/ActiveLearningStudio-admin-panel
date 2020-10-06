@extends('adminlte::page')

@section('title', 'Activity Type')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Create Activity Type</h1>
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
                    <h3 class="card-title">Create Activity Type Form</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                {{ Aire::open()->id('activity-types-form')->class('form-horizontal')
                    ->rules([
                        'title' => 'required|max:255',
                        'image' => 'required',
                        'order' => 'required|integer',
                        ])
                    }}
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::input('title', 'Title')->id('title')->addClass('form-control')->required() }}
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
                            {{ Aire::input('order', 'Order')->id('order')->addClass('form-control')->required() }}
                        </div>
                    </div>
                </div>

                <!-- /.card-body -->
                <div class="card-footer">
                    {{Aire::submit('Create Activity Type')->addClass('btn btn-info')}}
                    {{Aire::submit('Cancel')->addClass('btn btn-default float-right cancel')->data('redirect', route('admin.activity-types.index'))}}
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
        let url = api_url + api_v + "/admin/activity-types";
        multiPartFormSubmission("#activity-types-form", url, function (response){
            $("#image-preview").hide();
        });
    </script>
@endsection
