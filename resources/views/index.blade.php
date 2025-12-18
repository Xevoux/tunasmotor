@extends('layouts.app')

@section('title', 'Home - Tunas Motor')

@section('content')
@include('layouts.partials.header')

{{-- Hero Section --}}
@include('layouts.components.hero')

{{-- Features Section --}}
@include('layouts.components.features')

{{-- New Arrivals Section --}}
@include('layouts.components.new-arrivals', ['newProducts' => $newProducts])

{{-- Image Slider --}}
@include('layouts.components.image-slider')

{{-- Categories Section --}}
@include('layouts.components.categories', [
    'categories' => $categories,
    'totalProducts' => $totalProducts
])

{{-- Footer --}}
@include('layouts.partials.footer')

@endsection
