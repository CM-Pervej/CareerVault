<h1>{{ $career->name }}</h1>

<p><strong>Website:</strong> {{ $career->website }}</p>

<p>
    <strong>Career Page:</strong>
    <a href="{{ $career->career }}" target="_blank">
        {{ $career->career }}
    </a>
</p>

<p><strong>Industry:</strong> {{ $career->industry }}</p>

<p><strong>Country:</strong> {{ $career->country }}</p>

<a href="{{ route('careers.index') }}">Back</a>