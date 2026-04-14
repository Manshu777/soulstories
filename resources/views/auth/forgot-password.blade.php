@extends('layouts.app')

@section('content')
<div class="min-h-[90vh] flex items-center justify-center px-6 py-12 bg-gradient-to-br from-orange-50 via-white to-orange-100">

    <div class="max-w-md w-full bg-white rounded-[3rem] shadow-2xl shadow-orange-100/50 border border-orange-50 p-12">

        <div class="text-center mb-8">
            <h2 class="serif text-3xl text-slate-900 mb-2">Reset Password</h2>
            <p class="text-slate-500 text-sm">We’ll help you back into your sanctuary.</p>
        </div>

        <!-- STEP 1: EMAIL -->
        <form id="forgotForm" class="space-y-6">

            <div id="emailSection">
                <label class="text-xs uppercase tracking-widest font-bold text-slate-400">
                    Email Address
                </label>
                <input type="email" name="email" required
                    class="w-full mt-2 px-6 py-4 bg-slate-50 rounded-2xl focus:ring-2 focus:ring-orange-200 transition text-slate-700"
                    placeholder="your@soul.com">

                <button type="submit"
                    class="w-full mt-6 py-4 bg-slate-900 text-white rounded-2xl font-bold tracking-widest uppercase text-xs hover:bg-orange-600 transition">
                    Send OTP
                </button>
            </div>

            <!-- STEP 2: OTP -->
            <div id="otpSection" class="hidden space-y-6">

                <div>
                    <label class="text-xs uppercase tracking-widest font-bold text-slate-400">
                        Enter OTP
                    </label>
                    <input type="text" id="otpInput"
                        class="w-full mt-2 px-6 py-4 bg-slate-50 rounded-2xl focus:ring-2 focus:ring-orange-200 transition text-slate-700"
                        placeholder="6 digit OTP">
                </div>

                <button type="button" id="verifyOtpBtn"
                    class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold tracking-widest uppercase text-xs hover:bg-orange-600 transition">
                    Verify OTP
                </button>
            </div>

            <!-- STEP 3: RESET PASSWORD -->
            <div id="resetSection" class="hidden space-y-6">

                <div>
                    <label class="text-xs uppercase tracking-widest font-bold text-slate-400">
                        New Password
                    </label>
                    <input type="password" id="newPassword"
                        class="w-full mt-2 px-6 py-4 bg-slate-50 rounded-2xl focus:ring-2 focus:ring-orange-200 transition text-slate-700">
                </div>

                <div>
                    <label class="text-xs uppercase tracking-widest font-bold text-slate-400">
                        Confirm Password
                    </label>
                    <input type="password" id="confirmPassword"
                        class="w-full mt-2 px-6 py-4 bg-slate-50 rounded-2xl focus:ring-2 focus:ring-orange-200 transition text-slate-700">
                </div>

                <button type="button" id="resetPasswordBtn"
                    class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold tracking-widest uppercase text-xs hover:bg-orange-600 transition">
                    Reset Password
                </button>
            </div>

        </form>

    </div>
</div>


<!-- ALERT -->
<div id="customAlert"
     class="fixed top-6 right-6 translate-x-[120%] transition-all duration-500 z-50">
    <div id="alertBox"
         class="px-6 py-4 rounded-2xl shadow-2xl border flex items-center gap-3 min-w-[300px]">
        <span id="alertIcon" class="text-xl"></span>
        <span id="alertMessage" class="text-sm font-medium"></span>
    </div>
</div>

<script>
let userEmail = "";
let userOtp = "";

