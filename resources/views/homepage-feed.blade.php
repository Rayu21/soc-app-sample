<x-layout>
    <div class="container-fluid py-md-2">
        <div class="rows"> <!-- Fixed from 'rows' to 'row' -->
            <!-- Sidebar -->
            <div class="col-md-2">
                @include('components.sidebar')
            </div>
            <!-- Main Content -->
            <div class="col-md-8">
                <div class="container py-md-2 container--narrow">
                    @unless ($posts->isEmpty())
                        <h2 class="text-center mb-4">The Latest From Those You Follow</h2>

                        <!-- List of detailed posts -->
                        <div class="feed">
                            @foreach($posts as $post)
                                <div class="post-card mb-4 shadow-sm">
                                    <div class="post-header d-flex justify-content-between align-items-center p-3">
                                        <div class="post-title">
                                            <h5 class="card-title mb-0">{{$post->title}}</h5>
                                        </div>
                                        <div class="post-options">
                                            @can('update', $post)
                                                <!-- Edit Button -->
                                                <a href="/post/{{$post->id}}/edit" class="btn btn-sm text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <!-- Delete Button -->
                                                <form class="d-inline" action="/post/{{$post->id}}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>

                                    <!-- Post metadata -->
                                    <div class="post-meta d-flex align-items-center px-3 pb-2">
                                        <img class="user-avatar rounded-circle me-2" src="{{$post->user->avatar}}" alt="User avatar" style="width: 40px; height: 40px;">
                                        <span class="meta-info">
                                            Posted by <a href="#" class="fw-bold">{{$post->user->username}}</a> on {{$post->created_at->format('n/j/Y')}}
                                        </span>
                                    </div>

                                    <!-- Post body content -->
                                    <div class="post-body px-3">
                                        {!! $post->body !!}
                                    </div>

                                    <!-- Display media (image) -->
                                    @if($post->media)
                                        <div class="post-media mt-4 mb-4">
                                            <img src="{{ asset('storage/PostsImage/' . $post->media) }}" alt="Post image" class="img-fluid" style="max-width: 100%; border-radius: 10px;">
                                        </div>
                                    @endif

                                    <div class="post-footer mt-3 px-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <!-- Like Button -->
                                            <form action="{{ route('posts.like', $post->id) }}" method="POST" data-id="{{ $post->id }}">
                                                @csrf
                                                <button type="button" class="btn btn-like" data-id="{{ $post->id }}">
                                                    <i class="fas fa-thumbs-up"></i> Like <span id="likes-count-{{ $post->id }}">{{ $post->likes->count() }}</span>
                                                </button>
                                            </form>

                                            <!-- Comment Section -->
                                            <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="d-flex w-75 comment-form" data-post-id="{{ $post->id }}">
                                                @csrf
                                                <input type="text" name="comments" placeholder="Add a comment..." required class="form-control comment-input">
                                                <button type="submit" class="btn btn-comment">Comment</button>
                                            </form>
                                        </div>

                                        <!-- Display Comments -->
                                        <div class="comments-list mt-3" data-post-id="{{ $post->id }}">
                                            @foreach($post->comments->take(3) as $comment) <!-- Show only first 3 comments -->
                                                <div class="single-comment mb-2 p-3 bg-gray-50 rounded-md border border-gray-200">
                                                    <div class="flex items-center mb-2">
                                                        <img class="user2-avatar rounded-full h-8 w-8 mr-2" src="{{ $comment->user->avatar }}" alt="User Avatar" />
                                                        <strong class="text-gray-800 text-sm">{{ $comment->user->username }}</strong>
                                                    </div>
                                                    <div class="flex">
                                                        <p class="mb-2 text-gray-600 text-sm">{{ $comment->comments }}</p>
                                                        <small class="text-gray-400 ms-auto">{{ $comment->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    <div class="flex space-x-1 mt-1">
                                                        <button class="like-button" data-comment-id="{{ $comment->id }}">Like</button>
                                                        <button class="comment-button" data-comment-id="{{ $comment->id }}">Reply</button>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @if ($post->comments()->count() > 1)
                                                <button class="show-more-button" data-post-id="{{ $post->id }}" data-offset="2">
                                                    Show More Comments ({{ $post->comments()->count() - 1 }})
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- If no posts to show -->
                        <div class="text-center">
                            <h2>Hello <strong>{{auth()->user()->username}}</strong>, your feed is empty.</h2>
                            <p class="lead text-muted">Your feed displays the latest posts from the people you follow. If you don’t have any friends to follow, that’s okay; you can use the “Search” feature in the top menu bar to find content written by people with similar interests and then follow them.</p>
                        </div>
                    @endunless
                </div>
            </div>
        </div>
    </div>
</x-layout>
