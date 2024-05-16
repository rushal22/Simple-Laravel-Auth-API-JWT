<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Reset Your Password</h1>

    Click the following link to reset your password: <br>

    <a href="{{ $url }}">Click Here</a>

    <br>If you did not request a password reset, no further action is required.

    <br>Thanks,
    {{ config('app.name') }}
</body>
</html>
