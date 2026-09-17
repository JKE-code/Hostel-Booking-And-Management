@extends('layouts.public')

@section('title', 'Mess & Dining — Hygienic, Nutritious & Multi-Cuisine Food | HITAM')
@section('meta_description', 'Explore the HITAM Hostel Dining Hall and Mess facility. Review weekly breakfast, lunch, snacks, and dinner schedules, hygiene certifications, and student mess committee oversight.')

@section('content')
<!-- Page Banner -->
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb-hitam">
            <a href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> Home</a>
            <span>/</span>
            <span>Mess & Dining</span>
        </div>
        <h1 class="page-hero-title">Mess, Dining & Nutrition</h1>
        <p class="page-hero-sub">
            Wholesome, balanced meals crafted daily in an expansive, spotless dining hall adhering strictly to FSSAI hygiene guidelines and supervised by resident student representatives.
        </p>
    </div>
</section>

<!-- Mess Meal Schedule Timings -->
<section class="py-5">
    <div class="container py-2">
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="hitam-card p-4 text-center h-100">
                    <div class="facility-icon-wrap mx-auto mb-2">
                        <i class="bi bi-cup-hot"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Breakfast</h5>
                    <div class="badge bg-success-subtle text-success border border-success-subtle mb-2">Morning Energy</div>
                    <div class="h5 fw-bold text-forest mb-1">07:30 AM – 09:00 AM</div>
                    <div class="small text-muted">South & North Indian varieties with Tea/Coffee/Milk</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="hitam-card p-4 text-center h-100">
                    <div class="facility-icon-wrap mx-auto mb-2">
                        <i class="bi bi-egg-fried"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Lunch</h5>
                    <div class="badge bg-success-subtle text-success border border-success-subtle mb-2">Full Meal</div>
                    <div class="h5 fw-bold text-forest mb-1">12:30 PM – 02:00 PM</div>
                    <div class="small text-muted">Rice, Roti, Dal, Sabzi, Curd, Sambar, and Fresh Salad</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="hitam-card p-4 text-center h-100">
                    <div class="facility-icon-wrap mx-auto mb-2">
                        <i class="bi bi-cup-straw"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Evening Snacks</h5>
                    <div class="badge bg-success-subtle text-success border border-success-subtle mb-2">Refreshment</div>
                    <div class="h5 fw-bold text-forest mb-1">05:00 PM – 06:00 PM</div>
                    <div class="small text-muted">Hot snacks, biscuits, fresh tea, and filter coffee</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="hitam-card p-4 text-center h-100">
                    <div class="facility-icon-wrap mx-auto mb-2">
                        <i class="bi bi-moon-stars"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Dinner</h5>
                    <div class="badge bg-success-subtle text-success border border-success-subtle mb-2">Wholesome Diet</div>
                    <div class="h5 fw-bold text-forest mb-1">07:30 PM – 09:15 PM</div>
                    <div class="small text-muted">Warm Rotis, curries, flavored rice, dessert & buttermilk</div>
                </div>
            </div>
        </div>

        <!-- Weekly Menu Schedule -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <span class="hitam-section-badge">Weekly Routine</span>
                <h3 class="hitam-section-title mb-0">Official 7-Day Cycle Menu</h3>
            </div>
            <div>
                <span class="badge bg-white text-forest border px-3 py-2">
                    <i class="bi bi-check-all text-success me-1"></i> FSSAI Certified Kitchen
                </span>
            </div>
        </div>

        <div class="table-responsive hitam-table shadow-sm">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width: 14%;">Day</th>
                        <th style="width: 21%;">Breakfast (07:30 - 09:00)</th>
                        <th style="width: 25%;">Lunch (12:30 - 02:00)</th>
                        <th style="width: 18%;">Evening Snacks</th>
                        <th style="width: 22%;">Dinner (07:30 - 09:15)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-bold text-forest"><i class="bi bi-calendar3 me-1"></i> Monday</td>
                        <td>Idli, Medu Vada, Sambar, Coconut Chutney, Tea/Coffee/Milk</td>
                        <td>Steamed Rice, Phulka Roti, Dal Tadka, Aloo Gobi Masala, Rasam, Curd, Fryums</td>
                        <td>Sweet Corn & Hot Tea / Coffee</td>
                        <td>Phulka, Paneer Butter Masala, Jeera Rice, Dal Fry, Fresh Salad</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-forest"><i class="bi bi-calendar3 me-1"></i> Tuesday</td>
                        <td>Mysore Bonda, Upma, Ginger Chutney, Tea/Coffee/Milk</td>
                        <td>Steamed Rice, Chapati, Tomato Dal, Bhindi Fry, Sambar, Fresh Curd, Pickle</td>
                        <td>Samosa with Mint Chutney & Tea</td>
                        <td>Phulka, Mix Vegetable Curry, Steamed Rice, Tomato Rasam, Fruit Custard</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-forest"><i class="bi bi-calendar3 me-1"></i> Wednesday</td>
                        <td>Masala Dosa, Potato Masala, Sambar, Chutney, Tea/Coffee/Milk</td>
                        <td>Steamed Rice, Roti, Palak Dal, Meal Maker Curry, Pepper Rasam, Curd</td>
                        <td>Veg Puff & Hot Filter Coffee</td>
                        <td>Special Chicken Biryani / Veg Paneer Dum Biryani, Mirchi Ka Salan, Raitha, Gulab Jamun</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-forest"><i class="bi bi-calendar3 me-1"></i> Thursday</td>
                        <td>Poori with Aloo Kurma, Sambar, Tea/Coffee/Milk</td>
                        <td>Steamed Rice, Phulka, Chana Masala, Bottle Gourd Dal, Rasam, Curd, Papad</td>
                        <td>Biscuits, Banana & Tea / Milk</td>
                        <td>Methi Paratha, Veg Korma, Steamed Rice, Sambar, Moong Dal Halwa</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-forest"><i class="bi bi-calendar3 me-1"></i> Friday</td>
                        <td>Poha with Peanuts, Sev, Green Chutney, Tea/Coffee/Milk</td>
                        <td>Steamed Rice, Chapati, Rajma Masala, Capsicum Fry, Garlic Rasam, Fresh Curd</td>
                        <td>Mirchi Bajji / Onion Pakoda & Tea</td>
                        <td>Phulka, Egg Curry / Kadhai Paneer, Veg Pulao, Dal Makhani, Ice Cream</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-forest"><i class="bi bi-calendar3 me-1"></i> Saturday</td>
                        <td>Uttapam with Onion & Tomato, Sambar, Chutney, Tea/Coffee/Milk</td>
                        <td>Lemon Rice, Steamed Rice, Roti, Mango Dal, Cabbage Peas Poriyal, Curd</td>
                        <td>Aloo Bonda & Hot Tea</td>
                        <td>Phulka, Malai Kofta, Steamed Rice, Pepper Rasam, Payasam (Kheer)</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-forest"><i class="bi bi-calendar3 me-1"></i> Sunday</td>
                        <td>Stuffed Aloo Paratha with Curd, Pickle, Tea/Coffee/Milk</td>
                        <td>Special Feast: Bagara Rice, Paneer Butter Masala / Chicken Curry, Dal, Curd, Sweet</td>
                        <td>Tea / Coffee with Rusks & Cookies</td>
                        <td>Light Khichdi / Roti, Mix Veg Curry, Curd Rice with Pomegranate</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Mess Governance & Hygiene Pillars -->
