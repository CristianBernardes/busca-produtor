<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        /* Estilos para tornar o e-mail responsivo */
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
            }
        }
    </style>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif;">
    <div class="container" style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">
        <div style="padding: 20px;">
            <p style="font-size: 24px; font-weight: bold; margin: 0;">Olá!</p>
        </div>
        <div style="padding: 20px;">
            <p style="font-size: 16px; margin: 0;">Clique no link abaixo para redefinir sua senha:</p>
            <p style="font-size: 16px; margin: 20px 0;">
                <a href="{{ $url }}"
                    style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 5px;">{{ $title }}</a>
            </p>
            <p style="font-size: 16px; margin: 0;">Att,</p>
            <p style="font-size: 16px; margin: 0;">ViaGroup Tech</p>
        </div>
    </div>
</body>

</html>