/* ================= ALERT ================= */
function showAlert(type, message) {

    const alert = document.getElementById("customAlert");
    const box = document.getElementById("alertBox");
    const icon = document.getElementById("alertIcon");
    const text = document.getElementById("alertMessage");

    text.innerText = message;

    if (type === "success") {
        box.className = "px-6 py-4 rounded-2xl shadow-2xl border flex items-center gap-3 min-w-[300px] bg-emerald-50 border-emerald-200 text-emerald-700";
        icon.innerHTML = "✓";
    } else {
        box.className = "px-6 py-4 rounded-2xl shadow-2xl border flex items-center gap-3 min-w-[300px] bg-rose-50 border-rose-200 text-rose-700";
        icon.innerHTML = "⚠";
    }

    alert.classList.remove("translate-x-[120%]");
    alert.classList.add("translate-x-0");

    setTimeout(() => {
        alert.classList.remove("translate-x-0");
        alert.classList.add("translate-x-[120%]");
    }, 3500);
}


/* ================= LOADING BUTTON ================= */
function setLoading(button, loadingText) {
    button.disabled = true;
    button.innerHTML = `
        <span class="flex items-center justify-center gap-2">
            <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="white" stroke-width="4"></circle>
                <path class="opacity-75" fill="white"
                    d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
            ${loadingText}
        </span>
    `;
}

function resetButton(button, originalText) {
    button.disabled = false;
    button.innerHTML = originalText;
}


/* ================= STEP 1 SEND OTP ================= */
document.getElementById('forgotForm').addEventListener('submit', async function(e){
    e.preventDefault();

    const button = this.querySelector("button");
    const originalText = button.innerHTML;

    setLoading(button, "Sending...");

    userEmail = this.email.value;

    try {

        const res = await fetch('/api/auth/forgot-password',{
            method:'POST',
            headers:{'Content-Type':'application/json','Accept':'application/json'},
            body:JSON.stringify({email:userEmail})
        });

        const data = await res.json();

        if(res.ok){
            showAlert("success", data.message);
            document.getElementById('emailSection').classList.add('hidden');
            document.getElementById('otpSection').classList.remove('hidden');
        }else{
            showAlert("error", data.message || "Email not found.");
        }

    } catch (err) {
        showAlert("error", "Something went wrong.");
    }

    resetButton(button, originalText);
});


/* ================= STEP 2 VERIFY OTP ================= */
document.getElementById('verifyOtpBtn').addEventListener('click', async function(){

    const button = this;
    const originalText = button.innerHTML;

    setLoading(button, "Verifying...");

    userOtp = document.getElementById('otpInput').value.trim();

    try {

        const res = await fetch('/api/auth/verify-reset-otp',{
            method:'POST',
            headers:{'Content-Type':'application/json','Accept':'application/json'},
            body:JSON.stringify({email:userEmail, otp:userOtp})
        });

        const data = await res.json();

        if(res.ok){
            showAlert("success", data.message);
            document.getElementById('otpSection').classList.add('hidden');
            document.getElementById('resetSection').classList.remove('hidden');
        }else{
            showAlert("error", data.message || "Invalid OTP.");
        }

    } catch (err) {
        showAlert("error", "Server error.");
    }

    resetButton(button, originalText);
});


/* ================= STEP 3 RESET PASSWORD ================= */
document.getElementById('resetPasswordBtn').addEventListener('click', async function(){

    const button = this;
    const originalText = button.innerHTML;

    setLoading(button, "Resetting...");

    const password = document.getElementById('newPassword').value.trim();
    const confirm = document.getElementById('confirmPassword').value.trim();

    if(password !== confirm){
        showAlert("error", "Passwords do not match.");
        resetButton(button, originalText);
        return;
    }

    try {

        const res = await fetch('/api/auth/reset-password',{
            method:'POST',
            headers:{'Content-Type':'application/json','Accept':'application/json'},
            body:JSON.stringify({
                email:userEmail,
                otp:userOtp,
                password:password,
                password_confirmation:confirm
            })
        });

        const data = await res.json();

        if(res.ok){
            showAlert("success", data.message);
            setTimeout(()=>{ window.location.href="/login"; },1500);
        }else{

            if(data.errors){
                const firstError = Object.values(data.errors)[0][0];
                showAlert("error", firstError);
            } else {
                showAlert("error", data.message || "Invalid password.");
            }

        }

    } catch (err) {
        showAlert("error", "Server error.");
    }

    resetButton(button, originalText);
});
</script>
@endsection