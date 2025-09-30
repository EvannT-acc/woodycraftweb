<?php

namespace App\Http\Controllers;

use App\Models\Puzzle;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class PuzzleController extends Controller
{
    public function dashboard()
    {
        $categories = Categorie::withCount('puzzles')->with(['puzzles' => function($q){ $q->latest()->limit(3);}])->get();
        return view('dashboard', compact('categories'));
    }

    public function index(Request $request)
    {
        $query = Puzzle::with('categorie');

        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function($qq) use ($q){
                $qq->where('title', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->integer('categorie_id'));
        }

        $puzzles = $query->latest()->paginate(12)->withQueryString();
        $categories = Categorie::orderBy('name')->get();
        return view('puzzles.index', compact('puzzles','categories'));
    }

    public function create()
    {
        $categories = Categorie::orderBy('name')->pluck('name','id');
        return view('puzzles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => ['required','string','max:150'],
            'description'   => ['nullable','string','max:2000'],
            'difficulty'    => ['required','integer','between:1,5'],
            'price'         => ['required','numeric','min:0'],
            'categorie_id'  => ['required', Rule::exists('categories','id')],
            'image'         => ['nullable','image','max:2048','mimes:png,jpg,jpeg,webp'],
        ]);

        // Upload image si présente
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('puzzles', 'public');
        } else {
            $data['image_path'] = 'placeholders/puzzle.png';
        }

        $puzzle = Puzzle::create($data);

        return redirect()->route('puzzles.show', $puzzle)->with('message', 'Puzzle créé avec succès.');
    }

    public function show(Puzzle $puzzle)
    {
        $puzzle->load('categorie');
        return view('puzzles.show', compact('puzzle'));
    }

    public function edit(Puzzle $puzzle)
    {
        $categories = Categorie::orderBy('name')->pluck('name','id');
        return view('puzzles.edit', compact('puzzle','categories'));
    }

    public function update(Request $request, Puzzle $puzzle)
    {
        $data = $request->validate([
            'title'         => ['required','string','max:150'],
            'description'   => ['nullable','string','max:2000'],
            'difficulty'    => ['required','integer','between:1,5'],
            'price'         => ['required','numeric','min:0'],
            'categorie_id'  => ['required', Rule::exists('categories','id')],
            'image'         => ['nullable','image','max:2048','mimes:png,jpg,jpeg,webp'],
        ]);

        if ($request->hasFile('image')) {
            // Supprime l’ancienne si ce n’est pas le placeholder
            if ($puzzle->image_path && $puzzle->image_path !== 'placeholders/puzzle.png') {
                Storage::disk('public')->delete($puzzle->image_path);
            }
            $data['image_path'] = $request->file('image')->store('puzzles', 'public');
        }

        $puzzle->update($data);

        return redirect()->route('puzzles.show', $puzzle)->with('message', 'Puzzle mis à jour.');
    }

    public function destroy(Puzzle $puzzle)
    {
        if ($puzzle->image_path && $puzzle->image_path !== 'placeholders/puzzle.png') {
            Storage::disk('public')->delete($puzzle->image_path);
        }
        $puzzle->delete();

        return redirect()->route('puzzles.index')->with('message', 'Puzzle supprimé.');
    }

    public function exportPdf(Request $request)
    {
        $puzzles = Puzzle::with('categorie')->orderBy('title')->get();
        $pdf = Pdf::loadView('puzzles.pdf', compact('puzzles'))->setPaper('a4', 'portrait');
        return $pdf->download('puzzles.pdf');
    }
}
