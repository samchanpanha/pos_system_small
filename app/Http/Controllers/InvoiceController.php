<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\Product;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Invoice::leftJoin('users', 'users.id' , '=','invoices.user_id')
            ->get(['invoices.*','user.name, user.role']);
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
        $request->validate([
            'total' => 'required',
            'receive_usd' => 'required',
            'receive_riel' => 'required',
            'total_profit' => 'required',
            'user_id' => 'required'
        ]);

        //get last record
        $record = Invoice::latest()->first();
        if (!empty($record)){
            $expNum = explode('-', $record->invoice_number);
            $nextInvoiceNumber = $expNum[0].'-'. (intval($expNum[1])+1);
        } else {
            $nextInvoiceNumber = date('Y').'-0001';
        }


        $invoice = new Invoice();

        $invoice->invoice_number = $nextInvoiceNumber;
        $invoice->date = now();
        $invoice->total = $request->total;
        $invoice->discount =  "0";
        $invoice->discount_type = "%";
        $invoice->receive_usd =  $request->receive_usd;
        $invoice->receive_riel =  $request->receive_riel;
        $invoice->total_profit = $request->total_profit;
        $invoice->user_id = $request->user_id;
        $invoice->save();

        $invoice = Invoice::latest('id')->first();

        foreach ($request->productDetails as $index => $value){
            $invoiceDetails = new InvoiceDetails();
            $invoiceDetails->invoice_id = $invoice->id;
            $invoiceDetails->product_id = $value['id'];
            $invoiceDetails->qty = $request->detailsQty[$index]['newQty'];
            $invoiceDetails->price = $value['price'];
            $invoiceDetails->total = $request->detailsQty[$index]['amount'];
            $invoiceDetails->discount = "0";
            $invoiceDetails->discount_type = "%";

            // cut stock
            $product = Product::find($value['id']);
            $product->qty = $product->qty - $request->detailsQty[$index]['newQty'];

            $product->save();
            $invoiceDetails->save();
        }

        return response(['massage' => "Transaction Pay SuccessFul" ],201);
    }

    function generate_numbers($start, $count, $digits) {
        $result = array();
        for ($n = $start; $n < $start + $count; $n++) {

            $result[] = str_pad($n, $digits, "0", STR_PAD_LEFT);

        }
        return $result;
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return response()->json(['id' => $id],Response::HTTP_OK);
    }
}
