<a href="/admin/dashboard"><h3>Return to Dashboard</h3></a>
<a href="/admin/companies"><h3>Return to Companies list</h3></a>


<form id="company_update_form">
    <input type="hidden" name="id" value="<?= $data['company']['id'] ?>">

    <label for="fname">Company Name:</label><br>
    <input type="text" name="company_name" value="<?= $data['company']['company_name'] ?>"><br><br><br>

    <label for="lname">Company Code:</label><br>
    <input type="text" name="company_code" value="<?= $data['company']['company_code'] ?>"><br><br><br>

    <label for="lname">Status:</label><br>
    <select name="status_id" id="status">
        <option value="">Select below</option>
        <option <?php if($data['company']['status_id']==1){ echo 'selected'; } ?> value="1">Active</option>
        <option <?php if($data['company']['status_id']==0){ echo 'selected'; } ?> value="0">Inactive</option>
    </select><br><br><br>

    <input id="submit" type="submit" value="Update">
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>


    $('#submit').on('click', function(e) {
        e.preventDefault();
        var datastring = $("#company_update_form").serialize();
        $.ajax({
            type: "POST",
            url: "/company/update_post",
            data: datastring,
            dataType: "json",
            success: function(data) {
                window.location.href = "/admin/companies";
            },
            error: function(data) {
                alert(data.responseJSON.status_message);
            }
        });
    });



</script>