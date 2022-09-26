<?php

namespace App\Http\Controllers;

use App\Models\Data;
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
        $this->authorize('viewAny', Data::class);

        $data = Data::filter(request(['search']))
        ->orderby('name')->paginate(10)->appends(request()->query());

        if ($request->wantsJson()) {
            return $data;
        }

        return view('data.index')->with('data', $data);
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
            'description' => 'nullable|string|max:500',
            'value' => 'nullable',
            'notes' => 'nullable|string|max:250',
        ]);

        $data = Data::create($data);

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
        $this->authorize('view', $data);

        if ($request->wantsJson()) {
            return $data;
        }

        return $data;
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
        ]);

        $data->update($update);

        return $data;
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
