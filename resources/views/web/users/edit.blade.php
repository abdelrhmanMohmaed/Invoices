@extends('layouts.master')
@section('css')
    <!-- Internal Nice-select css  -->
    <link href="{{ URL::asset('assets/plugins/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet" />
@section('title')
    تعديل مستخدم
@stop


@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل
                مستخدم</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row">
    <div class="col-lg-12 col-md-12">

        @include('inc.success')
        @include('inc.errors')

        <div class="card">
            <div class="card-body">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-right">
                        <a class="btn btn-primary btn-sm" href="{{ route('users.index') }}">رجوع</a>
                    </div>
                </div><br>

                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    {{ method_field('patch') }}
                    @csrf
                    <div class="">
                        <div class="row mg-b-20">
                            <div class="parsley-input col-md-6" id="fnWrapper">
                                <label>اسم المستخدم: <span class="tx-danger">*</span></label>

                                <input class="form-control" value="{{ $user->name }}" name="name" id="name"
                                    type="text">
                            </div>

                            <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                                <label>البريد الالكتروني: <span class="tx-danger">*</span></label>

                                <input class="form-control" value="{{ $user->email }}" name="email" id="email"
                                    type="text">
                            </div>
                        </div>

                    </div>

                    <div class="row mg-b-20">
                        <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                            <label>كلمة المرور: <span class="tx-danger">*</span></label>

                            <input class="form-control" name="password" id="name" type="password">
                        </div>

                        <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                            <label> تاكيد كلمة المرور: <span class="tx-danger">*</span></label>

                            <input class="form-control" name="password_confirmation" id="name" type="password">
                        </div>
                    </div>

                    <div class="row row-sm mg-b-20">
                        <div class="col-lg-6">
                            <label class="form-label">حالة المستخدم</label>
                            <select name="active" id="select-beast" class="form-control  nice-select  custom-select">

                                @if ($user->active == 1)
                                    <option value="1">مفعل</option>
                                @else
                                    <option value="0">غير مفعل</option>
                                @endif
                                <option value="1">مفعل</option>
                                <option value="0">غير مفعل</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mg-b-20">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>نوع المستخدم</strong>
                                <label class="badge badge-success">{{ $user->role->name }}</label>
                                <select name="role_id" id="select-beast"
                                    class="form-control  nice-select  custom-select">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mg-t-30">
                        <button class="btn btn-main-primary pd-x-20" type="submit">تحديث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




</div>
<!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
@endsection
@section('js')

<!-- Internal Nice-select js-->
<script src="{{ URL::asset('assets/plugins/jquery-nice-select/js/jquery.nice-select.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/jquery-nice-select/js/nice-select.js') }}"></script>

<!--Internal  Parsley.min js -->
<script src="{{ URL::asset('assets/plugins/parsleyjs/parsley.min.js') }}"></script>
<!-- Internal Form-validation js -->
<script src="{{ URL::asset('assets/js/form-validation.js') }}"></script>
@endsection
