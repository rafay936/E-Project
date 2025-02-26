<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - Vaccination Portal</title>
    <link rel="stylesheet" href="web.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        h1{
            color: #6855e7b5;
        }
        .services-container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .service-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .service-card {
            background: #f8f9fa;
            color: #6855e7b5;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .service-card:hover {
            transform: translateY(-5px);
        }

        .service-card img {
            width: 100px;
            height: 100px;
            color: #6855e7b5 !important;
            margin-bottom: 1rem;
        }

        .service-card h3 {
            color: #6855e7b5;
            margin-bottom: 1rem;
        }

        .service-card p {
            color: #666;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .services-container {
                padding: 1rem;
            }
            
            .service-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="https://cdn-icons-png.flaticon.com/512/4386/4386905.png" alt="Vaccination Logo">
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="schedule.php">Schedule</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>

    <div class="services-container">
        <h1>Our Services</h1>
        
        <div class="service-grid">
            <div class="service-card">
                <img src="https://cdn-icons-png.flaticon.com/512/2785/2785402.png" alt="Vaccine Registration">
                <h3>Vaccine Registration</h3>
                <p>Register for various vaccines including COVID-19, Flu, and other routine vaccinations.</p>
            </div>

            <div class="service-card">
                <img src="https://cdn-icons-png.flaticon.com/512/3592/3592095.png" alt="Schedule Management">
                <h3>Schedule Management</h3>
                <p>Book, reschedule, or cancel vaccination appointments easily.</p>
            </div>

            <div class="service-card">
                <img src="https://cdn-icons-png.flaticon.com/512/1067/1067555.png" alt="Digital Certificates">
                <h3>Digital Certificates</h3>
                <p>Access and download digital vaccination certificates securely.</p>
            </div>
            
            <div class="service-card">
                <img src="https://cdn-icons-png.flaticon.com/512/2910/2910797.png" alt="Home Vaccination">
                <h3>Home Vaccination</h3>
                <p>Get vaccinated at home with our on-demand home vaccination services.</p>
            </div>

            <div class="service-card">
                <img src="https://cdn-icons-png.flaticon.com/512/2942/2942551.png" alt="24/7 Support">
                <h3>24/7 Support</h3>
                <p>Receive 24/7 customer support for vaccine-related inquiries.</p>
            </div>
            
            <div class="service-card">
                <img src="https://cdn-icons-png.flaticon.com/512/2942/2942551.png" alt="24/7 Support">
                <h3>24/7 Support</h3>
                <p>Receive 24/7 customer support for vaccine-related inquiries.</p>
            </div>

        </div>
        
    </div>

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
