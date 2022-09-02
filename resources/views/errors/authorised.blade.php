<!DOCTYPE html>
<html>
    <head>
        <title>Unauthorized</title>
        <link href="https://fonts.googleapis.com/css?family=Lato:400" rel="stylesheet" type="text/css">
        <link rel="icon" href="{{ Config::get('Constant.CDN_PATH').'assets/images/favicon.ico'}}" type="image/x-icon" />
        <link rel="apple-touch-icon" sizes="144x144" href="{{ Config::get('Constant.CDN_PATH').'assets/images/apple-touch-icon-144.png'}}" />
        <link rel="apple-touch-icon" sizes="114x114" href="{{ Config::get('Constant.CDN_PATH').'assets/images/apple-touch-icon-114.png'}}" />
        <link rel="apple-touch-icon" sizes="72x72" href="{{ Config::get('Constant.CDN_PATH').'assets/images/apple-touch-icon-72.png'}}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ Config::get('Constant.CDN_PATH').'assets/images/apple-touch-icon-57.png'}}" />
        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 40px;
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title" style="color: red;">IP BLOCKED</div>
                <p style="color: red;">{!! $message !!}</p>
            </div>
        </div>
    </body>
</html>