@extends('adminlte::page')

@section('title', 'Groups')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Groups</h1>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    <a class="btn-sm-app" href="{{route('admin.groups.create')}}">
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
                           <h3 class="card-title">GROUPS</h3>
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
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Created At</th>
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
            // initialize datatable
            initializeDataTable();
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
                    url: "{{ route('admin.groups.index') }}",
                    data: {
                        // start_date: start_date,
                        // end_date: end_date,
                    },
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'description', name: 'description'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "order": [[2, "desc"]]
            });
        }

        // destroy the group
        function destroy_data(id) {
            if (confirm('Are you sure?')) {
                let url = api_url + api_v + "/admin/groups/" + id;
                destroy(url, id); // url and id parameter for fading the element
            }
        }

    </script>
@endsection
