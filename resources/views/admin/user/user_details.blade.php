@extends('layouts/contentLayoutMaster')

@section('title', $title)

@section('vendor-style')
    <link rel="stylesheet" type="text/css" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css')) }}">

@endsection

@section('page-style')
    <link href="{{url('/css/jquery.loader.css')}}" rel="stylesheet"/>
@endsection

@section('content')
    <!-- Basic table -->
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">
                            <img class="img-responsive" style="width: 100px;"
                                 src="{{url($user->image && file_exists($user->image) ? $user->image : '/images/placeholder.jpg')}}"/>
                        </h4>
                    </div>
                    <div class="card-body">

                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-12">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td>{{trans('admin.name')}}</td>
                                        <td>{{$user->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('admin.email')}}</td>
                                        <td>{{$user->email}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('admin.phone')}}</td>
                                        <td>{{$user->full_phone}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('admin.registration_date')}}</td>
                                        <td>{{$user->created_at->format('Y-m-d h:i a')}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('admin.status')}}</td>
                                        <td>
                                            @if ($user->status === \App\Entities\Status::ACTIVE)
                                                <span class="btn btn-success">{{trans('admin.active_status')}} </span>
                                            @elseif ($user->status === \App\Entities\Status::UNVERIFIED) {
                                            <span class="btn btn-warning"
                                                  style="font-size: 10px;">{{trans('admin.unverified_status')}}</span>
                                            @elseif ($user->status === \App\Entities\Status::INACTIVE)
                                                <span class="btn btn-danger">{{trans('admin.blocked_status')}}</span>
                                            @endif
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('vendor-script')
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

        });

        function activeUser(item) {
            ban(item, '{{url( '/admin/user/change', [] , env('APP_ENV') === 'local' ?  false : true)}}', {
                error_message: '{{trans('admin.error_message')}}',
                error_title: '{{trans('admin.error_title')}}',
                ban_title: "{{trans('admin.activate_title')}}",
                ban_message: "{{trans('admin.activate_message')}}",
                inactivate: "{{trans('admin.activate_action')}}",
                cancel: "{{trans('admin.cancel')}}",
                status: "{{\App\Entities\Status::ACTIVE}}"
            });
        }

        function verifyAccount(item) {
            ban(item, '{{url(  '/admin/user/verify', [] , env('APP_ENV') === 'local' ?  false : true)}}', {
                error_message: '{{trans('admin.general_error_message')}}',
                error_title: '{{trans('admin.error_title')}}',
                ban_title: "{{trans('admin.verify_action')}}",
                ban_message: "{{trans('admin.verify_message')}}",
                inactivate: "{{trans('admin.verify_action')}}",
                cancel: "{{trans('admin.cancel')}}",
            });
        }

        function blockUser(item) {
            ban(item, '{{url( '/admin/user/change', [] , env('APP_ENV') === 'local' ?  false : true)}}', {
                error_message: '{{trans('admin.error_message')}}',
                error_title: '{{trans('admin.error_title')}}',
                ban_title: "{{trans('admin.ban_title')}}",
                ban_message: "{{trans('admin.ban_message')}}",
                inactivate: "{{trans('admin.inactive_action')}}",
                cancel: "{{trans('admin.cancel')}}",
                status: "{{\App\Entities\Status::INACTIVE}}",
            });
        }

    </script>
@endsection
