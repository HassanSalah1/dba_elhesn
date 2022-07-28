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
                                 src="{{url($product->image && file_exists($product->image) ? $product->image : '/images/placeholder.jpg')}}"/>
                        </h4>
                    </div>
                    <div class="card-body">

                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-12">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td>{{trans('admin.user_name')}}</td>
                                        <td>{{$product->user->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('admin.phone')}}</td>
                                        <td>{{$product->user->phone}}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('admin.name')}}</td>
                                        <td>{{$product->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('admin.description')}}</td>
                                        <td>{{$product->description}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('admin.category_name')}}</td>
                                        <td>{{@$product->category->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('admin.sub_category_name')}}</td>
                                        <td>{{@$product->sub_category->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('admin.sub_sub_category_name')}}</td>
                                        <td>{{@$product->sub_sub_category->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('admin.product_type')}}</td>
                                        <td>{{trans('api.'.(($product->type === \App\Entities\ProductType::DIRECT && $product->negotiation === 1) ? 'negotiation_type' : $product->type))}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('admin.price')}}</td>
                                        <td>
                                            {{$product->price.' '.trans('api.ryal')}}
                                        </td>
                                    </tr>
                                    @if ($product->type === \App\Entities\ProductType::BID)
                                        <tr>
                                            <td>{{trans('admin.max_price')}}</td>
                                            <td>
                                                {{$product->max_price.' '.trans('api.ryal')}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{trans('admin.start_bid')}}</td>
                                            <td>
                                                {{$product->created_at->format('Y-m-d H:i:s')}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{trans('admin.period')}}</td>
                                            <td>
                                                {{$product->period.' '.trans('admin.day')}}
                                            </td>
                                        </tr>


                                    @elseif ($product->negotiation === 1)
                                        <tr>
                                            <td>{{trans('admin.negotiation')}}</td>
                                            <td>
                                                {{$product->percent.' %'}}
                                            </td>
                                        </tr>
                                    @endif

                                    @foreach($product->all_fields as $field)
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
