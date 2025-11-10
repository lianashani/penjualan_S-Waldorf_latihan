@extends('member.layout')
@section('title','Profil Saya')
@push('styles')
<style>
/* Main Container */
.profile-container {
    max-width: 1400px;
    margin: 0 auto;
}

/* Cover Photo Section */
.profile-cover {
    height: 280px;
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
    border-radius: 16px 16px 0 0;
    position: relative;
    overflow: hidden;
}
.profile-cover::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.5;
}

/* Profile Header Card */
.profile-header-card {
    background: #fff;
    border-radius: 0 0 16px 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    margin-top: -80px;
    position: relative;
    z-index: 10;
    padding: 0 2rem 2rem;
}

/* Avatar Section */
.avatar-section {
    display: flex;
    align-items: flex-end;
    gap: 2rem;
    padding-top: 2rem;
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 2rem;
}
.profile-avatar-large {
    width: 140px;
    height: 140px;
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: #fff;
    box-shadow: 0 8px 32px rgba(0,0,0,0.15);
    border: 5px solid #fff;
    margin-top: -70px;
    flex-shrink: 0;
}
.profile-info-main {
    flex: 1;
}
.profile-name-large {
    font-size: 2rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
    letter-spacing: -0.5px;
}
.profile-meta {
    display: flex;
    gap: 2rem;
    align-items: center;
    color: #666;
    font-size: 0.95rem;
}
.profile-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.profile-meta-item i {
    color: #1a1a1a;
}

/* Tabs Navigation */
.profile-tabs {
    display: flex;
    gap: 0.5rem;
    margin-top: 2rem;
    border-bottom: 2px solid #f0f0f0;
}
.profile-tab {
    padding: 1rem 2rem;
    background: none;
    border: none;
    color: #666;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    font-size: 0.95rem;
}
.profile-tab:hover {
    color: #1a1a1a;
}
.profile-tab.active {
    color: #1a1a1a;
}
.profile-tab.active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 2px;
    background: #1a1a1a;
}

/* Tab Content */
.tab-content {
    display: none;
    padding: 2rem 0;
}
.tab-content.active {
    display: block;
}

/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}
.stat-card-modern {
    background: linear-gradient(135deg, #fafafa 0%, #fff 100%);
    border: 1px solid #e8e8e8;
    border-radius: 12px;
    padding: 2rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}
.stat-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: radial-gradient(circle, rgba(0,0,0,0.03) 0%, transparent 70%);
}
.stat-card-modern:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    border-color: #1a1a1a;
}
.stat-card-modern.gold {
    background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
    border-color: #ffd700;
}
.stat-card-modern.gold:hover {
    box-shadow: 0 8px 24px rgba(255,215,0,0.3);
}
.stat-icon-modern {
    width: 60px;
    height: 60px;
    background: rgba(0,0,0,0.05);
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin-bottom: 1.5rem;
    color: #1a1a1a;
}
.stat-card-modern.gold .stat-icon-modern {
    background: rgba(0,0,0,0.1);
}
.stat-label-modern {
    font-size: 0.875rem;
    color: #666;
    margin-bottom: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}
.stat-value-modern {
    font-size: 2rem;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1;
}

/* Form Section */
.form-section {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 12px;
    padding: 2.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    margin-bottom: 2rem;
}
.form-section-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #1a1a1a;
}
.form-section-subtitle {
    color: #666;
    margin-bottom: 2rem;
    font-size: 0.95rem;
}
.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}
.form-group-modern {
    margin-bottom: 0;
}
.form-label-modern {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    display: block;
    color: #1a1a1a;
}
.form-control-modern {
    width: 100%;
    padding: 0.875rem 1.25rem;
    border: 2px solid #e8e8e8;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #fafafa;
    color: #1a1a1a;
}
.form-control-modern:focus {
    outline: none;
    border-color: #1a1a1a;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(0,0,0,0.05);
}
.form-control-modern:disabled {
    background: #f5f5f5;
    color: #999;
    cursor: not-allowed;
}

