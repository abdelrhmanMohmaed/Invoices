<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoicesResource;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoicesController extends Controller
{
    use ApiResponseTraid;
    public function index()
    {
        $invoices = InvoicesResource::collection(Invoice::get());
        return $this->ApiResponse($invoices, 200, 'success');
    }
    public function show($id)
    {
        $invoices = Invoice::find($id);
        if ($invoices) return $this->ApiResponse(new InvoicesResource($invoices), 200, 'success');

        return $this->ApiResponse(null, 404, 'Not Found');
    }


    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'invoice_number' => 'required|string',
            'invoice_Date' => 'required|date',
            'Due_date' => 'required|date',
            'product' => 'required',
            'Section' => 'required',
            'Amount_collection' => 'nullable|',
            'Amount_Commission' => 'required|',
            'Discount' => 'required|',
            'Rate_VAT' => 'required|',
            'Value_VAT' => 'required|',
            'Total' => 'required|',
            'note' => 'required|max:1000',
            'pic' => 'required|max:10000|mimes:pdf,jpeg,jpg,png',
        ]);
        if ($validator->fails()) {

            return $this->ApiResponse(null, 401, $validator->errors());
        }

        $invoices = Invoice::create($request->all());
        if ($invoices)  return $this->ApiResponse(new InvoicesResource($invoices), 201, 'success');

        return $this->ApiResponse(null, 404, 'error');
    }
    public function update(Request $request, $id)
    {
        // $validator = Validator::make($request->all(), [
        //     'invoice_number' => 'required|string',
        //     'invoice_Date' => 'required|date',
        //     'Due_date' => 'required|date',
        //     'product' => 'required',
        //     'Section' => 'required',
        //     'Amount_collection' => 'nullable|',
        //     'Amount_Commission' => 'required|',
        //     'Discount' => 'required|',
        //     'Rate_VAT' => 'required|',
        //     'Value_VAT' => 'required|',
        //     'Total' => 'required|',
        //     'note' => 'required|max:1000',
        //     'pic' => 'required|max:10000|mimes:pdf,jpeg,jpg,png',
        // ]);
        // if ($validator->fails()) {

        //     return $this->ApiResponse(null, 401, $validator->errors());
        // }
        $invoice = Invoice::find($id);
        if (!$invoice) {
            return $this->ApiResponse(null, 404, 'Not Found');
        };

        $invoice->update($request->all());

        if ($invoice) {
            return $this->ApiResponse(new InvoicesResource($invoice), 201, 'success');
        };
    }
    public function delete($id)
    {
        $invoice = Invoice::find($id);
        if (!$invoice) {
            return $this->ApiResponse(null, 404, 'Not Found');
        };
        $invoice->delete();

        if ($invoice) {
            return $this->ApiResponse(Null, 200, 'success');
        };
    }
}
