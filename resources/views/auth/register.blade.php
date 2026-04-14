@extends('layouts.app')

@section('content')

<div class="min-h-[95vh] flex items-center justify-center px-6 py-12 bg-slate-50">

    <div class="max-w-6xl w-full bg-white rounded-[40px] shadow-2xl overflow-hidden flex flex-col md:flex-row">

        <!-- LEFT SIDE (REGISTER FORM) -->
        <div class="md:w-1/2 p-12">

            <h2 class="text-3xl font-serif text-slate-900 mb-2">
                Create Account
            </h2>
            <p class="text-slate-500 mb-8">
                Enter your details to begin your journey.
            </p>

            <form id="registerForm" class="space-y-6">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-slate-600">Full Name</label>
                        <input type="text" name="name" required
                            class="w-full mt-1 px-4 py-3 bg-slate-100 rounded-xl focus:ring-2 focus:ring-orange-300">
                    </div>

                    <div>
                        <label class="text-sm text-slate-600">Username (Pen Name)</label>
                        <input type="text" name="username" required
                            class="w-full mt-1 px-4 py-3 bg-slate-100 rounded-xl focus:ring-2 focus:ring-orange-300">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-slate-600">Email</label>
                        <input type="email" name="email" required
                            class="w-full mt-1 px-4 py-3 bg-slate-100 rounded-xl focus:ring-2 focus:ring-orange-300">
                    </div>

                    <div>
                        <label class="text-sm text-slate-600">Phone (Optional)</label>
                        <input type="text" name="phone"
                            class="w-full mt-1 px-4 py-3 bg-slate-100 rounded-xl focus:ring-2 focus:ring-orange-300">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-slate-600">Date of Birth</label>
                        <input type="date" name="dob" required
                            class="w-full mt-1 px-4 py-3 bg-slate-100 rounded-xl focus:ring-2 focus:ring-orange-300">
                    </div>

                    <div>
                        <label class="text-sm text-slate-600">Pronouns</label>
                        <select name="pronouns" required
                            class="w-full mt-1 px-4 py-3 bg-slate-100 rounded-xl focus:ring-2 focus:ring-orange-300">
                            <option value="">Select Pronouns</option>
                            <option>He/Him</option>
                            <option>She/Her</option>
                            <option>They/Them</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="text-sm text-slate-600">Password</label>
                    <input type="password" name="password" required
                        class="w-full mt-1 px-4 py-3 bg-slate-100 rounded-xl focus:ring-2 focus:ring-orange-300">
                </div>

                <button type="submit"
                    class="w-full py-4 bg-slate-900 text-white rounded-2xl font-semibold hover:bg-orange-600 transition">
                    CREATE ACCOUNT
                </button>

            </form>

            <p class="text-sm text-slate-500 mt-6">
                Already have an account?
                <a href="{{ route('login') }}" class="text-orange-600 font-semibold">
                    Login Here
                </a>
            </p>

        </div>

        <!-- RIGHT SIDE IMAGE -->
        <div class="hidden md:block md:w-1/2 relative">

            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f"
                 class="w-full h-full object-cover"
                 alt="Register Image">

            <div class="absolute inset-0 bg-black/30"></div>

            <div class="absolute bottom-10 left-10 text-white">
                <h3 class="text-3xl font-serif">Welcome Onboard</h3>
                <p class="text-sm mt-2 opacity-80">Create your account and start today.</p>
            </div>

        </div>

    </div>
</div>


<!-- OTP MODAL -->
<div id="otpModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-md rounded-3xl p-8 relative animate-fadeIn">

        <button onclick="closeOTP()"
            class="absolute top-4 right-4 text-slate-400 hover:text-red-500 text-xl">✕</button>

        <h3 class="text-2xl font-serif text-center mb-4">
            Verify Your Email
        </h3>

        <p class="text-slate-500 text-center mb-6">
            Enter the OTP sent to your email.
        </p>

        <form id="otpForm" class="space-y-4">

            <input type="text" name="otp" placeholder="Enter OTP"
                class="w-full px-4 py-3 bg-slate-100 rounded-xl text-center tracking-widest text-lg">

            <button type="submit"
                class="w-full py-3 bg-slate-900 text-white rounded-xl hover:bg-orange-600 transition">
                VERIFY OTP
            </button>

        </form>

    </div>
</div>


<!-- LOADER -->
<div id="loader"
     class="fixed inset-0 bg-white/70 hidden items-center justify-center z-[60]">
    <div class="w-16 h-16 border-4 border-orange-400 border-t-transparent rounded-full animate-spin"></div>
</div>


<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}
.animate-fadeIn {
    animation: fadeIn 0.3s ease-in-out;
}
</style>


<script>

function showLoader() {
    document.getElementById('loader').classList.remove('hidden');
    document.getElementById('loader').classList.add('flex');
}

function hideLoader() {
    document.getElementById('loader').classList.add('hidden');
}

function openOTP() {
    document.getElementById('otpModal').classList.remove('hidden');
    document.getElementById('otpModal').classList.add('flex');
}

function closeOTP() {
    document.getElementById('otpModal').classList.add('hidden');
}

// REGISTER
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    showLoader();

    const formData = Object.fromEntries(new FormData(this).entries());

    const response = await fetch('/api/auth/register', {
        method: 'POST',
        headers: { 'Accept': 'application/json','Content-Type': 'application/json' },
        body: JSON.stringify(formData)
    });

    const data = await response.json();
    hideLoader();

    if (data.status) {
        localStorage.setItem('verify_email', formData.email);
        openOTP();
    } else {
        alert(data.message || "Registration failed");
    }
});

// OTP VERIFY
document.getElementById('otpForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    showLoader();

    const response = await fetch('/api/auth/verify-otp', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            email: localStorage.getItem('verify_email'),
            otp: document.querySelector('[name="otp"]').value
        })
    });

    const data = await response.json();
    hideLoader();

    if (data.status) {
        alert("Verified Successfully!");
        window.location.href = "/login";
    } else {
        alert(data.message || "Invalid OTP");
    }
});

</script>

@endsection