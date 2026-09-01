<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin - Perpustakaan</title>

<script src="https://cdn.tailwindcss.com"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Pinyon+Script&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          emeraldbg: '#062e1a',
          cardgreen: '#083d23',
          inputgreen: '#042415',
          gold: '#d4a05f',
          goldmuted: '#b0804c',
          cream: '#f5ead9',
        },
        fontFamily: {
          serif: ['"Playfair Display"', 'serif'],
          script: ['"Pinyon Script"', 'cursive'],
          body: ['"Poppins"', 'sans-serif'],
        },
      },
    },
  };
</script>

<style>
  body {
    background-color: #062e1a;
    background-image:
      radial-gradient(circle at 20% 20%, rgba(212, 160, 95, 0.05) 0%, transparent 40%),
      radial-gradient(circle at 80% 80%, rgba(212, 160, 95, 0.04) 0%, transparent 40%),
      repeating-linear-gradient(45deg, rgba(255,255,255,0.015) 0px, rgba(255,255,255,0.015) 1px, transparent 1px, transparent 12px);
  }

  .beveled-card {
    box-shadow:
      10px 10px 10px -12px rgba(0,0,0,0.7);
  }

  .input-recessed {
    background-color: #042415;
    box-shadow: inset 0 2px 6px rgba(0,0,0,0.6), inset 0 0 0 1px rgba(212,160,95,0.15);
  }

  .input-recessed:focus {
    outline: none;
    box-shadow:
      inset 0 2px 6px rgba(0,0,0,0.6),
      0 0 0 1px #d4a05f,
      0 0 14px 2px rgba(212,160,95,0.45);
  }

  .placeholder-goldmuted::placeholder {
    color: #b0804c;
    opacity: 1;
  }

</style>
</head>
<body class="font-body min-h-screen flex items-center justify-center p-4 md:p-8">

  <div class="beveled-card bg-cardgreen w-full max-w-3xl rounded-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

    <div class="relative p-8 md:p-12 lg:p-14 flex flex-col justify-center">

      <div class="mb-3">
        <h1 class="font-serif text-center text-4xl lg:text-5xl text-[#d4a05f] tracking-wide font-bold">
          Login Admin
        </h1>
      </div>

      @if ($errors->any())
        <div class="mb-6 p-3 bg-red-900/40 border border-red-500/50 rounded-lg text-cream text-xs">
          {{ $errors->first() }}
        </div>
      @endif

      <form action="{{ url('/login') }}" method="POST" class="space-y-5">
        @csrf

        <div class="relative">
          <span class="absolute inset-y-0 left-4 flex items-center text-gold pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 4h16v16H4z" stroke="none"/>
              <path d="M3 6l9 7 9-7"/>
              <path d="M3 6v12a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1z"/>
            </svg>
          </span>
          <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="Email"
            required
            class="input-recessed placeholder-goldmuted w-full rounded-lg py-3.5 pl-12 pr-4 text-cream text-sm transition-shadow duration-300"
          >
        </div>

        <div class="relative">
          <span class="absolute inset-y-0 left-4 flex items-center text-gold pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="5" y="11" width="14" height="9" rx="2"/>
              <path d="M8 11V7a4 4 0 0 1 8 0v4"/>
            </svg>
          </span>
          <input
            type="password"
            name="password"
            placeholder="Password"
            required
            class="input-recessed placeholder-goldmuted w-full rounded-lg py-3.5 pl-12 pr-4 text-cream text-sm transition-shadow duration-300"
          >
        </div>

        <button
          type="submit"
          class="w-full bg-gold hover:bg-goldmuted text-[#062e1a] font-bold text-sm tracking-wide py-3.5 rounded-lg shadow-md hover:shadow-[0_8px_20px_rgba(212,160,95,0.35)] transition-all duration-300"
        >
          LOG IN
        </button>

      </form>

    </div>

    <div class="hidden md:block relative w-full h-full bg-[#062e1a]/40 overflow-hidden">
      <img
        src="https://images.pexels.com/photos/30484324/pexels-photo-30484324/free-photo-of-spacious-public-library-with-skylight-and-bookshelves.jpeg?auto=compress&w=1260&h=750&dpr=2"
        alt="library"
        class="absolute inset-0 w-full h-full object-cover"
      >
    </div>
  </div>

</body>
</html>