<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sigma Login Selection</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">


<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

    :root {
        --hf-magenta: #d6007b;
        --hf-magenta-light: #ff2ba6;
    }

    body {
        margin: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;

        background: linear-gradient(135deg, #000000, #333333, #000000, #666666);
        background-size: 300% 300%;


        font-family: 'Poppins', sans-serif;
        color: white;
    }

    @keyframes gradientFlow {
        0% { background-position: 0% 0%; }
        50% { background-position: 100% 100%; }
        100% { background-position: 0% 0%; }
    }

    .glass-card {
        width: 90%;
        max-width: 500px;
        padding: 45px 35px;
        text-align: center;
        border-radius: 22px;

        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.14);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);

        box-shadow: 0 0 35px rgba(214, 0, 123, 0.35);
        animation: fadeUp 0.7s ease-out;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(25px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .btn-hf {
        display: block;
        width: 100%;
        padding: 15px 0;
        margin-top: 15px;
        font-size: 18px;
        font-weight: 600;
        border-radius: 14px;
        cursor: pointer;
        transition: 0.25s ease;
    }

    .btn-admin {
        background: var(--hf-magenta);
        color: white;
        box-shadow: 0 0 18px rgba(214, 0, 123, 0.55);
    }
    .btn-admin:hover {
        background: var(--hf-magenta-light);
        transform: translateY(-4px);
        box-shadow: 0 0 25px rgba(255, 0, 170, 0.6);
    }

    .btn-salesman {
        background: rgba(255,255,255,0.9);
        color: black;
        box-shadow: 0 0 15px rgba(255,255,255,0.35);
    }
    .btn-salesman:hover {
        background: white;
        transform: translateY(-4px);
        box-shadow: 0 0 25px rgba(255,255,255,0.6);
    }
</style>
</head>

<body>
<div>
    <img src="" alt="">
</div>
<div class="glass-card">

    <!-- LOGO -->
    <div class="flex justify-center mb-6">
        <img
            src="{{ asset('images/logo.webp') }}"
            alt="Sigma Logo"
            class="h-20 w-auto drop-shadow-[0_0_15px_rgba(214,0,123,0.35)]"
        >
    </div>

    <h1 class="text-4xl font-extrabold tracking-wide mb-3">
        Welcome to <span class="text-[var(--hf-magenta-light)]">Sigma</span>
    </h1>

    <p class="text-gray-200 mb-8">
        Please login to continue
    </p>

    <!-- Login -->
    <button
        class="btn-hf btn-admin"
        onclick="location.href='{{ route('login') }}'">
        Login
    </button>

    <p class="text-gray-300 text-sm mt-6">
        © 2025 Sigma Tracking System
    </p>

</div>

</body>
</html>
