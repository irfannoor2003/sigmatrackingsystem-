<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $subjectText }}</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:20px;">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" cellpadding="20" cellspacing="0" style="background:#ffffff; border-radius:8px;">

                    <tr>
                        <td>
                            <h2 style="color:#ff2ba6;">Hello 👋</h2>

                            <p style="font-size:15px; line-height:1.6; color:#333;">
                                {!! nl2br(e($content)) !!}
                            </p>

                            <hr style="margin:30px 0;">

                            <p style="font-size:14px; color:#555;">
                                Regards,<br>
                                <strong>{{ $senderName }}</strong><br>
                                {{ ucfirst($senderRole) }}
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
