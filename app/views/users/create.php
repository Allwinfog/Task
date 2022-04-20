<a href="/admin/dashboard"><h3>Return to Dashboard</h3></a>
<a href="/admin/employees"><h3>Return to Employees list</h3></a>


<form id="user_create_form">
    <label for="fname">Name:</label><br>
    <input type="text" name="name" value="Allwin1"><br><br><br>

    <label for="lname">Email:</label><br>
    <input type="text" name="email" value="allwinfog@gmail.com"><br><br><br>

    <label for="lname">Password:</label><br>
    <input type="text" name="password" value="allwin"><br><br><br>

    <label for="lname">Contact:</label><br>
    <input type="text" name="contact" value="1234567890"><br><br><br>


    <label for="lname">Status:</label><br>

    <select name="status_id" id="status">
        <option value="">Select below</option>
        <option selected value="1">Active</option>
        <option value="0">Inactive</option>
    </select><br><br><br>


    <label for="lname">User type:</label><br>

    <select name="user_type" id="user_type">
        <option value="">Select below</option>
        <option value="1">Admin</option>
        <option value="2">Employee</option>
    </select><br><br><br>

    <div id="company_reportee">
        <label for="lname">Select Company:</label><br>

        <select name="company_id" id="company_id">
            <option value="">Select below</option>

            <?php
            foreach($data['companies'] as $company){
                echo '<option value="'.$company['id'].'">'.$company['company_name'].'</option>';
            }
            ?>
        </select><br><br><br>

        <label for="lname">Reports to:</label><br>

        <select name="reports_to" id="reports_to">
            <option value="">Select company first</option>
        </select><br><br><br><br>
    </div>


    <input id="submit" type="submit" value="Create">
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>

    $('#company_id').on('change', function() {
        var id = $(this).find(':selected')[0].value;
        $.ajax({
            type: 'POST',
            url: '/user/get_users_by_company_id?id='+id,
            success: function (data) {
                data = JSON.parse(data);
                var reports_to = $('#reports_to');
                reports_to.empty();
                for (var i = 0; i < data.length; i++) {
                    reports_to.append('<option id=' + data[i].id + ' value=' + data[i].id + '>' + data[i].name + '</option>');
                }
            }
        });

    });

    $('#user_type').on('change', function() {
        var user_type = $(this).find(':selected')[0].value;
        if(user_type==1){
            $('#company_reportee').hide();
        }else{
            $('#company_reportee').show();
        }
    });

    $('#submit').on('click', function(e) {
        e.preventDefault();
        var datastring = $("#user_create_form").serialize();
        $.ajax({
            type: "POST",
            url: "/user/create_post",
            data: datastring,
            dataType: "json",
            success: function(data) {
                window.location.href = "/admin/employees";
            },
            error: function(data) {
                alert(data.responseJSON.status_message);
            }
        });
    });



</script>