@php
    $isEdit = isset($client);
@endphp

<div class="rw-admin-form-grid">
    <label>
        <span>First name</span>
        <input type="text" name="first_name" value="{{ old('first_name', $client->first_name ?? '') }}" required>
        @error('first_name')<small>{{ $message }}</small>@enderror
    </label>

    <label>
        <span>Last name</span>
        <input type="text" name="last_name" value="{{ old('last_name', $client->last_name ?? '') }}" required>
        @error('last_name')<small>{{ $message }}</small>@enderror
    </label>

    <label>
        <span>Email</span>
        <input type="email" name="email" value="{{ old('email', $client->email ?? '') }}" required>
        @error('email')<small>{{ $message }}</small>@enderror
    </label>

    <label>
        <span>Phone</span>
        <input type="text" name="phone" value="{{ old('phone', $client->phone ?? '') }}">
        @error('phone')<small>{{ $message }}</small>@enderror
    </label>

    <label>
        <span>Loan type</span>
        <select name="loan_type">
            <option value="">— Select —</option>
            @foreach (config('riskwisdom.loan_types') as $value => $label)
                <option value="{{ $value }}" @selected(old('loan_type', $client->loan_type ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('loan_type')<small>{{ $message }}</small>@enderror
    </label>

    <label>
        <span>State</span>
        <select name="state">
            <option value="">— Select —</option>
            @foreach (config('riskwisdom.states') as $value => $label)
                <option value="{{ $value }}" @selected(old('state', $client->state ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('state')<small>{{ $message }}</small>@enderror
    </label>

    @if ($isEdit)
        <label>
            <span>Status</span>
            <select name="status" required>
                @foreach (config('riskwisdom.client_statuses') as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $client->status) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('status')<small>{{ $message }}</small>@enderror
        </label>
    @endif

    <label>
        <span>Assigned broker</span>
        <select name="assigned_user_id">
            <option value="">— Unassigned —</option>
            @foreach ($brokers as $broker)
                <option value="{{ $broker->id }}" @selected((string) old('assigned_user_id', $client->assigned_user_id ?? '') === (string) $broker->id)>
                    {{ $broker->username }}
                </option>
            @endforeach
        </select>
        @error('assigned_user_id')<small>{{ $message }}</small>@enderror
    </label>
</div>

<label class="rw-admin-form-full">
    <span>Notes</span>
    <textarea name="notes" rows="4" placeholder="Internal notes about this client file">{{ old('notes', $client->notes ?? '') }}</textarea>
    @error('notes')<small>{{ $message }}</small>@enderror
</label>
