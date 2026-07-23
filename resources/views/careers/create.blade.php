<h1>Add Company</h1>

<form action="{{ route('careers.store') }}" method="POST">
    @csrf

    <input type="text" name="name" placeholder="Company Name"><br><br>

    <input type="url" name="website" placeholder="Website"><br><br>

    <input type="url" name="career" placeholder="Career URL"><br><br>

    <input type="text" name="industry" placeholder="Industry"><br><br>

    <input type="text" name="country" placeholder="Country"><br><br>

    <button type="submit">Save</button>
</form>