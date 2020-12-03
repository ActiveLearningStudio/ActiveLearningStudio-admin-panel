@foreach($activityType['activityItems'] as $item)
    <p class="mt-0 mb-0">
        <a href="{{route('admin.activity-items.edit', $item['id'])}}" target="_blank">{{$item['title']}}</a>
    </p>
@endforeach
