@extends('layouts/contentLayoutMaster')

@section('title', $title)

@section('vendor-style')
@endsection

@section('page-style')
    <link href="{{url('/css/jquery.loader.css')}}" rel="stylesheet"/>
@endsection

@section('content')
    <!-- Basic Inputs start -->
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{trans('admin.settings_title')}}</h4>
                    </div>
                    <div class="card-body">
                        <form id="general-form">
                            <div class="row">


                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::EMAIL}}">{{trans('admin.'.\App\Entities\Key::EMAIL)}}</label>
                                        <input type="email" class="form-control" id="{{\App\Entities\Key::EMAIL}}"
                                               name="{{\App\Entities\Key::EMAIL}}"
                                               @if(isset($email) && $email) value="{{$email->value}}" @endif
                                               placeholder="{{trans('admin.'.\App\Entities\Key::EMAIL)}}"/>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::APP_PERCENTAGE}}">{{trans('admin.'.\App\Entities\Key::APP_PERCENTAGE)}}</label>
                                        <input type="number" class="form-control" min="0" max="99"
                                               id="{{\App\Entities\Key::APP_PERCENTAGE}}"
                                               name="{{\App\Entities\Key::APP_PERCENTAGE}}"
                                               @if(isset($app_percentage) && $app_percentage) value="{{$app_percentage->value}}"
                                               @endif
                                               placeholder="{{trans('admin.'.\App\Entities\Key::APP_PERCENTAGE)}}"/>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::FACEBOOK}}">{{trans('admin.'.\App\Entities\Key::FACEBOOK)}}</label>
                                        <input type="url" class="form-control" id="{{\App\Entities\Key::FACEBOOK}}"
                                               name="{{\App\Entities\Key::FACEBOOK}}"
                                               @if(isset($facebook) && $facebook) value="{{$facebook->value}}" @endif
                                               placeholder="{{trans('admin.'.\App\Entities\Key::FACEBOOK)}}"/>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::TWITTER}}">{{trans('admin.'.\App\Entities\Key::TWITTER)}}</label>
                                        <input type="url" class="form-control" id="{{\App\Entities\Key::TWITTER}}"
                                               name="{{\App\Entities\Key::TWITTER}}"
                                               @if(isset($twitter) && $twitter) value="{{$twitter->value}}" @endif
                                               placeholder="{{trans('admin.'.\App\Entities\Key::TWITTER)}}"/>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::INSTAGRAM}}">{{trans('admin.'.\App\Entities\Key::INSTAGRAM)}}</label>
                                        <input type="url" class="form-control" id="{{\App\Entities\Key::INSTAGRAM}}"
                                               name="{{\App\Entities\Key::INSTAGRAM}}"
                                               @if(isset($instagram) && $instagram) value="{{$instagram->value}}" @endif
                                               placeholder="{{trans('admin.'.\App\Entities\Key::INSTAGRAM)}}"/>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::SNAPCHAT}}">{{trans('admin.'.\App\Entities\Key::SNAPCHAT)}}</label>
                                        <input type="url" class="form-control" id="{{\App\Entities\Key::SNAPCHAT}}"
                                               name="{{\App\Entities\Key::SNAPCHAT}}"
                                               @if(isset($snapchat) && $snapchat) value="{{$snapchat->value}}" @endif
                                               placeholder="{{trans('admin.'.\App\Entities\Key::SNAPCHAT)}}"/>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::TELEGRAM}}">{{trans('admin.'.\App\Entities\Key::TELEGRAM)}}</label>
                                        <input type="url" class="form-control" id="{{\App\Entities\Key::TELEGRAM}}"
                                               name="{{\App\Entities\Key::TELEGRAM}}"
                                               @if(isset($telegram) && $telegram) value="{{$telegram->value}}" @endif
                                               placeholder="{{trans('admin.'.\App\Entities\Key::TELEGRAM)}}"/>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::WHATSAPP}}">{{trans('admin.'.\App\Entities\Key::WHATSAPP)}}</label>
                                        <input type="number" class="form-control" id="{{\App\Entities\Key::WHATSAPP}}"
                                               name="{{\App\Entities\Key::WHATSAPP}}"
                                               @if(isset($whatsapp) && $whatsapp) value="{{$whatsapp->value}}" @endif
                                               placeholder="{{trans('admin.'.\App\Entities\Key::WHATSAPP)}}"/>
                                    </div>
                                </div>


                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::MAX_TIME_TO_PAY}}">{{trans('admin.'.\App\Entities\Key::MAX_TIME_TO_PAY)}}</label>
                                        <input type="number" class="form-control" id="{{\App\Entities\Key::MAX_TIME_TO_PAY}}"
                                               name="{{\App\Entities\Key::MAX_TIME_TO_PAY}}"
                                               @if(isset($max_time_to_pay) && $max_time_to_pay) value="{{$max_time_to_pay->value}}" @endif
                                               placeholder="{{trans('admin.'.\App\Entities\Key::MAX_TIME_TO_PAY)}}"/>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::MAX_TIME_TO_APPROVAL_REJECTION}}">{{trans('admin.'.\App\Entities\Key::MAX_TIME_TO_APPROVAL_REJECTION)}}</label>
                                        <input type="number" class="form-control" id="{{\App\Entities\Key::MAX_TIME_TO_APPROVAL_REJECTION}}"
                                               name="{{\App\Entities\Key::MAX_TIME_TO_APPROVAL_REJECTION}}"
                                               @if(isset($max_time_to_approval_rejection) && $max_time_to_approval_rejection) value="{{$max_time_to_approval_rejection->value}}" @endif
                                               placeholder="{{trans('admin.'.\App\Entities\Key::MAX_TIME_TO_APPROVAL_REJECTION)}}"/>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::MAX_TIME_TO_CHOOSE_SHIPMENT}}">{{trans('admin.'.\App\Entities\Key::MAX_TIME_TO_CHOOSE_SHIPMENT)}}</label>
                                        <input type="number" class="form-control" id="{{\App\Entities\Key::MAX_TIME_TO_CHOOSE_SHIPMENT}}"
                                               name="{{\App\Entities\Key::MAX_TIME_TO_CHOOSE_SHIPMENT}}"
                                               @if(isset($max_time_to_choose_shipment) && $max_time_to_choose_shipment) value="{{$max_time_to_choose_shipment->value}}" @endif
                                               placeholder="{{trans('admin.'.\App\Entities\Key::MAX_TIME_TO_CHOOSE_SHIPMENT)}}"/>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <button type="submit" class="btn btn-primary">{{trans('admin.save')}}</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Basic Inputs end -->
@endsection

@section('vendor-script')
@endsection
@section('page-script')
    <script src="{{url('/js/scripts/custom/jquery.loader.js')}}"></script>
    <script>
        const csrf_token = '{{csrf_token()}}';
    </script>
    <script src="{{url('/js/scripts/custom/utils.js')}}"></script>
    <script>
        $(function () {

            $('#general-form').submit(function (e) {
                e.preventDefault();
                sendAjaxRequest(this, '{{url('/admin/setting/save', [] , env('APP_ENV') === 'local' ?  false : true)}}', {
                    error_message: '{{trans('admin.general_error_message')}}',
                    error_title: '',
                    loader: true,
                });
            });
        });
    </script>
@endsection
