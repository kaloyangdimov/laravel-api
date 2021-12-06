@extends('layouts.mail')
@section('title')
    <b>{{__('custom.user_was_deleted')}}</b>
@endsection
@section('content')
    {{ __('custom.hello') .', '}}
    <br/>
    {{ $user->name }} requested a delete
    <br/>{{ __('custom.their_account_requires_attention') }}

@endsection
