@extends("general/email/template")

@section('subject')
    {{$data['subject']}}
@stop
@section('title')
    <h1> <span style="color:#00cccc;text-align: center">{{$data['subject']}}</span></h1>
@stop


@section('message')
    {!! $data['message'] !!}
@stop