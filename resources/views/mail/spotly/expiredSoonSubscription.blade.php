@extends('mail.layouts.main')

@section('content')
<h2 class="content-title">Subscription Expiring Soon ⏰</h2>

<p class="content-text">
    Hi {{ $ownerName }},
</p>

<p class="content-text">
    This is a friendly reminder that your website subscription is expiring soon.
</p>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
    <tr>
        <td style="padding: 24px; border-radius: 12px; border: 1px solid #FEF3C7;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td style="color: #666666; font-size: 14px; padding-bottom: 8px;">
                        Your Website
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 20px; font-weight: 700; color: #292929;">
                        {{ $websiteName }}
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
                ⏰ Expiring Soon
            </p>
            <p style="margin: 0; color: #78350F; font-size: 14px; line-height: 1.5;">
                Your subscription will expire in <strong>{{ $timeRemaining }}</strong>. After expiration, your website will be automatically deactivated.
            </p>
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
    <tr>
        <td style="padding: 20px; background-color: #F5F7FA; border-radius: 8px;">
            <p style="margin: 0 0 12px 0; font-weight: 600; color: #292929; font-size: 15px;">
                ⚠️ What Happens if Not Renewed?
            </p>
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td style="padding: 8px 0; color: #666666; font-size: 14px; line-height: 1.5;">
                        <strong>•</strong> Your website will become inaccessible to visitors
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666666; font-size: 14px; line-height: 1.5;">
                        <strong>•</strong> All website features will be disabled
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666666; font-size: 14px; line-height: 1.5;">
                        <strong>•</strong> Your website will be automatically set to inactive
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<p class="content-text">
    <strong>Renew Now to Keep Your Website Active:</strong>
</p>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 16px 0;">
    <tr>
        <td style="padding: 15px 10px; background-color: #EEF2FF; border-radius: 12px;">
            <div style="width: 24px; height: 24px; background-color: #2952CC; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">1</div>
            <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Make Payment</p>
            <p style="margin: 0; color: #666666; font-size: 14px;">Click the button below to renew your subscription</p>
        </td>
    </tr>
    <tr>
        <td height="12" style="line-height:1px; font-size:1px;">&nbsp;</td>
    </tr>
    <tr>
        <td style="padding: 15px 10px; background-color: #EEF2FF; border-radius: 12px;">
            <div style="width: 24px; height: 24px; background-color: #2952CC; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">2</div>
            <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Instant Renewal</p>
            <p style="margin: 0; color: #666666; font-size: 14px;">Your subscription will be renewed immediately</p>
        </td>
    </tr>
    <tr>
        <td height="12" style="line-height:1px; font-size:1px;">&nbsp;</td>
    </tr>
    <tr>
        <td style="padding: 15px 10px; background-color: #EEF2FF; border-radius: 12px;">
            <div style="width: 24px; height: 24px; background-color: #2952CC; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">3</div>
            <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Stay Active</p>
            <p style="margin: 0; color: #666666; font-size: 14px;">Your website continues running without interruption</p>
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top: 32px;">
    <tr>
        <td>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td>
                        <a href="http://127.0.0.1:8000/dashboard/make-payment?search={{ urlencode($websiteName) }}" style="display: inline-block; padding: 14px 28px; background-color: #2952CC; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px;">
                            Renew Subscription Now
                        </a>
                    </td>
                    <td width="12"></td>
                    <td>
                        <a href="http://127.0.0.1:8000/dashboard/my-websites?search={{ urlencode($websiteName) }}" style="display: inline-block; padding: 14px 28px; background-color: #3F3F46; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px;">
                            View My Website
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
    <tr>
        <td style="padding: 20px; background-color: #ECFDF5; border-radius: 8px; border-left: 4px solid #10B981;">
            <p style="margin: 0 0 8px 0; font-weight: 600; color: #065F46; font-size: 15px;">
                💡 Pro Tip
            </p>
            <p style="margin: 0; color: #047857; font-size: 14px; line-height: 1.5;">
                Renew your subscription early to ensure uninterrupted service for your website and avoid any downtime.
            </p>
        </td>
    </tr>
</table>

<div class="divider"></div>

<p class="content-text" style="font-size: 14px; color: #666666;">
    If you have any questions about your subscription or need assistance, please feel free to contact our support team.
</p>

<p class="content-text">
    Best regards,<br>
    <strong>The Spotly Team</strong>
</p>
@endsection