@extends('mail.layouts.main')

@section('content')
<h2 class="content-title">Your Verification Code</h2>

<p class="content-text">
    Hello,
</p>

<p class="content-text">
    We've received a request to verify your identity. Please use the code below to complete your verification.
</p>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
    <tr>
        <td align="center">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td style="padding: 32px 48px; background-color: #EEF2FF; border-radius: 12px; border: 2px dashed #2952CC;">
                        <p style="margin: 0 0 8px 0; color: #666666; font-size: 14px; text-align: center;">
                            Your OTP Code
                        </p>
                        <p style="margin: 0; font-size: 36px; font-weight: 700; color: #2952CC; letter-spacing: 8px; text-align: center; font-family: 'Courier New', monospace;">
                            {{ $code }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
    <tr>
        <td style="padding: 20px; background-color: #FFF9E6; border-radius: 8px; border-left: 4px solid #F59E0B;">
            <p style="margin: 0 0 8px 0; font-weight: 600; color: #92400E; font-size: 15px;">
                ⏱️ Time Sensitive
            </p>
            <p style="margin: 0; color: #78350F; font-size: 14px; line-height: 1.5;">
                This code will expire in <strong>3 minutes</strong>. Please enter it promptly to complete your verification.
            </p>
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
    <tr>
        <td style="padding: 20px; background-color: #F5F7FA; border-radius: 8px;">
            <p style="margin: 0 0 12px 0; font-weight: 600; color: #292929; font-size: 15px;">
                🔒 Security Tips
            </p>
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td style="padding: 4px 0; color: #666666; font-size: 14px; line-height: 1.5;">
                        • Never share this code with anyone
                    </td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; color: #666666; font-size: 14px; line-height: 1.5;">
                        • Spotly will never ask for your code via email or phone
                    </td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; color: #666666; font-size: 14px; line-height: 1.5;">
                        • If you didn't request this code, please ignore this email
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div class="divider"></div>

<p class="content-text" style="font-size: 14px; color: #666666;">
    This is an automated message. If you did not request this verification code, please disregard this email.
</p>

<p class="content-text">
    Best regards,<br>
    <strong>The Spotly Team</strong>
</p>
@endsection