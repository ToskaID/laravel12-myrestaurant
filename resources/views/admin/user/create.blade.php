@extends('admin.layouts.master')
@section('title', 'Tambah Users')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Tambah Data Users</h3>
            <p class="text-subtitle text-muted">Silahkan isi data user yang ingin ditambahkan</p>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">Submit Error!</h5>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <form id="form-register" class="form" action="{{ route('users.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="fullname">FullName</label>
                            <input type="text" class="form-control" id="fullname" value="{{ old('fullname')}}" placeholder="Masukkan fullname" name="fullname" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" value="{{ old('username')}}" placeholder="Masukkan username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" value="{{ old('password')}}" placeholder="password minimal 8 karakter" name="password" required>
                            <small><a href="#" class="toggle-password" data-target="password">Lihat Password</a></small>
                        </div>
                         <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirmation" value="{{ old('password_confirmation')}}" placeholder="konfirmasi password" name="password_confirmation" required>
                            <small><a href="#" class="toggle-password" data-target="password_confirmation">Lihat Password</a></small>
                        </div>
                       
                        <div class="form-group">
                            <label for="name">Email</label>
                            <input type="email" class="form-control" id="name" value="{{ old('email')}}" placeholder="Masukkan email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Phone</label>
                            <input type="text" class="form-control" id="name" value="{{ old('phone')}}" placeholder="Masukkan No HandPhone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Role</label>
                            <select class="form-control" id="role_id" name="role_id" require>
                                <option value="" disabled selected>Pilih Role</option>
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}">{{$role->role_name}}</option>
                                @endforeach
                            </select>
                        
                        </div>


                        <div class="form-group d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                            <a href="{{ route('roles.index') }}" type="submit" class="btn btn-light-secondary me-1 mb-1">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Fitur Show/Hide Password
    const toggles = document.querySelectorAll('.toggle-password');
    
    toggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Ambil id dari data-target
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            
            // Cek tipe input
            if (input.getAttribute('type') === 'password') {
                input.setAttribute('type', 'text');
                this.textContent = 'Sembunyikan'; // Mengubah teks link
            } else {
                input.setAttribute('type', 'password');
                this.textContent = 'Lihat Password';
            }
        });
    });

    // 2. Validasi Password Match (Native)
    const form = document.getElementById('form-register');
    if (form) {
        form.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (password !== confirmPassword) {
                e.preventDefault(); // Batalkan kirim form
                alert('Password dan Konfirmasi Password tidak cocok!');
            }
        });
    }
});
</script>
@endsection