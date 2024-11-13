<div class="list-group">
    <div class="list-group">
        @foreach($posts as $post)
        <x-post :post="$post" hideAuthor />
        @endforeach
      </div>
 <!-- Add pagination links here -->
 <div class="pagination mt-4">
    {{ $posts->links() }}
</div>

