@extends('layouts/contentLayoutMaster')

@section('title', $title)

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css')) }}">

    <link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet"
          type="text/css"/>
@endsection

@section('page-style')
    <link href="{{url('/css/jquery.loader.css')}}" rel="stylesheet"/>
@endsection

@section('form_input')
    <div class="mb-1" id="dropify_image">
    </div>

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
        <label class="form-label" for="price">{{trans('admin.price')}}</label>
        <input type="number" id="name_en" name="price"
               class="form-control dt-post" min="0"
               placeholder="{{trans('admin.price')}}">
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

    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/jszip.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>


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

            let orderArr = ['id', 'buyer', 'seller' , 'type'];
            @if($status === 'refused')
            orderArr.push('refuse_reason');
            @else
            orderArr.push('status')
            @endif

            orderArr.push('actions');

            loadDataTables('{{ url("/admin/get/orders/data", [] , env('APP_ENV') === 'local' ?  false : true)}}?status={{$status}}',
                orderArr, '',
                {
                    'show': '{{trans('admin.show')}}',
                    'first': '{{trans('admin.first')}}',
                    'last': '{{trans('admin.last')}}',
                    'filter': '{{trans('admin.filter')}}',
                    'filter_type': '{{trans('admin.type_filter')}}',
                    export: true,
                });

        });

        function approveRequest(item) {
            ban(item, '{{url('/admin/orders/accept', [] , env('APP_ENV') === 'local' ?  false : true)}}', {
                error_message: '{{trans('admin.general_error_message')}}',
                error_title: '{{trans('admin.error_title')}}',
                ban_title: "{{trans('admin.approve_action')}}",
                ban_message: "{{trans('admin.approve_refuse_message')}}",
                inactivate: "{{trans('admin.approve_action')}}",
                cancel: "{{trans('admin.cancel')}}"
            });
        }

        function refuseRequest(item) {
            ban(item, '{{url('/admin/orders/refuse', [] , env('APP_ENV') === 'local' ?  false : true)}}', {
                error_message: '{{trans('admin.general_error_message')}}',
                error_title: '{{trans('admin.error_title')}}',
                ban_title: "{{trans('admin.refuse_action')}}",
                ban_message: "{{trans('admin.refuse_refuse_message')}}",
                inactivate: "{{trans('admin.refuse_action')}}",
                cancel: "{{trans('admin.cancel')}}"
            });
        }

    </script>
@endsection
