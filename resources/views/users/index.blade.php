@extends('layouts.main')
@section('content')

<div class="container-fluid">
    <div class="row justify-content-center d-flex align-content-center">
        <div class="col">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-4 mt-2">{{ __('users.AddUser')
                }}</a>
            <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __('users.Name') }}</th>
                        <th>{{ __('users.Username') }}</th>
                        <th>{{ __('users.UserCode') }}</th>
                        <th>{{ __('users.Email') }}</th>
                        <th>{{ __('users.FirstName') }}</th>
                        <th>{{ __('users.LastName') }}</th>
                        <th>{{ __('users.Level') }}</th>
                        <th>{{ __('users.Gender') }}</th>
                        <th>{{ __('users.Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->first_name. ' ' . $user->last_name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->user_code }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>
                            @if ($user->role == 'admin')
                            <span class="badge bg-primary">{{ __('users.Admin') }}</span>
                            @elseif ($user->role == 'operator')
                            <span class="badge bg-success">{{ __('users.Operator') }}</span>
                            @elseif ($user->role == 'borrower')
                            <span class="badge bg-info">{{ __('users.Borrower') }}</span>
                            @endif
                        </td>

                        <td>{{ $user->gender }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-success"><i
                                    class="fas fa-eye"></i> {{ __('users.View') }}</a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning"><i
                                    class="fas fa-edit"></i> {{ __('users.Edit') }}</a>
                            <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('{{ __('users.AreYouSureResetPassword') }}')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="fas fa-key"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.users.destroy', $user->username) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('{{ __('users.AreYouSureDeleteUser') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Tampilkan tautan navigasi halaman -->
            {{-- {{ Illuminate\Pagination\Paginator::render($users, 'full') }} --}}
            {{-- {{ $users->links('pagination::bootstrap-4') }} --}}
        </div>
    </div>
</div>
@endsection