<!DOCTYPE html>
<html>
<body>

<h2>Hello Admin,</h2>


<p>There are some student and teacher unapproved list..</p>
<BR>
    <ul>
        @foreach ($users as $user)
        <li>Name:- {{ $user->name }}   User Type:- {{ $user->user_type }}</li>
        
        @endforeach
        
    </ul>
    
<p>Thanks,
    <BR>
        {{ config('app.name') }}
</p>

</body>
</html>
