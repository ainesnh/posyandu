<div class="box-body" style="padding: 20px;">
    {{-- Nama --}}
    <div class="form-group @error('name') has-error @enderror">
        <label for="name">Nama Lengkap</label>
        <input type="text" name="name" class="form-control" id="name" 
               value="{{ old('name', $user->name ?? '') }}" placeholder="Masukkan nama lengkap" required>
        @error('name') <span class="help-block">{{ $message }}</span> @enderror
    </div>

    {{-- Email --}}
    <div class="form-group @error('email') has-error @enderror">
        <label for="email">Alamat Email</label>
        <input type="email" name="email" class="form-control" id="email" 
               value="{{ old('email', $user->email ?? '') }}" placeholder="email@example.com" required>
        @error('email') <span class="help-block">{{ $message }}</span> @enderror
    </div>

    {{-- Role --}}
    <div class="form-group @error('role_id') has-error @enderror">
        <label for="role_id">Role User</label>
        <select name="role_id" id="role_id" class="form-control" required>
            <option value="">-- Pilih Role --</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" 
                    {{ (old('role_id', $user->role_id ?? '') == $role->id) ? 'selected' : '' }}>
                    {{ ucfirst($role->nama_role) }} 
                </option>
            @endforeach
        </select>
        @error('role_id') <span class="help-block">{{ $message }}</span> @enderror
    </div>

    <hr style="border-top: 1px dashed #ddd;">

    {{-- Password --}}
    <div class="form-group @error('password') has-error @enderror">
        <label for="password">Password {{ isset($user->id) ? '(Kosongkan jika tidak ingin diubah)' : '' }}</label>
        <input type="password" name="password" class="form-control" id="password" 
               placeholder="Masukkan password" {{ isset($user->id) ? '' : 'required' }}>
        @error('password') <span class="help-block">{{ $message }}</span> @enderror
    </div>

    {{-- Konfirmasi Password --}}
    <div class="form-group">
        <label for="password_confirmation">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control" 
               id="password_confirmation" placeholder="Ulangi password">
    </div>

    {{-- Status --}}
    <div class="form-group">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="isactive" value="1" 
                    {{ old('isactive', $user->isactive ?? true) ? 'checked' : '' }}>
                <strong>Akun Aktif</strong>
            </label>
        </div>
    </div>
</div>