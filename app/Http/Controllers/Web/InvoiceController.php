<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;
use App\Models\InvoicesAttachment;
use App\Models\InvoicesDetail;
use App\Models\Product;
use App\Models\Section;
use App\Models\User;
use App\Notifications\AddInvoices;
use App\Notifications\AddInvoicesNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::active()->get();
        return view('web.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = Section::active()->get();
        $products = Product::active()->get();
        return view('web.invoices.create', compact('sections', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvoiceRequest $request)
    {
        DB::beginTransaction();
        try {
            $invoice = Invoice::create([
                'invoice_number' => $request->invoice_number,
                'invoice_date' => $request->invoice_Date,
                'due_date' => $request->Due_date,
                'product' => $request->product,
                'section_id' => $request->Section,
                'amount_collection' => $request->Amount_collection,
                'amount_commission' => $request->Amount_Commission,
                'discount' => $request->Discount,
                'rate_vat' => $request->Rate_VAT,
                'value_vat' => $request->Value_VAT,
                'total' => $request->Total,
                'status' => 'غير مدفوعه',
                'value_status' => '2',
                'note' => $request->note,
                'user' => Auth::user()->name,
            ]);

            InvoicesDetail::create([
                'invoice_id' => $invoice->id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section_id' => $request->Section,
                'status' => ' غير مدفوعه',
                'value_status' => '2',
                'note' => $request->note,
                'user' => Auth::user()->name,
            ]);

            if ($request->hasFile('pic')) {

                // $filePath = uploadImage('Attachments/' . $request->invoice_number . '/', $request->pic);
                $file = $request->file('pic');
                $file_name = $file->getClientOriginalName();
                $invoice_number = $request->invoice_number;

                $attachment = new InvoicesAttachment();
                $attachment->file_name  =  $file_name;
                $attachment->invoice_number  = $invoice_number;
                $attachment->created_by  = Auth::user()->name;
                $attachment->invoice_id  = $invoice->id;
                $attachment->save();

                $file_original_name = $request->pic->getClientOriginalName();
                $request->pic->move(public_path('Attachments/' . $invoice_number), $file_original_name);
            };
            $user =  User::first();
            // Notification::send($user, new AddInvoices($invoice));
            $user->notify(new AddInvoicesNotification($invoice));

            // Notification::route('mail', $email)->notify(new NewBooking($email));
            DB::commit();
            return redirect('invoices')->with('success', 'تم الاضافه بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
            return redirect('invoices')->with('error', 'حدث خطا ما برجاء المحاوله مره اخرى');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoices = Invoice::where('id', $id)->first();
        $sections = Section::active()->get();
        return view('web.invoices.edit', compact('invoices', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        DB::beginTransaction();
        try {
            $id = $request->invoice_id;
            $invoice = Invoice::findOrFail($id);

            $invoice->update([
                'invoice_number' => $request->invoice_number,
                'invoice_date' => $request->invoice_Date,
                'due_date' => $request->Due_date,
                'product' => $request->product,
                'section_id' => $request->Section,
                'amount_collection' => $request->Amount_collection,
                'amount_commission' => $request->Amount_Commission,
                'discount' => $request->Discount,
                'rate_vat' => $request->Rate_VAT,
                'value_vat' => $request->Value_VAT,
                'total' => $request->Total,
                'note' => $request->note,
            ]);

            InvoicesDetail::where('invoice_id', $id)->update([
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section_id' => $request->Section,
                'note' => $request->note,
            ]);

            InvoicesAttachment::where('invoice_id', $id)->update([
                'invoice_number' => $request->invoice_number,
            ]);

            DB::commit();
            return redirect('invoices')->with('success', 'تم التعديل بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'هذا المرفق تالف');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $invoice = Invoice::where('id', $request->invoice_id)->first();
        $invoiceDetail = InvoicesDetail::where('invoice_id', $request->invoice_id)->first();
        $invoiceAttachments = InvoicesAttachment::where('invoice_id', $request->invoice_id)->first();

        if (!$invoice) return back()->with('error', 'هذا القسم غير موجود');
        try {
            // return $invoiceAttachments->invoice_number;
            if (!empty($invoiceAttachments)) {
                Storage::disk('public_uploads')->deleteDirectory($invoiceAttachments->invoice_number);
            };
            $invoiceDetail->delete();
            $invoice->delete();
            return redirect('invoices')->with('success', 'تم الحذف بنجاح');
        } catch (\Exception $e) {
            return redirect('invoices')->with('error', 'حدث خطا ما برجاء المحاوله مره اخرى');
        }
    }

    public function getProducts($id)
    {
        $products = DB::table('products')
            ->where('section_id', $id)
            ->pluck('product_name', 'id');
        return json_decode($products);
    }

    public function invoicesDetails($id)
    {
        $invoice  = Invoice::where('id', $id)->first();
        $details = InvoicesDetail::where('invoice_id', $id)->get();
        $attachments = InvoicesAttachment::where('invoice_id', $id)->get();
        return view(
            'web.invoices.details',
            compact(
                'invoice',
                'details',
                'attachments'
            )
        );
    }

    public function status(Request $request)
    {
        $invoice = Invoice::where('id', $request->invoice_id)->first();
        if (!$invoice) return back()->with('error', 'هذا القسم غير موجود');
        try {
            $invoice->update([
                'active' => !$invoice->active,
            ]);
            return redirect('invoices')->with('success', 'تم الارشفه بنجاح');
        } catch (\Exception $e) {
            return redirect('invoices')->with('error', 'حدث خطا ما برجاء المحاوله مره اخرى');
        }
    }

    public function addAttachments(Request $request)
    {
        $this->validate(
            $request,
            [
                'file_name' => 'mimes:pdf,jpge.png,jpg',
            ],
            [
                'file_name.mimes' => 'pdf,jpeg,jpg,png هذا الحقل يجب ان يكون ',
            ]
        );

        try {
            $file = $request->file('file_name');
            $file_name = $file->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachment = new InvoicesAttachment();
            $attachment->file_name  =  $file_name;
            $attachment->invoice_number  = $invoice_number;
            $attachment->created_by  = Auth::user()->name;
            $attachment->invoice_id  = $request->invoice_id;
            $attachment->save();

            $file_original_name = $request->file_name->getClientOriginalName();
            $request->file_name->move(public_path('Attachments/' . $invoice_number), $file_original_name);

            return back()->with('success', 'تم الاضافه بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', ' حدث خطا ما برجاء حذف المرفق ان وجد والتجربه مره اخرى');
        }
    }

    public function open_file($invoice_number, $file_name)
    {
        try {
            $file = Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix($invoice_number . '/' . $file_name);
            return  response()->file($file);
        } catch (\Exception $e) {
            return back()->with('error', 'هذا المرفق تالف');
        }
    }

    public function get_file($invoice_number, $file_name)
    {
        try {
            $contents = Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix($invoice_number . '/' . $file_name);
            return  response()->download($contents);
        } catch (\Exception $e) {
            return back()->with('error', 'هذا المرفق تالف');
        }
    }

    public function delete_Attachment(Request $request)
    {
        InvoicesAttachment::findOrFail($request->id_file)->delete();
        Storage::disk('public_uploads')->delete($request->invoice_number . '/' . $request->file_name);

        return back()->with('success', 'تم الحذف بنجاح');
    }
    public function payment_show($id)
    {
        $invoices = Invoice::find($id);
        return view('web.invoices.status', compact('invoices'));
    }
    function payment_update($id, Request $request)
    {
        $invoice = Invoice::find($id);

        if ($request->Status === 'مدفوعة') {
            $invoice->update([
                'value_status' => 1,
                'status' => $request->Status,
                'payment_date' => $request->Payment_Date,
            ]);

            InvoicesDetail::create([
                'invoice_id' => $request->id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section_id' => $request->Section,
                'status' => $request->Status,
                'value_status' => 1,
                'note' => $request->note,
                'payment_date' => $request->Payment_Date,
                'user' => Auth::user()->name,
            ]);
        } else {
            $invoice->update([
                'value_status' => 3,
                'status' => $request->Status,
                'payment_date' => $request->Payment_Date,
            ]);

            InvoicesDetail::create([
                'invoice_id' => $request->id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section_id' => $request->Section,
                'status' => $request->Status,
                'value_status' => 3,
                'note' => $request->note,
                'payment_date' => $request->Payment_Date,
                'user' => Auth::user()->name,
            ]);
        }
        return redirect('invoices')->with('success', 'تم التعديل بنجاح');
    }

    public function invoices_paid()
    {
        $invoices = Invoice::active()->where('value_status', 1)->get();
        return view('web.invoices.paid', compact('invoices'));
    }

    public function invoices_unPaid()
    {
        $invoices = Invoice::active()->where('value_status', 2)->get();
        return view('web.invoices.unPaid', compact('invoices'));
    }

    public function invoices_partial()
    {
        $invoices = Invoice::active()->where('value_status', 3)->get();
        return view('web.invoices.partial', compact('invoices'));
    }
    public function invoices_active()
    {
        $invoices =  Invoice::where('active', 0)->get();
        return view('web.invoices.active', compact('invoices'));
    }
    public function Print_invoice($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        return view('web.invoices.print', compact('invoice'));
    }
    public function make_read_all(Request $request)
    {
        $userUnreadNotification = auth()->user()->unreadNotifications;

        if ($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
            return back();
        }
    }
    public function unreadNotifications_count()

    {
        return auth()->user()->unreadNotifications->count();
    }

    public function unreadNotifications()

    {
        foreach (auth()->user()->unreadNotifications as $notification) {

            return $notification->data['title'];
        }
    }
}
