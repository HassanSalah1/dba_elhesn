@extends("general.email.template")

@section('subject')
    {{trans('api.account_verification_subject')}}
@stop
@section('title')
    <h1> <span style="color:#00cccc;text-align: center">{{trans('api.account_verification_subject')}}</span></h1>
    <h41> {{str_replace('{username}' , $data['user']->name , trans('api.hello_user'))}}</h41>

@stop


@section('message')
    {!! str_replace(
    [
        '{code}',
    ] ,
     [
        '<b style="color:#00cccc;">'.$data['code'].'</b>',
     ] ,
      trans('api.account_verification_message')) !!}
@stop
