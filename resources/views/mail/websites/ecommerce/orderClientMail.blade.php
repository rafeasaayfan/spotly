@extends('mail.layouts.websites.main', ['websiteName' => $websiteName, 'websiteSubdomain' => $websiteSubdomain])

@section('content')
{{-- Dynamic Title Based on Action --}}
<h2 class="content-title">
    @if($action === 'new_order')
        Order Received! 📦
    @elseif($action === 'confirmed')
        Order Confirmed! 🎉
    @elseif($action === 'rejected')
        Order Update 📋
    @elseif($action === 'delivered')
        Order Delivered! ✅
    @elseif($action === 'cancelled')
        Order Cancelled 🚫
    @endif
</h2>

<p class="content-text">
    Dear Customer,
</p>

{{-- Dynamic Opening Message --}}
<p class="content-text">
    @if($action === 'new_order')
        Thank you for placing your order with us!
    @elseif($action === 'confirmed')
        Great news! Your order has been confirmed and is being prepared.
    @elseif($action === 'rejected')
        We're writing to inform you about an update regarding your order.
    @elseif($action === 'delivered')
        Your order has been successfully delivered. We hope you enjoy your purchase!
    @elseif($action === 'cancelled')
        We're writing to inform you that your order has been cancelled.
    @endif
</p>

{{-- Common: Order Information Card --}}
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
    <tr>
        <td style="padding: 24px; border-radius: 12px; border: 1px solid #E0E7FF;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td style="color: #666666; font-size: 14px; padding-bottom: 8px;">
                        Order Number
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 24px; font-weight: 700; color: #2952CC; padding-bottom: 16px;">
                        #{{ $orderNumber }}
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 16px; border-top: 1px solid #E5E7EB;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td style="color: #666666; font-size: 14px; padding-bottom: 8px;">
                                    Ordered From
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
        </td>
    </tr>
</table>

{{-- Status Alert Box --}}
@if($action === 'new_order')
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
        <tr>
            <td style="padding: 20px; background-color: #FFF9E6; border-radius: 8px; border-left: 4px solid #F59E0B;">
                <p style="margin: 0 0 8px 0; font-weight: 600; color: #92400E; font-size: 15px;">
                    ⏳ Order Pending
                </p>
                <p style="margin: 0; color: #78350F; font-size: 14px; line-height: 1.5;">
                    Your order is currently pending review. We'll notify you once it's confirmed by our team.
                </p>
            </td>
        </tr>
    </table>

@elseif($action === 'confirmed')
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
        <tr>
            <td style="padding: 20px; background-color: #ECFDF5; border-radius: 8px; border-left: 4px solid #10B981;">
                <p style="margin: 0 0 8px 0; font-weight: 600; color: #065F46; font-size: 15px;">
                    ✓ Order Confirmed
                </p>
                <p style="margin: 0; color: #047857; font-size: 14px; line-height: 1.5;">
                    Your order has been confirmed and is now being prepared for delivery.
                </p>
            </td>
        </tr>
    </table>

@elseif($action === 'rejected')
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
        <tr>
            <td style="padding: 20px; background-color: #FEF2F2; border-radius: 8px; border-left: 4px solid #EF4444;">
                <p style="margin: 0 0 8px 0; font-weight: 600; color: #991B1B; font-size: 15px;">
                    ✗ Order Rejected
                </p>
                <p style="margin: 0; color: #7F1D1D; font-size: 14px; line-height: 1.5;">
                    Unfortunately, we cannot process your order at this time. This may be due to product unavailability or other restrictions.
                </p>
            </td>
        </tr>
    </table>

@elseif($action === 'delivered')
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
        <tr>
            <td style="padding: 20px; background-color: #ECFDF5; border-radius: 8px; border-left: 4px solid #10B981;">
                <p style="margin: 0 0 8px 0; font-weight: 600; color: #065F46; font-size: 15px;">
                    ✓ Successfully Delivered
                </p>
                <p style="margin: 0; color: #047857; font-size: 14px; line-height: 1.5;">
                    Your order has been delivered to your address. Thank you for your payment!
                </p>
            </td>
        </tr>
    </table>

@elseif($action === 'cancelled')
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
        <tr>
            <td style="padding: 20px; background-color: #FEF2F2; border-radius: 8px; border-left: 4px solid #EF4444;">
                <p style="margin: 0 0 8px 0; font-weight: 600; color: #991B1B; font-size: 15px;">
                    ✗ Order Cancelled
                </p>
                <p style="margin: 0; color: #7F1D1D; font-size: 14px; line-height: 1.5;">
                    Your order has been cancelled as requested.
                </p>
            </td>
        </tr>
    </table>
@endif

{{-- Payment Method (Show for new_order and confirmed only) --}}
@if($action === 'new_order' || $action === 'confirmed')
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
        <tr>
            <td style="padding: 20px; background-color: #F5F7FA; border-radius: 8px;">
                <p style="margin: 0 0 12px 0; font-weight: 600; color: #292929; font-size: 15px;">
                    💰 Payment Method
                </p>
                <p style="margin: 0; color: #666666; font-size: 14px; line-height: 1.5;">
                    Cash on Delivery - You'll pay when your order is delivered to you.
                </p>
            </td>
        </tr>
    </table>