/* Buttons */
.btn-group {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}
.btn-modern {
    padding: 0.875rem 2.5rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.btn-primary-modern {
    background: #1a1a1a;
    color: #fff;
    border-color: #1a1a1a;
}
.btn-primary-modern:hover {
    background: #000;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.25);
}
.btn-secondary-modern {
    background: #fff;
    color: #1a1a1a;
    border-color: #e8e8e8;
}
.btn-secondary-modern:hover {
    border-color: #1a1a1a;
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

/* Info Display */
.info-display-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}
.info-display-item {
    padding: 1.5rem;
    background: #fafafa;
    border-radius: 10px;
    border: 1px solid #f0f0f0;
    transition: all 0.2s ease;
}
.info-display-item:hover {
    background: #f5f5f5;
    border-color: #e8e8e8;
}
.info-display-label {
    font-size: 0.75rem;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 0.75rem;
    font-weight: 700;
}
.info-display-value {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1a1a1a;
}

/* Activity Timeline */
.activity-timeline {
    margin-top: 2rem;
}
.timeline-item {
    display: flex;
    gap: 1.5rem;
    padding: 1.5rem;
    background: #fafafa;
    border-radius: 10px;
    margin-bottom: 1rem;
    border: 1px solid #f0f0f0;
    transition: all 0.2s ease;
}
.timeline-item:hover {
    background: #f5f5f5;
    border-color: #e8e8e8;
}
.timeline-icon {
    width: 48px;
    height: 48px;
    background: #1a1a1a;
    color: #fff;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}
.timeline-content h4 {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #1a1a1a;
}
.timeline-content p {
    color: #666;
    font-size: 0.875rem;
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .avatar-section {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .profile-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
    .profile-tabs {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .profile-tab {
        white-space: nowrap;
    }
    .form-row {
        grid-template-columns: 1fr;
    }
    .btn-group {
        flex-direction: column;
    }
}
</style>
@endpush

@section('content')
<div class="profile-container">
    <!-- Cover Photo -->
    <div class="profile-cover"></div>

    <!-- Profile Header Card -->
    <div class="profile-header-card">
        <!-- Avatar and Basic Info -->
        <div class="avatar-section">
            <div class="profile-avatar-large">
                <i class="mdi mdi-account"></i>
            </div>
            <div class="profile-info-main">
                <h1 class="profile-name-large">{{ $member->nama_member }}</h1>
                <div class="profile-meta">
                    <div class="profile-meta-item">
                        <i class="mdi mdi-email"></i>
                        <span>{{ $member->email }}</span>
                    </div>
                    <div class="profile-meta-item">
                        <i class="mdi mdi-calendar"></i>
                        <span>Bergabung {{ $member->created_at->format('M Y') }}</span>
                    </div>
                    <div class="profile-meta-item">
                        <i class="mdi mdi-shield-check"></i>
                        <span>{{ ucfirst($member->status) }} Member</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="profile-tabs">
            <button class="profile-tab active" onclick="switchTab('overview')">
                <i class="mdi mdi-view-dashboard"></i> Overview
            </button>
            <button class="profile-tab" onclick="switchTab('info')">
                <i class="mdi mdi-account-details"></i> Informasi Pribadi
            </button>
            <button class="profile-tab" onclick="switchTab('edit')">
                <i class="mdi mdi-pencil"></i> Edit Profil
            </button>
        </div>
    </div>

    <!-- Tab Contents -->

    <!-- Overview Tab -->
    <div id="overview-tab" class="tab-content active">
        <div class="stats-grid">
            <div class="stat-card-modern gold">
                <div class="stat-icon-modern">
                    <i class="mdi mdi-star-circle"></i>
                </div>
                <div class="stat-label-modern">Total Poin</div>
                <div class="stat-value-modern">{{ number_format($member->points ?? 0) }}</div>
                <p style="margin-top: 1rem; font-size: 0.875rem; opacity: 0.8; margin-bottom: 0;">
                    Gunakan poin untuk diskon!
                </p>
            </div>

            <div class="stat-card-modern">
                <div class="stat-icon-modern">
                    <i class="mdi mdi-cash-multiple"></i>
                </div>
                <div class="stat-label-modern">Total Belanja</div>
                <div class="stat-value-modern">Rp {{ number_format($totalSpent ?? 0, 0, ',', '.') }}</div>
            </div>

            <div class="stat-card-modern">
                <div class="stat-icon-modern">
                    <i class="mdi mdi-package-variant"></i>
                </div>
                <div class="stat-label-modern">Total Pesanan</div>
                <div class="stat-value-modern">{{ $totalOrders ?? 0 }}</div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="form-section-title">Aktivitas Terkini</h3>
            <p class="form-section-subtitle">Riwayat aktivitas akun Anda</p>

            <div class="activity-timeline">
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="mdi mdi-account-check"></i>
                    </div>
                    <div class="timeline-content">
                        <h4>Akun Dibuat</h4>
                        <p>{{ $member->created_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>

                @if($member->updated_at != $member->created_at)
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="mdi mdi-pencil"></i>
                    </div>
                    <div class="timeline-content">
                        <h4>Profil Diperbarui</h4>
                        <p>{{ $member->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Info Tab -->
    <div id="info-tab" class="tab-content">
        <div class="form-section">
            <h3 class="form-section-title">Informasi Pribadi</h3>
            <p class="form-section-subtitle">Detail lengkap profil Anda</p>

            <div class="info-display-grid">
                <div class="info-display-item">
                    <div class="info-display-label">Nama Lengkap</div>
                    <div class="info-display-value">{{ $member->nama_member }}</div>
                </div>

                <div class="info-display-item">
                    <div class="info-display-label">Email</div>
                    <div class="info-display-value">{{ $member->email }}</div>
                </div>

                <div class="info-display-item">
                    <div class="info-display-label">No. Telepon</div>
                    <div class="info-display-value">{{ $member->no_hp ?? 'Belum diisi' }}</div>
                </div>

                <div class="info-display-item">
                    <div class="info-display-label">Status Member</div>
                    <div class="info-display-value">{{ ucfirst($member->status) }}</div>
                </div>

                <div class="info-display-item" style="grid-column: 1 / -1;">
                    <div class="info-display-label">Alamat</div>
                    <div class="info-display-value">{{ $member->alamat ?? 'Belum diisi' }}</div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="form-section-title">Informasi Membership</h3>
            <p class="form-section-subtitle">Status dan benefit membership Anda</p>

            <div class="info-display-grid">
                <div class="info-display-item">
                    <div class="info-display-label">Status</div>
                    <div class="info-display-value">{{ ucfirst($member->status) }}</div>
                </div>

                <div class="info-display-item">
                    <div class="info-display-label">Poin Terkumpul</div>
                    <div class="info-display-value">{{ number_format($member->points ?? 0) }} Poin</div>
                </div>

                <div class="info-display-item">
                    <div class="info-display-label">Bergabung Sejak</div>
                    <div class="info-display-value">{{ $member->created_at->format('d M Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Tab -->
    <div id="edit-tab" class="tab-content">
        <div class="form-section">
            <h3 class="form-section-title">Edit Profil</h3>
            <p class="form-section-subtitle">Perbarui informasi pribadi Anda</p>

            <form action="{{ route('member.profile.update') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group-modern">
                        <label class="form-label-modern">Nama Lengkap</label>
                        <input type="text" name="nama_member" class="form-control-modern"
                               value="{{ $member->nama_member }}" required>
                    </div>

                    <div class="form-group-modern">
                        <label class="form-label-modern">Email</label>
                        <input type="email" name="email" class="form-control-modern"
                               value="{{ $member->email }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group-modern">
                        <label class="form-label-modern">No. Telepon</label>
                        <input type="text" name="no_hp" class="form-control-modern"
                               value="{{ $member->no_hp }}" placeholder="08xxxxxxxxxx">
                    </div>

                    <div class="form-group-modern">
                        <label class="form-label-modern">Status</label>
                        <input type="text" class="form-control-modern"
                               value="{{ ucfirst($member->status) }}" disabled>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group-modern" style="grid-column: 1 / -1;">
                        <label class="form-label-modern">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control-modern" rows="4"
                                  placeholder="Masukkan alamat lengkap Anda">{{ $member->alamat }}</textarea>
                    </div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn-modern btn-primary-modern">
                        <i class="mdi mdi-content-save"></i> Simpan Perubahan
                    </button>
                    <button type="button" class="btn-modern btn-secondary-modern" onclick="switchTab('info')">
                        <i class="mdi mdi-close"></i> Batal
                    </button>
                </div>
            </form>
        </div>

        <div class="form-section">
            <h3 class="form-section-title">Ubah Password</h3>
            <p class="form-section-subtitle">Update password akun Anda untuk keamanan</p>

            <form action="{{ route('member.profile.password') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group-modern">
                        <label class="form-label-modern">Password Lama</label>
                        <input type="password" name="current_password" class="form-control-modern" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group-modern">
                        <label class="form-label-modern">Password Baru</label>
                        <input type="password" name="password" class="form-control-modern" required>
                    </div>

                    <div class="form-group-modern">
                        <label class="form-label-modern">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control-modern" required>
                    </div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn-modern btn-primary-modern">
                        <i class="mdi mdi-lock-reset"></i> Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });

    // Remove active class from all tabs
    document.querySelectorAll('.profile-tab').forEach(tab => {
        tab.classList.remove('active');
    });

    // Show selected tab content
    document.getElementById(tabName + '-tab').classList.add('active');

    // Add active class to clicked tab
    event.target.closest('.profile-tab').classList.add('active');
}
</script>
@endpush
@endsection
