@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <iframe id="curriki-iframe" width="100%" height="600" src="" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script type="text/javascript">
        function loadDeferredIframe() {
            var iframe = document.getElementById("curriki-iframe");
            iframe.src = "http://knowage.currikistudio.org:8081/knowage/public/servlet/AdapterHTTP?ACTION_NAME=EXECUTE_DOCUMENT_ACTION&OBJECT_LABEL=User Statistics&TOOLBAR_VISIBLE=true&ORGANIZATION=DEFAULT_TENANT&NEW_SESSION=true";
        }
        window.onload = loadDeferredIframe;
    </script>
@endsection
