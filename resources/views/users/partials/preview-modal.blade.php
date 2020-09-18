<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{$project['name']}}</h3>
                <a href="javascript:void(0)" onclick="updateIndex(this, '{{$project['id']}}')"
                   class="float-right btn-sm btn-primary">{{$project['elasticsearch'] ? 'Remove Index': 'Index'}} </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{validate_api_url($project['thumb_url'])}}" style="max-width: 100%;height: auto">
                    </div>
                    <div class="col-md-8">
                        <p class="card-text">{{$project['description']}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Playlists</h3>
            </div>
            <div class="card-body">
                <div id="accordion">
                    @foreach($project['playlists'] as $playlist)
                        <div class="card card-primary">
                            <div class="card-header" data-toggle="collapse" data-parent="#accordion"
                                 href="#playlist-{{$playlist['id']}}">
                                <h4 class="card-title">
                                    <a href="javascript:void(0)" class="collapsed"
                                       aria-expanded="false">
                                        {{$playlist['title']}}
                                    </a>
                                </h4>
                            </div>
                            <div id="playlist-{{$playlist['id']}}" class="panel-collapse in collapse">
                                <div class="card-body">
                                    <div class="card-deck">
                                        @forelse($playlist['activities'] as $activity)
                                            <div class="card mb-3"
                                                 style="min-width: 15rem;max-width: 15rem;min-height: 150px;">
                                                <a href="{{activity_preview_url($playlist['id'], $activity['id'])}}"
                                                   target="_blank">
                                                    <div class="card-body text-center">
                                                        <img src="{{validate_api_url($activity['thumb_url'])}}"
                                                             style="max-width: 100%">
                                                        <p class="card-text">{{$activity['title']}}</p>
                                                    </div>
                                                </a>
                                            </div>
                                        @empty
                                            <p>No Activity Found!</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
