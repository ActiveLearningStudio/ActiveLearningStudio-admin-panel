<input type="checkbox" id="elastic-project-{{$project['id']}}" class="project_elastic"
onclick="updateIndex(this, {{$project['id']}})"
       {{$project['elasticsearch'] ? 'checked' : ''}} value="{{$project['id']}}">
<span class="elasticsearch">{{$project['elasticsearch'] ? ' Yes': ' No'}}</span>

