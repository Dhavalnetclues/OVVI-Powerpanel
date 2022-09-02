<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Oops! 404 The requested page not found</title>
        <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Arvo:wght@400;700&display=swap" rel="stylesheet">

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
                font-family: 'Arvo', serif;
            }

            .page_404{
                padding:40px 0;
                background:#fff;
                font-family: 'Arvo', serif;
                display: flex;
                align-items: center;
                height: 100vh;
            }

            .page_404 img {
                width:100%;
            }

            .four_zero_four_bg{     
                background-image: url({{ Config::get('Constant.CDN_PATH').'resources/images/404-gif.gif' }});
                background-repeat: no-repeat;
                background-size: 70%;
                height: 400px;
                background-position: center;
             }             
             
            .four_zero_four_bg h1{
                font-size:80px;
            }             
            
            .four_zero_four_bg h3{
                font-size:80px;
            }
                         
            .link_404{          
                color: #fff!important;
                padding: 10px 20px;
                background: #1d4da1;
                margin: 20px 0;
                display: inline-block;
                text-decoration: none !important;
                border-radius: 50px;
            }
            .contant_box_404{
                margin-top:-50px;
            }
        </style>
    </head>
    <body>
        <section class="page_404">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-10 text-center">
                        <div class="four_zero_four_bg">
                            <h1 class="text-center ">404</h1>
                        </div>
                
                        <div class="contant_box_404">
                            <h3 class="h2">Look like you're lost</h3>                
                            <p>the page you are looking for not avaible!</p>                
                            <a href="{{ url('/powerpanel') }}" class="link_404">Go to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>
