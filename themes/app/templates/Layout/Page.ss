<% include Header %>

<main class="container">
    <div class="row">
        <article class="col">
            <h1>$Title</h1>
            <p>CLICK THE GENERATE USER BUTTON BELOW</p>
            <a href="/user/randomUser"><h1>Generate User</h1></a>
        </article>

    </div>

    <% if $Form %>
        $Form
    <% end_if %>
</main>

<% include Footer %>

