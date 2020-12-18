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
                    <a class="btn-sm-app modal-preview" href="#" data-target="#users-import"
                       data-href="{{route('admin.users.bulk-import.modal')}}">
                        <i class="fas fa-file-import"></i> Import
                    </a>
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
               <div class="card-header">
                   <div class="row">
                       <div class="col-md-9">
                           <h3 class="card-title">USERS</h3>
                       </div>
                       <div id="date_range" class="col-md-3">
                           <i class="fa fa-calendar"></i>
                           <span></span> <i class="fa fa-caret-down"></i>
                       </div>
                   </div>
               </div>
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
                                        <th>Date Registered</th>
                                        <th>Email</th>
                                        <th>Organization Name</th>
                                        <th>Organization Type</th>
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
    @include('layouts.base-modal', ['modal' => ['id' => 'users-import', 'class' => 'modal-md', 'title' => 'Users Import']])
@stop

@section('js')
    <script type="text/javascript">
        $(function () {
            // initialize datatable
            initializeDataTable();
            // intiialize date range field
            initializeDateRange('#date_range');
        });

        var table = {};
        var start_date = '';
        var end_date = '';

        function initializeDataTable() {
            table = $('.table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                searchDelay: 800,
                deferRender: true,
                "ajax": {
                    url: "{{ route('admin.users.index') }}",
                    data: {
                        start_date: start_date,
                        end_date: end_date,
                    },
                },
                columns: [
                    {data: 'first_name', name: 'first_name'},
                    {data: 'last_name', name: 'last_name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'email', name: 'email'},
                    {data: 'organization_name', name: 'organization_name'},
                    {data: 'organization_type', name: 'organization_type'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "order": [[2, "desc"]]
            });
        }

        // call back for date_range
        function cb(start, end) {
            start_date = start.format('YYYY-MM-DD');
            end_date   = end.format('YYYY-MM-DD');
            $('#date_range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            table.destroy();
            initializeDataTable();
        }

        // destroy the user
        function destroy_data(id) {
            if (confirm('Are you sure?')) {
                let url = api_url + api_v + "/admin/users/" + id;
                destroy(url, id); // url and id parameter for fading the element
            }
        }

        // update the user role
        function update_role(ele) {
            resetAjaxParams();
            let role = $(ele).data('role');
            let id = $(ele).data('id');
            callParams.Url = api_url + api_v + "/admin/users/" + id + "/roles/" + role;
            ajaxCall(callParams, {}, function (result) {
                $(ele).data('role', 1 - role);
                $(ele).toggleText('Make Admin', 'Remove Admin');
            });
        }
    </script>
@endsection
