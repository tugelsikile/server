@extends('layout.app')
@section('title','Client Ujian')

@section('script')
    <script src="{{asset('js/exam/client.js?t=' . time())}}"></script>
@endsection
