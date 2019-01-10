<% include Header %>

$Test

<table class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Firstname</th>
            <th scope="col">Lastname</th>
            <th scope="col">Email</th>
            <th scope="col">Phone</th>
            <th scope="col">Photo</th>
        </tr>
    </thead>
    <% loop $User %>
    <tr scope="row">
        <td><p><strong>$FirstName</strong></p></td>
        <td><p><strong>$Surname</strong></p></td>
        <td><a href="mailto:$Email">$Email</a></td>
        <td><a href="tel:$Cell">$Cell</a></td>
        <td><img src="$Photo"/></td>
    </tr>
    <% end_loop %>

</table>

<% include Footer %>
