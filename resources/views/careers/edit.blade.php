<h1>Edit Company</h1>

<form action="{{ route('careers.update', $career) }}" method="POST">
    @csrf
    @method('PUT')

    <input type="text" name="name" value="{{ $career->name }}"><br><br>

    <input type="url" name="website" value="{{ $career->website }}"><br><br>

    <input type="url" name="career" value="{{ $career->career }}"><br><br>

    <input type="text" name="industry" value="{{ $career->industry }}"><br><br>

    <input type="text" name="country" value="{{ $career->country }}"><br><br>

    <button type="submit">Update</button>
</form>