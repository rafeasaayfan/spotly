@extends('mail.layouts.main')

@section('content')
<h2 class="content-title">
    @if($key === 'status')
        @if($status === 1)
            Website Approved! 🎉
        @else
            Website Application Update
        @endif
    @elseif($key === 'is_active')
        @if($status === 1)
            Website Activated! 🚀
        @else
            Website Status Update
        @endif
    @endif
</h2>

<p class="content-text">
    Hi {{ $ownerName }},
</p>

<!-- For Status -->
@if($key === 'status')
    @if($status === 1)
        <p class="content-text">
            Great news! Your website has been approved and is ready to go live.
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
                            <td style="font-size: 20px; font-weight: 700; color: #2952CC;">
                                {{ $websiteName }}
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
                        ✓ Approved Successfully
                    </p>
                    <p style="margin: 0; color: #047857; font-size: 14px; line-height: 1.5;">
                        Your website has been approved successfully. Welcome to the <strong>Spotly</strong> platform!
                    </p>
                </td>
            </tr>
        </table>

        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
            <tr>
                <td style="padding: 20px; background-color: #FFF9E6; border-radius: 8px; border-left: 4px solid #F59E0B;">
                    <p style="margin: 0 0 8px 0; font-weight: 600; color: #92400E; font-size: 15px;">
                        🎁 Free Trial Active
                    </p>
                    <p style="margin: 0; color: #78350F; font-size: 14px; line-height: 1.5;">
                        You now have a 3-day free trial. If no payment is made within this period, your website will automatically become inactive.
                    </p>
                </td>
            </tr>
        </table>

        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td>
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <a href="http://127.0.0.1:8000/dashboard/make-payment" style="display: inline-block; padding: 14px 28px; background-color: #3F3F46; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px;">
                                    Make a Payment
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    @else
        <p class="content-text">
            We regret to inform you that your website application could not be approved at this time.
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
                        ✗ Application Denied
                    </p>
                    <p style="margin: 0; color: #7F1D1D; font-size: 14px; line-height: 1.5;">
                        Your website has been denied.
                    </p>
                </td>
            </tr>
        </table>

        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
            <tr>
                <td style="padding: 20px; background-color: #FFF9E6; border-radius: 8px; border-left: 4px solid #F59E0B;">
                    <p style="margin: 0 0 12px 0; font-weight: 600; color: #92400E; font-size: 15px;">
                        Possible Reasons
                    </p>
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td style="padding: 8px 0; color: #78350F; font-size: 14px; line-height: 1.5;">
                                <strong>1.</strong> Submission of false or misleading data.
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color: #78350F; font-size: 14px; line-height: 1.5;">
                                <strong>2.</strong> Content that does not comply with our platform policies.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td>
                    <a href="http://127.0.0.1:8000/website-builder" style="display: inline-block; padding: 14px 28px; background-color: #3F3F46; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px;">
                        Create a New Website
                    </a>
                </td>
            </tr>
        </table>
    @endif

<!-- For the Active status -->
@elseif($key === 'is_active')
    @if($status === 1)
        <p class="content-text">
            Excellent news! Your website is now active and accessible to visitors.
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
                            <td style="font-size: 20px; font-weight: 700; color: #2952CC;">
                                {{ $websiteName }}
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
                        ✓ Activated Successfully
                    </p>
                    <p style="margin: 0; color: #047857; font-size: 14px; line-height: 1.5;">
                        Your website has been activated successfully. Welcome again to the <strong>Spotly</strong> platform!
                    </p>
                </td>
            </tr>
        </table>

        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td>
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <a href="http://127.0.0.1:8000/dashboard/make-payment" style="display: inline-block; padding: 14px 28px; background-color: #3F3F46; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px;">
                                    Make a Payment
                                </a>
                            </td>
                            <td width="12"></td>
                            <td>
                                <a href="https://{{ $websiteSubdomain }}.spotly.com" style="display: inline-block; padding: 14px 28px; background-color: #2952CC; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px;">
                                    Visit My Website
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    @else
        <p class="content-text">
            We wanted to inform you about an important status change regarding your website.
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
                        ⚠ Currently Inactive
                    </p>
                    <p style="margin: 0; color: #7F1D1D; font-size: 14px; line-height: 1.5;">
                        Your website is currently inactive.
                    </p>
                </td>
            </tr>
        </table>

        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
            <tr>
                <td style="padding: 20px; background-color: #FFF9E6; border-radius: 8px; border-left: 4px solid #F59E0B;">
                    <p style="margin: 0 0 12px 0; font-weight: 600; color: #92400E; font-size: 15px;">
                        Possible Reasons
                    </p>
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td style="padding: 8px 0; color: #78350F; font-size: 14px; line-height: 1.5;">
                                <strong>1.</strong> Payment has not been completed.
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color: #78350F; font-size: 14px; line-height: 1.5;">
                                <strong>2.</strong> Content violates our platform guidelines.
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color: #78350F; font-size: 14px; line-height: 1.5;">
                                <strong>3.</strong> Misleading or fraudulent activity detected.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td>
                    <a href="http://127.0.0.1:8000/dashboard/my-websites?search={{ $websiteName }}" style="display: inline-block; padding: 14px 28px; background-color: #3F3F46; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px;">
                        Review My Website
                    </a>
                </td>
            </tr>
        </table>
    @endif

<div class="divider"></div>
@endif

<p class="content-text" style="font-size: 14px; color: #666666;">
    If you have any questions, feel free to contact our support team.
</p>

<p class="content-text">
    Best regards,<br>
    <strong>The Spotly Team</strong>
</p>
@endsection