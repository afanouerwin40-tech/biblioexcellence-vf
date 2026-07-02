@extends('layouts.app')

@section('title', 'Catalogue — BiblioExcellence')
@section('page-title', 'Catalogue des livres')
@section('page-subtitle', 'Recherchez parmi notre collection')

@section('content')
    @livewire('book-search')
@endsection