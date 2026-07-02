@extends('layouts.app')
@section('title', 'Export My Data')
@section('content')
<section class="section">
    <div class="container" style="max-width:560px;">
        <div class="card text-center" style="padding:40px;">
            <div style="font-size:48px;margin-bottom:16px;">📁</div>
            <h1 style="font-size:28px;color:var(--navy);margin-bottom:12px;">Export Your Data</h1>
            <p style="color:#666;margin-bottom:24px;line-height:1.7;">Download a complete copy of all the personal data we hold about you and your family. Choose your preferred format:</p>
            <div class="flex gap-3" style="flex-wrap:wrap;justify-content:center;">
                <a href="{{ route('data-export.download', 'json') }}" class="btn-primary">⬇️ JSON</a>
                <a href="{{ route('data-export.download', 'txt') }}" class="btn-secondary">⬇️ Plain Text</a>
                <a href="{{ route('data-export.download', 'pdf') }}" class="btn-purple">⬇️ PDF</a>
            </div>
        </div>
    </div>
</section>
@endsection
