<?php

namespace App\Http\Controllers\Backend;

use App\Facades\Image;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Kitchen;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $recipes = Recipe::query();
            return DataTables::of($recipes)
                ->addColumn('category', function ($a) {
                    return $a->category->name;
                })
                ->addColumn('kitchen', function ($a) {
                    return $a->kitchen->name;
                })
                ->editColumn('image', function ($e) {
                    return "<img src='" . $e->getImageSrc() . "' style='width: 120px' >";
                })
                ->addColumn('action', function ($a) {
                    $edit = "<a class='text-primary' href='" . route('recipe.edit', $a->id) . "'><i class='fas fa-edit'></i></a>";
                    $delete = "<button recipe_id='" . $a->id . "' id='delete' class='text-danger ms-3 bg-transparent border-0'><i class='fa-solid fa-trash'></i></button>";
                    return "<div class='d-flex justify-content-center'>" . $edit . $delete . "</div>";
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }
        return view('backend.Recipe.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $categories = Category::all();
        $kitchens = Kitchen::all();
        return view('backend.Recipe.create', compact('categories', 'kitchens'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric|min:0.1',
            'category_id' => 'required',
            'kitchen_id' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg'
        ]);
        $validated['image'] = Image::make('Recipe/', $request->file('image'));
        Recipe::create($validated);
        return redirect()->route('recipe.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function show(Recipe $recipe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Recipe $recipe)
    {
        $categories = Category::all();
        $kitchens = Kitchen::all();
        return view('backend.Recipe.edit', compact('recipe', 'kitchens', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric|min:0.1',
            'category_id' => 'required',
            'kitchen_id' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg'
        ]);
        if($request->has('image')) {
            Image::delete('Recipe',$recipe->image);
            $validated['image'] = Image::make('Recipe',$request->file('image'));
        }
        $recipe->update($validated);
        return redirect()->route('recipe.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipe $recipe)
    {
        if(!empty($recipe->image)) {
            Image::delete('Recipe',$recipe->image);
        }
        $id = $recipe->id;
        $recipe->delete();
        return response()->json($id);
    }
}
