@extends('mail.layouts.main')

@section('content')
@if($isAdmin)
    <h2 class="content-title">Website Subscription Expired ⏰</h2>

    <p class="content-text">
        Hello Admin,
    </p>

    <p class="content-text">
        A website subscription has expired and requires attention.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
        <tr>
            <td style="padding: 24px; border-radius: 12px; border: 1px solid #FEE2E2;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td style="color: #666666; font-size: 14px; padding-bottom: 8px;">
                            Website Name
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 20px; font-weight: 700; color: #292929; padding-bottom: 16px;">
                            {{ $websiteName }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top: 16px; border-top: 1px solid #E5E7EB;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td style="color: #666666; font-size: 14px; padding-bottom: 8px;">
                                        Owner
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 16px; font-weight: 600; color: #292929;">
                                        {{ $ownerName }}
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
            <td style="padding: 20px; background-color: #FEF2F2; border-radius: 8px; border-left: 4px solid #EF4444;">
                <p style="margin: 0 0 8px 0; font-weight: 600; color: #991B1B; font-size: 15px;">
                    ⏰ Subscription Expired
                </p>
                <p style="margin: 0; color: #7F1D1D; font-size: 14px; line-height: 1.5;">
                    The subscription for this website has expired. The website will be automatically deactivated.
                </p>
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
                    Please review this website and follow up with the owner if necessary.
                </p>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td>
                <a href="http://127.0.0.1:8000/dashboard/websites?search={{ urlencode($websiteName) }}" style="display: inline-block; padding: 14px 28px; background-color: #2952CC; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px;">
                    Review Website
                </a>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <p class="content-text" style="font-size: 14px; color: #666666;">
        This is an automated notification sent when a website subscription expires on the Spotly platform.
    </p>

@else
    <h2 class="content-title">Subscription Expired ⏰</h2>

    <p class="content-text">
        Hi {{ $ownerName }},
    </p>

    <p class="content-text">
        We're writing to inform you that your website subscription has expired.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
        <tr>
            <td style="padding: 24px; border-radius: 12px; border: 1px solid #FEE2E2;">
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
            <td style="padding: 20px; background-color: #FEF2F2; border-radius: 8px; border-left: 4px solid #EF4444;">
                <p style="margin: 0 0 8px 0; font-weight: 600; color: #991B1B; font-size: 15px;">
                    ⏰ Subscription Expired
                </p>
                <p style="margin: 0; color: #7F1D1D; font-size: 14px; line-height: 1.5;">
                    Your website subscription has expired and your website has been automatically deactivated.
                </p>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
        <tr>
            <td style="padding: 20px; background-color: #FFF9E6; border-radius: 8px; border-left: 4px solid #F59E0B;">
                <p style="margin: 0 0 12px 0; font-weight: 600; color: #92400E; font-size: 15px;">
                    What This Means
                </p>
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td style="padding: 8px 0; color: #78350F; font-size: 14px; line-height: 1.5;">
                            <strong>•</strong> Your website is no longer accessible to visitors
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #78350F; font-size: 14px; line-height: 1.5;">
                            <strong>•</strong> All website features are temporarily disabled
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #78350F; font-size: 14px; line-height: 1.5;">
                            <strong>•</strong> Your data is safely stored and can be restored
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <p class="content-text">
        <strong>How to Reactivate Your Website:</strong>
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 16px 0;">
        <tr>
            <td style="padding: 15px 10px; background-color: #EEF2FF; border-radius: 12px;">
                <div style="width: 24px; height: 24px; background-color: #2952CC; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">1</div>
                <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Renew Your Subscription</p>
                <p style="margin: 0; color: #666666; font-size: 14px;">Make a payment to renew your subscription</p>
            </td>
        </tr>
        <tr>
            <td height="12" style="line-height:1px; font-size:1px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="padding: 15px 10px; background-color: #EEF2FF; border-radius: 12px;">
                <div style="width: 24px; height: 24px; background-color: #2952CC; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">2</div>
                <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Website Reactivation</p>
                <p style="margin: 0; color: #666666; font-size: 14px;">Your website will be automatically reactivated</p>
            </td>
        </tr>
        <tr>
            <td height="12" style="line-height:1px; font-size:1px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="padding: 15px 10px; background-color: #EEF2FF; border-radius: 12px;">
                <div style="width: 24px; height: 24px; background-color: #2952CC; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">3</div>
                <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Back Online</p>
                <p style="margin: 0; color: #666666; font-size: 14px;">Your website will be live and accessible again</p>
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
                                Renew Subscription
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

    <div class="divider"></div>

    <p class="content-text" style="font-size: 14px; color: #666666;">
        If you have any questions about your subscription or need assistance with renewal, please feel free to contact our support team.
    </p>
@endif

<p class="content-text">
    Best regards,<br>
    <strong>The Spotly Team</strong>
</p>
@endsection