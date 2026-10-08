<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>Reset your password</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f5f8; color:#273142; font-family:Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f3f5f8; padding:32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; background-color:#ffffff; border:1px solid #e4e8ef; border-radius:8px;">
                    <tr>
                        <td style="padding:28px 32px; background-color:#36454F; border-radius:8px 8px 0 0; color:#ffffff; text-align:center;">
                            <a href="{{ url('/') }}" style="display:inline-block; text-decoration:none;">
                                <img src="cid:imru-oep-logo@imru-oep" alt="{{ $appName }} logo" width="150" style="display:block; width:150px; max-width:100%; height:auto; border:0;">
                            </a>
                            <p style="margin:5px 0 0; font-size:18px; font-weight: bold; color:#e8f1fb;">Account Security</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <h1 style="margin:0 0 20px; font-size:24px; line-height:1.3; color:#1d2939;">Reset your password</h1>
                            <p style="margin:0 0 16px; font-size:16px; line-height:1.6;">Hello {{ $name }},</p>
                            <p style="margin:0 0 24px; font-size:16px; line-height:1.6;">
                                We received a request to reset the password for your account. Use the button below to choose a new password.
                            </p>
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 24px;">
                                <tr>
                                    <td align="center" bgcolor="#36454F" style="border-radius:5px;">
                                        <a href="{{ $resetUrl }}" style="display:inline-block; padding:13px 22px; border:1px solid #36454F; border-radius:5px; color:#ffffff; font-size:16px; font-weight:bold; text-decoration:none;">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:0 0 12px; font-size:14px; line-height:1.6; color:#475467;">
                                This link expires in {{ $expiresIn }} minutes. If the button does not work, copy and paste this address into your browser:
                            </p>
                            <p style="margin:0 0 24px; font-size:14px; line-height:1.6; word-break:break-all;">
                                <a href="{{ $resetUrl }}" style="color:#36454F;">{{ $resetUrl }}</a>
                            </p>
                            <p style="margin:0; font-size:14px; line-height:1.6; color:#475467;">
                                If you did not request a password reset, you can safely ignore this email. Your password will remain unchanged.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px 32px; border-top:1px solid #e4e8ef; color:#667085; font-size:12px; line-height:1.5;">
                            This is an automated message from {{ $appName }}. Please do not reply to this email.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
