<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Test</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

        </style>
    </head>
    <body>
        <button onclick="test()">A</button>
    </body>
    <div id="b">
    </div>
</html>
<script>
    function test() {
        var xhReq = new XMLHttpRequest();
        xhReq.open('GET', 'https://nowdialogue.com/api/merchant/48/widget/presets/86', false);
        xhReq.send(null);
        var serverResponse = xhReq.responseText;
        document.getElementById("b").innerHTML = serverResponse;
    }
</script>
