<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('layouts.evento.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate(Calendar::$rules);
        $calendar=Calendar::create($request->all());

    }

    /**
     * Display the specified resource.
     */
    public function show(Calendar $calendar)
    {
        $calendar= Calendar::all();
        return response()->json($calendar);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $calendar=Calendar::find($id);

        $calendar ->start=Carbon::createFromFormat('Y-m-d H:i:s', $calendar->start)->format('Y-m-d');
        $calendar ->end=Carbon::createFromFormat('Y-m-d H:i:s', $calendar->end)->format('Y-m-d');

        return response()->json($calendar);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Calendar $calendar)
    {
        request()->validate(Calendar::$rules);
        $calendar->update($request->all());
        return response()->json($calendar);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $calendar=Calendar::find($id)->delete();
        return response()->json($calendar);
    }
}
