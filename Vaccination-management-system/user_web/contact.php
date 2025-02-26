<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Vaccination Portal</title>
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

    <section class="contact-hero">
        <h1>Contact Us</h1>
        <p>Get in touch with us for any queries or support</p>
    </section>

    <section class="contact-info-section">
        <div class="contact-container">
            <div class="contact-cards">
                <div class="contact-card">
                    <i class="contact-icon">📞</i>
                    <h3>Phone</h3>
                    <p>+1 234 567 890</p>
                    <p>Mon-Fri: 9am to 6pm</p>
                </div>
                <div class="contact-card">
                    <i class="contact-icon">✉️</i>
                    <h3>Email</h3>
                    <p>vms@vaccineportal.com</p>
                    <p>support@vaccineportal.com</p>
                </div>
                <div class="contact-card">
                    <i class="contact-icon">📍</i>
                    <h3>Location</h3>
                    <p>Karachi</p>
                    <p>Pakistan</p>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-form-section">
        <div class="contact-container">
            <div class="form-and-map">
                <div class="contact-form">
                    <h2>Send us a Message</h2>
                    <form id="contactForm">
                        <div class="form-group">
                            <input type="text" id="name" name="name" placeholder="Your Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder="Your Email" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" id="phone" name="phone" placeholder="Your Phone">
                        </div>
                        <div class="form-group">
                            <select id="subject" name="subject" required>
                                <option value="">Select Subject</option>
                                <option value="appointment">Appointment</option>
                                <option value="inquiry">General Inquiry</option>
                                <option value="feedback">Feedback</option>
                                <option value="support">Support</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea id="message" name="message" placeholder="Your Message" required></textarea>
                        </div>
                        <button type="submit" class="submit-button">Send Message</button>
                    </form>
                </div>
                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6802941.523386144!2d65.84821899999999!3d30.375320499999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x38db52d2f8fd751f%3A0x46b7a1f7e614925c!2sPakistan!5e0!3m2!1sen!2s!4v1645654846367!5m2!1sen!2s"
                        width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy">
                    </iframe>
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
                    <p>Email: vms@vaccineportal.com</p>
                    <p>Phone: +1 234 567 890</p>
                    <p>Karachi,Pakistan</p>
                </div>
            </div>
            <div class="footer-links">
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><a href="services.html">Services</a></li>
                        <li><a href="schedule.html">Schedule</a></li>
                        <li><a href="contact.html">Contact</a></li>
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
            <p>&copy; 2025 Vaccination Portal. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>

</html>