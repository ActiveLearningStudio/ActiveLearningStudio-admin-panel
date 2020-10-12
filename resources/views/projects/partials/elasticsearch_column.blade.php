<input type="checkbox" class="project_public"
       onclick="updateIndex(this, {{$project['id']}})"
       {{$project['elasticsearch'] ? 'checked' : ''}} value="{{$project['id']}}">
<span class="elasticsearch">{{$project['elasticsearch'] ? ' Yes': ' No'}}</span>

