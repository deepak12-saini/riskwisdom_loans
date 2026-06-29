@extends('admin.layout')

@section('title', 'Edit '.$client->full_name)
@section('page_heading', 'Edit client file')

@section('topbar_actions')
    <a class="rw-button rw-button--ghost" href="{{ route('admin.clients.show', $client) }}">Back to file</a>
@endsection

@section('content')
    <section class="rw-admin-card rw-admin-card--form">
        <div class="rw-admin-card__header">
            <div>
                <h2>Edit {{ $client->full_name }}</h2>
                <p>Update contact details, assignment, and internal notes.</p>
            </div>
        </div>

        <form class="rw-admin-form" method="post" action="{{ route('admin.clients.update', $client) }}">
            @csrf
            @method('put')
            @include('admin.clients.partials.form', ['client' => $client, 'brokers' => $brokers])
            <div class="rw-admin-form-actions">
                <button class="rw-button rw-button--solid" type="submit">Save changes</button>
            </div>
        </form>
    </section>
@endsection
