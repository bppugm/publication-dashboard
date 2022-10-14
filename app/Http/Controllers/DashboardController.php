<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dashboards = Dashboard::select('id', 'name', 'description')
            ->filter(request(['search']))
            ->paginate(10)
            ->appends(request()->query());

        if (request()->wantsJson()) {
            return $dashboards;
        }

        $canManage = $request->user()->can('create', Dashboard::class);

        return view('dashboard.index', compact('dashboards', 'canManage'));
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
        $this->authorize('create', Dashboard::class);

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:300',
        ]);

        $dashboard = Dashboard::create($data);

        return $dashboard;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function show(Dashboard $dashboard, Request $request)
    {
        $this->authorize('view', $dashboard);

        $dashboard->load('data');
        $url = "";
        if ($request->filled('from')) {
            $prevs = collect($request->from);
            $last = $prevs->pull($prevs->count() - 1);
            $url = route('dashboard.show', [$last, 'from' => $prevs->toArray()]);
        }
        return view('dashboard.show', compact('dashboard', 'url'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function edit(Dashboard $dashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dashboard $dashboard)
    {
        $this->authorize('update', $dashboard);

        $data = $request->validate([
            'name' => 'string|max:100',
            'description' => 'string|max:300|nullable',
            'widgets' => 'nullable|array'
        ]);

        $dashboard->update($data);

        $dataIds = $dashboard->fresh()->extractedDataIds();
        $dashboard->data()->sync($dataIds);

        return $dashboard;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dashboard $dashboard)
    {
        $this->authorize('delete', $dashboard);

        $dashboard->delete();

        return response()->noContent();
    }

    /**
     * Display dashboard preview.
     */
    public function preview(Dashboard $dashboard, Request $request)
    {
        $dashboard->load('data');
        $url = "";
        if ($request->filled('from')) {
            $prevs = collect($request->from);
            $last = $prevs->pull($prevs->count() - 1);
            $url = route('dashboard.preview', [$last, 'from' => $prevs->toArray()]);
        }
        return view('dashboard.preview', compact('dashboard', 'url'));
    }
}
