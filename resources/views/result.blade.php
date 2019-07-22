<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Result Page</title>
    <style>
        #data {

        }
    </style>
</head>
<body>
    <p id="data">Data that has been submitted: <pre id="json">{{$upper_array}}</pre></p>
    <p class="result-text">Your data has been submitted {{$spinner}} times.</p>
    <a href="{{$link}}">View your submissions!</a>
</body>
</html>