@extends('adminlte::page')

@section('title', 'LMS Settings')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Create LMS Setting</h1>
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
                    <h3 class="card-title">Create LMS Setting Form</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                {{ Aire::open()->id('lms-settings-form')->class('form-horizontal')
                    ->rules([
                        'lms_url' => 'required|url',
                        'lms_access_token' => 'required|min:20',
                        'site_name' => 'required',
                        'user_id' => 'required|integer',
                        'lms_access_secret' => 'required_with:lms_access_key',
                        ])
                    }}
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::input('lms_url', 'LMS URL')->id('lms_url')->addClass('form-control')->required() }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::input('lms_access_token', 'LMS Access Token')->id('lms_access_token')->addClass('form-control')->required() }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::input('site_name', 'Site Name')->id('site_name')->addClass('form-control')->required() }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::select(['moodle' => 'Moodle', 'canvas' => 'Canvas'], 'lms_name', 'LMS Name')->id('lms_name')->addClass('form-control') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::input('lms_access_key', 'Access Key')->id('lms_access_key')->addClass('form-control') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::input('lms_access_secret', 'Secret Key')->id('lms_access_secret')->addClass('form-control') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::textarea('description', 'Description')->id('description')->addClass('form-control') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::select([], 'user_id', 'User')->id('user_id')->addClass('form-control')->required() }}
                        </div>
                    </div>

                </div>

                <!-- /.card-body -->
                <div class="card-footer">
                    {{Aire::submit('Create User')->addClass('btn btn-info')}}
                    {{Aire::submit('Cancel')->addClass('btn btn-default float-right cancel')->data('redirect', route('admin.lms-settings.index'))}}
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

        // get users from api
        $("#user_id").select2({
            theme: 'bootstrap4',
            // allowClear: true,  currently not working - need to debug
            minimumInputLength: 0,
            ajax: {
                url: api_url + api_v + "/admin/users",
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
                    var users = data.data;
                    return {
                        results: $.map(users, function (item) {
                            return {
                                text: item.name,
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

        // form submit event prevent
        $("#lms-settings-form").on('submit', function (e) {
            e.preventDefault();
            success_sel.find('.alert-success').remove();
            callParams.Type = "POST";
            callParams.Url = api_url + api_v + "/admin/lms-settings";
            // Set Data parameters
            dataParams = $(this).serialize();
            ajaxCall(callParams, dataParams, function (result) {
                if (result.message) {
                    showMessage(result.message);
                    resetForm("#lms-settings-form");
                }
            });
        });

    </script>
@endsection