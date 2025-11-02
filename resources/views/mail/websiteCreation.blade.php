@extends('mail.layouts.main')

@section('content')
@if($recipientType === 'admin')
<h2 class="content-title">New Website Creation Request</h2>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 24px 0;">
    <tr>
        <td style="padding: 20px; background-color: #F5F7FA; border-radius: 8px; border-left: 4px solid hsl(221 80% 40%);">
            <p style="margin: 0 0 12px 0; color: #666666; font-size: 14px;">
                Submitted by
            </p>
            <p style="margin: 0;">
                <a href="mailto:{{ $ownerEmail }}" style="color: hsl(221 80% 40%); text-decoration: none; font-weight: 600; font-size: 16px;">
                    {{ $ownerEmail }}
                </a>
            </p>
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 24px 0;">
    <tr>
        <td style="padding: 20px; border-radius: 12px; border: 1px solid #E0E7FF;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td style="padding-bottom: 8px; color: #666666; font-size: 14px;">
                        Website Name
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 18px; font-weight: 700; color: #292929;">
                        {{ $websiteName }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<p class="content-text" style="margin-top: 32px;">
    Please review this website creation request and take appropriate action.
</p>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td>
            <a href="http://127.0.0.1:8000/dashboard/websites?search={{ urlencode($websiteName) }}">
                Review in Dashboard
            </a>
        </td>
    </tr>
</table>

<div class="divider"></div>

<p class="content-text" style="font-size: 14px; color: #666666;">
    This is an automated notification sent when a new website is created on Spotly.
</p>

@elseif($recipientType === 'owner')
<h2 class="content-title">Website Created Successfully! 🎉</h2>

<p class="content-text">
    Hi there,
</p>

<p class="content-text">
    Thank you for choosing <strong>Spotly</strong>! We're excited to have you on board.
</p>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
    <tr>
        <td style="padding: 24px; border-radius: 12px; border: 1px solid #E0E7FF;">
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
                ⏳ Under Review
            </p>
            <p style="margin: 0; color: #78350F; font-size: 14px; line-height: 1.5;">
                Our team is currently reviewing your website. You'll receive another email once your website has been approved and is ready to go live.
            </p>
        </td>
    </tr>
</table>

<p class="content-text">
    <strong>What happens next?</strong>
</p>

<!-- 
    Margin does not work between <td> elements because table cells cannot have margin between them by CSS spec.
    To create space/gap between rows or cells in HTML emails, add spacing using e.g. a "spacer" row/tr between your tds/trs.
-->

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 16px 0;">
    <tr>
        <td style="padding: 15px 10px; background-color: #EEF2FF; border-radius: 12px;">
            <div style="width: 24px; height: 24px; background-color: #2952CC; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">1</div>
            <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Review Process</p>
            <p style="margin: 0; color: #666666; font-size: 14px;">Our admins will review your website submission</p>
        </td>
    </tr>
    <tr>
        <td height="12" style="line-height:1px; font-size:1px;">&nbsp;</td>
    </tr>
    <tr>
        <td style="padding: 15px 10px; background-color: #EEF2FF; border-radius: 12px;">
            <div style="width: 24px; height: 24px; background-color: #2952CC; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">2</div>
            <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Approval Notification</p>
            <p style="margin: 0; color: #666666; font-size: 14px;">You'll receive an email once approved</p>
        </td>
    </tr>
    <tr>
        <td height="12" style="line-height:1px; font-size:1px;">&nbsp;</td>
    </tr>
    <tr>
        <td style="padding: 15px 10px; background-color: #EEF2FF; border-radius: 12px;">
            <div style="width: 24px; height: 24px; background-color: #2952CC; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">3</div>
            <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Go Live</p>
            <p style="margin: 0; color: #666666; font-size: 14px;">Your website will be ready to launch with a free 3-day trial. After that, payment is required to continue.</p>
        </td>
    </tr>
</table>

<div class="divider"></div>

<p class="content-text" style="font-size: 14px; color: #666666;">
    This process typically takes 24-48 hours. If you have any questions, feel free to contact our support team.
</p>

<p class="content-text">
    Best regards,<br>
    <strong>The Spotly Team</strong>
</p>
@endif
@endsection