@extends('adminlte::page')

@section('title', 'LMS Settings')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">LMS Settings</h1>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    <a class="btn-sm-app" href="{{route('admin.lms-settings.create')}}">
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
                                        <th>URL</th>
                                        <th>Type</th>
                                        <th>User</th>
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
                searchDelay: 800,
                pageLength: 25,
                deferRender: true,
                ajax: "{{ route('admin.lms-settings.index') }}",
                columns: [
                    {data: 'lms_url', name: 'lms_url'},
                    {data: 'lms_name', name: 'lms_name'},
                    {data: 'user.name', name: 'user.name', searchable: false, orderable: false,},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });

        // destroy the lms setting
        function destroy_data(id) {
            if (confirm('Are you sure?')) {
                let url = api_url + api_v + "/admin/lms-settings/" + id;
                destroy(url, id); // url and id parameter for fading the element
            }
        }
    </script>
@endsection
