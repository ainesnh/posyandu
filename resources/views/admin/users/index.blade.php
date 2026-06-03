@extends('layouts.adminlte')

@section('title', 'Manajemen User')

@section('content')
    <style>
        .alert-floating {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 250px;
            max-width: 350px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-left: 5px solid #4ade80 !important;
        }
    </style>

    {{-- Alert AJAX --}}
    <div id="ajax-alert" style="display: none;" class="alert alert-success alert-dismissible alert-floating" style="background-color: #064e3b !important; color: white;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="color: white;">&times;</button>
        <i class="icon fa fa-check"></i> <span id="ajax-message"></span>
    </div>

    {{-- Alert Session --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible alert-floating" style="background-color: #064e3b !important; color: white;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="color: white;">&times;</button>
            <i class="icon fa fa-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="box shadow-sm" style="border-top: 3px solid #064e3b; border-radius: 8px; overflow: hidden;">
        <div class="box-header with-border" style="background-color: #fcfcfc; padding: 15px;">
            <h3 class="box-title" style="font-weight: 700; color: #064e3b;">
                <i class="fa fa-users" style="margin-right: 5px;"></i> Daftar Pengguna
            </h3>
            <div class="box-tools pull-right"> {{-- Tambahkan pull-right di sini --}}
                <a href="{{ route('admin.users.create') }}" class="btn btn-flat" style="background-color: #16a34a; color: white; border-radius: 4px;">
                    <i class="fa fa-user-plus"></i> Tambah User Baru
                </a>
            </div>
        </div>
        
        <div class="box-body" style="background-color: #f9fafb; border-bottom: 1px solid #eee; padding: 15px 20px;">
            <form action="{{ route('admin.users.index') }}" method="GET" class="form-inline">
                <div class="form-group mr-3">
                    <label for="status" class="control-label" style="margin-right: 10px; color: #4b5563; font-weight: 600;"> Status </label>
                    <select name="status" id="status" class="form-control select2" onchange="this.form.submit()" style="min-width: 200px; border-radius: 4px;">
                        <option value="all_active" {{ request('status') == 'all_active' ? 'selected' : '' }}>
                            User Aktif & Nonaktif
                        </option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>
                            User Terarsip
                        </option>
                    </select>
                </div>
            </form>
        </div>

        <div class="box-body table-responsive no-padding">
            <table class="table table-hover table-striped" style="vertical-align: middle;">
                <thead>
                    <tr style="background-color: #064e3b; color: #ffffff;">
                        <th class="text-center" style="width: 50px; padding: 12px;">ID</th>
                        <th style="padding: 12px;">Nama</th>
                        <th style="padding: 12px;">Email</th>
                        <th style="padding: 12px;">Role</th>
                        <th class="text-center" style="padding: 12px;">Status</th>
                        <th class="text-center" style="width: 150px; padding: 12px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="text-center" style="vertical-align: middle;">{{ $user->id }}</td>
                            <td style="vertical-align: middle;">
                                <strong>{{ $user->name }}</strong>
                                @if(auth()->id() == $user->id) <span class="label label-info" style="margin-left: 5px;">Anda</span> @endif
                            </td>
                            <td style="vertical-align: middle;">{{ $user->email }}</td>
                            <td style="vertical-align: middle;">
                                <span class="badge bg-gray">{{ $user->role->nama_role ?? 'N/A' }}</span>
                            </td>

                            <td class="text-center" style="vertical-align: middle;">
                                <button type="button" 
                                        class="btn-toggle-user-status" 
                                        data-id="{{ $user->id }}"
                                        data-url="{{ route('admin.users.deactivate', $user) }}"
                                        style="border: none; background: transparent; padding: 0;">
                                    <span id="label-status-{{ $user->id }}" class="label" 
                                          style="background-color: {{ $user->isactive == 1 ? '#16a34a' : '#dc2626' }}; padding: 8px 12px; font-weight: 500; cursor: pointer; border-radius: 4px; display: inline-block; min-width: 85px;">
                                        {{ $user->isactive == 1 ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </button>
                            </td>

                            <td class="text-center" style="vertical-align: middle;">
                                <div class="btn-group">
                                    @if($status == 'archived')
                                        <form action="{{ route('admin.users.restore', $user) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-default btn-sm" title="Kembalikan User"> Pulihkan </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-default btn-sm" title="Edit User">
                                            <i class="fa fa-pencil text-info"></i>
                                        </a>

                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('admin.users.archive', $user) }}" 
                                                method="POST" 
                                                class="form-archive" {{-- Tambahkan class ini --}}
                                                style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" {{-- Ubah dari submit ke button --}}
                                                        class="btn btn-default btn-sm btn-archive-trigger" 
                                                        title="Arsipkan">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 40px; color: #999;">
                                Belum ada data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .table-hover tbody tr:hover { background-color: #f0fdf4 !important; }
        #ajax-alert {
            background-color: #064e3b !important;
            border-color: #4ade80;
            color: white;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        if ($('.alert-floating').is(':visible')) {
            $('.alert-floating').delay(2000).fadeOut();
        }

        function showToast(message) {
            $('#ajax-message').text(message);
            $('#ajax-alert').fadeIn().delay(2000).fadeOut();
        }

        $('.btn-toggle-user-status').on('click', function() {
            let btn = $(this);
            let url = btn.data('url');
            let userId = btn.data('id');
            let label = $('#label-status-' + userId);

            if (userId == "{{ auth()->id() }}") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: 'Anda tidak bisa menonaktifkan akun Anda sendiri.',
                    confirmButtonColor: '#064e3b'
                });
                return;
            }

            btn.prop('disabled', true);
            label.css('opacity', '0.5');

            $.ajax({
                url: url,
                type: 'PATCH',
                success: function(response) {
                    if (response.success) {
                        if (response.new_status == 1) {
                            label.text('Aktif').css('background-color', '#16a34a');
                        } else {
                            label.text('Nonaktif').css('background-color', '#dc2626');
                        }
                        showToast('Status berhasil diperbarui');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 403) {
                        alert(xhr.responseJSON.message);
                    } else {
                        alert('Terjadi kesalahan sistem.');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false);
                    label.css('opacity', '1');
                }
            });
        });

        $('.btn-archive-trigger').on('click', function(e) {
            let form = $(this).closest('form');

            Swal.fire({
                title: 'Delete User?',
                text: "User ini tidak akan muncul di daftar aktif, tapi datanya tetap ada di sistem.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
    </script>
@endsection