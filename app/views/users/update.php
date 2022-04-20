<a href="/admin/dashboard"><h3>Return to Dashboard</h3></a>
<a href="/admin/employees"><h3>Return to Employees list</h3></a>

<form id="user_create_form">
    <input name="id" type="hidden" value="<?= $data['user']['id'] ?>">
    <label for="fname">Name:</label><br>
    <input type="text" name="name" value="<?= $data['user']['name'] ?>"><br><br><br>

    <label for="lname">Email:</label><br>
    <input type="text" name="email" value="<?= $data['user']['email'] ?>"><br><br><br>

    <label for="lname">Contact:</label><br>
    <input type="text" name="contact" value="<?= $data['user']['contact'] ?>"><br><br><br>

    <label for="lname">Status:</label><br>

    <select name="status_id" id="status">
        <option value="">Select below</option>
        <option <?php if($data['user']['status_id']==1){ echo 'selected'; } ?> value="1">Active</option>
        <option <?php if($data['user']['status_id']==0){ echo 'selected'; } ?> value="0">Inactive</option>
    </select><br><br><br>


    <label for="lname">User type:</label><br>

    <select name="user_type" id="user_type">
        <option value="">Select below</option>
        <option <?php if($data['user']['user_type']==1){ echo 'selected'; } ?> value="1">Admin</option>
        <option <?php if($data['user']['user_type']==2){ echo 'selected'; } ?> value="2">Employee</option>
    </select><br><br><br>

    <div id="company_reportee">
        <label for="lname">Select Company:</label><br>

        <select name="company_id" id="company_id">
            <option value="">Select below</option>

            <?php
            foreach($data['companies'] as $company){
                ?>
                <option <?php if($data['user']['company_id']==$company['id']){ echo 'selected'; } ?> value="<?=$company['id']?>"><?=$company['company_name']?></option>';
            <?php }
            ?>
        </select><br><br><br>

        <label for="lname">Reports to:</label><br>

        <select name="reports_to" id="reports_to">
            <option value="">Select company first</option>
        </select><br><br><br><br>
    </div>


    <input id="submit" type="submit" value="Update">
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
                    if(data[i].id != <?= $data['user']['id'] ?>) {
                        reports_to.append('<option id=' + data[i].id + ' value=' + data[i].id + '>' + data[i].name + '</option>');
                    }

                }
            }
        });

    });


    var id = $('#company_id').find(':selected')[0].value;
    $.ajax({
        type: 'POST',
        url: '/user/get_users_by_company_id?id='+id,
        success: function (data) {
            data = JSON.parse(data);
            var reports_to = $('#reports_to');
            reports_to.empty();
            for (var i = 0; i < data.length; i++) {
                if(data[i].id != <?= $data['user']['id'] ?>) {
                    reports_to.append('<option id=' + data[i].id + ' value=' + data[i].id + '>' + data[i].name + '</option>');
                }
            }
            $('#reports_to').val(<?php echo $data['user']['reports_to'] ?>);
            $('#reports_to').change();
        }
    });


    $('#user_type').on('change', function() {
        var user_type = $(this).find(':selected')[0].value;
        if(user_type==1){
            $('#company_reportee').hide();
        }else{
            $('#company_reportee').show();
        }
    });

    if(<?= $data['user']['user_type']?> == 1){
        $('#company_reportee').hide();
    }else{
        $('#company_reportee').show();
    }


    $('#submit').on('click', function(e) {
        e.preventDefault();
        var datastring = $("#user_create_form").serialize();
        $.ajax({
            type: "POST",
            url: "/user/update_post",
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