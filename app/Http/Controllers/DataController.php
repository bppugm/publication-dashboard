<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Data;
use App\Models\User;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'categories' => 'nullable|array',
            'categories.*' => 'string',
            'user' => 'nullable|integer',
        ]);

        $data = new Data;
        $query = request()->query();

        if ($request->filled('user')) {
            $query['user'] = $request->user()->cannot('viewAny', Data::class) ? $request->user()->id : $request->user;
        }
        if ($request->filled('me')) {
            $query['user'] = $request->user()->id;
        }

        $data = $data->filter($query)
        ->orderBy('name')->with(['categories' => function ($query) {
            $query->select('categories.id', 'categories.name', 'colour')->orderBy('name');
        }, 'user' => function ($query) {
            $query->select('id', 'name')->orderBy('name');
        }])->paginate(10)->withQueryString();

        if ($request->wantsJson()) {
            return $data;
        }

        $categories = [];
        if ($request->filled('categories')) {
            $categories = collect($request->categories)->map(fn ($item) => ['name' => $item])->toArray();
        }

        $user = null;
        if ($request->filled('user')){
            $user = User::where('id', $request->user)->first();
        }

        return view('data.index', compact('data', 'categories', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Data::class);

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:250',
            'value' => 'nullable',
            'notes' => 'nullable|string|max:500',
            'user_id' => 'nullable|integer',
            'categories' => 'nullable|array',
        ]);

        $data = Data::create($request->only('name', 'description', 'value', 'notes'));
        if ($request->has('categories')) {
            $data->categories()->sync($request->categories);
        };
        // if request has user_id, attach to user
        if ($request->has('user_id')) {
            $data->user()->associate($request->user_id);
            $data->save();
        }

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Data  $data
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Data $data)
    {
        $this->authorize('view', $data->load('categories','user'));

        if ($request->wantsJson()) {
            return $data;
        }

        $activities = $data->activities()->latest()->with('causer')->paginate();

        return view('data.show', compact('data', 'activities'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Data  $data
     * @return \Illuminate\Http\Response
     */
    public function edit(Data $data)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Data  $data
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Data $data)
    {
        $this->authorize('update', $data);

        $update = $request->validate([
            'name' => 'string|max:100',
            'description' => 'nullable|string|max:250',
            'value' => 'nullable',
            'notes' => 'nullable|string|max:500',
            'categories' => 'nullable|array',
            'user_id' => 'nullable|integer'
        ]);

        $data->update(request()->only('name', 'description', 'value', 'notes'));
        if ($request->has('categories')) {
            $data->categories()->sync($request->categories);
        };

        // if superadmin, attach data to user if request has user_id
        if ($request->user()->can('create', Data::class) && $request->has('user_id')) {
                $data->user()->associate($request->user_id);
                $data->save();
        }

        return $data->fresh()->load('categories', 'user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Data  $data
     * @return \Illuminate\Http\Response
     */
    public function destroy(Data $data)
    {
        $this->authorize('delete', $data);

        $data->delete();

        return response()->noContent();
    }
}
