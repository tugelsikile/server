@extends('layout.app')
@section('title','Ujian')

@section('script')
    <script src="{{asset('js/exam/index.js?t=' . time())}}"></script>
@endsection
