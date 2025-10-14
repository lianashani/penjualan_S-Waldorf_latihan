@extends('member.layout')
@section('title','Katalog')
@push('styles')
<style>
.bw-theme { background-color:#ffffff; color:#0f0f10; }
.bw-theme .text-primary { color:#111111 !important; background-color:transparent !important; }
.product-card { transition:all .3s ease; border:1px solid #e5e7eb; box-shadow:0 1px 3px rgba(0,0,0,.1); border-radius:12px; overflow:hidden; height:100%; display:flex; flex-direction:column }
.product-card:hover { transform:translateY(-4px); box-shadow:0 8px 25px rgba(0,0,0,.12); border-color:#d1d5db }
.product-image { height:280px; object-fit:cover; transition:transform .3s ease }
.product-card:hover .product-image { transform:scale(1.05) }
.variant-preview{cursor:pointer;transition:all .2s ease;width:22px;height:22px;border-radius:50%;border:2px solid #fff;box-shadow:0 0 0 1px rgba(0,0,0,.08),0 2px 4px rgba(0,0,0,.12);flex:0 0 22px;background-clip:padding-box}
.variant-bar{background:#fff;border-top:1px solid #e5e7eb;box-shadow:0 -4px 12px rgba(0,0,0,.08);z-index:2;display:flex;align-items:center}
.variant-count{color:#111;background:#fff;border:1px solid #e5e7eb;border-radius:999px;padding:0 8px;font-size:12px;height:22px;display:inline-flex;align-items:center;margin-left:6px;box-shadow:0 1px 2px rgba(0,0,0,.06)}
/* 3 columns on large screens */
@media (min-width:1200px){#productGrid .product-item{flex:0 0 33.3333%;max-width:33.3333%}}
@media (max-width:1200px){.col-xl-4{flex:0 0 50%;max-width:50%}}
@media (max-width:768px){.col-xl-4,.col-lg-6{flex:0 0 100%;max-width:100%}}
</style>
@endpush

@section('content')
<div class="bw-theme">
  <div class="row">
    <!-- Sidebar Filters -->
    <div class="col-12 col-lg-3 mb-4 collapse show" id="filterCol">
      <div class="card">
        <div class="card-header bg-light d-flex align-items-center justify-content-between">
          <strong><i class="mdi mdi-filter-variant me-1"></i>Filter</strong>
          <button class="btn btn-sm btn-light d-none d-lg-inline-flex" type="button" data-bs-toggle="collapse" data-bs-target="#filterCol" aria-controls="filterCol" id="filterHideBtn">Sembunyikan</button>
        </div>
        <div class="card-body">
          <form method="GET" action="{{ route('member.catalog.index') }}">
            <div class="mb-3">
              <label class="form-label">Kategori</label>
              <select name="kategori" class="form-control form-control-sm">
                <option value="">Semua</option>
                @foreach($kategoris as $kategori)
                  <option value="{{ $kategori->id_kategori }}" {{ request('kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                    {{ $kategori->nama_kategori }} ({{ $kategori->produks_count }})
                  </option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Harga</label>
              <div class="row g-2">
                <div class="col-6"><input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ request('min_price') }}"></div>
                <div class="col-6"><input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ request('max_price') }}"></div>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Urutkan</label>
              <select name="sort" class="form-control form-control-sm">
                <option value="featured" {{ request('sort', 'featured') == 'featured' ? 'selected' : '' }}>Featured</option>
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
              </select>
            </div>
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary btn-sm"><i class="mdi mdi-magnify me-1"></i>Terapkan</button>
              <a href="{{ route('member.catalog.index') }}" class="btn btn-light btn-sm">Reset</a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Product Grid -->
    <div class="col-12 col-lg-9" id="gridCol">
      <div class="row g-4" id="productGrid">
        @forelse($produks as $produk)
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 product-item">
          <div class="card product-card h-100 border-0 shadow-sm">
            <div class="position-relative overflow-hidden">
              <a href="{{ route('member.catalog.show', $produk->id_produk) }}" class="text-decoration-none">
                <img src="{{ $produk->main_image ?? ( $produk->gambar ? asset('storage/'.$produk->gambar) : asset('assets/images/no_image.jpg') ) }}" class="card-img-top product-image" alt="{{ $produk->nama_produk }}">
              </a>
              @if($produk->has_variants && $produk->activeVariants->count() > 0)
              <div class="position-absolute bottom-0 start-0 end-0 p-2 variant-bar">
                <div class="d-flex justify-content-center align-items-center gap-3">
                  @foreach($produk->activeVariants->take(4) as $variant)
                    <div class="variant-preview" style="background-color: {{ $variant->kode_warna ?? '#ccc' }};" title="{{ $variant->warna }} - {{ $variant->ukuran }}"></div>
                  @endforeach
                  @if($produk->activeVariants->count() > 4)
                    <span class="variant-count">+{{ $produk->activeVariants->count() - 4 }}</span>
                  @endif
                </div>
              </div>
              @endif
            </div>
            <div class="card-body d-flex flex-column p-3 flex-grow-1">
              <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                  <i class="mdi mdi-tag me-1"></i>{{ $produk->kategori->nama_kategori ?? '-' }}
                </span>
                @if($produk->is_featured)
                  <span class="badge bg-warning text-dark"><i class="mdi mdi-star me-1"></i>Featured</span>
                @endif
              </div>
              <h6 class="card-title mb-2">
                <a href="{{ route('member.catalog.show', $produk->id_produk) }}" class="text-decoration-none text-dark fw-bold">{{ $produk->nama_produk }}</a>
              </h6>
              @if($produk->rating_count > 0)
              <div class="mb-2"><div class="d-flex align-items-center"><span class="text-warning me-1">{!! $produk->rating_stars !!}</span><small class="text-muted">({{ $produk->rating_count }} ulasan)</small></div></div>
              @endif
              <div class="mb-3">
                <h5 class="text-primary mb-0 fw-bold">{{ $produk->formatted_price }}</h5>
                @if($produk->has_variants)
                  <small class="text-muted"><i class="mdi mdi-information-outline me-1"></i>Mulai dari</small>
                @endif
              </div>
              <div class="mb-3">
                <span class="badge bg-{{ $produk->stock_status == 'in_stock' ? 'success' : ($produk->stock_status == 'low_stock' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $produk->stock_status == 'in_stock' ? 'success' : ($produk->stock_status == 'low_stock' ? 'warning' : 'danger') }} border border-{{ $produk->stock_status == 'in_stock' ? 'success' : ($produk->stock_status == 'low_stock' ? 'warning' : 'danger') }} border-opacity-25">
                  <i class="mdi mdi-{{ $produk->stock_status == 'in_stock' ? 'check-circle' : ($produk->stock_status == 'low_stock' ? 'alert-circle' : 'close-circle') }} me-1"></i>
                  {{ $produk->stock_status_text }}
                </span>
              </div>
              <div class="mt-auto">
                <div class="d-grid gap-2">
                  <a href="{{ route('member.catalog.show', $produk->id_produk) }}" class="btn btn-primary btn-sm" style="color:white !important;"><i class="mdi mdi-eye me-1"></i>Lihat Detail</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        @empty
        <div class="col-12">
          <div class="card"><div class="card-body text-center py-5">
            <i class="mdi mdi-package-variant-closed display-1 text-muted opacity-50 d-block mb-3"></i>
            <h4 class="text-muted mb-2">Tidak ada produk ditemukan</h4>
            <a class="btn btn-outline-primary" href="{{ route('member.catalog.index') }}"><i class="mdi mdi-refresh me-1"></i>Reset Filter</a>
          </div></div>
        </div>
        @endforelse
      </div>

      @if(method_exists($produks, 'hasPages') && $produks->hasPages())
      <div class="d-flex justify-content-center mt-4">
        <div class="pagination-sm">{{ $produks->appends(request()->query())->links() }}</div>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection
