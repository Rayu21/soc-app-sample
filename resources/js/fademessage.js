export function fadeOutMessages() {
    // Success message fade out
    const successMessage = document.getElementById('success-message');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.transition = "opacity 0.5s ease";
            successMessage.style.opacity = "0";
            setTimeout(() => {
                successMessage.remove();
            }, 500); // Remove after fading out
        }, 3000); // 3 seconds before vanishing
    }

    // Failure message fade out
    const failureMessage = document.getElementById('failure-message');
    if (failureMessage) {
        setTimeout(() => {
            failureMessage.style.transition = "opacity 0.5s ease";
            failureMessage.style.opacity = "0";
            setTimeout(() => {
                failureMessage.remove();
            }, 500); // Remove after fading out
        }, 3000); // 3 seconds before vanishing
    }
}
