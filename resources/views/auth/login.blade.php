<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="{{ asset('storage/website/mafa-logo.jpg') }}">
  <title>Login</title>
  <style>
    :root {
      --primary: #034e7a;
      --white: #ffffff;
      --light-gray: #f4f4f4;
      --dark: #1f1f1f;
    }

    /* Global styles */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: var(--light-gray);
      padding: 1rem;
    }

    /* Card */
    .login-card {
      background-color: var(--white);
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
      padding: 2rem;
      width: 100%;
      max-width: 400px;
      border-top: 6px solid var(--primary);
    }

    .login-card h1 {
      color: var(--primary);
      text-align: center;
      margin-bottom: 2rem;
      font-weight: 700;
      letter-spacing: 0.5px;
    }

    /* Form styles */
    .login-card form {
      display: flex;
      flex-direction: column;
    }

    label {
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: var(--dark);
    }

    input[type="email"],
    input[type="password"] {
      padding: 0.75rem 1rem;
      border-radius: 8px;
      border: 1px solid #ccc;
      margin-bottom: 1.5rem;
      font-size: 1rem;
      transition: border-color 0.3s, box-shadow 0.3s;
    }

    input[type="email"]:focus,
    input[type="password"]:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(3, 78, 122, 0.2);
    }

    .form-check {
      display: flex;
      align-items: center;
      margin-bottom: 1.5rem;
      font-size: 0.95rem;
      color: var(--dark);
    }

    .form-check input {
      margin-right: 0.5rem;
    }

    button {
      padding: 0.75rem 1rem;
      border: none;
      border-radius: 8px;
      background-color: var(--primary);
      color: var(--white);
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      font-size: 1rem;
      letter-spacing: 0.3px;
    }

    button:hover {
      background-color: #023a5b;
      transform: translateY(-1px);
    }

    .text-center {
      text-align: center;
      margin-top: 1.5rem;
    }

    .text-center a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s;
    }

    .text-center a:hover {
      text-decoration: underline;
    }

    /* Error alert */
    .alert {
      background-color: #f8d7da;
      color: #721c24;
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1.5rem;
      border: 1px solid #f5c6cb;
    }

    .alert ul {
      margin: 0;
      padding-left: 1.2rem;
    }

    /* Responsive */
    @media (max-width: 500px) {
      .login-card {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>

  <div class="login-card">

    <h1>Login</h1>

    <!-- Display errors if any -->
    @if ($errors->any())
      <div class="alert">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Login Form -->
    <form action="{{ route('login') }}" method="POST">
      @csrf
      <label for="email">Email</label>
      <input type="email" name="email" id="email" value="{{ old('email') }}" required>

      <label for="password">Password</label>
      <input type="password" name="password" id="password" required>

      <div class="form-check">
        <input type="checkbox" name="remember" id="remember">
        <label for="remember">Remember me</label>
      </div>

      <button type="submit">Login</button>
    </form>

    <!-- <div class="text-center">
      <a href="{{ route('register') }}">Don't have an account? Register here</a>
    </div> -->

  </div>

</body>
</html>
