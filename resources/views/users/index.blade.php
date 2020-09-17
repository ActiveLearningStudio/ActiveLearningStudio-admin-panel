@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Users</h1>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
{{--                    <a class="btn-sm-app" href="#">--}}
{{--                        <i class="fas fa-file-import"></i> Import--}}
{{--                    </a>--}}
                    <a class="btn-sm-app" href="{{route('admin.users.create')}}">
                        <i class="fas fa-plus"></i> Add
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
            {{--   <div class="card-header">
                   <h3 class="card-title">USERS</h3>
               </div>--}}
            <!-- /.card-header -->
                <div class="card-body">
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-striped dataTable" role="grid">
                                    <thead>
                                    <tr role="row">
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script type="text/javascript">
        $(function () {
            var table = $('.table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                deferRender: true,
                ajax: "{{ route('admin.users.index') }}",
                columns: [
                    {data: 'first_name', name: 'first_name'},
                    {data: 'last_name', name: 'last_name'},
                    {data: 'email', name: 'email'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "order": [[ 0, "asc" ]]
            });
        });

        // destroy the user
        function destroy_data(id){
            if (confirm('Are you sure?')){
                success_sel.find('.alert-success').remove();
                resetAjaxParams( "DELETE");
                callParams.Url = api_url + api_v + "/admin/users/" + id;
                ajaxCall(callParams, dataParams, function (result) {
                    if (result.message) {
                        showMessage(result.message);
                        $(".action-group").find("[data-id='" + id + "']").closest('tr').fadeOut('slow');
                    }
                });
            }
        }
    </script>
@endsection
