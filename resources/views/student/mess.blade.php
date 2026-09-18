@extends('layouts.student')

@section('title', 'Today\'s Mess Menu — Rahul Sharma')
@section('page_title', 'Today\'s Dining & Mess Schedule')

@section('student_content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
                <div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle mb-1">Central Dining Hall</span>
                    <h4 class="fw-bold text-forest mb-0">Today's Meal Schedule</h4>
                </div>
                <span class="badge bg-light text-forest border px-3 py-2 fw-semibold">
                    <i class="bi bi-calendar3 me-1"></i> {{ date('l, d M Y') }}
                </span>
            </div>

            <!-- Breakfast Card -->
            <div class="p-3 border rounded-3 mb-3 d-flex flex-column flex-sm-row gap-3 align-items-start bg-light">
                <div class="rounded-3 bg-white p-3 border text-center" style="min-width: 90px;">
                    <i class="bi bi-cup-hot fs-3 text-warning"></i>
                    <small class="d-block fw-bold text-dark mt-1">Breakfast</small>
                </div>
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <h6 class="fw-bold text-dark mb-0">Idli, Medu Vada, Sambar & Coconut Chutney</h6>
                        <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.7rem;">07:30 – 09:00 AM</span>
                    </div>
                    <p class="small text-muted mb-0">Accompanied by freshly brewed Filter Coffee, Masala Tea, and Warm Milk.</p>
                </div>
            </div>

            <!-- Lunch Card -->
            <div class="p-3 border rounded-3 mb-3 d-flex flex-column flex-sm-row gap-3 align-items-start bg-success-subtle bg-opacity-25 border-success">
                <div class="rounded-3 bg-white p-3 border text-center" style="min-width: 90px;">
                    <i class="bi bi-egg-fried fs-3 text-success"></i>
                    <small class="d-block fw-bold text-dark mt-1">Lunch</small>
                </div>
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <h6 class="fw-bold text-dark mb-0">Steamed Rice, Phulka Roti, Dal Tadka, Aloo Gobi Masala</h6>
                        <span class="badge bg-success text-white" style="font-size: 0.7rem;">Current / Upcoming</span>
                    </div>
                    <p class="small text-muted mb-0">Fresh Curd, Tomato Rasam, Papad, and Seasonal Green Salad.</p>
                </div>
            </div>

            <!-- Snacks Card -->
            <div class="p-3 border rounded-3 mb-3 d-flex flex-column flex-sm-row gap-3 align-items-start bg-light">
                <div class="rounded-3 bg-white p-3 border text-center" style="min-width: 90px;">
                    <i class="bi bi-cup-straw fs-3 text-danger"></i>
                    <small class="d-block fw-bold text-dark mt-1">Evening</small>
                </div>
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <h6 class="fw-bold text-dark mb-0">Sweet Corn / Onion Pakoda & Hot Tea</h6>
                        <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.7rem;">05:00 – 06:00 PM</span>
                    </div>
                    <p class="small text-muted mb-0">Crispy snacks served with mint dip and biscuits.</p>
                </div>
            </div>

            <!-- Dinner Card -->
            <div class="p-3 border rounded-3 d-flex gap-3 align-items-start bg-light">
                <div class="rounded-3 bg-white p-3 border text-center" style="min-width: 90px;">
                    <i class="bi bi-moon-stars fs-3 text-primary"></i>
                    <small class="d-block fw-bold text-dark mt-1">Dinner</small>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h6 class="fw-bold text-dark mb-0">Phulka Roti, Paneer Butter Masala, Jeera Rice, Dal Fry</h6>
                        <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.7rem;">07:30 – 09:15 PM</span>
                    </div>
                    <p class="small text-muted mb-0">Sweet Dessert: Warm Gulab Jamun & Buttermilk.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-chat-heart text-forest me-2"></i>Mess Feedback</h5>
            <p class="small text-muted mb-3">
                Rate today's food quality to help the Student Mess Committee maintain dining hygiene standards.
            </p>
            <div class="d-flex justify-content-center gap-2 mb-3 fs-3 text-warning">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star"></i>
            </div>
            <textarea class="form-control mb-3" rows="2" placeholder="Any suggestions for today's dishes?"></textarea>
            <button class="btn btn-hitam-green btn-sm w-100 py-2" onclick="alert('Thank you! Feedback recorded for the mess committee.');">
                Submit Feedback
            </button>
        </div>
    </div>
</div>
@endsection
