document.addEventListener('DOMContentLoaded', function() {
    const commentForms = document.querySelectorAll('.comment-form');

    commentForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const formData = new FormData(this);
            const postId = this.dataset.postId; // Get the post ID from the data attribute

            fetch(`/post/${postId}/comment`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // This header helps to identify AJAX requests
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const commentsList = document.querySelector(`.comments-list[data-post-id="${postId}"]`);

                    // Prepend the new comment to the top of the comments list
                    const newComment = document.createElement('div');
                    newComment.classList.add('single-comment', 'mb-2', 'p-3');
                    newComment.innerHTML = `
                        <div class="d-flex align-items-center">
                            <img class="user2-avatar rounded-circle me-2" src="${data.avatar}" alt="${data.user}'s avatar" />
                            <strong><span>${data.user}:</span></strong>
                        </div>
                        <p class="mb-1 comment-body">${data.comment}</p>
                        <small class="text-muted">${data.created_at}</small>
                    `;
                    commentsList.prepend(newComment);
                    this.reset(); // Reset the form

                    // Update the "Show More Comments" button
                    const showMoreButton = document.querySelector(`.show-more-button[data-post-id="${postId}"]`);
                    showMoreButton.style.display = 'block'; // Show the button
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Load more comments
    const loadMoreButtons = document.querySelectorAll('.show-more-button');

    loadMoreButtons.forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const offset = parseInt(this.dataset.offset, 10); // Start from the next set of comments

            fetch(`/post/${postId}/comment/load-more?offset=${offset}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const commentsList = document.querySelector(`.comments-list[data-post-id="${postId}"]`);

                // Append more comments to the list
                data.comments.forEach(comment => {
                    const newComment = document.createElement('div');
                    newComment.classList.add('single-comment', 'mb-2', 'p-3');
                    newComment.innerHTML = `
                        <div class="d-flex align-items-center">
                            <img class="user2-avatar rounded-circle me-2" src="${comment.user.avatar}" alt="${comment.user.username}'s avatar" />
                            <strong><span>${comment.user.username}:</span></strong>
                        </div>
                        <p class="mb-1 comment-body">${comment.comments}</p>
                        <small class="text-muted">${new Date(comment.created_at).toLocaleString()}</small>
                    `;
                    commentsList.appendChild(newComment); // Add the new comment to the bottom
                });

                // Update the button for loading more comments
                const newOffset = offset + 3; // Increase the offset
                this.dataset.offset = newOffset; // Update the offset in data attribute
                const moreCommentsCount = data.moreCommentsCount;

                if (moreCommentsCount > 0) {
                    this.textContent = `Show More Comments (${moreCommentsCount})`;
                } else {
                    this.style.display = 'none'; // Hide the button if no more comments
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