<section class="py-5 bg-white border-top">
    <div class="container py-2">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="hitam-section-badge">Student Representation</span>
                <h3 class="hitam-section-title">Student Mess Committee</h3>
                <p class="text-secondary mb-3">
                    At HITAM, the mess menu is never dictated in isolation. An elected Student Mess Committee representing various states, dietary preferences, and year batches meets with the hostel caterers every month.
                </p>
                <p class="text-secondary mb-4">
                    The committee performs surprise kitchen audits, verifies grocery quality, checks cooking oil standards, and updates the cyclical menu based on student feedback.
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('public.downloads') }}" class="btn btn-hitam-green">
                        <i class="bi bi-download me-1"></i> Download Printable Menu PDF
                    </a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="p-4 bg-light border rounded-3">
                    <h5 class="fw-bold text-forest mb-3"><i class="bi bi-patch-check text-success me-2"></i>Kitchen Hygiene Standards</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <i class="bi bi-check-circle-fill text-success mt-1"></i>
                            <div>
                                <div class="fw-bold">Steam Cooking Plant</div>
                                <div class="small text-muted">Reduces direct fuel exposure and ensures maximum nutrient preservation and hygienic food preparation.</div>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <i class="bi bi-check-circle-fill text-success mt-1"></i>
                            <div>
                                <div class="fw-bold">Daily Utensil Sterilization</div>
                                <div class="small text-muted">Stainless steel plates, bowls, and cutlery are sanitized in boiling water washing bays after every meal.</div>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill text-success mt-1"></i>
                            <div>
                                <div class="fw-bold">FSSAI Certified Raw Ingredients</div>
                                <div class="small text-muted">Branded pulses, farm-fresh vegetables delivered daily, and AGMARK certified cooking oils.</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
