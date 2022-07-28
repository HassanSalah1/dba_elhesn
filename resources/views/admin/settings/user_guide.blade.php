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
    <link href="{{url('/css/dropify.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{url('/css/custom/fancybox.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .dropify-message p {
            line-height: 3.5rem !important;
            font-size: 22px !important;
        }
    </style>
@endsection

@section('form_input')
    <div class="mb-1" id="dropify_image">

    </div>

    <div class="mb-1">
        <label class="form-label" for="description_ar">{{trans('admin.description_ar')}}</label>
        <textarea id="description_ar" name="description_ar"
                  class="form-control dt-post"
                  placeholder="{{trans('admin.description_ar')}}"
        ></textarea>
    </div>

    <div class="mb-1">
        <label class="form-label" for="description_en">{{trans('admin.description_en')}}</label>
        <textarea id="description_en" name="description_en"
                  class="form-control dt-post"
                  placeholder="{{trans('admin.description_en')}}"
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
                                {{trans('admin.add_user_guide')}}
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
    <script src="{{url('/js/scripts/custom/dropify.min.js')}}"></script>
    <script src="{{url('/js/scripts/custom/fancybox.min.js')}}"></script>
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
                title: '{{trans('admin.add_user_guide')}}',
                dropify: true
            });

            onClose();

            loadDataTables('{{ url("/admin/user/guide/data", [] , env('APP_ENV') === 'local' ?  false : true)}}',
                ['image', 'description', 'actions'], '',
                {
                    'show': '{{trans('admin.show')}}',
                    'first': '{{trans('admin.first')}}',
                    'last': '{{trans('admin.last')}}',
                    'filter': '{{trans('admin.filter')}}',
                    'filter_type': '{{trans('admin.type_filter')}}',
                    fancy: true
                });

            $('#general-form').submit(function (e) {
                e.preventDefault();
                sendModalAjaxRequest(this, '{{url('/admin/user/guide/add', [] , env('APP_ENV') === 'local' ?  false : true)}}',
                    '{{url('/admin/user/guide/edit', [] , env('APP_ENV') === 'local' ?  false : true)}}', {
                        error_message: '{{trans('admin.general_error_message')}}',
                        error_title: '',
                        loader: true,
                    });
            });

        });

        function initDropify(image = null) {
            let html = '<label class="control-label" for="image">'+
                '{{trans('admin.image')}}</label>'+
                '<input name="image" type="file" class="dropify" data-default-file="'+(image ? image : '')+'" '+
                'data-max-file-size="3M" data-allowed-file-extensions="png jpg jpeg"/>';
            $('#dropify_image').html(html);
            $('.dropify').dropify({
                messages: {
                    'default': '{{trans('admin.dropify_default')}}',
                    'replace': '{{trans('admin.dropify_replace')}}',
                    'remove': '{{trans('admin.dropify_remove')}}',
                    'error': '{{trans('admin.dropify_error')}}'
                },
                error: {
                    'fileSize': '{{trans('admin.dropify_error')}}',
                }
            });
        }

        function editUserGuide(item) {
            var id = $(item).attr('id');
            var form = new FormData();
            form.append('id', id);
            $('.modal-title').text('{{trans('admin.edit_user_guide')}}');
            pub_id = id;
            $.ajax({
                url: '{{url('/admin/user/guide/data', [] , env('APP_ENV') === 'local' ?  false : true)}}',
                method: 'POST',
                data: form,
                processData: false,
                contentType: false,
                headers: {'X-CSRF-TOKEN': csrf_token},
                success: function (response) {
                    $('#general-form textarea[name=description_ar]').val(response.data.description_ar);
                    $('#general-form textarea[name=description_en]').val(response.data.description_en);

                    initDropify(response.data.image);
                    $('.general_modal').modal('toggle');
                    edit = true;
                    add = false;

                },
                error: function () {
                    toastr['error']('{{trans('admin.general_error_message')}}', '{{trans('admin.error_title')}}');
                }
            });

        }

        function deleteUserGuide(item) {
            ban(item, '{{url('/admin/user/guide/delete', [] , env('APP_ENV') === 'local' ?  false : true)}}', {
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
