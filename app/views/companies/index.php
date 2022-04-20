
<a href="/admin/dashboard"><h3>Return to Dashboard</h3></a>
<a href="/company/create"><h3>Create Company</h3></a>


<br><br>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<div class="input-group">
    Search by Company code
    <div class="form-outline">
        <input type="search" id="search_input" class="form-control" />

    </div>
    <button type="button" class="btn btn-primary" onclick="get_json_data(true)">
        <label class="form-label" for="form1">Search</label>
    </button>
</div>

<div class="container">
    <h2>Employee list</h2>
    <table id="table" class="table table-striped">
        <thead>
        <tr>
            <th >ID</th>
            <th >Company Code</th>
            <th >Name</th>
            <th >Status</th>
            <th >Created on</th>
            <th >Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr class="tr_data">

        </tr>
        </tbody>
    </table>
</div>





<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

<script>
    document.addEventListener( "DOMContentLoaded", get_json_data, false );
    function get_json_data(search=false){
        var json_url = '/company/get_company_list';
        if(search){
            json_url = '/company/get_company_list?search='+$('#search_input').val();
        }

        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = JSON.parse(this.responseText);
                append_json(data);
            }
        }
        xmlhttp.open("POST", json_url, true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send();
    }

    //this function appends the json data to the table 'table'
    function append_json(data){
        console.log(data);
        var table = document.getElementById('table');
        $("#table").find("tr:not(:first)").remove();
        data.forEach(function(object) {
            var tr = document.createElement('tr');
            tr.innerHTML = '<td>' + object.id + '</td>' +
                '<td>' + object.company_code + '</td>' +
                '<td><a href="/company/employees?company_id=' + object.id + '">' + object.company_name + '</a></td>' +
                '<td>' + object.status_name + '</td>'+
                '<td>' + object.created_at + '</td>'+
                '<td><a href="/company/edit?company_id=' + object.id + '">Edit</a>&nbsp&nbsp&nbsp<a href="/company/delete_post?id=' + object.id + '"">Delete</a></td>';
            table.appendChild(tr);
        });
    }
</script>