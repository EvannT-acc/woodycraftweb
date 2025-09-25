<form method="POST" action="{{ route('puzzles.store') }}" enctype="multipart/form-data">
    @csrf
    <input type="text" name="name" placeholder="Nom">
    <input type="text" name="category" placeholder="Catégorie">
    <textarea name="description"></textarea>
    <input type="file" name="image">
    <input type="number" step="0.01" name="price" placeholder="Prix">
    <button type="submit">Créer</button>
</form>
