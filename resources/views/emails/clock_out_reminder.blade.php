<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Clock Out Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f9f9f9; padding:20px;">
    <div style="max-width:600px; margin:auto; background:#ffffff; padding:20px; border-radius:8px;">
        <h2>⏰ Clock Out Reminder</h2>

        <p>Hello {{ $user->name }},</p>

        <p>You have completed <strong>8 working hours</strong>.</p>

        <p>Please remember to <strong>clock out</strong>.</p>

        <br>
        <strong>HR Team</strong>
    </div>
</body>
</html>
