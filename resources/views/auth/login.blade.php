<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nutri App - Login</title>

  <!-- Fonts + Icons + Bootstrap -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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
      align-items: center;
      justify-content: center;
      padding: 2rem;
      background-color: #e9f7ef;
    }

    .welcome-heading {
      font-size: 2rem;
      font-weight: 700;
      color: #14532d;
      margin-bottom: 1.2rem;
      text-align: center;
    }

    .left-side img {
      max-width: 90%;
      height: auto;
      max-height: 440px;
      object-fit: cover;
      border-radius: 12px;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
    }

    .right-side {
      flex: 1;
      padding: 5% 10%;
      background-color: #ffffff;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .btn-success {
      background-color: #14532d;
      border: none;
    }

    .btn-success:hover {
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

  <!-- ✅ MAIN LOGIN LAYOUT -->
  <div class="main">
    <!-- Left -->
    <div class="left-side text-center">
      <h2 class="welcome-heading">Welcome back to NutriApp!</h2>
      <img src="{{ asset('img/nutrihub-welcome.jpg') }}" alt="Welcome to Nutri App" />
    </div>

    <!-- Right -->
    <div class="right-side">
      <h2 class="mb-4">Login to Your Account</h2>
      @if ($errors->any())
        <div class="alert alert-danger">
          {{ $errors->first() }}
        </div>
      @endif
      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" id="email" name="email" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3 form-check">
          <input type="checkbox" name="remember" class="form-check-input" id="remember">
          <label class="form-check-label" for="remember">Remember Me</label>
        </div>

        <button type="submit" class="btn btn-success">Login</button>
      </form>

      <p class="mt-3">Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
    </div>
  </div>
</body>
</html>
