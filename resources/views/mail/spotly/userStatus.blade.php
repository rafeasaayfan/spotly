@extends('mail.layouts.main')

@section('content')
<h2 class="content-title">
    @if($status === 'active')
        Account Activated! 🎉
    @elseif($status === 'inactive')
        Account Status Update ⚠️
    @elseif($status === 'banned')
        Account Suspended 🚫
    @endif
</h2>

<p class="content-text">
    Hi {{ $userName }},
</p>

{{-- Active Status --}}
@if($status === 'active')
    <p class="content-text">
        Great news! Your account has been activated and you now have full access to all platform features.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
        <tr>
            <td style="padding: 20px; background-color: #ECFDF5; border-radius: 8px; border-left: 4px solid #10B981;">
                <p style="margin: 0 0 8px 0; font-weight: 600; color: #065F46; font-size: 15px;">
                    ✓ Account Active
                </p>
                <p style="margin: 0; color: #047857; font-size: 14px; line-height: 1.5;">
                    Your account is now active. Welcome back to the <strong>Spotly</strong> platform!
                </p>
            </td>
        </tr>
    </table>

    <p class="content-text">
        <strong>What you can do now:</strong>
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 16px 0;">
        <tr>
            <td style="padding: 15px 10px; background-color: #EEF2FF; border-radius: 12px;">
                <div style="width: 24px; height: 24px; background-color: #2952CC; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">✓</div>
                <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Create New Websites</p>
                <p style="margin: 0; color: #666666; font-size: 14px;">Build and launch new websites on our platform</p>
            </td>
        </tr>
        <tr>
            <td height="12" style="line-height:1px; font-size:1px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="padding: 15px 10px; background-color: #EEF2FF; border-radius: 12px;">
                <div style="width: 24px; height: 24px; background-color: #2952CC; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">✓</div>
                <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Manage Existing Websites</p>
                <p style="margin: 0; color: #666666; font-size: 14px;">Full control over all your websites and content</p>
            </td>
        </tr>
        <tr>
            <td height="12" style="line-height:1px; font-size:1px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="padding: 15px 10px; background-color: #EEF2FF; border-radius: 12px;">
                <div style="width: 24px; height: 24px; background-color: #2952CC; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">✓</div>
                <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Access All Features</p>
                <p style="margin: 0; color: #666666; font-size: 14px;">Use all platform features without restrictions</p>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top: 32px;">
        <tr>
            <td>
                <a href="http://127.0.0.1:8000/dashboard" style="display: inline-block; padding: 14px 28px; background-color: #2952CC; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px;">
                    Go to Dashboard
                </a>
            </td>
        </tr>
    </table>

{{-- Inactive Status --}}
@elseif($status === 'inactive')
    <p class="content-text">
        We wanted to inform you about an important change to your account status.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
        <tr>
            <td style="padding: 20px; background-color: #FFF9E6; border-radius: 8px; border-left: 4px solid #F59E0B;">
                <p style="margin: 0 0 8px 0; font-weight: 600; color: #92400E; font-size: 15px;">
                    ⚠ Account Inactive
                </p>
                <p style="margin: 0; color: #78350F; font-size: 14px; line-height: 1.5;">
                    Your account has been set to inactive status. Some features are now restricted.
                </p>
            </td>
        </tr>
    </table>

    <p class="content-text">
        <strong>Current Account Limitations:</strong>
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 16px 0;">
        <tr>
            <td style="padding: 15px 10px; background-color: #FEF2F2; border-radius: 12px;">
                <div style="width: 24px; height: 24px; background-color: #EF4444; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">✗</div>
                <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Cannot Create New Websites</p>
                <p style="margin: 0; color: #666666; font-size: 14px;">Creating new websites is temporarily disabled</p>
            </td>
        </tr>
        <tr>
            <td height="12" style="line-height:1px; font-size:1px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="padding: 15px 10px; background-color: #ECFDF5; border-radius: 12px;">
                <div style="width: 24px; height: 24px; background-color: #10B981; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">✓</div>
                <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Can Manage Existing Websites</p>
                <p style="margin: 0; color: #666666; font-size: 14px;">You can still manage and update your existing websites</p>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
        <tr>
            <td style="padding: 20px; background-color: #F5F7FA; border-radius: 8px;">
                <p style="margin: 0 0 12px 0; font-weight: 600; color: #292929; font-size: 15px;">
                    💡 Need Full Access?
                </p>
                <p style="margin: 0; color: #666666; font-size: 14px; line-height: 1.5;">
                    If you believe this is a mistake or would like to restore full access to your account, please contact our support team.
                </p>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td>
                <a href="http://127.0.0.1:8000/dashboard/my-websites" style="display: inline-block; padding: 14px 28px; background-color: #3F3F46; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px;">
                    Manage My Websites
                </a>
            </td>
        </tr>
    </table>

