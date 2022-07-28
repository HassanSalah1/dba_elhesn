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
        <label class="form-label" for="message">{{trans('admin.message')}}</label>
        <textarea id="message" name="message" rows="10" class="form-control dt-post"
                  placeholder="{{trans('admin.message')}}"
        ></textarea>
    </div>
    <input type="hidden" name="id">
@stop

@section('content')
    <!-- Basic table -->
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">
                        </h4>
                    </div>
                    <div class="card-datatable">
                        @include('panels.table')
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ Basic table -->

    @include('panels.modal')
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
            onClose();

            loadDataTables('{{ url("/admin/contacts/data", [] , env('APP_ENV') === 'local' ?  false : true)}}',
                ['user', 'contact_type', 'message', 'actions'], '',
                {
                    'show': '{{trans('admin.show')}}',
                    'first': '{{trans('admin.first')}}',
                    'last': '{{trans('admin.last')}}',
                    'filter': '{{trans('admin.filter')}}',
                    'filter_type': '{{trans('admin.type_filter')}}',
                });

            $('#general-form').submit(function (e) {
                e.preventDefault();
                sendModalAjaxRequest(this, '{{url('/admin/contact/replay', [] , env('APP_ENV') === 'local' ?  false : true)}}',
                    '', {
                        error_message: '{{trans('admin.general_error_message')}}',
                        error_title: '',
                        loader: true,
                    });
            });
        });

        function replay(item) {
            add = true;
            $('.modal-title').text('{{trans('admin.replay_action')}}');
            resetForm($('#general-form'));
            $('#general-form input[name=id]').val($(item).attr('id'));
        }
    </script>
@endsection
