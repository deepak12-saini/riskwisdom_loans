@extends('admin.layout')

@section('title', 'New client file')
@section('page_heading', 'New client file')

@section('topbar_actions')
    <a class="rw-button rw-button--ghost" href="{{ route('admin.clients.index') }}">Back to clients</a>
@endsection

@section('content')
    <section class="rw-admin-card rw-admin-card--form">
        <div class="rw-admin-card__header">
            <div>
                <h2>Create client file</h2>
                <p>Add a client manually or convert a website lead from the enquiries list.</p>
            </div>
        </div>

        <form class="rw-admin-form" method="post" action="{{ route('admin.clients.store') }}">
            @csrf
            @if ($errors->any())
                <div class="rw-admin-alert rw-admin-alert--error" role="alert">
                    Please fix the highlighted fields and try again.
                </div>
            @endif
            @include('admin.clients.partials.form', ['brokers' => $brokers])
            <div class="rw-admin-form-actions">
                <button class="rw-button rw-button--solid" type="submit">Create client file</button>
            </div>
        </form>
    </section>
@endsection
