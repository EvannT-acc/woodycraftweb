<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Liste des Puzzles</title>
    <style>
        body{ font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1{ font-size: 18px; margin-bottom: 12px; }
        table{ width:100%; border-collapse: collapse; }
        th,td{ border:1px solid #999; padding:6px; }
        th{ background:#eee; }
    </style>
</head>
<body>
<h1>Liste des Puzzles</h1>
<table>
    <thead>
    <tr>
        <th>Titre</th>
        <th>Catégorie</th>
        <th>Difficulté</th>
        <th>Prix (€)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($puzzles as $p)
        <tr>
            <td>{{ $p->title }}</td>
            <td>{{ $p->categorie?->name }}</td>
            <td>{{ $p->difficulty }}/5</td>
            <td style="text-align:right">{{ number_format($p->price,2,',',' ') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
