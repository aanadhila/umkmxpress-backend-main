<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\ShipmentItem;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ShipmentItemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $shipmentItem = Shipment::findOrFail($request->shipment_id)->items()->get();
            return DataTables::of($shipmentItem)
                ->editColumn('weight', function ($item) {
                    return $item->weight . ' Kg';
                })
                ->addColumn('total_weight', function ($item) {
                    return ($item->amount * $item->weight) . ' Kg';
                })
                ->addIndexColumn()
                ->make();
        }
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShipmentItem  $shipmentItem
     * @return \Illuminate\Http\Response
     */
    public function show(ShipmentItem $shipmentItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShipmentItem  $shipmentItem
     * @return \Illuminate\Http\Response
     */
    public function edit(ShipmentItem $shipmentItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShipmentItem  $shipmentItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShipmentItem $shipmentItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShipmentItem  $shipmentItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShipmentItem $shipmentItem)
    {
        //
    }
}
