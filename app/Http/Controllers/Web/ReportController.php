<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('web.reports.index');
    }

    public function search(Request $request)
    {

        // return $request;
        if ($request->rdio == 1) {
            // في حالة البحث بدون التاريخ
            if ($request->type && $request->start_at == '' && $request->end_at == '') {
                $invoices = Invoice::where('status', $request->type)->get();
                $type = $request->type;
                return view('web.reports.index', compact('type'))->withDetails($invoices);
            }
            // في حالة البحث بتاريخ
            else {

                $start_at = date($request->start_at);
                $end_at = date($request->end_at);
                $invoices = Invoice::whereBetween('invoice_Date', [$start_at, $end_at])->where('status', $request->type)->get();
                $type = $request->type;

                return view('web.reports.index', compact('type'))->withDetails($invoices);
            }
        } else {
            $invoices = Invoice::where('invoice_number', $request->invoice_number)->get();
            $type = $request->type;
            return view('web.reports.index', compact('type'))->withDetails($invoices);
        }
    }
    public function report_customers()
    {

        $sections = Section::all();
        return view('web.reports.customer', compact('sections'));
    }


    public function search_customers(Request $request)
    {
        if ($request->Section && $request->product && $request->start_at == '' && $request->end_at == '') {

            $invoices = Invoice::select('*')->where('section_id', '=', $request->Section)->where('product', '=', $request->product)->get();
            $sections = Section::all();
            return view('web.reports.customer', compact('sections'))->withDetails($invoices);
        } else {

            $start_at = date($request->start_at);
            $end_at = date($request->end_at);

            $invoices = Invoice::whereBetween('invoice_date', [$start_at, $end_at])->where('section_id', '=', $request->Section)->where('product', '=', $request->product)->get();
            $sections = Section::all();
            return view('web.reports.customer', compact('sections'))->withDetails($invoices);
        }
    }
}