@endif

{{-- What Happens Next Section --}}
@if($action === 'new_order')
    <p class="content-text">
        <strong>What happens next?</strong>
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 16px 0;">
        <tr>
            <td style="padding: 15px 10px; background-color: #EEF2FF; border-radius: 12px;">
                <div style="width: 24px; height: 24px; background-color: #2952CC; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">1</div>
                <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Order Review</p>
                <p style="margin: 0; color: #666666; font-size: 14px;">Our team will review and confirm your order</p>
            </td>
        </tr>
        <tr>
            <td height="12" style="line-height:1px; font-size:1px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="padding: 15px 10px; background-color: #EEF2FF; border-radius: 12px;">
                <div style="width: 24px; height: 24px; background-color: #2952CC; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">2</div>
                <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Confirmation Email</p>
                <p style="margin: 0; color: #666666; font-size: 14px;">You'll receive an email once your order is confirmed</p>
            </td>
        </tr>
        <tr>
            <td height="12" style="line-height:1px; font-size:1px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="padding: 15px 10px; background-color: #EEF2FF; border-radius: 12px;">
                <div style="width: 24px; height: 24px; background-color: #2952CC; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">3</div>
                <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Delivery</p>
                <p style="margin: 0; color: #666666; font-size: 14px;">Your order will be delivered and you'll pay on delivery</p>
            </td>
        </tr>
    </table>

@elseif($action === 'confirmed')
    <p class="content-text">
        <strong>What happens next?</strong>
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 16px 0;">
        <tr>
            <td style="padding: 15px 10px; background-color: #EEF2FF; border-radius: 12px;">
                <div style="width: 24px; height: 24px; background-color: #2952CC; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">1</div>
                <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Order Preparation</p>
                <p style="margin: 0; color: #666666; font-size: 14px;">We're preparing your order for shipment</p>
            </td>
        </tr>
        <tr>
            <td height="12" style="line-height:1px; font-size:1px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="padding: 15px 10px; background-color: #EEF2FF; border-radius: 12px;">
                <div style="width: 24px; height: 24px; background-color: #2952CC; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">2</div>
                <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Delivery</p>
                <p style="margin: 0; color: #666666; font-size: 14px;">Your order will be delivered to your address</p>
            </td>
        </tr>
        <tr>
            <td height="12" style="line-height:1px; font-size:1px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="padding: 15px 10px; background-color: #EEF2FF; border-radius: 12px;">
                <div style="width: 24px; height: 24px; background-color: #2952CC; border-radius: 50%; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700;">3</div>
                <p style="margin: 6px 0 4px 0; font-weight: 600; color: #292929; font-size: 15px;">Payment on Delivery</p>
                <p style="margin: 0; color: #666666; font-size: 14px;">Pay with cash when you receive your order</p>
            </td>
        </tr>
    </table>

@elseif($action === 'rejected')
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
        <tr>
            <td style="padding: 20px; background-color: #F5F7FA; border-radius: 8px;">
                <p style="margin: 0 0 12px 0; font-weight: 600; color: #292929; font-size: 15px;">
                    💡 What You Can Do
                </p>
                <p style="margin: 0; color: #666666; font-size: 14px; line-height: 1.5;">
                    If you have any questions about why your order was rejected, please contact <strong>{{ $websiteName }}</strong> for more information.
                </p>
            </td>
        </tr>
    </table>

@elseif($action === 'delivered')
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
        <tr>
            <td style="padding: 20px; background-color: #F5F7FA; border-radius: 8px;">
                <p style="margin: 0 0 12px 0; font-weight: 600; color: #292929; font-size: 15px;">
                    ⭐ We Value Your Feedback
                </p>
                <p style="margin: 0; color: #666666; font-size: 14px; line-height: 1.5;">
                    We hope you're satisfied with your purchase! If you have any issues or concerns, please don't hesitate to reach out to us.
                </p>
            </td>
        </tr>
    </table>

@elseif($action === 'cancelled')
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 32px 0;">
        <tr>
            <td style="padding: 20px; background-color: #F5F7FA; border-radius: 8px;">
                <p style="margin: 0 0 12px 0; font-weight: 600; color: #292929; font-size: 15px;">
                    💡 Need Help?
                </p>
                <p style="margin: 0; color: #666666; font-size: 14px; line-height: 1.5;">
                    If you have any questions about this cancellation or would like to place a new order, please contact <strong>{{ $websiteName }}</strong>.
                </p>
            </td>
        </tr>
    </table>
@endif

{{-- Common: Divider --}}
<div class="divider"></div>

{{-- Common: Footer Message --}}
<p class="content-text" style="font-size: 14px; color: #666666;">
    If you have any questions about your order, please don't hesitate to contact <strong>{{ $websiteName }}</strong>.
</p>

<p class="content-text">
    @if($action === 'delivered')
        Thank you for shopping with us!<br>
    @else
        Thank you for your order!<br>
    @endif
    <strong>{{ $websiteName }}</strong>
</p>
@endsection