<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invoice_number' => 'required|string',
            'invoice_Date' => 'required|date',
            'Due_date' => 'required|date',
            'product' => 'required|exists:products,product_name',
            'Section' => 'required|exists:sections,id',
            'Amount_collection' => 'nullable|',
            'Amount_Commission' => 'required|',
            'Discount' => 'required|',
            'Rate_VAT' => 'required|',
            'Value_VAT' => 'required|',
            'Total' => 'required|',
            'note' => 'required|max:1000',
            'pic' => 'required|max:10000|mimes:pdf,jpeg,jpg,png',
        ];
    }
    public function messages()
    {
        return [
            'required' => 'هذا الحقل مطلوب',
            'string' => 'هذا الحقل يجب ان يكون حروف وارقام',
            'date' => 'هذا الحقل يجب ان يكون تاريخ',
            'pic.mimes' => 'pdf,jpeg,jpg,png هذا الحقل يجب ان يكون ',
        ];
    }
}
