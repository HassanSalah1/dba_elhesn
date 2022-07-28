@extends('layouts/contentLayoutMaster')

@section('title', $title)

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css')) }}">

@endsection

@section('page-style')
    <link href="{{url('/css/jquery.loader.css')}}" rel="stylesheet"/>
@endsection

@section('form_input')
    <div class="mb-1">
        <label class="form-label" for="question">{{trans('admin.question_ar')}}</label>
        <input type="text" name="question_ar"
            class="form-control dt-full-name"
            id="question_ar"
            placeholder="{{trans('admin.question_ar')}}" />
    </div>

    <div class="mb-1">
        <label class="form-label" for="question_en">{{trans('admin.question_en')}}</label>
        <input type="text" name="question_en"
               class="form-control dt-full-name"
               id="question_en"
               placeholder="{{trans('admin.question_en')}}" />
    </div>

    <div class="mb-1">
        <label class="form-label" for="answer_ar">{{trans('admin.answer_ar')}}</label>
        <textarea id="answer_ar" name="answer_ar"
            class="form-control dt-post"
            placeholder="{{trans('admin.answer_ar')}}"
        ></textarea>
    </div>

    <div class="mb-1">
        <label class="form-label" for="answer_en">{{trans('admin.answer_en')}}</label>
        <textarea id="answer_en" name="answer_en"
                  class="form-control dt-post"
                  placeholder="{{trans('admin.answer_en')}}"
        ></textarea>
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
                                {{trans('admin.add_faq')}}
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
        $(function() {

            addModal({
                title: '{{trans('admin.add_faq')}}',
            });

            onClose();

            loadDataTables('{{ url("/admin/faqs/data", [] , env('APP_ENV') === 'local' ?  false : true)}}',
                ['question' , 'answer', 'actions'], '',
                {
                    'show': '{{trans('admin.show')}}',
                    'first': '{{trans('admin.first')}}',
                    'last': '{{trans('admin.last')}}',
                    'filter': '{{trans('admin.filter')}}',
                    'filter_type': '{{trans('admin.type_filter')}}',
                });

            $('#general-form').submit(function (e) {
                e.preventDefault();
                sendModalAjaxRequest(this, '{{url('/admin/faq/add', [] , env('APP_ENV') === 'local' ?  false : true)}}',
                    '{{url('/admin/faq/edit', [] , env('APP_ENV') === 'local' ?  false : true)}}', {
                        error_message: '{{trans('admin.general_error_message')}}',
                        error_title: '',
                        loader: true,
                    });
            });

        });

        function editFaq(item) {
            var id = $(item).attr('id');
            var form = new FormData();
            form.append('id', id);
            $('.modal-title').text('{{trans('admin.edit_faq')}}');
            pub_id = id;
            $.ajax({
                url: '{{url('/admin/faq/data', [] , env('APP_ENV') === 'local' ?  false : true)}}',
                method: 'POST',
                data: form,
                processData: false,
                contentType: false,
                headers: {'X-CSRF-TOKEN': csrf_token},
                success: function (response) {
                    $('#general-form input[name=question_ar]').val(response.data.question_ar);
                    $('#general-form input[name=question_en]').val(response.data.question_en);
                    $('#general-form textarea[name=answer_ar]').val(response.data.answer_ar);
                    $('#general-form textarea[name=answer_en]').val(response.data.answer_en);

                    $('.general_modal').modal('toggle');
                    edit = true;
                    add = false;

                },
                error: function () {
                    toastr['error']('{{trans('admin.general_error_message')}}', '{{trans('admin.error_title')}}');
                }
            });

        }

        function deleteFaq(item) {
            ban(item, '{{url('/admin/faq/delete', [] , env('APP_ENV') === 'local' ?  false : true)}}', {
                error_message: '{{trans('admin.general_error_message')}}',
                error_title: '{{trans('admin.error_title')}}',
                ban_title: "{{trans('admin.delete_action')}}",
                ban_message: "{{trans('admin.delete_message')}}",
                inactivate: "{{trans('admin.delete_action')}}",
                cancel: "{{trans('admin.cancel')}}"
            });
        }
    </script>
@endsection
