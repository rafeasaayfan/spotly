@extends('mail.layouts.websites.main', ['websiteName' => $websiteName, 'websiteSubdomain' => $websiteSubdomain])

@section('content')
<h2 class="content-title">New Order Received! 🛍️</h2>

<p class="content-text">
    Great news! You've received a new order on your website.
</p>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
    <tr>
        <td style="padding: 24px; border-radius: 12px; border: 1px solid #E0E7FF;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td style="color: #666666; font-size: 14px; padding-bottom: 8px;">
                        Website
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 20px; font-weight: 700; color: #2952CC; padding-bottom: 16px;">
                        {{ $websiteName }}
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 16px; border-top: 1px solid #E5E7EB;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td style="color: #666666; font-size: 14px; padding-bottom: 8px;">
                                    Order Number
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 18px; font-weight: 700; color: #292929;">
                                    #{{ $orderNumber }}
                                </td>
                            </tr>
                        </table>
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
                ⚡ Action Required
            </p>
            <p style="margin: 0; color: #78350F; font-size: 14px; line-height: 1.5;">
                Please review this order and confirm or reject it from your dashboard.
            </p>
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top: 32px;">
    <tr>
        <td>
            <a href="http://{{ $websiteSubdomain }}.spotly.test:8000/dashboard/orders?search={{ $orderNumber }}" style="display: inline-block; padding: 14px 28px; background-color: #2952CC; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px;">
                Go to Dashboard
            </a>
        </td>
    </tr>
</table>

<div class="divider"></div>

<p class="content-text" style="font-size: 14px; color: #666666;">
    This is an automated notification sent when a new order is placed on your website.
</p>
@endsection