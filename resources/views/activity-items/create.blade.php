@extends('adminlte::page')

@section('title', 'Activity Item')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Create Activity Item</h1>
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
                    <h3 class="card-title">Create Activity Item Form</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                {{ Aire::open()->id('activity-items-form')->class('form-horizontal')
                    ->rules([
                        'title' => 'required|max:255',
                        'description' => 'required',
                        'demo_activity_id' => 'max:255',
                        'demo_video_id' => 'max:255',
                        'image' => 'required',
                        'order' => 'required|integer',
                        'activity_type_id' => 'required|integer',
                        'type' => 'required',
                        'h5pLib' => 'required_if:type,h5p',
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
                            {{ Aire::textarea('description', 'Description')->id('description')->addClass('form-control')->required() }}
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
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::select([], 'activity_type_id', 'Activity Type')->id('activity_type_id')->addClass('form-control')->required() }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::select(['h5p' => 'H5P', 'immersive_reader' => 'Immersive Reader'], 'type', 'Category')->id('type')
                                ->addClass('form-control')->required() }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::input('h5pLib', 'H5P Lib')->id('h5pLib')->addClass('form-control')->placeholder('H5P.InteractiveVideo 1.21') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::input('demo_video_id', 'Demo Video ID')->id('demo_video_id')->addClass('form-control') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::input('demo_activity_id', 'Demo Activity ID')->id('demo_activity_id')->addClass('form-control') }}
                        </div>
                    </div>
                </div>

                <!-- /.card-body -->
                <div class="card-footer">
                    {{Aire::submit('Create Activity Item')->addClass('btn btn-info')}}
                    {{Aire::submit('Cancel')->addClass('btn btn-default float-right cancel')->data('redirect', route('admin.activity-items.index'))}}
                </div>
                <!-- /.card-footer -->
                {{ Aire::close() }}
            </div>
        </div>
    </div>
@stop
@section('js')
    <script type="text/javascript">
        // initialize select2
        let url = api_url + api_v + "/admin/activity-types";
        initializeSelect2("#activity_type_id", url);

        // form submit
        url = api_url + api_v + "/admin/activity-items";
        multiPartFormSubmission("#activity-items-form", url, function (response){
            $("#image-preview").hide();
            $("select").val(null);
            $("#activity_type_id").empty().trigger('change')
        });
    </script>
@endsection
