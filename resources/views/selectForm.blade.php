<html>

<head>
    <title>Select form</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src='http://js.jotform.com/JotForm.min.js'></script>
    <script src='http://js.jotform.com/FormPicker.min.js'></script>
</head>

<body>
    <div id="results"></div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script>
        JF.FormPicker({
            multiSelect: false,
            infinite_scroll: true,
            search: true,
            onSelect: function(r) {
                var selectedIds = [];
                for (var i = 0; i < r.length; i++) {
                    selectedIds.push(r[i].id);
                }
                console.log(selectedIds[0]);
                // $("#results").html('Selected form ids: ' + selectedIds[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '/app',
                    data: {
                        formId: selectedIds[0]
                    },
                    dataType: 'html',
                    success: function(data) { 
                        console.log("success sending and receiving data");
                        // console.log('data is: ',data);
                        /*let fields = data.form_fields;
                        fields = JSON.parse(fields);
                        console.log('parsed data is: ',fields);
                        let notSupportedFields = [];
                        // console.log(Object.keys(fields).length);
                        // console.log(typeof Object.keys(fields));
                        

                        for(let i = 0; i < Object.keys(fields).length; i++) { // TODO: LOOK AGAIN
                            // console.log('every field is:',fields[ Object.keys(fields)[i] ]);
                            // console.log('every inside field is:',fields[ Object.keys(fields)[i] ][0].isSupported ); 

                            if(fields[ Object.keys(fields)[i] ][0].isSupported == -2 && fields[ Object.keys(fields)[i] ][0].isRequired == 1){ // in case of not supported field
                                notSupportedFields.push(fields[ Object.keys(fields)[i] ][0].name);
                            }
                        };
                        let errors = "<p>The reason currently we can't autofill your form is: </p>";
                        errors += "<ul>";
                        for(let i = 0; i < notSupportedFields.length; i++){
                            errors += "<li>We currently don't support autofilling of "+ notSupportedFields[i] +" and it is required in your form.</li>";
                        }
                        errors += "</ul>";*/
                        $("#results").html(data);
                    },
                    error: function(data) {
                        var errors = data.responseText;
                        console.log(errors);
                    }
                });
            },
            onReady: function() {
                console.log('Form modal rendered');
            },
            onClose: function() {
                console.log('Form picker closed');
            },
            onLoad: function(formList, markup) {
                // $("#results").html('Form list rendered');
                console.log('All forms loaded', formList);
                console.log('Forms list HTML markup', markup);
            }
        });
    </script>
</body>

</html>