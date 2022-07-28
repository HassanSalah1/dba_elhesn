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
        <label class="form-label" for="percent">{{trans('admin.percent')}}</label>
        <input type="number" required name="percent" min="0" max="99"
               class="form-control dt-full-name" id="percent"
               placeholder="{{trans('admin.percent')}}"/>
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
                                {{trans('admin.add_negotiation_percent')}}
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
                title: '{{trans('admin.add_negotiation_percent')}}',
            });

            onClose();

            loadDataTables('{{ url("/admin/negotiation/percents/data", [] , env('APP_ENV') === 'local' ?  false : true)}}',
                ['percent', 'actions'], '',
                {
                    'show': '{{trans('admin.show')}}',
                    'first': '{{trans('admin.first')}}',
                    'last': '{{trans('admin.last')}}',
                    'filter': '{{trans('admin.filter')}}',
                    'filter_type': '{{trans('admin.type_filter')}}',
                });

            $('#general-form').submit(function (e) {
                e.preventDefault();
                sendModalAjaxRequest(this, '{{url('/admin/negotiation/percent/add', [] , env('APP_ENV') === 'local' ?  false : true)}}',
                    '{{url('/admin/negotiation/percent/edit', [] , env('APP_ENV') === 'local' ?  false : true)}}', {
                        error_message: '{{trans('admin.general_error_message')}}',
                        error_title: '',
                        loader: true,
                    });
            });

        });

        function editNegotiationPercentage(item) {
            var id = $(item).attr('id');
            var form = new FormData();
            form.append('id', id);
            $('.modal-title').text('{{trans('admin.edit_negotiation_percent')}}');
            pub_id = id;
            $.ajax({
                url: '{{url('/admin/negotiation/percent/data', [] , env('APP_ENV') === 'local' ?  false : true)}}',
                method: 'POST',
                data: form,
                processData: false,
                contentType: false,
                headers: {'X-CSRF-TOKEN': csrf_token},
                success: function (response) {
                    $('#general-form input[name=percent]').val(response.data.percent);

                    $('.general_modal').modal('toggle');
                    edit = true;
                    add = false;

                },
                error: function () {
                    toastr['error']('{{trans('admin.general_error_message')}}', '{{trans('admin.error_title')}}');
                }
            });

        }

        function deleteNegotiationPercentage(item) {
            ban(item, '{{url('/admin/negotiation/percent/delete', [] , env('APP_ENV') === 'local' ?  false : true)}}', {
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
