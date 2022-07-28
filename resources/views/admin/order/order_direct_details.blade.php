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
                                 src="{{url( ($order->image  && file_exists($order->image->image)) ? $order->image->image : '/images/placeholder.jpg')}}"/>
                        </h4>
                    </div>
                    <div class="card-body">

                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-12">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td>{{trans('admin.buyer_details')}}</td>
                                        <td>
                                            <ul>
                                                @if ($order->user_type === \App\Entities\OrderUserType::BUYER)
                                                    <li>{{$order->user->name}}</li>
                                                    <li>{{$order->user->full_phone}}</li>
                                                    @if ($order->user->role === \App\Entities\UserRoles::REGISTER)
                                                        <li>{{trans('admin.invitation_sent')}}</li>
                                                    @endif
                                                @elseif ($order->other_user)
                                                    <li>{{$order->other_user->name}}</li>
                                                    <li>{{$order->other_user->full_phone}}</li>
                                                    @if ($order->other_user->role === \App\Entities\UserRoles::REGISTER)
                                                        <li>{{trans('admin.invitation_sent')}}</li>
                                                    @endif
                                                @endif
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('admin.seller_details')}}</td>
                                        <td>
                                            <ul>
                                                @if ($order->user_type === \App\Entities\OrderUserType::SELLER)
                                                    <li>{{$order->user->name}} </li>
                                                    <li>{{$order->user->full_phone}} </li>
                                                    @if ($order->user->role === \App\Entities\UserRoles::REGISTER)
                                                        <li>{{trans('admin.invitation_sent')}}</li>
                                                    @endif
                                                @elseif ($order->other_user)
                                                    <li>{{$order->other_user->name}}</li>
                                                    <li>{{$order->other_user->full_phone}}</li>
                                                    @if ($order->other_user->role === \App\Entities\UserRoles::REGISTER)
                                                        <li>{{trans('admin.invitation_sent')}}</li>
                                                    @endif
                                                @endif
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('admin.name')}}</td>
                                        <td>{{$order->normalOrder->product->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('admin.description')}}</td>
                                        <td>{{$order->normalOrder->product->description}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('admin.category_name')}}</td>
                                        <td>{{@$order->normalOrder->product->category->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('admin.sub_category_name')}}</td>
                                        <td>{{@$order->normalOrder->product->sub_category->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('admin.sub_sub_category_name')}}</td>
                                        <td>{{@$order->normalOrder->product->sub_sub_category->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('admin.price')}}</td>
                                        <td>
                                            {{$order->normalOrder->total_price.' '.trans('api.ryal')}}
                                        </td>
                                    </tr>

                                    @foreach($order->normalOrder->all_fields as $field)
                                        <tr>
                                            <td>{{$field['key']}}</td>
                                            <td>{{$field['value']}}</td>
                                        </tr>
                                    @endforeach
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
