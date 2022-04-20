<a href="/admin/dashboard"><h3>Return to Dashboard</h3></a>
<a href="/admin/companies"><h3>Return to Companies list</h3></a>


<form id="company_create_form">
    <label for="fname">Company Name:</label><br>
    <input type="text" name="company_name" value="ABC"><br><br><br>

    <label for="lname">Company Code:</label><br>
    <input type="text" name="company_code" value="123"><br><br><br>

    <label for="lname">Status:</label><br>
    <select name="status_id" id="status">
        <option value="">Select below</option>
        <option selected value="1">Active</option>
        <option value="0">Inactive</option>
    </select><br><br><br>

    <input id="submit" type="submit" value="Create">
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $('#submit').on('click', function(e) {
        e.preventDefault();
        var datastring = $("#company_create_form").serialize();
        $.ajax({
            type: "POST",
            url: "/company/create_post",
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