@extends('layouts.master')

@section('content')

@section('breadcrumb')
News / Detail / {{$news->id}}
@endsection

<div class="panel-header panel-header-sm">
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-4">
                            <h4 class="card-title">Detail News {{$news->id}}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                        <div class="alert alert-danger m-2" style="color:white;font-weight:bold">
                            {{ session('error') }}
                        </div>
                        @endif
                        <form>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" id="title" name="title" class="form-control"
                                            placeholder="title" value="{{$news->title}}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label style="color:black">Image</label>
                                        <div class="grid grid-cols-6">
                                            <img id="image_display" class="object-cover"
                                                style="width:10rem;height:10rem;object-fit:cover"
                                                src="{{asset($news->image)}}" alt="image description">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label style="color:black">Description</label>
                                        <div style="text-align: justify">
                                            <div class="form-control" style="text-align: justify" disabled>{!!
                                                $news->description !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>YouTube Link</label>
                                        <div class="grid grid-cols-6">
                                            <div id="video_container">
                                                @if($news->link_video)
                                                {!! $news->link_video !!}
                                                @else
                                                <img id="video_display" class="object-contain items-center"
                                                    style="width:20rem;height:10rem;object-fit:cover"
                                                    src="{{ asset('assets/img/no-video.png') }}">
                                                @endif
                                            </div>
                                        </div>
                                        <input type="text"
                                            class="form-control mt-3 @error('link_video') is-invalid @enderror"
                                            id="link_video" name="link_video" value="{{$news->link_video}}"
                                            placeholder="Enter YouTube Video Link" disabled>
                                        @error('link_video')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{ route('news.edit', $news->id) }}" class="btn btn-primary text-white"
                                        type="submit"><i class="bi bi-pencil mx-1"></i>Edit</a>
                                    <a href="/news" class="btn btn-info text-white"><i
                                            class="bi bi-arrow-return-left mx-1"></i>Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('jquery')
<script>
    document.getElementById('video').addEventListener('input', function () {
        var youtubeLink = this.value.trim();
        var videoContainer = document.getElementById('video_container');

        if (youtubeLink === '') {
            videoContainer.innerHTML = '<img id="video_display" class="object-contain items-center" style="width:20rem;height:10rem;object-fit:cover" src="{{ asset('assets/img/no-video.png') }}">';
        } else {
            var videoId = extractVideoId(youtubeLink);
            if (videoId) {
                videoContainer.innerHTML = '<iframe id="video_display" width="560" height="315" src="https://www.youtube.com/embed/' + videoId + '" frameborder="0" allowfullscreen></iframe>';
            } else {
                videoContainer.innerHTML = '<p class="text-danger">Invalid YouTube link</p>';
            }
        }
    });

    function extractVideoId(url) {
        var regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        var match = url.match(regExp);
        if (match && match[2].length === 11) {
            return match[2];
        } else {
            return null;
        }
    }
</script>
@endsection