<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <title>{{ $websiteName ?? '' }}</title>
    <style>
        /* Reset */
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .email-header {
            background-color: #f0f0f0;
            padding: 32px 40px;
            text-align: center;
            border-radius: 5px;
        }

        .email-body {
            padding: 40px;
        }

        .email-footer {
            background-color: #f0f0f0;
            padding: 32px 40px;
            text-align: center;
            font-size: 13px;
            color: #767da5;
            border-radius: 5px;
        }

        .brand-name {
            font-size: 28px;
            font-weight: 700;
            color: #1650b0;
            margin: 0;
        }

        .content-title {
            font-size: 24px;
            font-weight: 700;
            color: #292929;
            margin: 0 0 16px 0;
            line-height: 1.3;
        }

        .content-text {
            font-size: 16px;
            line-height: 1.6;
            color: #292929;
            margin: 0 0 16px 0;
        }

        .button {
            display: inline-block;
            padding: 3px 5px 3px 5px;
            background-color: #1650b0;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            margin: 24px 0;
            transition: background-color 0.2s;
        }

        .button:hover {
            background-color: #124193;
        }

        .divider {
            height: 1px;
            background-color: #767da5 !important;
            margin: 32px 0;
            opacity: 0.6;
        }

        .footer-links a {
            color: #1650b0;
            text-decoration: none;
            margin: 0 12px;
        }

        .footer-text {
            color: #767da5;
            margin: 8px 0;
        }

        /* Mobile responsive */
        @media only screen and (max-width: 600px) {

            .email-header,
            .email-body,
            .email-footer {
                padding: 24px 20px !important;
            }

            .content-title {
                font-size: 20px !important;
            }

            .content-text {
                font-size: 15px !important;
            }

            .button {
                display: block !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }
        }
    </style>
</head>

<body>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" class="email-container" width="600" cellspacing="0" cellpadding="0" border="0">

                    <!-- Header -->
                    <tr>
                        <td class="email-header">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
                                <tr>
                                    <!-- Logo Component Call -->
                                    <!-- <td valign="middle" style="padding-right: 8px;">
                                        <x-app-logo />
                                    </td> -->
                                    <!-- Brand Name -->
                                    <td valign="middle">
                                        <h1 class="brand-name" style="margin: 0; padding: 0;">{{ $websiteName }}</h1>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td class="email-body">
                            @yield('content')
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="email-footer">
                            <div class="footer-links" style="margin-bottom: 16px;">
                                <a href="http://{{ $websiteSubdomain }}.spotly.com">Visit Website</a>
                                <!-- <a href="{{ $supportUrl ?? '#' }}">Support</a> -->
                                <!-- <a href="{{ $privacyUrl ?? '#' }}">Privacy Policy</a> -->
                            </div>
                            <p class="footer-text">
                                © {{ date('Y') }} {{ $websiteName }}. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>