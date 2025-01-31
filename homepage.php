<!DOCTYPE html>
<html lang="en">
  <style>
    * Basic Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* Body Styles */
body {
  font-family: Arial, sans-serif;
  background-color: #f4f4f4;
  color: #333;
}

/* Header Styles */
header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  background-color: #333;
  color: #fff;
}

.logo-container .logo {
  width: 150px;
}

nav {
  display: flex;
  align-items: center;
}

nav .nav-links {
  list-style-type: none;
  display: flex;
}

nav .nav-links li {
  margin: 0 15px;
}

nav .nav-links a {
  text-decoration: none;
  color: #fff;
  font-weight: bold;
}

nav .menu-toggle {
  display: none;
  background-color: transparent;
  border: none;
  font-size: 30px;
  color: #fff;
}

/* Hero Section */
.hero {
  text-align: center;
  padding: 100px 20px;
  background-color: #4CAF50;
  color: white;
}

.hero h1 {
  font-size: 3rem;
}

.hero p {
  font-size: 1.2rem;
  margin-bottom: 20px;
}

.hero button {
  padding: 10px 20px;
  font-size: 1rem;
  background-color: #333;
  color: white;
  border: none;
  cursor: pointer;
}

.hero button:hover {
  background-color: #555;
}

/* About Section */
.about, .services, .contact {
  padding: 60px 20px;
  text-align: center;
}

h2 {
  font-size: 2rem;
  margin-bottom: 20px;
}

p {
  font-size: 1.1rem;
}

/* Footer Styles */
footer {
  background-color: #333;
  color: white;
  text-align: center;
  padding: 20px 0;
}

/* Responsive Styles */
@media screen and (max-width: 768px) {
  nav .nav-links {
      display: none;
      width: 100%;
      flex-direction: column;
      text-align: center;
      padding: 20px;
      background-color: #333;
  }

  nav .nav-links.active {
      display: flex;
  }

  nav .nav-links li {
      margin: 10px 0;
  }

  nav .menu-toggle {
      display: block;
  }

  .hero h1 {
      font-size: 2rem;
  }

  .hero p {
      font-size: 1rem;
  }

  .hero button {
      font-size: 0.9rem;
  }
}

  </style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo-container">
        <img src="download.jpeg" alt="Logo" class="logo" width="100" height="100">
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <button class="menu-toggle" id="menuToggle">&#9776;</button>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <h1>Vaccination Management System</h1>
        <p>Your journey to success starts here.</p>
        <button onclick="scrollToSection('about')">Learn More</button>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <h2>About Us</h2>
        <p>We are a team of professionals dedicated to providing the best services for your needs.</p>
    </section>

    <!-- Services Section -->
    <section id="services" class="services">
        <h2>Our Services</h2>
        <p>We offer a variety of services to help you achieve your goals.</p>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <h2>Contact Us</h2>
        <p>Get in touch with us for more information!</p>
    </section>

    <!-- Footer Section -->
    <footer>
        
    </footer>

    <script src="script.js"></script>
</body>
</html>
