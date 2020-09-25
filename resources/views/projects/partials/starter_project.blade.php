<input type="checkbox" class="project_id checkbox d-none"
       {{$project['starter_project'] ? 'checked' : ''}} value="{{$project['id']}}">
<span class="starter_project">{{$project['starter_project']? ' Yes': ' No'}}</span>
