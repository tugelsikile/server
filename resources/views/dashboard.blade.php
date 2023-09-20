@extends('layout.app')
@section('title','Dashbaord')
@section('content')

@endsection

@section('script')
    <script src="{{asset('js/dashboard.js?t=' . time())}}"></script>
@endsection
