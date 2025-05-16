    @extends('layouts.admin')
    @section('title', $title)
    @section('subtitle', $subtitle)

    @push('styles')
        <style>
            .fas, .far {
                color: #f39c12;  /* Màu vàng cho sao */
                font-size: 18px;  /* Kích thước sao */
            }
        </style>
    @endpush
    @section('content')
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">{{ $subtitle }}</h4>
                <ul class="breadcrumbs">
                    <li class="nav-home">
                        <a href="{{ route('admin') }}">
                            <i class="icon-home"></i>
                        </a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="{{ route('admins.material.index') }}">Danh sách nguyên liệu</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item">Chỉnh sửa</li>
                </ul>
            </div>

            {{-- Thông báo --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header"><h5 class="card-title">Thông tin nguyên liệu</h5></div>
                        <div class="card-body">
                            <form action="{{ route('admins.material.update', $nguyenLieu->ma_nguyen_lieu) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="ma_nguyen_lieu">Mã nguyên liệu</label>
                                    <input type="text" id="ma_nguyen_lieu" class="form-control" value="{{ $nguyenLieu->ma_nguyen_lieu }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="ten_nguyen_lieu">Tên nguyên liệu</label>
                                    <input type="text" name="ten_nguyen_lieu" id="ten_nguyen_lieu" class="form-control @error('ten_nguyen_lieu') is-invalid @enderror" value="{{ old('ten_nguyen_lieu', $nguyenLieu->ten_nguyen_lieu) }}" required>
                                    @error('ten_nguyen_lieu') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="ma_nha_cung_cap">Nhà cung cấp</label>
                                    <select name="ma_nha_cung_cap" id="ma_nha_cung_cap" class="form-control @error('ma_nha_cung_cap') is-invalid @enderror">
                                        <option value="">-- Chọn nhà cung cấp --</option>
                                        @foreach($nhaCungCaps as $supplier)
                                            <option value="{{ $supplier->ma_nha_cung_cap }}" {{ old('ma_nha_cung_cap', $nguyenLieu->ma_nha_cung_cap) == $supplier->ma_nha_cung_cap ? 'selected' : '' }}>
                                                {{ $supplier->ten_nha_cung_cap }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ma_nha_cung_cap') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="so_luong">Số lượng</label>
                                    <input type="number" name="so_luong" id="so_luong" class="form-control @error('so_luong') is-invalid @enderror" value="{{ old('so_luong', $nguyenLieu->so_luong) }}" min="0" required>
                                    @error('so_luong') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="don_vi">Đơn vị</label>
                                    <input type="text" name="don_vi" id="don_vi" class="form-control @error('don_vi') is-invalid @enderror" value="{{ old('don_vi', $nguyenLieu->don_vi) }}" required>
                                    @error('don_vi') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="gia">Giá (VNĐ)</label>
                                    <input type="number" name="gia" id="gia" class="form-control @error('gia') is-invalid @enderror" value="{{ old('gia', $nguyenLieu->gia) }}" min="0" required>
                                    @error('gia') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="loai_nguyen_lieu">Loại</label>
                                    <select name="loai_nguyen_lieu" id="loai_nguyen_lieu" class="form-control @error('loai_nguyen_lieu') is-invalid @enderror" required>
                                        <option value="0" {{ old('loai_nguyen_lieu', $nguyenLieu->loai_nguyen_lieu) == 0 ? 'selected' : '' }}>Nguyên liệu</option>
                                        <option value="1" {{ old('loai_nguyen_lieu', $nguyenLieu->loai_nguyen_lieu) == 1 ? 'selected' : '' }}>Vật liệu</option>
                                    </select>
                                    @error('loai_nguyen_lieu') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="trang_thai">Trạng thái</label>
                                    <select name="trang_thai" id="trang_thai" class="form-control @error('trang_thai') is-invalid @enderror" required>
                                        <option value="1" {{ old('trang_thai', $nguyenLieu->trang_thai) == 1 ? 'selected' : '' }}>Còn hàng</option>
                                        <option value="2" {{ old('trang_thai', $nguyenLieu->trang_thai) == 2 ? 'selected' : '' }}>Hết hàng</option>
                                        <option value="3" {{ old('trang_thai', $nguyenLieu->trang_thai) == 3 ? 'selected' : '' }}>Ngừng bán</option>
                                    </select>
                                    @error('trang_thai') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="text-center form-group">
                                    <button type="submit" class="btn btn-primary">Cập nhật nguyên liệu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection


