@forelse($packages as $package)
    <div class="col-lg-6 col-xl-4 col-md-6 col-sm-6 col-12 p-0 package"
        data-package='{
            "id": {{ $package->id }},
            "title": "{{ replaceQuotes($package->title) }}",
            "description": "{{ replaceQuotes($package->description) }}",
            "current_price": {{ $package->current_price }},
            "duration": {{ $package->duration ?? 0 }},
            "mrp_price": {{ $package->mrp_price }},
            "thumb_image": "{{asset('theme/admin/assets/img/package/default-package.png') }}",
            "quantity": 1
        }'>
        <div class="package-card position-relative shadow-sm">
            <span class="badge {{ $package->status ? 'badge-success' : 'badge-danger' }} package-stock-badge">
                {{ $package->status ? 'Active' : 'Inactive' }}
            </span>
            <img src="{{ asset('theme/admin/assets/img/package/default-package.png') }}" 
                alt="{{ $package->title }}" 
                class="img-fluid package-image">
            <div class="package-detail">
                <h6 class="package-title">{{ $package->title }}</h6>
                <p class="package-description">{{ Str::limit($package->description, 50) }}</p>
                @if ($package->duration)
                    <span class="badge badge-info">{{ $package->duration }} days</span>
                @else
                    <span class="badge badge-secondary">Duration not set</span>
                @endif
                <p class="package-price">৳{{ number_format($package->current_price, 2) }}</p>
            </div>
        </div>
    </div>
@empty
    <div class="col-12 text-center">
        <h5>No packages found.</h5>
    </div>
@endforelse

<style>
    .package-stock-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 12px;
        color: #fff;
    }

    .badge-success {
        background-color: #28a745;
    }

    .badge-danger {
        background-color: #dc3545;
    }

    .badge-info {
        background-color: #17a2b8;
    }

    .badge-secondary {
        background-color: #6c757d;
    }

    .package-card {
        margin: 10px;
        padding: 10px;
        background-color: white;
        border: 1px solid #f0f0f0;
        border-radius: 8px;
        text-align: center;
        transition: transform 0.2s ease-in-out;
    }

    .package-card:hover {
        transform: scale(1.05);
    }

    .package-image {
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
    }

    .package-detail {
        margin-top: 10px;
    }

    .package-title {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 5px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .package-description {
        font-size: 14px;
        color: #666;
        margin-bottom: 5px;
    }

    .package-price {
        font-size: 18px;
        font-weight: bold;
        color: #007bff;
    }
</style>
