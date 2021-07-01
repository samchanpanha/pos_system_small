<?php

namespace App\Http\Controllers;

use App\Models\InvoiceDetails;
use Illuminate\Http\Request;

class InvoiceDetailsController extends Controller
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
        foreach ($request->productDetails as $index => $product){
            $invoiceDetails = new InvoiceDetails();
            $invoiceDetails->invoice_id = $request->invoiceId;
            $invoiceDetails->product_id = $product['id'];
            $invoiceDetails->qty = $request->detailsQty[$index]['newQty'];
            $invoiceDetails->price = $product['price'];
            $invoiceDetails->total = $request->detailsQty[$index]['amount'];
            $invoiceDetails->discount = "0";
            $invoiceDetails->discount_type = "%";
            $invoiceDetails->save();
        }

        return response(['massage' => "Inserted SuccessFull" ],201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
