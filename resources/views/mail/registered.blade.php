@extends('layouts.mail')
@section('title')
    <b>{{__('custom.new_user_registered')}}</b>
@endsection
@section('content')
    {{ __('custom.hello') .', '}}
    <br/>
    {{ $registeredUser->name }} {{__('custom.just_signed_up')}}
    <br/>{{ __('custom.their_account_requires_attention') }}

@endsection
