@extends('layouts.app')
@section('title', 'Couriers')
@section('page-title')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold flex-column justify-content-center my-0">
            Courier Management
        </h1>
    </div>
@endsection
@section('content')
    <div class="card card-docs flex-row-fluid mb-2">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title align-items-start flex-column">
                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                        <path
                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                            fill="currentColor" />
                    </svg>
                </span>
                <input type="search" name="search" class="form-control form-control-solid ps-15 w-lg-250px w-150px" id="search" placeholder="Cari.." />
            </div>
            <div class="card-toolbar">
                <div class="d-flex flex-stack gap-3">
                    <select class="form-select w-150px" id="statusFilter" data-control="select2" data-placeholder="Semua Status" data-allow-clear="true">
                        <option></option>
                        @foreach (config('data.courier_status') as $key => $value)
                            <option value="{{ $key }}">{{ $value['label'] }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourierModal">
                        +Kurir
                    </button>
                </div>
                <div class="modal fade" tabindex="-1" id="addCourierModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="addCourierForm" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h3 class="modal-title w-100 text-center">Tambah Kurir</h3>
                                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                        <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-5 d-flex justify-content-center">
                                        <div class="image-input image-input-outline text-center" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }})">
                                            <div class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }})">
                                                <div class="image-input-wrapper w-150px h-150px" style="background-image: none;"></div>
                                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-kt-initialized="1">
                                                    <i class="bi bi-pencil-fill fs-7"></i>
                                                    <input type="file" name="photo" accept=".png, .jpg, .jpeg">
                                                    <input type="hidden" name="photo_remove" value="1">
                                                </label>
                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-kt-initialized="1">
                                                    <i class="bi bi-x fs-2"></i>
                                                </span>
                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-kt-initialized="1">
                                                    <i class="bi bi-x fs-2"></i>
                                                </span>
                                            </div>
                                            <div class="form-text">Max. 5MB, 1:1 ratio</div>
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="user_id" class="required form-label">User</label>
                                        <select name="user_id" class="form-control form-select" id="user_id" data-control="select2"></select>
                                    </div>
                                    <div class="mb-5">
                                        <label for="phonenumber" class="required form-label">No. Whatsapp</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="+62">+62</span>
                                            <input type="text" name="phonenumber" id="phonenumber" class="form-control" placeholder="8xxx..." aria-label="phonenumber" aria-describedby="+62" required />
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="nik" class="required form-label">Nomor Induk Kewarganegaraan (NIK)</label>
                                        <input type="text" name="nik" id="nik" class="form-control" placeholder="NIK" required />
                                    </div>
                                    <div class="mb-5">
                                        <label for="ktp" class="required form-label">Foto KTP</label>
                                        <div class="d-flex justify-content-center">
                                            <div class="image-input image-input-outline text-center" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }})">
                                                <div class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }})">
                                                    <div class="image-input-wrapper w-300px h-150px" style="background-image: none;"></div>
                                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-kt-initialized="1">
                                                        <i class="bi bi-pencil-fill fs-7"></i>
                                                        <input type="file" name="ktp" accept=".png, .jpg, .jpeg" required>
                                                        <input type="hidden" name="ktp_remove" value="1">
                                                    </label>
                                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-kt-initialized="1">
                                                        <i class="bi bi-x fs-2"></i>
                                                    </span>
                                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-kt-initialized="1">
                                                        <i class="bi bi-x fs-2"></i>
                                                    </span>
                                                </div>
                                                <div class="form-text">Max. 5MB</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="nosim" class="required form-label">Nomor SIM</label>
                                        <input type="text" name="nosim" id="nosim" class="form-control" placeholder="No. SIM" required />
                                    </div>
                                    <div class="mb-5">
                                        <label for="sim" class="required form-label">Foto SIM</label>
                                        <div class="d-flex justify-content-center">
                                            <div class="image-input image-input-outline text-center" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }})">
                                                <div class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }})">
                                                    <div class="image-input-wrapper w-300px h-150px" style="background-image: none;"></div>
                                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-kt-initialized="1">
                                                        <i class="bi bi-pencil-fill fs-7"></i>
                                                        <input type="file" name="sim" accept=".png, .jpg, .jpeg" required>
                                                        <input type="hidden" name="sim_remove" value="1">
                                                    </label>
                                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-kt-initialized="1">
                                                        <i class="bi bi-x fs-2"></i>
                                                    </span>
                                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-kt-initialized="1">
                                                        <i class="bi bi-x fs-2"></i>
                                                    </span>
                                                </div>
                                                <div class="form-text">Max. 5MB</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="nopol" class="required form-label">Plat Nomor</label>
                                        <input type="text" name="nopol" id="nopol" class="form-control" placeholder="A XXXX XXXX" required />
                                    </div>
                                    <div class="mb-5">
                                        <label for="platno" class="required form-label">Foto Platno</label>
                                        <div class="d-flex justify-content-center">
                                            <div class="image-input image-input-outline text-center" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }})">
                                                <div class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }})">
                                                    <div class="image-input-wrapper w-300px h-150px" style="background-image: none;"></div>
                                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-kt-initialized="1">
                                                        <i class="bi bi-pencil-fill fs-7"></i>
                                                        <input type="file" name="platno" accept=".png, .jpg, .jpeg" required>
                                                        <input type="hidden" name="platno_remove" value="1">
                                                    </label>
                                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-kt-initialized="1">
                                                        <i class="bi bi-x fs-2"></i>
                                                    </span>
                                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-kt-initialized="1">
                                                        <i class="bi bi-x fs-2"></i>
                                                    </span>
                                                </div>
                                                <div class="form-text">Max. 5MB</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="vehicle_type" class="required form-label">Jenis Kendaraan</label>
                                        <select name="vehicle_type" class="form-control form-select" id="vehicle_type" data-control="select2" data-placeholder="Pilih Jenis Kendaraan" data-hide-search="true">
                                            <option value=""></option>
                                            <option value="Sepeda Motor">Sepeda Motor</option>
                                        </select>
                                    </div>
                                    <div class="mb-5">
                                        <label for="vehicle_type" class="required form-label">Spesialis Kurir</label>
                                        <select name="courier_specialist" class="form-control form-select" data-placeholder="Spesialis Kurir">
                                            <option value="Toko Makanan dan Minuman">Toko Makanan dan Minuman</option>
                                            <option value="Pusat Perbelanjaan dan Grosir">Pusat Perbelanjaan dan Grosir</option>
                                            <option value="Toko Kerajinan dan Suvenir">Toko Kerajinan dan Suvenir</option>
                                            <option value="Toko Spesialis dan Lain-lain">Toko Spesialis dan Lain-lain</option>
                                            <option value="Toko Rumah dan Kebutuhan Sehari-hari">Toko Rumah dan Kebutuhan Sehari-hari</option>
                                            <option value="Toko Pakaian dan Aksesori">Toko Pakaian dan Aksesori</option>
                                            <option value="Toko Perhiasan dan Aksesori Mewah">Toko Perhiasan dan Aksesori Mewah</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary me-10" id="btn_add_courier">
                                        <span class="indicator-label">
                                            Simpan
                                        </span>
                                        <span class="indicator-progress">
                                            Tunggu <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <table id="couriers-table" class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="fw-semibold fs-6 text-muted">
                        <th>No</th>
                        <th>Profil</th>
                        <th>Nama Kurir</th>
                        <th>No. Whatsapp</th>
                        <th>Plat Nomor</th>
                        <th>Jenis Kendaraan</th>
                        <th>Cluster</th>
                        <th>Saldo</th>
                        <th>Spesialis</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="editCourierModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editCourierForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="courierId">
                    <div class="modal-header">
                        <h3 class="modal-title w-100 text-center">Edit Kurir</h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-5 d-flex justify-content-center">
                            <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }})">
                                <div class="image-input-wrapper w-150px h-150px" id="editPhoto"></div>
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-kt-initialized="1">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <input type="file" name="photo" accept=".png, .jpg, .jpeg">
                                    <input type="hidden" name="photo_remove">
                                </label>
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-kt-initialized="1">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-kt-initialized="1">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label for="user_id" class="required form-label">User</label>
                            <select name="user_id" class="form-control form-select" id="editUser_id" data-control="select2"></select>
                        </div>
                        <div class="mb-5">
                            <label for="phonenumber" class="required form-label">No. Whatsapp</label>
                            <div class="input-group">
                                <span class="input-group-text" id="+62">+62</span>
                                <input type="text" name="phonenumber" id="editPhonenumber" class="form-control" placeholder="8xxx..." aria-label="phonenumber" aria-describedby="+62" required />
                            </div>
                        </div>
                        <div class="mb-5">
                            <label for="nik" class="required form-label">Nomor Induk Kewarganegaraan (NIK)</label>
                            <input type="text" name="nik" id="editNik" class="form-control" placeholder="NIK" required />
                        </div>
                        <div class="mb-5">
                            <label for="ktp" class="required form-label">Foto KTP</label>
                            <div class="d-flex justify-content-center">
                                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }})">
                                    <div class="image-input-wrapper w-300px h-150px" id="editKtp"></div>
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-kt-initialized="1">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="ktp" accept=".png, .jpg, .jpeg">
                                        <input type="hidden" name="ktp_remove">
                                    </label>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-kt-initialized="1">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-kt-initialized="1">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label for="nosim" class="required form-label">Nomor SIM</label>
                            <input type="text" name="nosim" id="editNosim" class="form-control" placeholder="No. SIM" required />
                        </div>
                        <div class="mb-5">
                            <label for="sim" class="required form-label">Foto SIM</label>
                            <div class="d-flex justify-content-center">
                                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }})">
                                    <div class="image-input-wrapper w-300px h-150px" id="editSim"></div>
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-kt-initialized="1">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="sim" accept=".png, .jpg, .jpeg">
                                        <input type="hidden" name="sim_remove">
                                    </label>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-kt-initialized="1">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-kt-initialized="1">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label for="nopol" class="required form-label">Plat Nomor</label>
                            <input type="text" name="nopol" id="editNopol" class="form-control" placeholder="A XXXX XXXX" required />
                        </div>
                        <div class="mb-5">
                            <label for="platno" class="required form-label">Foto Platno</label>
                            <div class="d-flex justify-content-center">
                                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }})">
                                    <div class="image-input-wrapper w-300px h-150px" id="editplatno"></div>
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-kt-initialized="1">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="platno" accept=".png, .jpg, .jpeg">
                                        <input type="hidden" name="platno_remove">
                                    </label>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-kt-initialized="1">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-kt-initialized="1">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label for="vehicle_type" class="required form-label">Jenis Kendaraan</label>
                            <select name="vehicle_type" class="form-control form-select" id="editVehicle_type" data-control="select2" data-placeholder="Pilih Jenis Kendaraan" data-hide-search="true">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label for="vehicle_type" class="required form-label">Spesialis Kurir</label>
                            <select name="courier_specialist" class="form-control form-select" data-placeholder="Spesialis Kurir" id="edit_courier_specialist">
                                <option value="Toko Makanan dan Minuman">Toko Makanan dan Minuman</option>
                                <option value="Pusat Perbelanjaan dan Grosir">Pusat Perbelanjaan dan Grosir</option>
                                <option value="Toko Kerajinan dan Suvenir">Toko Kerajinan dan Suvenir</option>
                                <option value="Toko Spesialis dan Lain-lain">Toko Spesialis dan Lain-lain</option>
                                <option value="Toko Rumah dan Kebutuhan Sehari-hari">Toko Rumah dan Kebutuhan Sehari-hari</option>
                                <option value="Toko Pakaian dan Aksesori">Toko Pakaian dan Aksesori</option>
                                <option value="Toko Perhiasan dan Aksesori Mewah">Toko Perhiasan dan Aksesori Mewah</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary me-10" id="btn_edit_courier">
                            <span class="indicator-label">
                                Simpan
                            </span>
                            <span class="indicator-progress">
                                Tunggu <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="updateStatusCourierModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="updateStatusCourierForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="courierIdStatus" name="id">
                    <div class="modal-header">
                        <h3 class="modal-title w-100 text-center">Update Status Kurir</h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-5">
                            <label for="vehicle_type" class="form-label">Nama Kurir</label>
                            <div id="courierName"></div>
                        </div>
                        <div class="mb-5">
                            <label for="vehicle_type" class="form-label">Status saat ini</label>
                            <div id="badge"></div>
                        </div>
                        <div class="mb-5">
                            <label for="status" class="required form-label">Status</label>
                            <select name="status" class="form-control form-select" id="editStatus" data-control="select2" data-placeholder="Pilih status" data-hide-search="true">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary me-10" id="btn_edit_courier">
                            <span class="indicator-label">
                                Simpan
                            </span>
                            <span class="indicator-progress">
                                Tunggu <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="editClusterModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="updateClusterForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="courierIdCluster" name="id">
                    <div class="modal-header">
                        <h3 class="modal-title w-100 text-center">Update Cluster Kurir</h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-5">
                            <label for="vehicle_type" class="form-label">Nama Kurir</label>
                            <div id="courierNameCluster"></div>
                        </div>
                        <div class="mb-5">
                            <label for="cluster_id" class="required form-label">Cluster</label>
                            <select name="new_cluster" class="form-control form-select" id="new_cluster">
                                <option value="Kluster 1">Kluster 1</option>
                                <option value="Kluster 2">Kluster 2</option>
                                <option value="Kluster 3">Kluster 3</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary me-10" id="btn_edit_courier">
                            <span class="indicator-label">
                                Simpan
                            </span>
                            <span class="indicator-progress">
                                Tunggu <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        var datatable = $('#couriers-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            stateSave: false,
            ajax: {
                url: '{!! url()->current() !!}',
            },
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: true,
                searchable: true,
                width: '5'
            }, {
                data: 'photo',
                name: 'photo',
                orderable: false,
                searchable: false,
                width: '10'
            }, {
                data: 'name',
                name: 'name',
                orderable: true,
                searchable: true,
                width: '15'
            }, {
                data: 'phonenumber',
                name: 'phonenumber',
                orderable: true,
                searchable: true,
                width: '10'
            }, {
                data: 'nopol',
                name: 'nopol',
                orderable: true,
                searchable: true,
                width: '10'
            }, {
                data: 'vehicle_type',
                name: 'vehicle_type',
                orderable: true,
                searchable: true,
                width: '10'
            }, {
                data: 'new_cluster',
                name: 'new_cluster',
                orderable: true,
                searchable: true,
                width: '10'
            }, {
                data: 'balance',
                name: 'balance',
                orderable: true,
                searchable: true,
                width: '10'
            }, 
            {
                data: 'courier_specialist',
                name: 'courier_specialist',
                orderable: true,
                searchable: true,
                width: '10'
            },
            {
                data: 'badge',
                name: 'badge',
                orderable: false,
                searchable: false,
                width: '10'
            }, {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                width: '5',
                className: 'text-center'
            }, ],
            order: [
                [0, "asc"]
            ]
        });

        $('#search').on('keyup', function() {
            datatable.search(this.value).draw();
        });

        $('#statusFilter').on('change', function() {
            datatable.ajax.url('{!! url()->current() !!}?status=' + $(this).val()).load();
            datatable.ajax.reload();
        });

        $('#updateStatusCourierModal').on('hidden.bs.modal', function() {
            $('#courierName').html("");
            $('#badge').html("");
        })

        $(document).ready(function() {
            $('#user_id').select2({
                ajax: {
                    url: "{{ route('dropdown.users') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            add: 1,
                        }
                        return query;
                    },
                },
                placeholder: 'Pilih User',
                dropdownParent: $("#addCourierModal"),
                width: '100%'
            });
            $('#editUser_id').select2({
                ajax: {
                    url: "{{ route('dropdown.users') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            add: 0,
                        }
                        return query;
                    },
                },
                placeholder: 'Pilih User',
                dropdownParent: $("#editCourierModal > .modal-dialog > .modal-content"),
                width: '100%',
            });
        })

        $('#addCourierForm').submit(function(e) {
            e.preventDefault();
            $('#btn_add_courier').prop('disabled', true);
            $('#btn_add_courier').attr("data-kt-indicator", "on");
            $.ajax({
                url: "{{ route('couriers.store') }}",
                type: "POST",
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                data: new FormData(this),
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                },
                success: function(data) {
                    toastr.success(data.message, 'Sukses');
                    $('#addCourierModal').modal('toggle');
                    $('#addCourierForm').trigger('reset');
                    $('#couriers-table').DataTable().ajax.reload();
                }
            });
            $('#btn_add_courier').prop('disabled', false);
            $('#btn_add_courier').removeAttr("data-kt-indicator");
        });

        $(document).on('click', '#editCourier', function() {
            var id = $(this).data('id');
            var url = "{{ route('couriers.edit', ':id') }}";
            $.ajax({
                url: url.replace(':id', id),
                type: "GET",
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                },
                success: function(data) {
                    $('#editPhoto').css('background-image', 'url(' + data.photo + ')');
                    $('#courierId').val(data.id);
                    $('#editUser_id').append($("<option selected></option>").val(data.user_id).text(data.user.name)).trigger('change');
                    $('#editNik').val(data.nik);
                    $('#editKtp').css('background-image', 'url(' + data.ktp + ')');
                    $('#editNosim').val(data.nosim);
                    $('#editSim').css('background-image', 'url(' + data.sim + ')');
                    $('#editNopol').val(data.nopol);
                    $('#editplatno').css('background-image', 'url(' + data.platno + ')');
                    $('#editPhonenumber').val(data.phonenumber.substring(2));
                    $('#editVehicle_type').append($("<option selected></option>").val(data.vehicle_type).text(data.vehicle_type)).trigger('change');
                    $('#edit_courier_specialist').append($("<option selected></option>").val(data.courier_specialist).text(data.courier_specialist)).trigger('change');
                }
            });
        });

        $('#editCourierForm').submit(function(e) {
            e.preventDefault();
            var id = $('#courierId').val();
            var url = "{{ route('couriers.update', ':id') }}";
            $('#btn_edit_courier').prop('disabled', true);
            $('#btn_edit_courier').attr("data-kt-indicator", "on");
            $.ajax({
                url: url.replace(':id', id),
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                data: new FormData(this),
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                },
                success: function(data) {
                    toastr.success(data.message, 'Sukses');
                    $('#editCourierModal').modal('toggle');
                    $('#couriers-table').DataTable().ajax.reload();
                }
            });
            $('#btn_edit_courier').prop('disabled', false);
            $('#btn_edit_courier').removeAttr("data-kt-indicator");
        });

        $(document).on('click', '#updateStatusCourier', function() {
            var id = $(this).data('id');
            var url = "{{ route('couriers.edit', ':id') }}";
            $('#editStatus').select2({
                ajax: {
                    url: url.replace(':id', id),
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                        }
                        return query;
                    },
                },
                placeholder: 'Pilih User',
                dropdownParent: $("#updateStatusCourierModal > .modal-dialog > .modal-content"),
                width: '100%',
            });
            $.ajax({
                url: url.replace(':id', id),
                type: "GET",
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                },
                success: function(data) {
                    $('#courierIdStatus').val(data.id);
                    $('#courierName').append(data.user.name);
                    $('#badge').append(data.badge);
                }
            });
        });

        $('#updateStatusCourierForm').submit(function(e) {
            e.preventDefault();
            var id = $('#courierId').val();
            var url = "{{ route('couriers.update.status', ['id' => ':id']) }}";
            $('#btn_edit_courier').prop('disabled', true);
            $('#btn_edit_courier').attr("data-kt-indicator", "on");
            $.ajax({
                url: url.replace(':id', id),
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                data: new FormData(this),
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                },
                success: function(data) {
                    toastr.success(data.message, 'Sukses');
                    $('#updateStatusCourierModal').modal('toggle');
                    $('#couriers-table').DataTable().ajax.reload();
                }
            });
            $('#btn_edit_courier').prop('disabled', false);
            $('#btn_edit_courier').removeAttr("data-kt-indicator");
        });

        $(document).on("click", "#deleteConfirm", function(e) {
            e.preventDefault();
            var name = $(this).data('name');
            var text = 'Apakah anda yakin ingin menghapus kurir ":name"?'
            Swal.fire({
                customClass: {
                    confirmButton: 'bg-danger',
                },
                title: 'Apakah anda yakin?',
                text: text.replace(':name', name),
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.preventDefault();
                    var id = $(this).data("id");
                    var route = "{{ route('couriers.destroy', ':id') }}";
                    $.ajax({
                        url: route.replace(':id', id),
                        type: 'DELETE',
                        data: {
                            _token: $("meta[name='csrf-token']").attr("content"),
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                timerProgressBar: true,
                            })
                            $('#couriers-table').DataTable().ajax.reload();
                        },
                        error: function(data) {
                            toastr.error(data.responseJSON.message, 'Error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '#editCluster', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            $('#courierNameCluster').text('');
            $('#cluster_id').select2({
                ajax: {
                    url: "{{ route('dropdown.clusters') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                        }
                        return query;
                    },
                },
                placeholder: 'Pilih Cluster',
                dropdownParent: $("#editClusterModal > .modal-dialog > .modal-content"),
                width: '100%',
            });
            var url = "{{ route('couriers.edit', ':id') }}";
            $.ajax({
                url: url.replace(':id', id),
                type: "GET",
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                },
                success: function(data) {
                    $('#courierIdCluster').val(data.id);
                    $('#courierNameCluster').text(data.user.name);
                    $('#cluster_id').append($("<option selected></option>").val(data.cluster_id).text(data.cluster.name)).trigger('change');
                }
            });
        });

        $('#updateClusterForm').submit(function(e) {
            e.preventDefault();
            var id = $('#courierIdCluster').val();
            var url = "{{ route('couriers.update.cluster', ['id' => ':id']) }}";
            $('#btn_edit_courier').prop('disabled', true);
            $('#btn_edit_courier').attr("data-kt-indicator", "on");
            $.ajax({
                url: url.replace(':id', id),
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                data: new FormData(this),
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                },
                success: function(data) {
                    toastr.success(data.message, 'Sukses');
                    $('#editClusterModal').modal('toggle');
                    $('#couriers-table').DataTable().ajax.reload();
                }
            });
            $('#btn_edit_courier').prop('disabled', false);
            $('#btn_edit_courier').removeAttr("data-kt-indicator");
        });
    </script>
@endpush
