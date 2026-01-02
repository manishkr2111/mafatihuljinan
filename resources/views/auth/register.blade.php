<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="{{ asset('storage/website/mafa-logo.jpg') }}">
  <script src="https://www.google.com/recaptcha/api.js?render={{ env('GOOGLE_RECAPTCHA_KEY') }}"></script>
  <title>Register</title>
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

    /* Card container */
    .register-card {
      background-color: var(--white);
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      padding: 2rem;
      width: 100%;
      max-width: 450px;
      border-top: 6px solid var(--primary);
    }

    .register-card h1 {
      color: var(--primary);
      text-align: center;
      margin-bottom: 2rem;
      font-weight: 700;
      letter-spacing: 0.5px;
    }

    label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: var(--dark);
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 0.75rem 1rem;
      margin-bottom: 1.5rem;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 1rem;
      transition: border-color 0.3s, box-shadow 0.3s;
    }

    input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(3, 78, 122, 0.2);
    }

    button {
      width: 100%;
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
      .register-card {
        padding: 1.5rem;
      }
    }
  </style>
</head>

<body>

  <div class="register-card">
    <h1>Register</h1>

    <!-- Display validation errors if any -->
    @if ($errors->any())
    <div class="alert">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <form action="{{ route('register') }}" method="POST">
      @csrf
      <input type="hidden" name="g-recaptcha-response" id="recaptcha_response">

      <label for="name">Full Name</label>
      <input type="text" name="name" id="name" value="{{ old('name') }}" required>

      <label for="email">Email</label>
      <input type="email" name="email" id="email" value="{{ old('email') }}" required>

      <label for="password">Password</label>
      <input type="password" name="password" id="password" required>

      <label for="password_confirmation">Confirm Password</label>
      <input type="password" name="password_confirmation" id="password_confirmation" required>

      <button type="submit">Register</button>
    </form>

    <div class="text-center">
      <a href="{{ route('login') }}">Already have an account? Login here</a>
    </div>
  </div>

</body>
<script>
  grecaptcha.ready(function() {
    grecaptcha.execute("{{ env('GOOGLE_RECAPTCHA_KEY') }}", {
        action: 'login'
      })
      .then(function(token) {
        document.getElementById('recaptcha_response').value = token;
      });
  });
</script>

</html>