@extends('layouts.admin')

@section('title', 'Pengaturan Landing Page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-cog me-2"></i>Pengaturan Landing Page</h4>
                </div>
                <div class="card-body">
                    
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <!-- Logo Section -->
                    <div class="mb-5">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-image me-2 text-primary"></i>Logo Perusahaan
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                @if($settings['logo'])
                                <div class="mb-3">
                                    <img src="{{ Storage::url($settings['logo']) }}" alt="Logo" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                                @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>Logo belum diupload
                                </div>
                                @endif

                                <form action="{{ route('admin.settings.update-logo') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Upload Logo Baru</label>
                                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*" required>
                                        @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Format: JPG, PNG, SVG. Max: 2MB</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload me-2"></i>Update Logo
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- WhatsApp Section -->
                    <div class="mb-5">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="fab fa-whatsapp me-2 text-success"></i>Nomor WhatsApp
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{ route('admin.settings.update-wa') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Nomor WhatsApp (format: 628xxxxx)</label>
                                        <input type="text" name="whatsapp_number" class="form-control @error('whatsapp_number') is-invalid @enderror" value="{{ $settings['whatsapp_number'] }}" required>
                                        @error('whatsapp_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Contoh: 6281234567890</small>
                                    </div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-2"></i>Update WhatsApp
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Service Images Section -->
                    <div class="mb-5">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-images me-2 text-info"></i>Gambar Layanan
                        </h5>
                        
                        <!-- Multimedia Services -->
                        <h6 class="text-primary mb-3"><i class="fas fa-film me-2"></i>Multimedia</h6>
                        <div class="row mb-4">
                            <!-- Animasi 3D -->
                            <div class="col-md-6 col-lg-3 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Animasi 3D</h6>
                                        @if($settings['animasi_3d_image'])
                                        <img src="{{ Storage::url($settings['animasi_3d_image']) }}" class="img-fluid mb-2 rounded" alt="Animasi 3D">
                                        @else
                                        <div class="alert alert-secondary text-center py-5">
                                            <i class="fas fa-cube fa-3x mb-2"></i>
                                            <p class="mb-0 small">Belum ada gambar</p>
                                        </div>
                                        @endif
                                        <form action="{{ route('admin.settings.update-layanan-image') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="layanan_key" value="animasi_3d_image">
                                            <input type="file" name="image" class="form-control form-control-sm mb-2" accept="image/*" required>
                                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                                <i class="fas fa-upload me-1"></i>Update
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Visual Effect -->
                            <div class="col-md-6 col-lg-3 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Visual Effect</h6>
                                        @if($settings['visual_effect_image'])
                                        <img src="{{ Storage::url($settings['visual_effect_image']) }}" class="img-fluid mb-2 rounded" alt="Visual Effect">
                                        @else
                                        <div class="alert alert-secondary text-center py-5">
                                            <i class="fas fa-magic fa-3x mb-2"></i>
                                            <p class="mb-0 small">Belum ada gambar</p>
                                        </div>
                                        @endif
                                        <form action="{{ route('admin.settings.update-layanan-image') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="layanan_key" value="visual_effect_image">
                                            <input type="file" name="image" class="form-control form-control-sm mb-2" accept="image/*" required>
                                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                                <i class="fas fa-upload me-1"></i>Update
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Company Profile -->
                            <div class="col-md-6 col-lg-3 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Video Company Profile</h6>
                                        @if($settings['company_profile_image'])
                                        <img src="{{ Storage::url($settings['company_profile_image']) }}" class="img-fluid mb-2 rounded" alt="Company Profile">
                                        @else
                                        <div class="alert alert-secondary text-center py-5">
                                            <i class="fas fa-video fa-3x mb-2"></i>
                                            <p class="mb-0 small">Belum ada gambar</p>
                                        </div>
                                        @endif
                                        <form action="{{ route('admin.settings.update-layanan-image') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="layanan_key" value="company_profile_image">
                                            <input type="file" name="image" class="form-control form-control-sm mb-2" accept="image/*" required>
                                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                                <i class="fas fa-upload me-1"></i>Update
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- TVC -->
                            <div class="col-md-6 col-lg-3 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">TVC</h6>
                                        @if($settings['tvc_image'])
                                        <img src="{{ Storage::url($settings['tvc_image']) }}" class="img-fluid mb-2 rounded" alt="TVC">
                                        @else
                                        <div class="alert alert-secondary text-center py-5">
                                            <i class="fas fa-tv fa-3x mb-2"></i>
                                            <p class="mb-0 small">Belum ada gambar</p>
                                        </div>
                                        @endif
                                        <form action="{{ route('admin.settings.update-layanan-image') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="layanan_key" value="tvc_image">
                                            <input type="file" name="image" class="form-control form-control-sm mb-2" accept="image/*" required>
                                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                                <i class="fas fa-upload me-1"></i>Update
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- IT Services -->
                        <h6 class="text-primary mb-3"><i class="fas fa-laptop-code me-2"></i>Information Technology</h6>
                        <div class="row">
                            <!-- Web Developer -->
                            <div class="col-md-6 col-lg-3 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Web Developer</h6>
                                        @if($settings['web_developer_image'])
                                        <img src="{{ Storage::url($settings['web_developer_image']) }}" class="img-fluid mb-2 rounded" alt="Web Developer">
                                        @else
                                        <div class="alert alert-secondary text-center py-5">
                                            <i class="fas fa-laptop-code fa-3x mb-2"></i>
                                            <p class="mb-0 small">Belum ada gambar</p>
                                        </div>
                                        @endif
                                        <form action="{{ route('admin.settings.update-layanan-image') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="layanan_key" value="web_developer_image">
                                            <input type="file" name="image" class="form-control form-control-sm mb-2" accept="image/*" required>
                                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                                <i class="fas fa-upload me-1"></i>Update
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Apps Developer -->
                            <div class="col-md-6 col-lg-3 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Apps Developer</h6>
                                        @if($settings['apps_developer_image'])
                                        <img src="{{ Storage::url($settings['apps_developer_image']) }}" class="img-fluid mb-2 rounded" alt="Apps Developer">
                                        @else
                                        <div class="alert alert-secondary text-center py-5">
                                            <i class="fas fa-mobile-alt fa-3x mb-2"></i>
                                            <p class="mb-0 small">Belum ada gambar</p>
                                        </div>
                                        @endif
                                        <form action="{{ route('admin.settings.update-layanan-image') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="layanan_key" value="apps_developer_image">
                                            <input type="file" name="image" class="form-control form-control-sm mb-2" accept="image/*" required>
                                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                                <i class="fas fa-upload me-1"></i>Update
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Catatan:</strong> Perubahan akan langsung terlihat di halaman landing page. Format gambar yang direkomendasikan: JPG/PNG, ukuran maksimal 2MB.
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('landing') }}" target="_blank" class="btn btn-info">
                            <i class="fas fa-external-link-alt me-2"></i>Lihat Landing Page
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .card-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #495057;
        min-height: 40px;
    }
    .img-thumbnail {
        border: 2px solid #dee2e6;
    }
</style>
@endpush