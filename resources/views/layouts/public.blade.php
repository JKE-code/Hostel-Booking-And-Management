<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'HITAM Hostel Portal — Hyderabad Institute of Technology and Management')</title>
    <meta name="description" content="@yield('meta_description', 'Official Hostel Portal of Hyderabad Institute of Technology and Management (HITAM). Residential accommodations, student facilities, notices, and administration.')">
    
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/hitam-logo.jpg') }}">

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom Institutional CSS -->
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">

    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    @include('components.navbar')

    <main class="flex-grow-1">
        @yield('content')
    </main>

    @include('components.footer')

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Global Scroll Reveal & Animation Engine -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Sticky Navbar Glassmorphism on Scroll
            const navbar = document.querySelector('.hitam-navbar');
            if (navbar) {
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 30) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                });
            }

            // Auto-tag card & grid rows with reveal animations & staggers
            const gridContainers = document.querySelectorAll('.row.g-4, .row.g-3');
            gridContainers.forEach(container => {
                if (!container.classList.contains('stagger-group')) {
                    container.classList.add('stagger-group');
                }
            });

            // Target elements for scroll reveals
            const revealSelectors = '.reveal, .reveal-up, .reveal-left, .reveal-right, .reveal-scale, .hitam-card, .facility-item, .gallery-card, .notice-row, .accordion-item';
            const revealElements = document.querySelectorAll(revealSelectors);

            revealElements.forEach(el => {
                if (!el.classList.contains('reveal') && 
                    !el.classList.contains('reveal-left') && 
                    !el.classList.contains('reveal-right') && 
                    !el.classList.contains('reveal-scale')) {
                    el.classList.add('reveal');
                }
            });

            // Intersection Observer Configuration
            const observerOptions = {
                root: null,
                rootMargin: '0px 0px -50px 0px',
                threshold: 0.15
            };

            const revealObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                        // Unobserve after animating once
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.reveal, .reveal-up, .reveal-left, .reveal-right, .reveal-scale').forEach(el => {
                revealObserver.observe(el);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
