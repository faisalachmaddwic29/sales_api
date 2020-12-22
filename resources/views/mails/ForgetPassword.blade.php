@extends('mails.layout')

@section('content')
    <div>
        <p>PIN anda hanya berlaku 60 Detik</p>
        <p style="font-weight: bold">{{ $data->token }}</p>
    </div>
@endsection