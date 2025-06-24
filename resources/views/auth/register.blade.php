<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - Nutri App</title>

  <!-- Fonts + Icons + Bootstrap -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <style>
    * {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    body {
      margin: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    header {
      background-color: #c6f2d2;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.8rem 2rem;
      flex-wrap: wrap;
    }

    header nav ul {
      display: flex;
      list-style: none;
      gap: 1.5rem;
      margin: 0;
      padding: 0;
    }

    header nav ul li a {
      text-decoration: none;
      color: #000;
    }

    .login-btn {
      border: 1px solid #000;
      padding: 0.4rem 1rem;
      background: white;
      border-radius: 4px;
    }

    .login-btn:hover {
      background-color: #f1f1f1;
    }

    .main {
      display: flex;
      flex-grow: 1;
      min-height: calc(100vh - 80px);
    }

    .left-side {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 2rem;
      background-color: #e9f7ef;
    }

    .left-side img {
      max-width: 90%;
      height: auto;
      max-height: 440px;
      object-fit: cover;
      border-radius: 12px;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
    }

    .left-side h2 {
      font-size: 2rem;
      font-weight: 700;
      color: #14532d;
      margin-bottom: 1.2rem;
    }

    .right-side {
      flex: 1;
      padding: 5% 10%;
      background-color: #ffffff;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .form-box {
      width: 100%;
      max-width: 420px;
    }

    .form-box h2 {
      margin-bottom: 1.5rem;
      font-weight: 700;
    }

    .form-box .btn-primary {
      background-color: #14532d;
      border: none;
    }

    .form-box .btn-primary:hover {
      background-color: #0f3e21;
    }

    @media (max-width: 768px) {
      .main {
        flex-direction: column;
      }

      .left-side, .right-side {
        width: 100%;
        padding: 2rem;
      }
    }
  </style>
</head>

<body>

<!-- ✅ HEADER -->
<header>
  <div class="d-flex align-items-center">
    <img src="{{ asset('img/nutriteam-logo.png') }}" alt="Nutri Logo" style="height: 50px; width: auto; margin-right: 12px;">
    <div class="logo fw-bold fs-5 text-success">NUTRI APP</div>
  </div>

  <nav>
    <ul>
      <li><a href="{{ route('home') }}">Home</a></li>
      <li><a href="{{ route('buyer.products.index') }}">Shop</a></li>
      <li><a href="#about">About Us</a></li>
    </ul>
  </nav>

  <div class="d-flex align-items-center gap-3">
    <a href="{{ route('buyer.cart.index') }}" style="color: green;">
      <i class="fa-solid fa-cart-shopping fs-5"></i>
    </a>
    <a href="{{ route('login') }}">
      <button class="login-btn">Login</button>
    </a>
  </div>
</header>

<!-- ✅ REGISTER BODY -->
<div class="main">
  <!-- Left -->
  <div class="left-side text-center">
    <h2>Create Your Account</h2>
    <img src="{{ asset('img/nutrihub-welcome.jpg') }}" alt="Welcome to Nutri App" />
  </div>

  <!-- Right -->
  <div class="right-side">
    <div class="form-box">
      <h2>Create an Account</h2>
      <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
          <label for="name" class="form-label">Full Name</label>
          <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
          @error('name')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="role" class="form-label">Register as</label>
          <select name="role" id="role" class="form-select" required>
            <option value="">Select Role</option>
            <option value="buyer" {{ old('role') == 'buyer' ? 'selected' : '' }}>Buyer</option>
            <option value="farmer" {{ old('role') == 'farmer' ? 'selected' : '' }}>Farmer</option>
          </select>
          @error('role')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
          @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" id="password" name="password" class="form-control" required>
          @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="password_confirmation" class="form-label">Confirm Password</label>
          <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>

        <div class="mb-3 text-center">
          <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">View Terms and Conditions</a>
        </div>


        <div class="d-flex justify-content-between align-items-center">
          <a href="{{ route('login') }}">Already registered?</a>
          <button type="submit" id="registerBtn" class="btn btn-primary" disabled>Register</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 📄 Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="termsModalLabel">NutriApp Terms of Service (TOS)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body" style="font-size: 0.95rem; max-height: 60vh; overflow-y: auto;">
        <p><strong>Last Updated: May 18, 2025</strong></p>
        <p>Welcome to NutriApp, the official online platform of NUTRITECH ONLINE STORE...</p>

        <h6>1. INTRODUCTION</h6>
        <ul>
          <li>NutriApp is a digital marketplace that connects local farmers ("Sellers") with consumers ("Buyers")...</li>
          <li>These Terms govern your use of NutriApp...</li>
          <li>By registering an account, you agree to these Terms and our Privacy Policy.</li>
        </ul>

        <h6>2. USER ROLES & ACCOUNT REGISTRATION</h6>
        <ul>
          <li>Users must register as Farmer, Buyer, or Admin.</li>
          <li>Registration requires valid IDs, business permits (if applicable), and accurate info.</li>
          <li>Keep your login credentials secure; NutriApp is not liable for unauthorized access due to negligence.</li>
        </ul>

        <h6>3. PLATFORM USAGE</h6>
        <ul>
          <li>Sellers can post and sell products; Buyers can browse, order, and chat with Sellers.</li>
          <li>All platform activity must comply with laws.</li>
          <li>Prohibited uses include fraud, impersonation, or posting illegal items.</li>
        </ul>

        <h6>4. PAYMENTS</h6>
        <ul>
          <li>Payments are processed securely via PayMongo (GCash, Maya, card, bank transfer).</li>
          <li>NutriApp implements a Buyer Protection Period — payment is released upon confirmed delivery.</li>
          <li>NutriApp does not store payment details; PayMongo handles transactions securely.</li>
        </ul>

        <h6>5. DELIVERY SUPPORT</h6>
        <ul>
          <li>NutriApp helps coordinate deliveries using third-party providers (e.g., Lalamove).</li>
          <li>Delivery agreements must be confirmed between Buyer and Seller.</li>
          <li>NutriApp is not responsible for logistics delays or damage.</li>
        </ul>

        <h6>6. CONTENT & INTELLECTUAL PROPERTY</h6>
        <ul>
          <li>All design, logos, and materials are owned by NutriTech Online Store.</li>
          <li>Users grant NutriApp a license to use product listings and related content.</li>
        </ul>

        <h6>7. TERMINATION & VIOLATIONS</h6>
        <ul>
          <li>NutriApp may suspend or ban accounts that:</li>
          <ul>
            <li>Submit fake documents</li>
            <li>Abuse or harass other users</li>
            <li>Engage in spam or illegal activity</li>
          </ul>
          <li>You may request account deletion; pending issues must be resolved first.</li>
        </ul>

        <h6>8. PRIVACY & DATA SECURITY</h6>
        <ul>
          <li>We collect and store necessary data under the Data Privacy Act of 2012.</li>
          <li>Sensitive data like IDs and proof of payment are protected and only shared with authorized staff.</li>
        </ul>

        <h6>9. LIMITATIONS OF LIABILITY</h6>
        <ul>
          <li>NutriApp does not guarantee product quality or delivery outcomes.</li>
          <li>Our liability is limited to PHP 5,000 or the order total — whichever is lower.</li>
        </ul>

        <h6>10. GOVERNING LAW & DISPUTES</h6>
        <ul>
          <li>These Terms follow Philippine law.</li>
          <li>Disputes should be settled amicably; unresolved cases fall under Philippine jurisdiction.</li>
        </ul>

        <h6>11. ADDITIONAL PLATFORM FEATURES</h6>
        <ul>
          <li><strong>Ratings & Reviews:</strong> Buyers may rate products. Abusive or false reviews may be removed.</li>
          <li><strong>Returns & Refunds:</strong> Return requests must follow platform rules and be submitted within the return period.</li>
          <li><strong>Manual QR Payments:</strong> Buyers using GCash/Maya/manual transfer must upload proof. Orders will only be confirmed after admin verification.</li>
          <li><strong>Push Notifications:</strong> NutriApp sends notifications related to orders, chats, and updates. You can manage preferences in your profile.</li>
        </ul>

        <h6>12. CONTACT US</h6>
        <ul>
          <li>Support: <a href="mailto:nutrisupport@nutriapp.ph">nutrisupport@nutriapp.ph</a></li>
          <li>Privacy Inquiries: <a href="mailto:nutriprivacy@nutriapp.ph">nutriprivacy@nutriapp.ph</a></li>
        </ul>

        <p class="mt-3"><strong>By using NutriApp, you confirm that you have read, understood, and agreed to these Terms of Service.</strong></p>
      </div>

      <div class="modal-footer d-flex justify-content-between">
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="modalAgree">
          <label class="form-check-label" for="modalAgree">I agree to the Terms & Conditions</label>
        </div>
        <button type="button" class="btn btn-success" id="closeTerms" data-bs-dismiss="modal" disabled>Done</button>
      </div>
    </div>
  </div>
</div>


<!-- ✅ Enable button if checkbox is checked -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const modalAgree = document.getElementById('modalAgree');
    const closeBtn = document.getElementById('closeTerms');
    const registerBtn = document.getElementById('registerBtn');

    modalAgree.addEventListener('change', function () {
      closeBtn.disabled = !this.checked;
    });

    closeBtn.addEventListener('click', function () {
      if (modalAgree.checked) {
        registerBtn.disabled = false;
      }
    });
  });
</script>


</body>
</html>
