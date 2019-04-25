@extends('layouts.layout')

@section('content')

  <h1>{{$user->username}}</h1>
  <h3>Email: {{$user->email}}</h3>

  <br><br>
  <p>Show other stats: Maybe like total votes, comment history, ect</p>

@endsection

@section('extra-js')

@endsection