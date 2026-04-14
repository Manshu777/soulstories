<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
</head>

<body style="margin:0;padding:0;background:#f9fafb;font-family:Arial,Helvetica,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f9fafb;padding:40px 0;">
        <tr>
            <td align="center">

                <table width="500" cellpadding="0" cellspacing="0"
                    style="background:#ffffff;border-radius:12px;padding:40px;box-shadow:0 10px 25px rgba(0,0,0,0.05);">

                    <tr>
                        <td align="center" style="font-size:24px;font-weight:bold;color:#111827;">
                            SoulStories
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 0;font-size:16px;color:#374151;">
                            Hi {{ $name }},
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size:15px;color:#6b7280;line-height:1.6;">
                            Use the OTP below to verify your email address. This OTP is valid for 10 minutes.
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:30px 0;">
                            <div
                                style="display:inline-block;padding:15px 30px;background:#111827;color:#ffffff;font-size:22px;font-weight:bold;border-radius:8px;letter-spacing:4px;">
                                {{ $otp }}
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size:13px;color:#9ca3af;text-align:center;">
                            If you did not create this account, please ignore this email.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
