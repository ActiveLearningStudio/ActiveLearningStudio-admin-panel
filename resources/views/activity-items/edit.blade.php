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
                {{ Aire::open()->id('activity-items-form')->class('form-horizontal')->put()->bind($response['data'])
                    ->rules([
                        'title' => 'required|max:255',
                        'description' => 'required|max:255',
                        'demo_activity_id' => 'max:255',
                        'demo_video_id' => 'max:255',
                        'image' => 'required',
                        'order' => 'required|integer',
                        'activity_type_id' => 'required|integer',
                        'type' => 'required',
                        'h5pLib' => 'required',
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
                            {{ Aire::file('image', 'Image')->id('image') }}
                            <p></p>
                            <img id="image-preview" src="{{validate_api_url($response['data']['image'])}}"
                                 alt="Uploaded Image" onerror="this.style.display='none'"
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
                            {{ Aire::input('h5pLib', 'H5P Lib')->id('h5pLib')->required()->addClass('form-control')->placeholder('H5P.InteractiveVideo 1.21') }}
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
                    {{Aire::submit('Update Activity Item')->addClass('btn btn-info')}}
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
        $(".cancel").on("click", function (e) {
            e.preventDefault();
            window.location.href = $(this).data('redirect');
        });

        // get types from api
        $("#activity_type_id").select2({
            theme: 'bootstrap4',
            // allowClear: true,  currently not working - need to debug
            minimumInputLength: 0,
            ajax: {
                url: api_url + api_v + "/admin/activity-types",
                dataType: 'json',
                type: "GET",
                delay: 500,
                data: function (params) {
                    // Query parameters will be ?search=[term]&type=public&limit=100
                    return {
                        q: params.term,
                        type: 'public',
                        page: params.page || 1
                    };
                },
                processResults: function (data) {
                    var types = data.data;
                    return {
                        results: $.map(types, function (item) {
                            return {
                                text: item.title,
                                id: item.id
                            }
                        }),
                        pagination: {
                            more: data.links.next
                        }
                    };
                }
            }
        });

        // set the already selected user option
        var $option = $("<option selected></option>").val('{{$response['data']['activityType']['id']}}').text('{{$response['data']['activityType']['title']}}');
        $('#activity_type_id').append($option).trigger('change');

        // form submit event prevent
        $("#activity-items-form").on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: api_url + api_v + "/admin/activity-items/" + {{$response['data']['id']}},
                method: "POST",
                processData: false, // needed for image upload
                contentType: false, // needed for image upload
                data: new FormData(this),
                dataType: 'json',
                success: function (result) {
                    if (result.message) {
                        showMessage(result.message);
                    }
                },
                error: function (response) {
                    response = JSON.parse(response.responseText);
                    if (response.errors) {
                        showErrors(response.errors);
                    } else {
                        alert('Something went wrong, try again later!');
                    }
                }
            });
        });

        // image preview
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#image-preview').attr('src', e.target.result).show();
                }

                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        // on image upload
        $("#image").change(function () {
            readURL(this);
        });
    </script>
@endsection
