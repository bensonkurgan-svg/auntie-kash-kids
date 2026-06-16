@extends('layouts.app')
@section('title', 'Export My Data')
@section('content')
<section class="section">
    <div class="container" style="max-width:560px;">
        <div class="card text-center" style="padding:40px;">
            <div style="font-size:48px;margin-bottom:16px;">📁</div>
            <h1 style="font-size:28px;color:var(--navy);margin-bottom:12px;">Export Your Data</h1>
            <p style="color:#666;margin-bottom:24px;line-height:1.7;">Download a complete copy of all the personal data we hold about you and your family, in JSON format.</p>
            <form method="POST" action="{{ route('data-export') }}">@csrf
                <button type="submit" class="btn-primary w-full">Download My Data</button>
            </form>
        </div>
    </div>
</section>
@endsection
