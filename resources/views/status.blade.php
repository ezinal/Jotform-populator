<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Status Page</title>
    <style>
        table, th, td {
            border: 1px solid black;
        }
        form {
            margin: 1%;
        }
        .error {
            background: red;
        }
        .success {
            background: green;
        }
        td {
            text-align: center;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <th>Qid</th>
            <th>Type</th>
            <th>Is supported</th>
            <th>Is required</th>
            <th>Name</th>
            <th>Extra Parameters</th>
        </tr>
        @foreach ($fields as $ff)
            <tr class="{{$ff['isSupported'] == -2 ? 'error' : 'success'}}">
                <td>{{$ff['qid'] }}</td>
                <td>{{$ff['type'] }}</td>
                <td>{{$ff['isSupported']}}</td>
                <td>{{$ff['isRequired']}}</td>
                <td>{{$ff['name']}}</td>
                <td>{{json_encode($ff['extra'])}}</td>
            </tr>
        @endforeach
    </table>
    <form id="howManySubsForm" method="POST" action="{{ action('AutofillController@handler') }}">
        {{csrf_field()}}
        <p>Please choose how many submissions should be made:</p>
        <input type="hidden" name="fields" value="{{json_encode($fields)}}">
        <input type="hidden" name="formId" value="{{$formId}}">
        <input type="number" name="spinner" min="1" max="15" required> 
        <input type="submit" id="submit">
    </form>
    <script>
        
    </script>
</body>
</html>