@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center">

    <div class="bg-white p-10 rounded-2xl shadow-xl w-96">

        <h2 class="text-2xl font-bold mb-6 text-center">
            Verify OTP
        </h2>

        <form id="otpForm" class="space-y-4">

            <input type="email" id="email" readonly
                class="w-full px-4 py-3 bg-slate-100 rounded-xl">

            <input type="text" name="otp" placeholder="Enter OTP" required
                class="w-full px-4 py-3 bg-slate-50 rounded-xl">

            <button type="submit"
                class="w-full py-3 bg-slate-900 text-white rounded-xl hover:bg-orange-600">
                Verify
            </button>

        </form>

    </div>
</div>

<script>
document.getElementById('email').value = localStorage.getItem('verify_email');

document.getElementById('otpForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const response = await fetch('/api/auth/verify-otp', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            email: localStorage.getItem('verify_email'),
            otp: document.querySelector('[name="otp"]').value
        })
    });

    const data = await response.json();

    if (data.status) {
        alert("Verified successfully!");
        localStorage.removeItem('verify_email');
        window.location.href = "/login";
    } else {
        alert(data.message || "Invalid OTP");
    }
});
</script>

@endsection