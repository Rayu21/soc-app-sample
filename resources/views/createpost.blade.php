<x-layout>
    <div class="container py-md-5 container--narrow">
        <form action="/new-post" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="form-group mb-4">
            <label for="post-title" class="text-muted mb-1 fontsmall"><small>Title</small></label>
            <input value="{{ old('title') }}" name="title" id="post-title" class="form-control form-control-lg form-control-title" type="text" placeholder="Enter your post title" autocomplete="off" />
            @error('title')
            <p class="m-0 small alert alert-danger shadow-sm">{{ $message }}</p>
            @enderror
          </div>

          <div class="form-group mb-4">
            <label for="post-body" class="text-muted mb-1"><small>Body Content</small></label>
            <textarea name="body" id="post-body" class="body-content tall-textarea form-control" placeholder="Write your content here">{{ old('body') }}</textarea>
            @error('body')
            <p class="m-0 small alert alert-danger shadow-sm">{{ $message }}</p>
            @enderror
          </div>

          <div class="form-group mb-4">
            <label for="media-upload" class="text-muted mb-1"><small>Upload Media</small></label>
            <input type="file" name="media" id="media-upload" class="form-control-file" multiple />
            @error('media')
            <p class="m-0 small alert alert-danger shadow-sm">{{ $message }}</p>
            @enderror
          </div>

          <button class="btn btn-primary btn-lg w-100">Save New Post</button>
        </form>
      </div>
  </x-layout>
