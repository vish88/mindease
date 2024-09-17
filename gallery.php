<?php include "includes/header.php"; ?>

<section class="media-gallery">
    <a href="#img1">
        <img src="./assets/image1.jpg" alt="Counseling Session">
    </a>
    <a href="#img2">
        <img src="./assets/image2.jpg" alt="Workshop Event">
    </a>
    <a href="#img3">
        <img src="./assets/image3.jpg" alt="Group Discussion">
    </a>
</section>

<div id="lightbox" class="lightbox" onclick="this.style.display='none'">
    <a href="#" class="close-btn">X</a>
    <img id="img1" src="./assets/image1.jpg" alt="Counseling Session">
    <img id="img2" src="./assets/image2.jpg" alt="Workshop Event">
    <img id="img3" src="./assets/image3.jpg" alt="Group Discussion">
</div>


<section class="video-section">
    <h2>What We Offer</h2>
    <iframe width="715" height="402" src="https://www.youtube.com/embed/oxx564hMBUI" title="What Is Mental Health?"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
</section>

<section class="audio-section">
    <h2>Listen what our Experts say!</h2>
    <audio controls>
        <source src="./assets/test.mp3" type="audio/mp3">
        Your browser does not support the audio element.
    </audio>
</section>

<script>
    // Basic JavaScript for handling the lightbox functionality
    const thumbnails = document.querySelectorAll('.media-gallery a');
    const lightbox = document.getElementById('lightbox');
    const lightboxImages = lightbox.querySelectorAll('img');

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function (e) {
            e.preventDefault();
            const imgId = this.href.split('#')[1];
            lightbox.style.display = 'flex';
            lightboxImages.forEach(img => {
                img.style.display = img.id === imgId ? 'block' : 'none';
            });
        });
    });

    lightbox.addEventListener('click', function (e) {
        if (e.target.tagName !== 'IMG') {
            lightbox.style.display = 'none';
        }
    });
</script>

<?php include "includes/footer.php"; ?>