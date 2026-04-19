<?php
require_once 'connection.php';

// Check if user is logged in
$logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';
$user_email = $_SESSION['user_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripZone | Complete Travel Management System with CRUD</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ==================== NAVIGATION BAR ==================== -->
<nav class="navbar">
    <div class="container nav-container">
        <div class="logo">TripZone<span>.</span></div>
        <div class="nav-links" id="navLinks">
            <a href="#home" class="nav-link">Home</a>
            <a href="#goal" class="nav-link">Goal</a>
            <a href="#packages" class="nav-link">Packages</a>
            <a href="#" id="myBookingsNav" class="nav-link" style="display: <?php echo $logged_in ? 'inline-block' : 'none'; ?>;">My Bookings</a>
            <a href="#gallery" class="nav-link">Gallery</a>
            <a href="#contacts" class="nav-link">Contacts</a>
            <a href="chatbot.php" class="chat-btn"><i class="fas fa-comment-dots"></i> AI Chatbot</a>
            
            <?php if($logged_in): ?>
                <div class="user-info" id="userInfo">
                    <span class="user-name"><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($user_name); ?></span>
                    <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            <?php else: ?>
                <div class="auth-buttons" id="authButtons">
                    <a href="register.php" class="register-btn"><i class="fas fa-user-plus"></i> Register</a>
                    <a href="login.php" class="login-btn"><i class="fas fa-key"></i> Login</a>
                </div>
            <?php endif; ?>
        </div>
        <div class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </div>
    </div>
</nav>

<!-- ==================== HOME SECTION ==================== -->
<section id="home" class="hero">
    <div class="container hero-container">
        <div class="hero-text">
            <div class="hero-badge"><i class="fas fa-globe-asia"></i> Explore Bangladesh</div>
            <h1>Discover Your Next <span class="gradient-text">Adventure</span></h1>
            <p>Experience the beauty of Bangladesh's most stunning destinations — from longest sea beaches to lush green valleys, luxury cruises, and unforgettable memories.</p>
            <button class="btn-primary" id="exploreHeroBtn">Explore Packages <i class="fas fa-arrow-right"></i></button>
        </div>
        <div class="hero-image">
            <img src="assets/images/saint-martin-island-drone-photography_203617-3.avif" alt="Saint Martin Island" onerror="this.src='https://placehold.co/600x400/2A9D8F/white?text=TripZone'">
            <div class="floating-card"><i class="fas fa-ship"></i> Luxury Cruise</div>
        </div>
    </div>
</section>

<!-- ==================== GOAL SECTION ==================== -->
<section id="goal" class="section">
    <div class="container">
        <h2 class="section-title">Our Mission & Vision</h2>
        <p class="section-sub">Curating authentic travel experiences across Bangladesh</p>
        <div class="goal-grid">
            <div class="goal-card">
                <i class="fas fa-newspaper"></i>
                <h3>Great Press Release</h3>
                <p>Global recognition for local wonders and sustainable tourism initiatives</p>
            </div>
            <div class="goal-card">
                <i class="fas fa-feather-alt"></i>
                <h3>Unique Content</h3>
                <p>Storytelling that inspires wanderlust and cultural connection</p>
            </div>
            <div class="goal-card">
                <i class="fas fa-chart-simple"></i>
                <h3>Project Goal</h3>
                <p>Sustainable tourism, local empowerment & impactful content creation</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== PACKAGES SECTION ==================== -->
<section id="packages" class="section packages-section">
    <div class="container">
        <h2 class="section-title">✨ Tour Packages</h2>
        <p class="section-sub">Choose the perfect escape for your soul</p>
        <div class="packages-grid">
            <!-- Package 1 - Cox's Bazar -->
            <div class="package-card" data-pkg="Cox's Bazar" data-price="7500">
                <div class="package-img" style="background-image: url('assets/images/coxbazar.jpg');"></div>
                <div class="package-content">
                    <h3>Cox's Bazar Tour</h3>
                    <div class="package-duration"><i class="far fa-calendar-alt"></i> 2 days | 1 Night</div>
                    <div class="package-price"><i class="fas fa-taka"></i> 7,500 BDT <span class="per-person">/ per person</span></div>
                    <ul class="package-features">
                        <li><i class="fas fa-hotel"></i> Hotel Accommodation</li>
                        <li><i class="fas fa-car"></i> Transfer facility</li>
                        <li><i class="fas fa-map-marked-alt"></i> Tour guide</li>
                        <li><i class="fas fa-mug-hot"></i> Breakfast included</li>
                    </ul>
                    <button class="book-btn">Book Now <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>
            <!-- Package 2 - Sajek Valley -->
            <div class="package-card" data-pkg="Sajek Valley" data-price="7500">
                <div class="package-img" style="background-image: url('assets/images/sajek.jpg');"></div>
                <div class="package-content">
                    <h3>Sajek Valley Tour</h3>
                    <div class="package-duration"><i class="far fa-calendar-alt"></i> 2 days | 1 Night</div>
                    <div class="package-price"><i class="fas fa-taka"></i> 7,500 BDT <span class="per-person">/ per person</span></div>
                    <ul class="package-features">
                        <li><i class="fas fa-building"></i> Resort stay</li>
                        <li><i class="fas fa-bus"></i> Transport</li>
                        <li><i class="fas fa-user-friends"></i> Local guide</li>
                        <li><i class="fas fa-camera"></i> Sightseeing</li>
                    </ul>
                    <button class="book-btn">Book Now <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>
            <!-- Package 3 - Saint Martin -->
            <div class="package-card" data-pkg="Saint Martin" data-price="10000">
                <div class="package-img" style="background-image: url('assets/images/Saint_Martins.jpg');"></div>
                <div class="package-content">
                    <h3>Saint Martin Tour</h3>
                    <div class="package-duration"><i class="far fa-calendar-alt"></i> 4 days | 3 Night</div>
                    <div class="package-price"><i class="fas fa-taka"></i> 10,000 BDT <span class="per-person">/ per person</span></div>
                    <ul class="package-features">
                        <li><i class="fas fa-hotel"></i> Hotel Stay</li>
                        <li><i class="fas fa-ship"></i> Cruise transfer</li>
                        <li><i class="fas fa-chalkboard-user"></i> Expert guide</li>
                        <li><i class="fas fa-cocktail"></i> Cocktail tour</li>
                    </ul>
                    <button class="book-btn">Book Now <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== MY BOOKINGS SECTION ==================== -->

<section id="my-bookings" class="section" style="display: none;">
    <div class="container">
        <h2 class="section-title">📋 My Bookings</h2>
        <p class="section-sub">Your confirmed tour packages</p>
        <div id="bookingsList" class="bookings-container">
            <div class="no-bookings">
                <i class="fas fa-calendar-alt"></i>
                <p>No bookings yet. Book your first package!</p>
                <button onclick="document.getElementById('packages').scrollIntoView({behavior:'smooth'})" class="btn-primary" style="margin-top:15px;">Explore Packages</button>
            </div>
        </div>
    </div>
</section>

<!-- ==================== GALLERY SECTION ==================== -->
<section id="gallery" class="section">
    <div class="container">
        <h2 class="section-title">Moments of Paradise</h2>
        <p class="section-sub">Click any image to open in full view</p>
        <div class="swiper gallery-swiper">
            <div class="swiper-wrapper" id="gallerySwiperWrapper"></div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</section>

<!-- ==================== CONTACTS SECTION ==================== -->
<section id="contacts" class="section contact-section">
    <div class="container">
        <h2 class="section-title">Get In Touch</h2>
        <div class="contact-wrapper">
            <div class="contact-info">
                <h3><i class="fas fa-map-marker-alt"></i> Route Office</h3>
                <div class="info-detail">
                    <p><i class="fas fa-building"></i> Kalabagan, Dhaka, Bangladesh</p>
                    <p><i class="fas fa-envelope"></i> info@tripzone.com</p>
                    <p><i class="fas fa-phone-alt"></i> +8801947303196</p>
                    <p><i class="far fa-clock"></i> Saturday - Friday : 9am - 6pm</p>
                </div>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="contact-form">
                <h3>Send us a message</h3>
                <form id="messageForm">
                    <div class="form-group"><input type="text" id="fullName" placeholder="Your full name" required></div>
                    <div class="form-group"><input type="email" id="emailAddr" placeholder="Your email address" required></div>
                    <div class="form-group"><input type="text" id="subjectMsg" placeholder="Subject" required></div>
                    <div class="form-group"><textarea rows="3" id="msgContent" placeholder="Your message here..." required></textarea></div>
                    <button type="submit" class="send-btn"><i class="fas fa-paper-plane"></i> Send Message</button>
                    <div id="formFeedback" class="form-feedback"></div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ==================== FOOTER ==================== -->
<!-- ==================== FOOTER ==================== -->
<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-logo">TripZone<span>.</span></div>
            <p>© 2026 TripZone — All rights reserved. Explore Bangladesh with TripZone.</p>
            <div class="footer-features">
                <span><i class="fas fa-ship"></i> Luxury Cruise</span>
                <span><i class="fas fa-umbrella-beach"></i> Beach Resorts</span>
                <span><i class="fas fa-swimmer"></i> Snorkeling Adventure</span>
            </div>
            
            <!-- Team Members Section -->
            <div class="developer-info">
                <div class="dev-divider">
                    <span><i class="fas fa-users"></i> Development Team</span>
                </div>
                <div class="team-grid">
                    <!-- Member 1: Md Ebrahim Hossain Nahid -->
                    <div class="team-card" data-member="ebrahim">
                        <div class="team-avatar"><i class="fas fa-user-astronaut"></i></div>
                        <div class="team-details">
                            <h4>Md Ebrahim Hossain Nahid</h4>
                            <p><i class="fas fa-id-card"></i> ID: 222-15-6400</p>
                            <p><i class="fas fa-graduation-cap"></i> Dept: CSE</p>
                            <p><i class="fas fa-university"></i> Daffodil International University</p>
                        </div>
                        <div class="team-click-icon"><i class="fas fa-info-circle"></i> Click for details</div>
                    </div>
                    
                    <!-- Member 2: Md Ismail Hossain Nahin -->
                    <div class="team-card" data-member="nahin">
                        <div class="team-avatar"><i class="fas fa-user-astronaut"></i></div>
                        <div class="team-details">
                            <h4>Md Ismail Hossain Nahin</h4>
                            <p><i class="fas fa-id-card"></i> ID: 222-15-6401</p>
                            <p><i class="fas fa-graduation-cap"></i> Dept: CSE</p>
                            <p><i class="fas fa-university"></i> Daffodil International University</p>
                        </div>
                        <div class="team-click-icon"><i class="fas fa-info-circle"></i> Click for details</div>
                    </div>
                    
                    <!-- Member 3: Kazi Tanvir Ahmed Shakib -->
                    <div class="team-card" data-member="shakib">
                        <div class="team-avatar"><i class="fas fa-user-astronaut"></i></div>
                        <div class="team-details">
                            <h4>Kazi Tanvir Ahmed Shakib</h4>
                            <p><i class="fas fa-id-card"></i> ID: 222-15-6519</p>
                            <p><i class="fas fa-graduation-cap"></i> Dept: CSE</p>
                            <p><i class="fas fa-university"></i> Daffodil International University</p>
                        </div>
                        <div class="team-click-icon"><i class="fas fa-info-circle"></i> Click for details</div>
                    </div>
                    
                    <!-- Member 4: Md Naim Ahmmed -->
                    <div class="team-card" data-member="naim">
                        <div class="team-avatar"><i class="fas fa-user-astronaut"></i></div>
                        <div class="team-details">
                            <h4>Md Naim Ahmmed</h4>
                            <p><i class="fas fa-id-card"></i> ID: 222-15-6542</p>
                            <p><i class="fas fa-graduation-cap"></i> Dept: CSE</p>
                            <p><i class="fas fa-university"></i> Daffodil International University</p>
                        </div>
                        <div class="team-click-icon"><i class="fas fa-info-circle"></i> Click for details</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
	
	<div class="footer-links" style="margin-top: 15px;">
    <a href="admin/admin_login.php" style="color: #CFDFDA; text-decoration: none; font-size: 0.8rem;">
        <i class="fas fa-shield-alt"></i> Admin Panel
    </a>
</div>
	
	
	
	
	
	
	
</footer>

<!-- ==================== MODALS ==================== -->

<!-- Lightbox Modal -->
<div id="lightbox" class="lightbox" style="display:none">
    <span class="close-lightbox">&times;</span>
    <img id="lightboxImg" src="">
</div>

<!-- Developer Modal -->
<div id="developerModal" class="dev-modal-overlay" style="display:none">
    <div class="dev-modal-container">
        <div class="dev-modal-header"><h2><i class="fas fa-user-secret"></i> Developer Information</h2><span class="dev-modal-close">&times;</span></div>
        <div class="dev-modal-body">
            <div class="dev-profile-icon"><i class="fas fa-laptop-code"></i></div>
            <div class="dev-info-grid">
                <div class="dev-info-item"><label><i class="fas fa-user"></i> Full Name</label><p>Md Ismail Hossain Nahin</p></div>
                <div class="dev-info-item"><label><i class="fas fa-id-badge"></i> Student ID</label><p>222-15-6401</p></div>
                <div class="dev-info-item"><label><i class="fas fa-graduation-cap"></i> Department</label><p>Computer Science and Engineering (CSE)</p></div>
                <div class="dev-info-item"><label><i class="fas fa-university"></i> University</label><p>Daffodil International University (DIU)</p></div>
                <div class="dev-info-item"><label><i class="fas fa-code"></i> Course</label><p>Software Project 1 (CSE226)</p></div>
                <div class="dev-info-item"><label><i class="fas fa-calendar-alt"></i> Project Year</label><p>2025-2026</p></div>
            </div>
            <div class="dev-signature"><p>"Developing innovative solutions for better travel experiences"</p><div class="dev-signature-line"></div><span>Md Ismail Hossain Nahin</span></div>
        </div>
        <div class="dev-modal-footer"><button class="dev-close-btn">Close</button></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="script.js"></script>

<script>
// Pass PHP session data to JavaScript
const loggedIn = <?php echo $logged_in ? 'true' : 'false'; ?>;
const userName = '<?php echo addslashes($user_name); ?>';
const userEmail = '<?php echo addslashes($user_email); ?>';
</script>
</body>
</html>