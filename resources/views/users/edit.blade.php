<x-app-layout>
    <x-slot name="header">
        Edit User
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto bg-white p-6 shadow rounded">

            @if ($errors->any())
                <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.update',$user->id) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label>Nama</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name',$user->name) }}"
                           class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label>Email</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email',$user->email) }}"
                           class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label>Password (kosongkan jika tidak diganti)</label>
                    <input type="password"
                           name="password"
                           class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label>Role</label>
                    <select name="role" class="w-full border rounded p-2">
                        <option value="admin" {{ $user->role=='admin'?'selected':'' }}>Admin</option>
                        <option value="user" {{ $user->role=='user'?'selected':'' }}>User</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label>Status</label>
                    <select name="status" class="w-full border rounded p-2">
                        <option value="active" {{ $user->status=='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ $user->status=='inactive'?'selected':'' }}>Inactive</option>
                        <option value="blocked" {{ $user->status=='blocked'?'selected':'' }}>Blocked</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label>Alamat</label>
                    <textarea name="alamat"
                              class="w-full border rounded p-2">{{ old('alamat',$user->alamat) }}</textarea>
                </div>

                <div class="mb-4">
                    <label>Foto Profile</label><br>
                    @if($user->foto_profile)
                        <img src="{{ asset('storage/'.$user->foto_profile) }}"
                             class="w-24 mb-2 rounded">
                    @endif
                    <input type="file" name="foto_profile" class="w-full">
                </div>

                <div class="mb-4">
                    <label>Foto KTP</label><br>
                    @if($user->foto_ktp)
                        <img src="{{ asset('storage/'.$user->foto_ktp) }}"
                             class="w-40 mb-2 rounded">
                    @endif
                    <input type="file" name="foto_ktp" class="w-full">
                </div>

                <button class="bg-blue-600 text-white px-4 py-2 rounded">
                    Update
                </button>

            </form>
        </div>
    </div>
</x-app-layout>
