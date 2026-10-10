@extends('adminlte::page')

@section('title', 'Kelola Pengguna')
@section('content_header') <h1>Kelola Pengguna</h1> @stop

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($users as $u)
                    <tr>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->roles->pluck('name')->join(', ') }}</td>
                        <td>@if($u->is_active) <span class="badge text-bg-success">Aktif</span> @else <span class="badge text-bg-danger">Nonaktif</span> @endif</td>
                        <td>
                            <form action="{{ route('admin.pengguna.updateRole', $u) }}" method="POST" class="d-inline">
                                @csrf @method('PUT')
                                <select name="role" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                    @foreach($listRole as $role)<option value="{{ $role }}" @selected($u->hasRole($role))>{{ ucfirst($role) }}</option>@endforeach
                                </select>
                            </form>
                            <form action="{{ route('admin.pengguna.toggleAktif', $u) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $u->is_active ? 'btn-danger' : 'btn-success' }}">{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop