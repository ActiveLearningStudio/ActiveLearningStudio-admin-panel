@extends('adminlte::page')

@section('title', 'Activity Type')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Activity Type</h1>
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
                    <h3 class="card-title">Edit Activity Type Form</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                {{ Aire::open()->id('activity-types-form')->class('form-horizontal')->put()->bind($response['data'])
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
                </div>

                <!-- /.card-body -->
                <div class="card-footer">
                    {{Aire::submit('Update Activity Type')->addClass('btn btn-info')}}
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
        $(".cancel").on("click", function (e) {
            e.preventDefault();
            window.location.href = $(this).data('redirect');
        });

        // form submit event prevent
        $("#activity-types-form").on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: api_url + api_v + "/admin/activity-types/" + {{$response['data']['id']}},
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
