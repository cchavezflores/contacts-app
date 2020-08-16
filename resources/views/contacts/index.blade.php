@extends('layouts.app')

@section('content')
    <contacts contacts="{{ json_encode($contacts) }}"></contacts>
@endsection
