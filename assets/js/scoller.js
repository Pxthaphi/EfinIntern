$(document).ready(function() {
    const tagContainer = document.getElementById('tagContainer');
    const tags = tagContainer.querySelectorAll('.tag'); // Assuming tags have a class 'tag'
    let isDown = false;
    let startX;
    let scrollLeft;

    function showOverlay() {
        tagContainer.classList.add('overlay-effect');
        tagContainer.classList.remove('default-overlay');
    }

    function hideOverlay() {
        tagContainer.classList.remove('overlay-effect');
        tagContainer.classList.add('default-overlay');
    }

    function disablePointerEvents() {
        tags.forEach(tag => {
            tag.style.pointerEvents = 'none';
        });
    }

    function enablePointerEvents() {
        tags.forEach(tag => {
            tag.style.pointerEvents = '';
        });
    }

    tagContainer.addEventListener('mousedown', (e) => {
        isDown = true;
        tagContainer.classList.add('active');
        tagContainer.style.cursor = 'grab';
        startX = e.pageX - tagContainer.offsetLeft;
        scrollLeft = tagContainer.scrollLeft;
        showOverlay();
        disablePointerEvents();

        // Disable text selection
        document.body.style.userSelect = 'none';
    });

    tagContainer.addEventListener('mouseleave', () => {
        if (isDown) {
            isDown = false;
            tagContainer.classList.remove('active');
            hideOverlay();
            enablePointerEvents();
            document.body.style.userSelect = ''; // Re-enable text selection
        }
    });

    tagContainer.addEventListener('mouseup', () => {
        if (isDown) {
            isDown = false;
            tagContainer.classList.remove('active');
            tagContainer.style.cursor = 'default';
            hideOverlay();
            enablePointerEvents();
            document.body.style.userSelect = ''; // Re-enable text selection
        }
    });

    tagContainer.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - tagContainer.offsetLeft;
        const walk = (x - startX) * 2; // scroll-fast
        tagContainer.scrollLeft = scrollLeft - walk;
    });

    // Add event listener for wheel event
    tagContainer.addEventListener('wheel', (e) => {
        e.preventDefault();
        const scrollAmount = e.deltaY * 3; // Adjust this value to change the scroll speed
        tagContainer.scrollLeft += scrollAmount;
    });
});