document.addEventListener('DOMContentLoaded', function () {
    const likeButtons = document.querySelectorAll('.btn-like');

    likeButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent default form submission

            const postId = this.dataset.id;
            const formData = new FormData();

            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the likes count
                    const likesCountElement = document.getElementById(`likes-count-${postId}`);
                    likesCountElement.textContent = data.likes_count;

                    // Toggle the active class on the button
                    this.classList.toggle('active');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
