<?php

namespace App\Http\Controllers;

use App\Models\Puzzle;
use Illuminate\Http\Request;

class PuzzleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $puzzles = Puzzle::all();
    return view('puzzles.index', compact('puzzles'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        return view('puzzles.create');
    }


    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:100',
            'category' => 'required|max:100',
            'description' => 'required|max:500',
            'price' => 'required|numeric|between:0,99.99',
    ]);

    Puzzle::create($data);

    return redirect()->route('puzzles.create')->with('message', 'Puzzle créé avec succès !');
    }

    /**
     * Display the specified resource.
     */

    public function show(Puzzle $puzzle)
    {
        return view('puzzles.show', compact('puzzle'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Puzzle $puzzle)
    {
        return view('puzzles.edit', compact('puzzle'));
    }

    /**
     * Update the specified resource in storage.
     */

     public function update(Request $request, Puzzle $puzzle)
     {
         $data = $request->validate([
             'name' => 'required|max:100',
             'category' => 'required|max:100',
             'description' => 'required|max:500',
             'price' => 'required|numeric|between:0,99.99',
         ]);
     
         $puzzle->update($data);
     
         return redirect()->route('puzzles.edit', $puzzle)->with('message', 'Puzzle mis à jour avec succès !');
     }
     

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Puzzle $puzzle)
    {
        $puzzle->delete();
    return redirect()->route('puzzles.index')->with('message', 'Puzzle supprimé avec succès !');
    }
}
