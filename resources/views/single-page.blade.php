<x-layout :doctitle="$post->title">
    <div class="container py-md-5 container--narrow">
        <!-- Post title and action buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">{{$post->title}}</h2>
            @can('update', $post)
            <span>
                <!-- Edit Button -->
                <a href="/post/{{$post->id}}/edit" class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <!-- Delete Button -->
                <form class="d-inline" action="/post/{{$post->id}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </span>
            @endcan
        </div>

        <!-- Post metadata -->
        <p class="text-muted small mb-4">
            <a href="#"><img class="rounded-circle me-2" src="{{$post->user->avatar}}" alt="User avatar" style="width: 40px; height: 40px;"></a>
            Posted by <a href="#" class="fw-bold">{{$post->user->username}}</a> on {{$post->created_at->format('n/j/Y')}}
        </p>

        <!-- Post body -->
        <div class="body-content">
            {!! $post->body !!}
        </div>

        <!-- Display media (image) -->
        @if($post->media)
        <div class="post-media mt-4 mb-4">

            <img src="{{ asset('storage/PostsImage/' . $post->media) }}" alt="Post image" class="img-fluid" style="max-width: 100%; border-radius: 10px;">
        </div>
        @endif

    </div>
</x-layout>
