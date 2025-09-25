@foreach($puzzles as $puzzle)
  <div>
    <h2>{{ $puzzle->name }}</h2>
    <p>{{ $puzzle->price }} €</p>
    <img src="{{ asset('storage/'.$puzzle->image) }}" width="200">
  </div>
@endforeach

{{ $puzzles->links() }}