{{-- Banned Status --}}
@elseif($status === 'banned')
    <p class="content-text">
        We're writing to inform you that your account has been suspended.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
        <tr>
            <td style="padding: 20px; background-color: #FEF2F2; border-radius: 8px; border-left: 4px solid #EF4444;">
                <p style="margin: 0 0 8px 0; font-weight: 600; color: #991B1B; font-size: 15px;">
                    🚫 Account Banned
                </p>
                <p style="margin: 0; color: #7F1D1D; font-size: 14px; line-height: 1.5;">
                    Your account has been suspended and all access has been revoked.
                </p>
            </td>
        </tr>
    </table>

    <p class="content-text">
        <strong>Account Restrictions:</strong>
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 16px 0;">
        <tr>
            <td style="padding: 15px 10px; background-color: #FEF2F2; border-radius: 12px;">
                <div style="width: 24px; height: 24px; background-color: #EF4444; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">✗</div>
                <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">All Platform Access Revoked</p>
                <p style="margin: 0; color: #666666; font-size: 14px;">You cannot access the dashboard or any features</p>
            </td>
        </tr>
        <tr>
            <td height="12" style="line-height:1px; font-size:1px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="padding: 15px 10px; background-color: #FEF2F2; border-radius: 12px;">
                <div style="width: 24px; height: 24px; background-color: #EF4444; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">✗</div>
                <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">All Websites Deactivated</p>
                <p style="margin: 0; color: #666666; font-size: 14px;">Your websites have been automatically set to inactive</p>
            </td>
        </tr>
        <tr>
            <td height="12" style="line-height:1px; font-size:1px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="padding: 15px 10px; background-color: #FEF2F2; border-radius: 12px;">
                <div style="width: 24px; height: 24px; background-color: #EF4444; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">✗</div>
                <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Cannot Create or Manage Content</p>
                <p style="margin: 0; color: #666666; font-size: 14px;">All account activities are suspended</p>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
        <tr>
            <td style="padding: 20px; background-color: #FFF9E6; border-radius: 8px; border-left: 4px solid #F59E0B;">
                <p style="margin: 0 0 12px 0; font-weight: 600; color: #92400E; font-size: 15px;">
                    Possible Reasons for Suspension
                </p>
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td style="padding: 8px 0; color: #78350F; font-size: 14px; line-height: 1.5;">
                            <strong>•</strong> Violation of platform terms of service
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #78350F; font-size: 14px; line-height: 1.5;">
                            <strong>•</strong> Fraudulent or misleading activity
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #78350F; font-size: 14px; line-height: 1.5;">
                            <strong>•</strong> Harmful or prohibited content
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #78350F; font-size: 14px; line-height: 1.5;">
                            <strong>•</strong> Repeated policy violations
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
        <tr>
            <td style="padding: 20px; background-color: #F5F7FA; border-radius: 8px;">
                <p style="margin: 0 0 12px 0; font-weight: 600; color: #292929; font-size: 15px;">
                    📧 Need to Appeal?
                </p>
                <p style="margin: 0; color: #666666; font-size: 14px; line-height: 1.5;">
                    If you believe this suspension was made in error or would like to appeal this decision, please contact our support team with your account details.
                </p>
            </td>
        </tr>
    </table>
@endif

<div class="divider"></div>

<p class="content-text" style="font-size: 14px; color: #666666;">
    If you have any questions or concerns about your account status, please feel free to contact our support team.
</p>

<p class="content-text">
    Best regards,<br>
    <strong>The Spotly Team</strong>
</p>
@endsection