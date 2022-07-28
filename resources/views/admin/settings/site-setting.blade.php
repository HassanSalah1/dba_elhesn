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
                        <h4 class="card-title">{{trans('admin.site_home_title')}}</h4>
                    </div>
                    <div class="card-body">
                        <form id="general-form">
                            <div class="row">


                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::SMALL_ABOUT_AR}}">{{trans('admin.'.\App\Entities\Key::SMALL_ABOUT_AR)}}</label>
                                        <textarea type="email" class="form-control"
                                                  id="{{\App\Entities\Key::SMALL_ABOUT_AR}}"
                                                  name="{{\App\Entities\Key::SMALL_ABOUT_AR}}"
                                                  placeholder="{{trans('admin.'.\App\Entities\Key::SMALL_ABOUT_AR)}}">@if(isset($small_about_ar) && $small_about_ar){{$small_about_ar->value}}@endif</textarea>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::SMALL_ABOUT_EN}}">{{trans('admin.'.\App\Entities\Key::SMALL_ABOUT_EN)}}</label>
                                        <textarea type="email" class="form-control"
                                                  id="{{\App\Entities\Key::SMALL_ABOUT_EN}}"
                                                  name="{{\App\Entities\Key::SMALL_ABOUT_EN}}"
                                                  placeholder="{{trans('admin.'.\App\Entities\Key::SMALL_ABOUT_EN)}}">@if(isset($small_about_en) && $small_about_en){{$small_about_en->value}}@endif</textarea>
                                    </div>
                                </div>


                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::DIRECT_AR}}">{{trans('admin.'.\App\Entities\Key::DIRECT_AR)}}</label>
                                        <textarea type="email" class="form-control"
                                                  id="{{\App\Entities\Key::DIRECT_AR}}"
                                                  name="{{\App\Entities\Key::DIRECT_AR}}"
                                                  placeholder="{{trans('admin.'.\App\Entities\Key::DIRECT_AR)}}">@if(isset($direct_ar) && $direct_ar){{$direct_ar->value}}@endif</textarea>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::DIRECT_EN}}">{{trans('admin.'.\App\Entities\Key::DIRECT_EN)}}</label>
                                        <textarea type="email" class="form-control"
                                                  id="{{\App\Entities\Key::DIRECT_EN}}"
                                                  name="{{\App\Entities\Key::DIRECT_EN}}"
                                                  placeholder="{{trans('admin.'.\App\Entities\Key::DIRECT_EN)}}">@if(isset($direct_en) && $direct_en){{$direct_en->value}}@endif</textarea>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::BID_AR}}">{{trans('admin.'.\App\Entities\Key::BID_AR)}}</label>
                                        <textarea type="email" class="form-control"
                                                  id="{{\App\Entities\Key::BID_AR}}"
                                                  name="{{\App\Entities\Key::BID_AR}}"
                                                  placeholder="{{trans('admin.'.\App\Entities\Key::BID_AR)}}">@if(isset($bid_ar) && $bid_ar){{$bid_ar->value}}@endif</textarea>
                                    </div>
                                </div>


                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::BID_EN}}">{{trans('admin.'.\App\Entities\Key::BID_EN)}}</label>
                                        <textarea type="email" class="form-control"
                                                  id="{{\App\Entities\Key::BID_EN}}"
                                                  name="{{\App\Entities\Key::BID_EN}}"
                                                  placeholder="{{trans('admin.'.\App\Entities\Key::BID_EN)}}">@if(isset($bid_en) && $bid_en){{$bid_en->value}}@endif</textarea>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::NEGOTIATION_AR}}">{{trans('admin.'.\App\Entities\Key::NEGOTIATION_AR)}}</label>
                                        <textarea type="email" class="form-control"
                                                  id="{{\App\Entities\Key::NEGOTIATION_AR}}"
                                                  name="{{\App\Entities\Key::NEGOTIATION_AR}}"
                                                  placeholder="{{trans('admin.'.\App\Entities\Key::NEGOTIATION_AR)}}">@if(isset($negotiation_ar) && $negotiation_ar){{$negotiation_ar->value}}@endif</textarea>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::NEGOTIATION_EN}}">{{trans('admin.'.\App\Entities\Key::NEGOTIATION_EN)}}</label>
                                        <textarea type="email" class="form-control"
                                                  id="{{\App\Entities\Key::NEGOTIATION_EN}}"
                                                  name="{{\App\Entities\Key::NEGOTIATION_EN}}"
                                                  placeholder="{{trans('admin.'.\App\Entities\Key::NEGOTIATION_EN)}}">@if(isset($negotiation_en) && $negotiation_en){{$negotiation_en->value}}@endif</textarea>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::DAMAIN_AR}}">{{trans('admin.'.\App\Entities\Key::DAMAIN_AR)}}</label>
                                        <textarea type="email" class="form-control"
                                                  id="{{\App\Entities\Key::DAMAIN_AR}}"
                                                  name="{{\App\Entities\Key::DAMAIN_AR}}"
                                                  placeholder="{{trans('admin.'.\App\Entities\Key::DAMAIN_AR)}}">@if(isset($damain_ar) && $damain_ar){{$damain_ar->value}}@endif</textarea>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::DAMAIN_EN}}">{{trans('admin.'.\App\Entities\Key::DAMAIN_EN)}}</label>
                                        <textarea type="email" class="form-control"
                                                  id="{{\App\Entities\Key::DAMAIN_EN}}"
                                                  name="{{\App\Entities\Key::DAMAIN_EN}}"
                                                  placeholder="{{trans('admin.'.\App\Entities\Key::DAMAIN_EN)}}">@if(isset($damain_en) && $damain_en){{$damain_en->value}}@endif</textarea>
                                    </div>
                                </div>


                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::DOWNLOAD_AR}}">{{trans('admin.'.\App\Entities\Key::DOWNLOAD_AR)}}</label>
                                        <textarea type="email" class="form-control"
                                                  id="{{\App\Entities\Key::DOWNLOAD_AR}}"
                                                  name="{{\App\Entities\Key::DOWNLOAD_AR}}"
                                                  placeholder="{{trans('admin.'.\App\Entities\Key::DOWNLOAD_AR)}}">@if(isset($download_ar) && $download_ar){{$download_ar->value}}@endif</textarea>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::DOWNLOAD_EN}}">{{trans('admin.'.\App\Entities\Key::DOWNLOAD_EN)}}</label>
                                        <textarea type="email" class="form-control"
                                                  id="{{\App\Entities\Key::DOWNLOAD_EN}}"
                                                  name="{{\App\Entities\Key::DOWNLOAD_EN}}"
                                                  placeholder="{{trans('admin.'.\App\Entities\Key::DOWNLOAD_EN)}}">@if(isset($download_en) && $download_en){{$download_en->value}}@endif</textarea>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::GOOGLE_PLAY}}">{{trans('admin.'.\App\Entities\Key::GOOGLE_PLAY)}}</label>
                                        <input type="url" class="form-control" id="{{\App\Entities\Key::GOOGLE_PLAY}}"
                                               name="{{\App\Entities\Key::GOOGLE_PLAY}}"
                                               @if(isset($google_play) && $google_play) value="{{$google_play->value}}" @endif
                                               placeholder="{{trans('admin.'.\App\Entities\Key::GOOGLE_PLAY)}}"/>
                                    </div>
                                </div>


                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label"
                                               for="{{\App\Entities\Key::APPLE_STORE}}">{{trans('admin.'.\App\Entities\Key::APPLE_STORE)}}</label>
                                        <input type="url" class="form-control" id="{{\App\Entities\Key::APPLE_STORE}}"
                                               name="{{\App\Entities\Key::APPLE_STORE}}"
                                               @if(isset($apple_store) && $apple_store) value="{{$apple_store->value}}" @endif
                                               placeholder="{{trans('admin.'.\App\Entities\Key::APPLE_STORE)}}"/>
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
                sendAjaxRequest(this, '{{url('/admin/setting/site/save', [] , env('APP_ENV') === 'local' ?  false : true)}}', {
                    error_message: '{{trans('admin.general_error_message')}}',
                    error_title: '',
                    loader: true,
                });
            });
        });
    </script>
@endsection
