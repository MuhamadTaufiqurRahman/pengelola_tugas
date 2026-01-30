// Animate progress ring on load
document.addEventListener('DOMContentLoaded', function () {
    const progressRing = document.querySelector('.progress-ring');
    if (progressRing) {
        progressRing.style.transition = 'stroke-dashoffset 1s ease-out';
    }
});
