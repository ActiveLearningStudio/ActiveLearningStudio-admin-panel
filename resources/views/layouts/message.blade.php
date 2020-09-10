@if(Session::get('message'))
    <div class="alert alert-{{ Session::get('message_type') }}">
        <span>
            {{ Session::get('message') }} </span>
    </div>
@endif


@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif