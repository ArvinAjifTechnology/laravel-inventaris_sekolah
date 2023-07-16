<div class="form-group">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user[0]->username) }}" unique>
    @error('username')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <label for="user_code">User Code</label>
    <input type="text" id="user_code" name="user_code" class="form-control @error('user_code') is-invalid @enderror" value="{{ old('user_code', $user[0]->user_code) }}" unique>
    @error('user_code')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user[0]->email) }}" required>
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <label for="first_name">First Name</label>
    <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name', $user[0]->first_name) }}">
</div>
<div class="form-group">
    <label for="last_name">Last Name</label>
    <input type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name', $user[0]->last_name) }}">
</div>
<div class="form-group">
    <label for="level">Level</label>
    <select id="level" name="level" class="form-control">
        <option value="admin" {{ old('level', $user[0]->level) == 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="teacher" {{ old('level', $user[0]->level) == 'teacher' ? 'selected' : '' }}>Teacher</option>
        <option value="student" {{ old('level', $user[0]->level) == 'student' ? 'selected' : '' }}>Student</option>
    </select>
</div>
<div class="form-group">
    <label for="gender">Gender</label>
    <select id="gender" name="gender" class="form-control">
        <option value="laki-laki" {{ old('gender', $user[0]->gender) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
        <option value="perempuan" {{ old('gender', $user[0]->gender) == 'perempuan' ? 'selected' : '' }}>perempuan</option>
    </select>
</div>
