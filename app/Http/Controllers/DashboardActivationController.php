<?php

namespace App\Http\Controllers;

use App\Events\DashboardActivationUpdated;
use App\Models\Dashboard;
use Illuminate\Http\Request;

class DashboardActivationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Dashboard $dashboard)
    {
        $this->authorize('update', $dashboard);
        // validate the request has id
        $request->validate([
            'id' => 'required|integer',
        ]);
        // get dashboard order where order is not null by order
        $dashboards = Dashboard::active()->orderBy('order', 'desc')->first();
        // if there is no dashboard with order then $last = 0
        $last = $dashboards ? $dashboards->order : 0;
        // next order is $last + 1
        $nextOrder = $last + 1;

        if ($nextOrder >= 7) {
            // if next order is 7 then return error
            return response()->json([
                'message' => 'You have reached the maximum number of dashboards',
            ], 422);
        }

        // Select the dashboard by id and update the order with $nextOrder
        $dashboard = Dashboard::find($request->id);
        $dashboard->order = $nextOrder;
        $dashboard->save();

        DashboardActivationUpdated::dispatch($dashboard);

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
        $dashboard->load('data');
        $url = "";
        if ($request->filled('from')) {
            $prevs = collect($request->from);
            $last = $prevs->pull($prevs->count() - 1);
            $url = route('dashboard.preview', [$last, 'from' => $prevs->toArray()]);
        }
        $hideNavbar = true;
        if ($request->filled('from') && $request->from[count($request->from) - 1] == 'home') {
            $url = route('home');
        }

        return view('dashboard.preview', compact('dashboard', 'url', 'hideNavbar'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->authorize('create', Dashboard::class);

        // validate foreach request
        $request->validate([
            '*.id' => 'required|integer',
            '*.order' => 'required|integer',
        ]);

        foreach ($request->all() as $data) {
            $dashboard = Dashboard::active()->find($data['id']);
            $dashboard->order = $data['order'];
            $dashboard->save();
        }

        DashboardActivationUpdated::dispatch($dashboard);

        return response()->noContent();
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

        // get the old order from the dashboard
        $oldOrder = $dashboard->order;
        $dashboard->order = 0; // delete the order
        $dashboard->save();

        //rearanage the order of the dashboard
        $dashboards = Dashboard::where('order', '>', $oldOrder)->orderBy('order', 'asc')->get();
        foreach ($dashboards as $dashboard) {
            $dashboard->order = $dashboard->order - 1;
            $dashboard->save();
        }

        DashboardActivationUpdated::dispatch($dashboard);

        return $dashboard;
    }
}
