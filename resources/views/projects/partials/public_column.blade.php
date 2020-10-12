<input type="checkbox" class="project_public"
       onclick="togglePublic(this, {{$project['id']}})"
       {{$project['is_public'] ? 'checked' : ''}} value="{{$project['id']}}">
<span class="is_public">{{$project['is_public'] ? ' Yes': ' No'}}</span>
