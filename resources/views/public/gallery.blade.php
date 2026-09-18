@extends('layouts.public')

@section('title', 'Hostel Photo Gallery & Campus Life — HITAM')
@section('meta_description', 'View photographs of HITAM hostel residential wings, furnished bedrooms, hygienic dining hall, sports grounds, green campus courtyards, and student celebrations.')

@section('content')
<!-- Page Banner -->
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb-hitam">
            <a href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> Home</a>
            <span>/</span>
            <span>Gallery</span>
        </div>
        <h1 class="page-hero-title">Hostel Photo Gallery</h1>
        <p class="page-hero-sub">
            Take a visual tour through our residential infrastructure, dining facilities, tranquil study environments, and lively student activities.
        </p>
    </div>
</section>

<!-- Gallery Grid -->
<section class="py-5">
    <div class="container py-2">
        <!-- Filter Tabs -->
        <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
            <button class="btn btn-sm btn-hitam-green">All Photos</button>
            <button class="btn btn-sm btn-hitam-outline-green">Hostel Blocks</button>
            <button class="btn btn-sm btn-hitam-outline-green">Student Rooms</button>
            <button class="btn btn-sm btn-hitam-outline-green">Mess & Dining</button>
            <button class="btn btn-sm btn-hitam-outline-green">Sports & Gym</button>
            <button class="btn btn-sm btn-hitam-outline-green">Green Campus</button>
        </div>

        <div class="row g-4">
            <!-- Item 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="gallery-card">
                    <div class="gallery-img-box" style="background-image: url('{{ asset('images/hostel-building.jpg') }}'); background-size: cover; background-position: center;">
                        <div class="gallery-overlay">
                            <div>
                                <span class="badge bg-success mb-1">Boys Block</span>
                                <h6 class="fw-bold mb-0 text-white">Boys Hostel Main Façade</h6>
                                <small class="text-white-50">Multi-storey residential wing with green courtyards</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="gallery-card">
                    <div class="gallery-img-box" style="background-image: url('{{ asset('images/student-room.jpg') }}'); background-size: cover; background-position: center;">
                        <div class="gallery-overlay">
                            <div>
                                <span class="badge bg-success mb-1">Bedrooms</span>
                                <h6 class="fw-bold mb-0 text-white">Furnished 2-Sharing Room</h6>
                                <small class="text-white-50">Double occupancy room with study desk and wardrobes</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="gallery-card">
                    <div class="gallery-img-box" style="background-image: url('{{ asset('images/dining-hall.jpg') }}'); background-size: cover; background-position: center;">
                        <div class="gallery-overlay">
                            <div>
                                <span class="badge bg-success mb-1">Mess & Dining</span>
                                <h6 class="fw-bold mb-0 text-white">Central Dining Hall</h6>
                                <small class="text-white-50">FSSAI certified steam kitchen and banquet seating</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 4 -->
            <div class="col-md-6 col-lg-4">
                <div class="gallery-card">
                    <div class="gallery-img-box" style="background: linear-gradient(135deg, #1B5E20, #0D2818);">
                        <div class="gallery-overlay">
                            <div>
                                <span class="badge bg-success mb-1">Girls Block</span>
                                <h6 class="fw-bold mb-0 text-white">Girls Hostel Reception & Security Desk</h6>
                                <small class="text-white-50">Biometric turnstiles and 24/7 warden station</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 5 -->
            <div class="col-md-6 col-lg-4">
                <div class="gallery-card">
                    <div class="gallery-img-box" style="background: linear-gradient(135deg, #2E7D32, #153E23);">
                        <div class="gallery-overlay">
                            <div>
                                <span class="badge bg-success mb-1">Fitness & Gym</span>
                                <h6 class="fw-bold mb-0 text-white">Indoor Fitness Centre</h6>
                                <small class="text-white-50">Cardio machines, weights, and cross-trainer equipment</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 6 -->
            <div class="col-md-6 col-lg-4">
                <div class="gallery-card">
                    <div class="gallery-img-box" style="background: linear-gradient(135deg, #153E23, #0D2818);">
                        <div class="gallery-overlay">
                            <div>
                                <span class="badge bg-success mb-1">Academics</span>
                                <h6 class="fw-bold mb-0 text-white">Night Study Reading Hall</h6>
                                <small class="text-white-50">Airy quiet reading room with charging stations</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 7 -->
            <div class="col-md-6 col-lg-4">
                <div class="gallery-card">
                    <div class="gallery-img-box" style="background: linear-gradient(135deg, #0D2818, #1B5E20);">
                        <div class="gallery-overlay">
                            <div>
                                <span class="badge bg-success mb-1">Sports</span>
                                <h6 class="fw-bold mb-0 text-white">Floodlit Volleyball & Basketball Court</h6>
                                <small class="text-white-50">Evening recreation and sports practice</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 8 -->
            <div class="col-md-6 col-lg-4">
                <div class="gallery-card">
                    <div class="gallery-img-box" style="background: linear-gradient(135deg, #1B5E20, #2E7D32);">
                        <div class="gallery-overlay">
                            <div>
                                <span class="badge bg-success mb-1">Sustainability</span>
                                <h6 class="fw-bold mb-0 text-white">Solar Thermal Hot Water Grid</h6>
                                <small class="text-white-50">Eco-conscious rooftop solar water heaters</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 9 -->
            <div class="col-md-6 col-lg-4">
                <div class="gallery-card">
                    <div class="gallery-img-box" style="background: linear-gradient(135deg, #153E23, #2E7D32);">
                        <div class="gallery-overlay">
                            <div>
                                <span class="badge bg-success mb-1">Events</span>
                                <h6 class="fw-bold mb-0 text-white">Festive Cultural Banquet</h6>
                                <small class="text-white-50">Hostel residents celebrating traditional festivals</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
