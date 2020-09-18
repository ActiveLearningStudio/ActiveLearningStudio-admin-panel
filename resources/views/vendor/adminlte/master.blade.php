<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        @yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 3'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
    </title>

    {{-- Custom stylesheets (pre AdminLTE) --}}
    @yield('adminlte_css_pre')

    {{-- Base Stylesheets --}}
    @if(!config('adminlte.enabled_laravel_mix'))
        <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

        {{-- Configured Stylesheets --}}
        @include('adminlte::plugins', ['type' => 'css'])

        <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    @else
        <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_mix_css_path', 'css/app.css')) }}">
    @endif
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    {{-- Custom Stylesheets (post AdminLTE) --}}
    @yield('adminlte_css')

    {{-- Favicon --}}
    @if(config('adminlte.use_ico_only'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.png') }}"/>
    @elseif(config('adminlte.use_full_favicon'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}"/>
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicons/android-icon-192x192.png') }}">
        <link rel="manifest" href="{{ asset('favicons/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    @endif

</head>

<body class="@yield('classes_body')" @yield('body_data')>

{{-- Body Content --}}
@yield('body')

{{-- Base Scripts --}}
@if(!config('adminlte.enabled_laravel_mix'))
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

    {{-- Configured Scripts --}}
    @include('adminlte::plugins', ['type' => 'js'])

    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
@else
    <script src="{{ mix(config('adminlte.laravel_mix_js_path', 'js/app.js')) }}"></script>
@endif
<script>
    var api_url = '{{api_url()}}';
    var api_v = '/v1';
    var err_sel = $(".container-fluid:first");
    var success_sel = err_sel;
    var callParams = {};
    var dataParams = {};
    callParams.Type = "Get";
    callParams.DataType = "JSON"; // Return data type e-g Html, Json etc

    // set the header - bearer token
    $.ajaxSetup({
        beforeSend: function(xhr) {
            xhr.setRequestHeader('Authorization', 'Bearer {{session("access_token")}}');
            $('#overlay').fadeIn();
        },complete: function(){
            $('#overlay').fadeOut();
        },
    });

    // generic ajax call
    function ajaxCall(callParams, dataParams, callback) {
        err_sel.find('.alert-danger').remove();
        $.ajax({
            type: callParams.Type,
            url: callParams.Url,
            quietMillis: 100,
            dataType: callParams.DataType,
            data: dataParams,
            cache: true,
            success: function (response) {
                callback(response);
            },
            error: function (response) {
                response = JSON.parse(response.responseText);
                if(response.errors){
                    showErrors(response.errors);
                }else{
                    alert('Something went wrong, try again later!');
                }
            }
        });
    }

    /**
     * Generic function for showing the validation errors
     * @param errors
     */
    function showErrors(errors) {
        var errors_li = '';
        $.each(errors, function (key, val) {
            if (typeof val === 'string') {
                errors_li += '<li>' + val + '</li>';
            } else {
                errors_li += '<li>' + val[0] + '</li>';
            }
        });
        showMessage(errors_li, 'error');
        // err_sel.append('<div class="alert alert-danger"><ul>' + errors_li + '</ul></div>').scrollTop();
        // $(window).scrollTop(success_sel.scrollTop()); // scroll to the message
    }

    /**
     * Generic function for showing the success/error message
     * @param message
     * @param type
     * @param title
     * @param addClass
     * @param icon
     */
    function showMessage(message, type = 'success', title = 'SUCCESS', addClass = 'bg-success', icon = 'fa fa-check') {
        $("#toastsContainerTopRight").remove();
        if (type === 'error'){
            addClass = 'bg-danger';
            title = 'ERROR';
            icon = 'fa fa-exclamation-triangle';
        }
        $(document).Toasts('create', {
            title: title,
            class: addClass,
            icon: icon,
            body: message,
        });
        // success_sel.append('<div class="alert alert-success"><p>' + message + '</p></div>');
        // $(window).scrollTop(success_sel.scrollTop()); // scroll to the message
    }

    /**
     * Reset ajax call and data params
     */
    function resetAjaxParams(type = 'Get'){
        callParams = {};
        dataParams = {};
        callParams.Type = type;
        callParams.DataType = "JSON";
    }

    /**
     * Reset the form data
     * @param target
     */
    function resetForm(target){
        $(target).trigger("reset");
        $(".form-control").removeClass("is-valid");
        $(".valid-feedback").remove();
    }

    // toggle text same like class
    $.fn.extend({
        toggleText: function(a, b){
            return this.text((this.text()).trim() === b ? a : b);
        }
    });

    // pace restart for ajax request
    // $(document).ajaxStart(function () {
    //     Pace.restart();
    // });
</script>
{{-- Custom Scripts --}}
@yield('js')

</body>

</html>
