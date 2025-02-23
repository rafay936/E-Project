<?php
// Connection function using PDO
function getConnection() {
    $host = 'localhost';
    $db   = 'vms_db';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
         PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
         PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
         return new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
         die("Connection failed: " . $e->getMessage());
    }
}

$conn = getConnection();

// Fetch Total Vaccinations Done
$stmt = $conn->query("SELECT COUNT(*) AS total_vaccinations FROM vaccination_records");
$totalVaccinations = $stmt->fetch()['total_vaccinations'];

// Fetch Total Medical Centers
$stmt = $conn->query("SELECT COUNT(*) AS total_centers FROM hospitals");
$totalCenters = $stmt->fetch()['total_centers'];

// Fetch Vaccines Pending (from vaccine_requests where status is 'pending')
$stmt = $conn->query("SELECT COUNT(*) AS vaccines_pending FROM vaccine_requests WHERE status = 'pending'");
$vaccinesPending = $stmt->fetch()['vaccines_pending'];
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaccination Portal</title>
    <link rel="stylesheet" href="web.css">
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <img src="logo.png" alt="Vaccination Logo">
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="schedule.php">Schedule</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>
    <section class="hero">
        <div class="hero-content">
            <h1>Get Vaccinated, Stay Protected</h1>
            <p>Protect yourself and your loved ones by getting vaccinated today. Safe and effective vaccines are now
                available.</p>
            <a href="\admin\r_web\schedule.php"><button class="cta-button">Book Appointment</button></a>
        </div>
        <div class="hero-image">
            <img src="https://img.freepik.com/free-vector/flat-hand-drawn-patient-taking-medical-examination_52683-57829.jpg"
                alt="Vaccination Illustration">
        </div>
    </section>

    <section class="features">
        <h2>Our Services</h2>
        <div class="feature-cards">
            <div class="feature-card">
                <img src="https://cdn-icons-png.flaticon.com/512/4386/4386931.png" alt="Vaccination">
                <h3>Vaccination</h3>
                <p>Safe and effective vaccines for all age groups</p>
            </div>
            <div class="feature-card">
                <img src="https://cdn-icons-png.flaticon.com/512/4386/4386934.png" alt="Consultation">
                <h3>Consultation</h3>
                <p>Expert medical consultation and guidance</p>
            </div>
            <div class="feature-card">
                <img src="https://cdn-icons-png.flaticon.com/512/4386/4386939.png" alt="Certificate">
                <h3>Certificates</h3>
                <p>Digital vaccination certificates</p>
            </div>
        </div>
    </section>

    <section class="vaccination-info">
    <div class="info-content">
        <h2>Why Vaccinate?</h2>
        <ul class="benefits-list">
            <li>Protect against serious diseases</li>
            <li>Build immunity safely</li>
            <li>Prevent spread of diseases</li>
            <li>Save lives and promote health</li>
        </ul>
    </div>
    <div class="stats">
        <div class="stat-card">
            <h3><?php echo htmlspecialchars($totalVaccinations); ?></h3>
            <p>Vaccinations Done</p>
        </div>
        <div class="stat-card">
            <h3><?php echo htmlspecialchars($totalCenters); ?></h3>
            <p>Medical Centers</p>
        </div>
        <div class="stat-card">
            <h3><?php echo htmlspecialchars($vaccinesPending); ?></h3>
            <p>Vaccines Pending</p>
        </div>
    </div>
</section>


    <section class="immunization-tips">
        <div class="tips-container">
            <div class="tips-image">
                <img src="vms-image.jpg" alt="Immunization Tips">
            </div>
            <div class="tips-content">
                <h2>Immunization Tips</h2>
                <div class="tips-grid">
                    <div class="tip-card">
                        <h3>Before Vaccination</h3>
                        <p>Get proper rest and eat well before your appointment</p>
                    </div>
                    <div class="tip-card">
                        <h3>During Vaccination</h3>
                        <p>Wear comfortable clothing with easy access to your arm</p>
                    </div>
                    <div class="tip-card">
                        <h3>After Vaccination</h3>
                        <p>Stay at the center for 15-30 minutes for observation</p>
                    </div>
                    <div class="tip-card">
                        <h3>Side Effects</h3>
                        <p>Mild symptoms are normal and usually resolve in few days</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="testimonials">
        <h2>What Our Patients Say</h2>
        <div class="testimonial-cards">
            <div class="testimonial-card">
                <img src="user.avif" alt="Patient 2">
                <p class="testimonial-text">"I appreciate how they explained everything clearly. Felt very safe
                    throughout."</p>
                <h4>Patient one</h4>
                <p class="testimonial-role">Parent</p>
            </div>
            <div class="testimonial-card">
                <img src="user.avif" alt="Patient 3">
                <p class="testimonial-text">"Excellent service and friendly staff. Would highly recommend to everyone!"
                </p>
                <h4>Patient Two</h4>
                <p class="testimonial-role">Senior Citizen</p>
            </div>
            <div class="testimonial-card">
                <img src="user.avif" alt="Patient 4">
                <p class="testimonial-text">"As a healthcare worker, I'm impressed by their systematic approach to
                    vaccination."</p>
                <h4>Patient three</h4>
                <p class="testimonial-role">Healthcare Professional</p>
            </div>
        </div>
    </section>

    <section class="faq-section">
        <h2>Frequently Asked Questions</h2>
        <div class="faq-container">
            <div class="faq-item">
                <div class="faq-question">
                    <h3>What documents do I need for vaccination?</h3>
                    <span class="toggle-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>Please bring your government-issued ID, previous vaccination records if any, and any relevant
                        medical history documents.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h3>How do I schedule a vaccination appointment?</h3>
                    <span class="toggle-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>You can schedule an appointment through our online booking system, mobile app, or by calling our
                        helpline number.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h3>What is the waiting time after vaccination?</h3>
                    <span class="toggle-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>We require all patients to wait for 15-30 minutes after vaccination for observation of any
                        immediate reactions.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h3>How do I get my vaccination certificate?</h3>
                    <span class="toggle-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>Digital certificates are automatically generated and sent to your registered email after
                        vaccination completion.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section logo-section">
                <img src="logo.png" alt="Footer Logo" class="footer-logo">
                <p>Your trusted partner in vaccination and healthcare services.</p>
                <div class="contact-info">
                    <p>Email: info@vaccineportal.com</p>
                    <p>Phone: +1 234 567 890</p>
                    <p>Address: 123 Health Street</p>
                </div>
            </div>
            <div class="footer-links">
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#schedule">Schedule</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#faq">FAQ</a></li>
                        <li><a href="#blog">Blog</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Services</h3>
                    <ul>
                        <li><a href="#vaccines">Vaccines</a></li>
                        <li><a href="#consultation">Medical Consultation</a></li>
                        <li><a href="#certificates">Certificates</a></li>
                        <li><a href="#support">24/7 Support</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Follow Us</h3>
                    <div class="social-links">
                        <a href="#">Facebook</a>
                        <a href="#">Twitter</a>
                        <a href="#">Instagram</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Vaccination Portal. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>

</html>