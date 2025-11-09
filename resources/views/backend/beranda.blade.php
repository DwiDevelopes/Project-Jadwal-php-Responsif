@extends('layouts.app')

@section('content')
    @auth
        <h2>Selamat datang, {{ Auth::user()->name }}!</h2>
        <p>Ini adalah halaman beranda khusus pengguna yang sudah login.</p>
    @endauth

    @guest
        <h2>Selamat datang di halaman Beranda!</h2>
        <p>Silakan <a href="{{ route('login') }}">login</a> untuk melanjutkan.</p>
    @endguest
@endsection
