@extends('layouts.app')

@section('content')
<div class="min-h-[90vh] flex items-center justify-center px-6 py-12 bg-gradient-to-br from-orange-50 via-white to-orange-100">

    <div class="max-w-5xl w-full bg-white rounded-[3rem] shadow-2xl shadow-orange-100/50 border border-orange-50 overflow-hidden flex flex-col md:flex-row">

        <!-- LEFT SIDE -->
        <div class="md:w-1/2 bg-slate-900 relative p-12 flex flex-col justify-between overflow-hidden">
            <div class="absolute inset-0 opacity-40">
                <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?auto=format&fit=crop&w=800&q=80"
                     class="w-full h-full object-cover">
            </div>

            <div class="relative z-10">
                <div class="serif text-2xl text-orange-400 font-bold">
                    Soul Diaries
                </div>
            </div>

            <div class="relative z-10">
                <h2 class="serif text-4xl text-white leading-tight mb-4">
                    "Words are the <br>
                    <span class="italic font-light text-orange-200">
                        keys to the soul.
                    </span>"
                </h2>
                <p class="text-slate-400 text-sm tracking-widest uppercase">
                    — Welcome Home
                </p>
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="md:w-1/2 p-12 md:p-20 flex flex-col justify-center">

            <div class="mb-10">
                <h3 class="serif text-3xl text-slate-900 mb-2">Login</h3>
                <p class="text-slate-500 text-sm">
                    Continue your journey into the quiet.
                </p>
                @if(session('error'))
                <p class="mt-3 text-red-600 text-sm">{{ session('error') }}</p>
                @endif
            </div>

            <!-- LOGIN FORM -->
            <form id="loginForm" class="space-y-6">

                <div class="space-y-2">
                    <label class="text-[10px] uppercase tracking-widest font-bold text-slate-400 ml-1">
                        Email Address
                    </label>
                    <input type="email" name="email" required
                        class="w-full px-6 py-4 bg-slate-50 rounded-2xl focus:ring-2 focus:ring-orange-200 transition text-slate-700 placeholder:text-slate-300"
                        placeholder="your@soul.com">
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center px-1">
                        <label class="text-[10px] uppercase tracking-widest font-bold text-slate-400">
                            Password
                        </label>
                        <a href="{{ route('forgot.password') }}"
                           class="text-[10px] uppercase tracking-widest font-bold text-orange-500 hover:text-orange-600 transition">
                            Forgot?
                        </a>
                    </div>
                    <input type="password" name="password" required
                        class="w-full px-6 py-4 bg-slate-50 rounded-2xl focus:ring-2 focus:ring-orange-200 transition text-slate-700">
                </div>

                <button type="submit"
                    class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold tracking-widest uppercase text-xs hover:bg-orange-600 shadow-xl shadow-orange-100 transition-all duration-300">
                    Enter the Sanctuary
                </button>
            </form>

            <div class="mt-12 text-center space-y-2">
                <p class="text-sm text-slate-400">
                    New here?
                    <a href="{{ route('register') }}"
                       class="text-orange-600 font-bold border-b border-orange-200 hover:border-orange-500 transition">
                        Create an Account
                    </a>
                </p>
                <p class="text-sm text-slate-400">
                    <a href="{{ route('admin.login') }}" class="text-slate-500 hover:text-slate-700">Admin login →</a>
                </p>
            </div>
        </div>
    </div>
</div>


<!-- ================= CUSTOM THEME ALERT ================= -->
<div id="customAlert"
     class="fixed top-6 right-6 translate-x-[120%] transition-all duration-500 z-50">

    <div id="alertBox"
         class="px-6 py-4 rounded-2xl shadow-2xl border flex items-center gap-3 min-w-[300px] backdrop-blur-xl">

        <span id="alertIcon" class="text-xl"></span>
        <span id="alertMessage" class="text-sm font-medium"></span>
    </div>
</div>


<script>
/* ================= ALERT FUNCTION ================= */
function showAlert(type, message) {

    const alert = document.getElementById("customAlert");
    const box = document.getElementById("alertBox");
    const icon = document.getElementById("alertIcon");
    const text = document.getElementById("alertMessage");

    text.innerText = message;

    if (type === "success") {
        box.className =
            "px-6 py-4 rounded-2xl shadow-2xl border flex items-center gap-3 min-w-[300px] bg-emerald-50 border-emerald-200 text-emerald-700";
        icon.innerHTML = "✓";
    } else {
        box.className =
            "px-6 py-4 rounded-2xl shadow-2xl border flex items-center gap-3 min-w-[300px] bg-rose-50 border-rose-200 text-rose-700";
        icon.innerHTML = "⚠";
    }

    alert.classList.remove("translate-x-[120%]");
    alert.classList.add("translate-x-0");

    setTimeout(() => {
        alert.classList.remove("translate-x-0");
        alert.classList.add("translate-x-[120%]");
    }, 3500);
}


/* ================= LOGIN API ================= */
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const button = this.querySelector("button");
    const originalText = button.innerHTML;

    button.innerHTML = "Entering...";
    button.disabled = true;

    const formData = Object.fromEntries(new FormData(this).entries());

    try {

        const response = await fetch('/api/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (response.ok && data.status) {

            localStorage.setItem("token", data.token);

            showAlert("success", "Welcome back to your sanctuary ✨");

            // Set web session so navbar shows Dashboard, Write, Library, profile
            setTimeout(() => {
                window.location.href = "/auth/session-set?token=" + encodeURIComponent(data.token);
            }, 1500);

        } else {
            showAlert("error", data.message || "Invalid credentials.");
        }

    } catch (error) {
        showAlert("error", "Something went wrong. Please try again.");
    }

    button.innerHTML = originalText;
    button.disabled = false;
});
</script>

@endsection