<?php
// include 'db.php';
session_start(); // Start the session to track login status

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['email']);

// Handle logout request
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
<?php include 'includes/header.php'; ?>

<header class="hero-section">
    <div class="hero-content">
        <h1>Welcome to <span class="tag">MindEase Counseling</span></h1>
        <p>Always here for you! We provide a safe and inclusive space for you to seek support and work towards a
            healthier mind. Start your journey today with MindEase Coaches</p>
        <?php if ($isLoggedIn): ?>
            <div class="user-info auth-forms">
                <p>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?>!</p>
                <a href="?action=logout" class="cta-button">Logout</a>
            </div>
        <?php else: ?>
            <div class="auth-forms">
                <a href="login.php" class="cta-button">Get in touch</a>

            </div>
        <?php endif; ?>

    </div>
    <div class="hero-image">
        <img src="./assets/testimonials.png" alt="Counseling" />
    </div>
</header>

<footer class="message-bar">
    <p>Offering online mental health services with compassion and understanding to support your well-being.</p>
</footer>

<section class="services-section">
    <h2>Mindease <span class="tag">Counseling</span></h2>
    <h3>Supporting Your Mental Health Journey with Compassion & Understanding</h3>
    <div class="services-cards">
        <div class="service-card">
            <div class="icon">
                <img src="./assets/counseling.png" alt="Professional Counseling Services">
            </div>
            <h4>Professional Counseling Services</h4>
            <p>Seek support from our experienced therapists who are dedicated to your well-being.</p>
            <a href="#" class="card-link">Get Started</a>
        </div>
        <div class="service-card">
            <div class="icon">
                <img src="./assets/coaching.png" alt="Online Therapy Sessions">
            </div>
            <h4>Online Therapy Sessions</h4>
            <p>Access mental health support from the comfort of your own home with our secure online platform.</p>
            <a href="#" class="card-link">Learn More</a>
        </div>
        <div class="service-card">
            <div class="icon">
                <img src="./assets/treatment.png" alt="Personalized Treatment Plans">
            </div>
            <h4>Personalized Treatment Plans</h4>
            <p>Receive customized therapy plans tailored to your individual needs and goals.</p>
            <a href="#" class="card-link">Discover More</a>
        </div>
        <div class="service-card">
            <div class="icon">
                <img src="./assets/support.png" alt="24/7 Support">
            </div>
            <h4>24/7 Support</h4>
            <p>Our team is available round the clock to provide assistance and guidance when you need it most.</p>
            <a href="#" class="card-link">Get Help Now</a>
        </div>
    </div>
</section>

<section class="how-it-works-section">
    <h2>How It Works</h2>
    <div class="how-it-works-steps">
        <div class="step">
            <img src="./assets/step1.png" alt="Step 1">
            <h3>Step 1: Book a Session</h3>
            <p>Choose a date and time that works best for you and book a session online with our easy-to-use scheduling
                tool.</p>
        </div>
        <div class="step">
            <img src="./assets/step2.png" alt="Step 2">
            <h3>Step 2: Meet Your Counselor</h3>
            <p>Meet your professional counselor in a secure and comfortable environment, either in-person or online.</p>
        </div>
        <div class="step">
            <img src="./assets/step3.png" alt="Step 3">
            <h3>Step 3: Start Your Journey</h3>
            <p>Begin your path to better mental health with personalized guidance and support every step of the way.</p>
        </div>
    </div>
</section>

<header class="hero-section2">
    <div class="hero-image">
        <img src="./assets/testimonials.png" alt="Counseling" />
    </div>
    <div class="hero-content" style="text-align: right;">
        <h1>Quality Care, <span class="tag">Anytime, Anywhere</span></h1>
        <p>Experience compassionate and professional support through our convenient online platform.</p>
    </div>
</header>

<section class="testimonial-section">
    <h2>Voice of <span class="tag">Satisfaction</span></h2>
    <h3>Discover How Businesses Thrive with Building Success Solutions</h3>
    <div class="testimonials">
        <div class="testimonial-card">
            <img src="./assets/person1.jpg" alt="Emily Davis">
            <p>Choosing this business solution was a game changer. The results speak for themselves. Highly recommend
                for anyone serious about success.</p>
            <h4>Emily Davis, Marketing Specialist</h4>
        </div>
        <div class="testimonial-card">
            <img src="./assets/person2.jpg" alt="Laura Miller">
            <p>Our experience with Building Success has been outstanding. Their expertise and commitment significantly
                impacted our business growth.</p>
            <h4>Laura Miller, Customer Relations</h4>
        </div>
        <div class="testimonial-card">
            <img src="./assets/person3.jpg" alt="Daniel Clark">
            <p>Impressed with the results of their services. Building Success has a proven track record of delivering
                effective solutions for businesses.</p>
            <h4>Daniel Clark, Product Manager</h4>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>