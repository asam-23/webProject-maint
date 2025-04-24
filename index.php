<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <title>Online Library</title>
</head>
<body>

<header>
<div class="logo">My<span style="color: #ff9f1c;">Library</span></div>
    <nav>
        <a href="index.php">Home</a>
        <a href="viewbooks.php">Books</a>
        <a href="#contact">Contact</a>
    </nav>
    <div class="auth-buttons">
        <a href="login.php">Login</a>
        <a href="register.php">Sign Up</a>
    </div>
</header>

<section class="hero">
    <div class="hero-text">
        <h1>Library Management System</h1>
        <p>An online library management platform that allows you to easily add, edit, and delete books.</p>
        <a href="books.php">Add & Manage Books</a>
    </div>
    <div class="hero-image"></div>
</section>
<section id="contact" class="contact-section">
    <div class="container contact-container">
        <div class="contact-info-box">
            <h2> Contact Us</h2>
            <p>We’d love to hear from you! Reach out anytime.</p>
            <p><i class="bi bi-envelope orange-icon"></i> Email: support@mylibrary.com</p>
            <p><i class="bi bi-telephone orange-icon"></i> Phone: +966 555 123 456</p>
        </div>

        <form class="contact-form" action="#" method="post">
            <textarea name="message" placeholder="Write your message here..." required></textarea>
            <button type="submit"><i class="bi bi-send-fill"></i> Send</button>
        </form>
    </div>
</section>
<footer class="footer">
  <div class="footer-container">
    <p>&copy; 2025 <span style="font-weight: bold;">My</span><span style="color: #ff9f1c; font-weight: bold; ">Library</span>. All rights reserved.</p>
  </div>
  <div class="social-icons">
      <a href="#" target="_blank"><i class="bi bi-linkedin"></i></a>
      <a href="#" target="_blank"><i class="bi bi-facebook"></i></a>
      <a href="#" target="_blank"><i class="bi bi-twitter"></i></a>
    </div>
</footer>

</body>
</html>
