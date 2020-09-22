<strong>Activity Type:</strong><br>
{{$activityItem['activityType']['title']}}
<br><strong>Item Type:</strong><br>
{{$activityItem['type']}}
@if($activityItem['type'] === "h5p")
    <br><strong>Activity Item Value:</strong><br>
    {{$activityItem['h5pLib']}}

@endif
