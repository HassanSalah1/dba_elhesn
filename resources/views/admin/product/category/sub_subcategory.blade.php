@extends('layouts.contentLayoutMaster')

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
        <label class="form-label" for="name_ar">{{trans('admin.name_ar')}}</label>
        <input type="text" name="name_ar"
               class="form-control dt-full-name"
               id="name_ar"
               placeholder="{{trans('admin.name_ar')}}" />
    </div>

    <div class="mb-1">
        <label class="form-label" for="name_en">{{trans('admin.name_en')}}</label>
        <input type="text" name="name_en"
               class="form-control dt-full-name"
               id="name_en"
               placeholder="{{trans('admin.name_en')}}" />
    </div>

    <input type="hidden" name="category_id"/>

    <div class="mb-1">
        <button type="button" id="add_field" class="btn btn-primary">
            {{trans('admin.add_field')}}
        </button>
    </div>
    <hr>

    <div id="fields">

    </div>

    <hr>
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
                                {{trans('admin.add_subcategory')}}
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
        let fields = 0;

        $(function() {

            $('#add_field').on('click' , (e) => {
                addField();
            });

            addModal({
                title: '{{trans('admin.add_subcategory')}}',
                hiddenName: 'category_id',
                hiddenValue: '{{$id}}',
                fields: true
            });

            onClose();

            loadDataTables('{{ url("/admin/categories/get/sub/data/".$id, [] , env('APP_ENV') === 'local' ?  false : true)}}',
                ['name_ar' , 'name_en' , 'actions'], '',
                {
                    'show': '{{trans('admin.show')}}',
                    'first': '{{trans('admin.first')}}',
                    'last': '{{trans('admin.last')}}',
                    'filter': '{{trans('admin.filter')}}',
                    'filter_type': '{{trans('admin.type_filter')}}',
                });

            $('#general-form').submit(function (e) {
                e.preventDefault();
                sendModalAjaxRequest(this, '{{url('/admin/category/add', [] , env('APP_ENV') === 'local' ?  false : true)}}',
                    '{{url('/admin/category/edit', [] , env('APP_ENV') === 'local' ?  false : true)}}', {
                        error_message: '{{trans('admin.general_error_message')}}',
                        error_title: '',
                        loader: true,
                    });
            });

        });

        function addField() {
            createFieldHtml(fields);
            fields++;
        }

        function createFieldHtml(fieldId , value = ''){
            let html = '<div class="mb-1" id="field_'+fieldId+'">' +
                '<input type="text" name="field_name[]" class="form-control" style="display: inline-block;width:70%;margin-left: 10px;"'+
                ' value="'+( value ? value: '')+'" placeholder="{{trans('admin.field_name')}}" />'+
                '<button type="button" class="btn btn-danger"  onclick="deleteField(\'field_'+fieldId+'\')"><i data-feather="delete"></i></button>'+
                '</div>';
            $('#fields').append(html);
            feather.replace();
        }

        function deleteField(fieldId) {
            $('#' + fieldId).remove();
            fields--;
        }

        function editCategory(item) {
            var id = $(item).attr('id');
            var form = new FormData();
            form.append('id', id);
            $('.modal-title').text('{{trans('admin.edit_category')}}');
            pub_id = id;
            $.ajax({
                url: '{{url('/admin/category/data', [] , env('APP_ENV') === 'local' ?  false : true)}}',
                method: 'POST',
                data: form,
                processData: false,
                contentType: false,
                headers: {'X-CSRF-TOKEN': csrf_token},
                success: function (response) {
                    $('#general-form input[name=name_ar]').val(response.data.name_ar);
                    $('#general-form input[name=name_en]').val(response.data.name_en);
                    $('#general-form input[name=category_id]').val(response.data.category_id);
                    $('#fields').html('');
                    fields = response.data.all_fields.length;
                    for (let index = 0; index < fields; index++) {
                        createFieldHtml(index, response.data.all_fields[index].key);
                    }
                    $('.general_modal').modal('toggle');
                    edit = true;
                    add = false;

                },
                error: function () {
                    toastr['error']('{{trans('admin.general_error_message')}}', '{{trans('admin.error_title')}}');
                }
            });

        }

        function deleteCategory(item) {
            ban(item, '{{url('/admin/category/delete', [] , env('APP_ENV') === 'local' ?  false : true)}}', {
                error_message: '{{trans('admin.general_error_message')}}',
                error_title: '{{trans('admin.error_title')}}',
                ban_title: "{{trans('admin.delete_action')}}",
                ban_message: "{{trans('admin.delete_message')}}",
                inactivate: "{{trans('admin.delete_action')}}",
                cancel: "{{trans('admin.cancel')}}"
            });
        }

        function restoreCategory(item) {
            ban(item, '{{url('/admin/category/restore', [] , env('APP_ENV') === 'local' ?  false : true)}}', {
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
