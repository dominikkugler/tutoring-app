<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserTeaching;
use App\Models\Category;


class TutorTeachingController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('teachings.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'rate' => 'required|numeric|min:0',
        ]);

        UserTeaching::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'rate' => $request->rate,
        ]);

        return redirect()->route('tutor.dashboard')->with('success', 'Teaching added successfully!');
    }

    public function destroy(UserTeaching $teaching)
    {
        $teaching->delete();
        return redirect()->route('tutor.dashboard')->with('success', 'Teaching removed successfully!');
    }

}
