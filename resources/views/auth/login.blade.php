@extends('layouts.app')

@section('content')
<!--  a propriedade com o - é convertida para camelCase -->
    <login-component csrf_token="{{ @csrf_token() }}"></login-component>
@endsection
