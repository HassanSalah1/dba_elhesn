@extends('layouts/contentLayoutMaster')

@section('title', $title)

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css')) }}">
@endsection

@section('page-style')
    <link href="{{url('/css/jquery.loader.css')}}" rel="stylesheet"/>
@endsection

@section('form_input')
    <div class="mb-1">
        <label class="form-label" for="name_ar">{{trans('admin.name_ar')}}</label>
        <input type="text" id="name_ar" name="name_ar"
                  class="form-control dt-post"
                  placeholder="{{trans('admin.name_ar')}}">
    </div>

    <div class="mb-1">
        <label class="form-label" for="name_en">{{trans('admin.name_en')}}</label>
        <input type="text" id="name_en" name="name_en"
               class="form-control dt-post"
               placeholder="{{trans('admin.name_en')}}">
    </div>

    <div class="mb-1">
        <label class="form-label" for="account_number">{{trans('admin.account_number')}}</label>
        <input type="text" id="name_en" name="account_number"
               class="form-control dt-post"
               placeholder="{{trans('admin.account_number')}}">
    </div>
@stop

@section('content')
    <!-- Basic table -->
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">
                            <button type="button" class="btn btn-primary" id="add_btn"
                                    data-bs-toggle="modal" data-bs-target=".general_modal">
                                {{trans('admin.add_bank_account')}}
                            </button>
                        </h4>
                    </div>
                    <div class="card-datatable">
                        @include('panels.table')
                    </div>
                    <!-- Modal to add new record -->
                    @include('panels.modal')
                </div>
            </div>
        </div>
    </section>
    <!--/ Basic table -->
@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.min.js')) }}"></script>

    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
@endsection
@section('page-script')
    <script src="{{url('/js/scripts/custom/jquery.loader.js')}}"></script>
    <script>
        let add = false;
        let edit = false;
        let pub_id;
        let csrf_token = '{{csrf_token()}}';
    </script>
    <script src="{{url('/js/scripts/custom/utils.js')}}"></script>
    <script>
        $(function () {



            addModal({
                title: '{{trans('admin.add_bank_account')}}',
            });

            onClose();

            loadDataTables('{{ url("/admin/bank_accounts/data", [] , env('APP_ENV') === 'local' ?  false : true)}}',
                ['name_ar', 'name_en' , 'account_number', 'actions'], '',
                {
                    'show': '{{trans('admin.show')}}',
                    'first': '{{trans('admin.first')}}',
                    'last': '{{trans('admin.last')}}',
                    'filter': '{{trans('admin.filter')}}',
                    'filter_type': '{{trans('admin.type_filter')}}',
                });

            $('#general-form').submit(function (e) {
                e.preventDefault();
                sendModalAjaxRequest(this, '{{url('/admin/bank_account/add', [] , env('APP_ENV') === 'local' ?  false : true)}}',
                    '{{url('/admin/bank_account/edit', [] , env('APP_ENV') === 'local' ?  false : true)}}', {
                        error_message: '{{trans('admin.general_error_message')}}',
                        error_title: '',
                        loader: true,
                    });
            });

        });

        function editBankAccount(item) {
            var id = $(item).attr('id');
            var form = new FormData();
            form.append('id', id);
            $('.modal-title').text('{{trans('admin.edit_bank_account')}}');
            pub_id = id;
            $.ajax({
                url: '{{url('/admin/bank_account/data', [] , env('APP_ENV') === 'local' ?  false : true)}}',
                method: 'POST',
                data: form,
                processData: false,
                contentType: false,
                headers: {'X-CSRF-TOKEN': csrf_token},
                success: function (response) {
                    $('#general-form input[name=account_number]').val(response.data.account_number);
                    $('#general-form input[name=name_ar]').val(response.data.name_ar);
                    $('#general-form input[name=name_en]').val(response.data.name_en);
                    $('.general_modal').modal('toggle');
                    edit = true;
                    add = false;

                },
                error: function () {
                    toastr['error']('{{trans('admin.general_error_message')}}', '{{trans('admin.error_title')}}');
                }
            });

        }


        function deleteBankAccount(item) {
            ban(item, '{{url('/admin/bank_account/delete', [] , env('APP_ENV') === 'local' ?  false : true)}}', {
                error_message: '{{trans('admin.general_error_message')}}',
                error_title: '{{trans('admin.error_title')}}',
                ban_title: "{{trans('admin.delete_action')}}",
                ban_message: "{{trans('admin.delete_message')}}",
                inactivate: "{{trans('admin.delete_action')}}",
                cancel: "{{trans('admin.cancel')}}"
            });
        }

        function restoreBankAccount(item) {
            ban(item, '{{url('/admin/bank_account/restore', [] , env('APP_ENV') === 'local' ?  false : true)}}', {
                error_message: '{{trans('admin.general_error_message')}}',
                error_title: '{{trans('admin.error_title')}}',
                ban_title: "{{trans('admin.restore_action')}}",
                ban_message: "{{trans('admin.restore_message')}}",
                inactivate: "{{trans('admin.restore_action')}}",
                cancel: "{{trans('admin.cancel')}}"
            });
        }
    </script>
@endsection
