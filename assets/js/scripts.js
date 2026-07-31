// Pricing Configuration for Alluri Resorts
// Central configuration file for all pricing-related settings

// Room pricing configuration
const PRICING_CONFIG = {
    // Base room prices (before GST)
    roomPrices: {
        nonac: 2500,
        ac: 3500,
        deluxe: 5000,
        suite: 7000
    },
    
    // Add-on pricing
    addons: {
        extraBed: 500,
        breakfast: 200,
        campfire: 1500,
        dhimsa: 2500
    },
    
    // Tax configuration
    taxes: {
        gst: 5, // 5% GST
        serviceTax: 0 // No additional service tax
    },
    
    // Dynamic pricing multipliers
    multipliers: {
        weekday: 1.0,
        weekend: 1.2,
        festival: 1.5,
        peak: 1.8
    },
    
    // Smart offers configuration
    offers: {
        earlyBookingDiscount: 10, // 10% discount
        earlyBookingDays: 7, // Book 7+ days in advance
        twoNightDiscount: 500, // ₹500 off for 2+ nights
        fiveNightDiscount: 850 // ₹850 off for 5+ nights
    },
    
    // FOMO (Fear of Missing Out) settings
    fomo: {
        enabled: true,
        lowInventoryThreshold: 3, // Show "Only X rooms left" when <= 3 rooms
        highDemandThreshold: 10 // Show "High demand" when >= 10 bookings
    },
    
    // Currency settings
    currency: {
        symbol: '₹',
        code: 'INR',
        locale: 'en-IN'
    }
};

// Utility functions for pricing calculations
const PricingUtils = {
    // Calculate price with GST
    calculateWithGST: function(basePrice, gstPercent = PRICING_CONFIG.taxes.gst) {
        return Math.round(basePrice * (1 + gstPercent / 100));
    },
    
    // Format price with currency symbol
    formatPrice: function(price) {
        return `${PRICING_CONFIG.currency.symbol}${price.toLocaleString(PRICING_CONFIG.currency.locale)}`;
    },
    
    // Get room price with multiplier
    getRoomPriceWithMultiplier: function(roomType, multiplier = 1.0) {
        const basePrice = PRICING_CONFIG.roomPrices[roomType] || 0;
        const adjustedPrice = basePrice * multiplier;
        return this.calculateWithGST(adjustedPrice);
    },
    
    // Get addon price
    getAddonPrice: function(addonType) {
        return PRICING_CONFIG.addons[addonType] || 0;
    },
    
    // Calculate total booking price
    calculateBookingTotal: function(roomType, nights, addons = [], multiplier = 1.0) {
        const roomPrice = this.getRoomPriceWithMultiplier(roomType, multiplier);
        const roomTotal = roomPrice * nights;
        
        const addonTotal = addons.reduce((total, addon) => {
            return total + this.getAddonPrice(addon);
        }, 0);
        
        return roomTotal + addonTotal;
    }
};

// Initialize pricing configuration
function initializePricingConfig() {
    // Load custom settings from localStorage if available
    const customSettings = localStorage.getItem('resortSettings');
    if (customSettings) {
        try {
            const settings = JSON.parse(customSettings);
            
            // Merge custom settings with default config
            if (settings.addons) {
                Object.assign(PRICING_CONFIG.addons, settings.addons);
            }
            if (settings.dynamicPricing) {
                Object.assign(PRICING_CONFIG.multipliers, {
                    weekday: settings.dynamicPricing.weekdayMultiplier || PRICING_CONFIG.multipliers.weekday,
                    weekend: settings.dynamicPricing.weekendMultiplier || PRICING_CONFIG.multipliers.weekend,
                    festival: settings.dynamicPricing.festivalMultiplier || PRICING_CONFIG.multipliers.festival,
                    peak: settings.dynamicPricing.peakMultiplier || PRICING_CONFIG.multipliers.peak
                });
            }
            if (settings.smartOffers) {
                Object.assign(PRICING_CONFIG.offers, settings.smartOffers);
            }
            if (settings.fomo) {
                Object.assign(PRICING_CONFIG.fomo, settings.fomo);
            }
        } catch (error) {
            console.warn('Error loading custom pricing settings:', error);
        }
    }
    
    // DISABLED: Load custom room prices from localStorage
    // This was interfering with database-driven pricing
    // const customRoomPrices = localStorage.getItem('roomPrices');
    // if (customRoomPrices) {
    //     try {
    //         const roomPrices = JSON.parse(customRoomPrices);
    //         Object.assign(PRICING_CONFIG.roomPrices, roomPrices);
    //     } catch (error) {
    //         console.warn('Error loading custom room prices:', error);
    //     }
    // }
}

// Export configuration and utilities to global scope
window.PRICING_CONFIG = PRICING_CONFIG;
window.PricingUtils = PricingUtils;

// Initialize on load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializePricingConfig);
} else {
    initializePricingConfig();
}

// Listen for storage changes to update pricing in real-time
window.addEventListener('storage', function(e) {
    if (e.key === 'resortSettings' || e.key === 'roomPrices') {
        initializePricingConfig();
        
        // Trigger price update event for other scripts
        window.dispatchEvent(new CustomEvent('pricingConfigUpdated', {
            detail: { config: PRICING_CONFIG }
        }));
    }
});

// Listen for custom pricing update events
window.addEventListener('addonPriceUpdated', function(e) {
    if (e.detail && e.detail.addonType && e.detail.newPrice) {
        PRICING_CONFIG.addons[e.detail.addonType] = e.detail.newPrice;
    }
});
// ========================================
// CENTRALIZED INITIALIZATION SYSTEM
// ========================================

// Global initialization state
const INIT_STATE = {
  domReady: false,
  scriptsLoaded: false,
  initialized: false
};

// Safe DOM element getter with caching
const DOM_CACHE = {};
function safeGetElement(id) {
  if (!DOM_CACHE[id]) {
    DOM_CACHE[id] = document.getElementById(id);
  }
  return DOM_CACHE[id];
}

// Safe DOM query with error handling
function safeQuery(selector, context = document) {
  try {
    return context.querySelector(selector);
  } catch (error) {
    console.warn(`DOM query failed for selector: ${selector}`, error);
    return null;
  }
}

// Safe DOM query all with error handling
function safeQueryAll(selector, context = document) {
  try {
    return context.querySelectorAll(selector) || [];
  } catch (error) {
    console.warn(`DOM query all failed for selector: ${selector}`, error);
    return [];
  }
}

// Navigation Active State
function setActiveNavLink() {
  try {
    const currentPage = window.location.pathname.split("/").pop() || "index.html";
    const navLinks = safeQueryAll(".nav-menu a");

    console.log('🔍 Setting active nav link for page:', currentPage);
    console.log('🔍 Found nav links:', navLinks.length);

    navLinks.forEach((link) => {
      const href = link.getAttribute("href");
      link.classList.remove("active"); // Remove active from all first
      
      if (href === currentPage || (currentPage === "" && href === "index.html")) {
        link.classList.add("active");
        console.log('✅ Added active class to:', href, link.textContent);
        
        // Force immediate visual feedback
        link.style.color = '#62b784';
        link.style.fontWeight = '600';
        
        // Check if we're on home page
        if (document.body.classList.contains('home-page')) {
          link.style.color = '#0A6A4E';
        }
      } else {
        // Reset inline styles for non-active links
        link.style.color = '';
        link.style.fontWeight = '';
      }
    });
    
    // Double-check: log all active links
    const activeLinks = safeQueryAll(".nav-menu a.active");
    console.log('🎯 Active links after setting:', activeLinks.length);
    activeLinks.forEach(link => {
      console.log('🎯 Active link:', link.textContent, link.href);
    });
    
  } catch (error) {
    console.error('❌ Error setting active nav link:', error);
  }
}

// Close mobile menu when link is clicked or when clicking outside
function setupMobileMenuClose() {
  try {
    const navToggle = safeGetElement("nav-toggle");
    const navLinks = safeQueryAll(".nav-menu a");

    if (navToggle && navLinks.length > 0) {
      // Close menu when clicking on nav links
      navLinks.forEach((link) => {
        link.addEventListener("click", (e) => {
          navToggle.checked = false;
          
          // Immediately set active state on click
          const allLinks = safeQueryAll(".nav-menu a");
          allLinks.forEach(l => {
            l.classList.remove("active");
            l.style.color = '';
            l.style.fontWeight = '';
          });
          
          // Set clicked link as active
          link.classList.add("active");
          link.style.color = document.body.classList.contains('home-page') ? '#0A6A4E' : '#62b784';
          link.style.fontWeight = '600';
          
          console.log('🖱️ Clicked link set as active:', link.textContent);
        });
      });
      
      // Add click-outside functionality using a different approach
      setupClickOutsideClose(navToggle);
    }
  } catch (error) {
    console.error('Error setting up mobile menu close:', error);
  }
}

// Separate function for click-outside functionality to avoid interference
function setupClickOutsideClose(navToggle) {
  if (!navToggle) return;
  
  // Use mousedown instead of click to avoid interference with the checkbox toggle
  document.addEventListener("mousedown", (e) => {
    // Only apply this behavior on mobile/tablet screens
    if (window.innerWidth <= 1024 && navToggle.checked) {
      const navbar = document.querySelector('.navbar');
      const navMenu = document.querySelector('.nav-menu');
      const navToggleLabel = document.querySelector('.nav-toggle-label');
      
      // Check if the click is outside the navbar entirely
      if (navbar && !navbar.contains(e.target)) {
        // Use setTimeout to ensure this happens after any checkbox toggle
        setTimeout(() => {
          navToggle.checked = false;
        }, 10);
      }
      // Or if clicking on navbar but not on menu or toggle button
      else if (navbar && navbar.contains(e.target) && 
               navMenu && !navMenu.contains(e.target) &&
               navToggleLabel && !navToggleLabel.contains(e.target)) {
        setTimeout(() => {
          navToggle.checked = false;
        }, 10);
      }
    }
  });
}

// Force active state check multiple times
function forceActiveStateCheck() {
  setActiveNavLink();
  
  // Check again after a short delay
  setTimeout(() => {
    setActiveNavLink();
  }, 100);
  
  // Check again after page is fully loaded
  setTimeout(() => {
    setActiveNavLink();
  }, 500);
}

// FAQ Accordion
function setupFAQAccordion() {
  console.log('setupFAQAccordion called');
  try {
    const faqItems = document.querySelectorAll(".faq-item");
    console.log('FAQ items found:', faqItems.length);

    if (faqItems.length > 0) {
      faqItems.forEach((item, index) => {
        const question = item.querySelector(".faq-question");
        console.log(`FAQ item ${index}:`, question ? 'has question' : 'no question');

        if (question) {
          // Remove any existing listeners by cloning
          const newQuestion = question.cloneNode(true);
          question.parentNode.replaceChild(newQuestion, question);
          
          newQuestion.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            console.log(`FAQ item ${index} clicked`);
            
            // Close all other items
            faqItems.forEach((otherItem) => {
              if (otherItem !== item) {
                otherItem.classList.remove("active");
              }
            });

            // Toggle current item
            const wasActive = item.classList.contains("active");
            item.classList.toggle("active");
            console.log(`FAQ item ${index} is now:`, item.classList.contains("active") ? 'active' : 'inactive');
          });
        }
      });
      console.log('FAQ accordion setup complete');
    } else {
      console.log('No FAQ items found on this page');
    }
  } catch (error) {
    console.error('Error setting up FAQ accordion:', error);
  }
}

// Set minimum dates for check-in and check-out
function setupDateInputs() {
  try {
    const checkinInput = document.getElementById("checkin")
    const checkoutInput = document.getElementById("checkout")

    if (checkinInput && checkoutInput) {
      const today = new Date().toISOString().split("T")[0]
      checkinInput.min = today
      checkoutInput.min = today

      checkinInput.addEventListener("change", () => {
        checkoutInput.min = checkinInput.value
        updateBookingSummary()
      })

      checkoutInput.addEventListener("change", updateBookingSummary)
    }
  } catch (error) {
    console.error('Error setting up date inputs:', error);
  }
}

// Calculate booking summary
function updateBookingSummary() {
  const roomType = document.querySelector('input[name="room_type"]:checked')
  const checkin = document.getElementById("checkin")?.value
  const checkout = document.getElementById("checkout")?.value
  const adults = Number.parseInt(document.getElementById("adults")?.value) || 0
  const children = Number.parseInt(document.getElementById("children")?.value) || 0
  const extraPersons = Number.parseInt(document.getElementById("extra_persons")?.value) || 0
  const breakfastAddon = document.getElementById("breakfast_addon")?.checked || false

  if (!roomType || !checkin || !checkout) return

  // Calculate nights
  const checkinDate = new Date(checkin)
  const checkoutDate = new Date(checkout)
  const nights = Math.max(1, Math.ceil((checkoutDate - checkinDate) / (1000 * 60 * 60 * 24)))

  // Get room rate
  const roomRate = Number.parseInt(roomType.dataset.price)

  // Calculate totals
  const roomSubtotal = roomRate * nights
  const extraPersonsTotal = extraPersons * 500 * nights
  const breakfastTotal = breakfastAddon ? extraPersons * 200 * nights : 0
  const subtotalAmount = roomSubtotal + extraPersonsTotal + breakfastTotal
  const otherTaxes = Math.round(subtotalAmount * 0.03) // 3% other taxes (2% Razorpay + 1% platform)
  const totalAmount = subtotalAmount + otherTaxes

  // Get room names
  const roomNames = {
    "nonac": "Non A/C Room",
    "non-ac": "Non A/C Room",
    ac: "A/C Room",
    deluxe: "Deluxe A/C Room",
    suite: "Suite",
  }

  // Update summary
  const summaryRoom = document.getElementById("summary_room")
  const summaryCheckin = document.getElementById("summary_checkin")
  const summaryCheckout = document.getElementById("summary_checkout")
  const summaryNights = document.getElementById("summary_nights")
  const summaryRoomRate = document.getElementById("summary_room_rate")
  const summarySubtotal = document.getElementById("summary_subtotal")
  const summaryExtra = document.getElementById("summary_extra")
  const summaryBreakfast = document.getElementById("summary_breakfast")
  const summaryOtherTaxes = document.getElementById("summary_other_taxes")
  const summaryTotal = document.getElementById("summary_total")

  if (summaryRoom) {
    summaryRoom.textContent = roomNames[roomType.value]
    summaryCheckin.textContent = new Date(checkin).toLocaleDateString("en-IN")
    summaryCheckout.textContent = new Date(checkout).toLocaleDateString("en-IN")
    summaryNights.textContent = nights
    summaryRoomRate.textContent = "₹" + roomRate.toLocaleString()
    summarySubtotal.textContent = "₹" + roomSubtotal.toLocaleString()
    summaryExtra.textContent = "₹" + extraPersonsTotal.toLocaleString()
    summaryBreakfast.textContent = "₹" + breakfastTotal.toLocaleString()
    summaryOtherTaxes.textContent = "₹" + otherTaxes.toLocaleString()
    summaryTotal.textContent = "₹" + totalAmount.toLocaleString()

    // Update Razorpay amount (in paise)
    updateRazorpayAmount(totalAmount)
  }
}

// Update Razorpay amount and enable button
function updateRazorpayAmount(amount) {
  const razorpayButton = document.getElementById("razorpay-button")
  const fullPaymentAmount = document.getElementById("full_payment_amount")
  const advancePaymentAmount = document.getElementById("advance_payment_amount")
  
  if (razorpayButton) {
    // Store full amount in data attribute
    razorpayButton.dataset.fullAmount = amount * 100 // Convert to paise
    razorpayButton.dataset.advanceAmount = (amount * 0.5) * 100 // 50% in paise
    
    // Update payment option displays
    if (fullPaymentAmount) {
      fullPaymentAmount.textContent = "₹" + amount.toLocaleString()
    }
    if (advancePaymentAmount) {
      advancePaymentAmount.textContent = "₹" + (amount * 0.5).toLocaleString()
    }
    
    // Enable button if amount is valid
    if (amount > 0) {
      razorpayButton.disabled = false
    } else {
      razorpayButton.disabled = true
    }
  }
}

// Initialize Razorpay payment
function initializeRazorpay() {
  const razorpayButton = document.getElementById("razorpay-button")
  
  if (razorpayButton) {
    razorpayButton.addEventListener("click", () => {
      // Check if a room is selected
      const roomSelected = document.querySelector('input[name="room_type"]:checked')
      if (!roomSelected) {
        alert("Please Select a Room")
        return
      }
      
      const name = document.getElementById("name")?.value || ""
      const email = document.getElementById("email")?.value || ""
      const mobile = document.getElementById("mobile")?.value || ""
      const termsChecked = document.getElementById("terms")?.checked || false
      
      // Validate required fields
      if (!name || !email || !mobile) {
        alert("Please fill in all guest information fields")
        return
      }
      
      if (!termsChecked) {
        alert("Please agree to the terms and conditions")
        return
      }
      
      // Get payment type
      const paymentType = document.querySelector('input[name="payment_type"]:checked')?.value || "full"
      const fullAmount = Number.parseInt(razorpayButton.dataset.fullAmount) || 0
      const advanceAmount = Number.parseInt(razorpayButton.dataset.advanceAmount) || 0
      const amount = paymentType === "advance" ? advanceAmount : fullAmount
      
      if (amount <= 0) {
        alert("Please select a room and dates")
        return
      }
      
      // Payment description based on type
      const description = paymentType === "advance" 
        ? "Advance Payment (50%) - Room Booking" 
        : "Full Payment - Room Booking"
      
      // Razorpay options
      const options = {
        key: "rzp_test_RdbUJPIDy6AufF", // Test Key ID
        amount: amount, // Amount in paise
        currency: "INR",
        name: "Alluri Resorts",
        description: description,
        image: "", // Add your logo URL here if you have one
        prefill: {
          name: name,
          email: email,
          contact: mobile
        },
        theme: {
          color: "#8b0000"
        },
        method: {
          upi: true,
          card: true,
          netbanking: true,
          wallet: true
        },
        handler: function(response) {
          // Payment successful
          console.log("Payment Response:", response)
          
          // Store booking data in localStorage
          const roomType = document.querySelector('input[name="room_type"]:checked')
          const checkin = document.getElementById("checkin")?.value
          const checkout = document.getElementById("checkout")?.value
          const roomNames = {
            "nonac": "Non A/C Room",
            "non-ac": "Non A/C Room",
            ac: "A/C Room",
            deluxe: "Deluxe A/C Room",
            suite: "Suite",
          }
          
          const checkinDate = new Date(checkin)
          const checkoutDate = new Date(checkout)
          const nights = Math.max(1, Math.ceil((checkoutDate - checkinDate) / (1000 * 60 * 60 * 24)))
          
          // Format dates as DD/MM/YYYY
          const formatDateDDMMYYYY = (date) => {
            const d = new Date(date);
            const day = String(d.getDate()).padStart(2, '0');
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const year = d.getFullYear();
            return `${day}/${month}/${year}`;
          };
          
          const totalAmountText = document.getElementById("summary_total")?.textContent || "₹0"
          const paidAmountText = paymentType === "advance" 
            ? document.getElementById("advance_payment_amount")?.textContent || "₹0"
            : totalAmountText
          
          // Get extra persons and breakfast data
          const extraPersons = parseInt(document.getElementById("extra_persons")?.value) || 0;
          const breakfastAddon = document.getElementById("breakfast_addon")?.checked || false;
          
          // Get all the calculated amounts from the summary
          const roomRate = Number.parseInt(roomType.dataset.price);
          const roomSubtotal = roomRate * nights;
          const extraPersonsTotal = extraPersons * 500 * nights;
          const breakfastTotal = breakfastAddon ? extraPersons * 200 * nights : 0;
          const subtotalAmount = roomSubtotal + extraPersonsTotal + breakfastTotal;
          const serviceCharge = Math.round(subtotalAmount * 0.03);
          const grandTotal = subtotalAmount + serviceCharge;
          
          const bookingData = {
            name: name,
            email: email,
            mobile: mobile,
            roomType: roomNames[roomType.value],
            checkin: formatDateDDMMYYYY(checkin),
            checkout: formatDateDDMMYYYY(checkout),
            nights: nights,
            roomRate: roomRate,
            roomSubtotal: roomSubtotal,
            extraPersons: extraPersons,
            extraPersonsTotal: extraPersonsTotal,
            breakfastAddon: breakfastAddon,
            breakfastTotal: breakfastTotal,
            subtotalAmount: subtotalAmount,
            serviceCharge: serviceCharge,
            grandTotal: grandTotal,
            totalAmount: totalAmountText,
            paidAmount: paidAmountText,
            paymentType: paymentType === "advance" ? "Advance (50%)" : "Full Payment",
            remainingAmount: paymentType === "advance" ? paidAmountText : "₹0"
          }
          
          localStorage.setItem('bookingData', JSON.stringify(bookingData))
          
          // Redirect to success page
          window.location.href = "booking-success.html?payment_id=" + response.razorpay_payment_id
        },
        modal: {
          ondismiss: function() {
            console.log("Payment cancelled by user")
          }
        }
      }
      
      const rzp = new Razorpay(options)
      
      rzp.on("payment.failed", function(response) {
        alert("Payment Failed: " + response.error.description)
        console.error("Payment Error:", response.error)
      })
      
      rzp.open()
    })
  }
}

// Setup booking form listeners
function setupBookingForm() {
  const roomTypeInputs = document.querySelectorAll('input[name="room_type"]')
  const adultsInput = document.getElementById("adults")
  const childrenInput = document.getElementById("children")
  const extraPersonsInput = document.getElementById("extra_persons")
  const breakfastAddon = document.getElementById("breakfast_addon")

  // Exit early if booking form elements don't exist (not on booking page)
  if (!adultsInput || !childrenInput || !extraPersonsInput) {
    return;
  }

  // Function to enforce occupancy limits - DISABLED for multi-room booking
  function enforceOccupancyLimits() {
    // This function is disabled because we now use dynamic-guest-limits.js
    // for multi-room booking which handles capacity limits dynamically
    return;
  }

  roomTypeInputs.forEach((input) => {
    input.addEventListener("change", () => {
      enforceOccupancyLimits()
      updateBookingSummary()
    })
  })

  if (adultsInput) {
    adultsInput.addEventListener("change", () => {
      enforceOccupancyLimits()
      updateBookingSummary()
    })
  }
  
  if (childrenInput) {
    childrenInput.addEventListener("change", () => {
      enforceOccupancyLimits()
      updateBookingSummary()
    })
  }
  
  if (extraPersonsInput) extraPersonsInput.addEventListener("change", updateBookingSummary)
  if (breakfastAddon) breakfastAddon.addEventListener("change", updateBookingSummary)
  
  // Initial enforcement
  enforceOccupancyLimits()
}

// Handle contact form submission
function setupContactForm() {
  const contactForm = document.getElementById("contact-form")

  if (contactForm) {
    contactForm.addEventListener("submit", (e) => {
      e.preventDefault()

      const formMessage = document.getElementById("form-message")
      const formData = new FormData(contactForm)

      // Simulate form submission (in production, this would send to a server)
      console.log("Contact form submitted:", Object.fromEntries(formData))

      // Show success message
      formMessage.className = "success"
      formMessage.textContent = "Thank you for your message! We will get back to you soon."

      // Reset form
      contactForm.reset()

      // Hide message after 5 seconds
      setTimeout(() => {
        formMessage.style.display = "none"
      }, 5000)
    })
  }
}

// Get URL parameters (for room selection)
function getUrlParameter(name) {
  const urlParams = new URLSearchParams(window.location.search)
  return urlParams.get(name)
}

// Preset room selection if coming from rooms page
function presetRoomSelection() {
  const roomParam = getUrlParameter("room")
  if (roomParam) {
    const roomInput = document.querySelector(`input[name="room_type"][value="${roomParam}"]`)
    if (roomInput) {
      roomInput.checked = true
      updateBookingSummary()
    }
  }
}

// Navbar scroll effect
function handleNavbarScroll() {
  const navbar = document.querySelector('.navbar');
  
  if (!navbar) {
    console.log('❌ Navbar not found!');
    return;
  }
  
  console.log('✅ Main script navbar handler found navbar');
  console.log('✅ Navbar current classes:', navbar.className);
  console.log('✅ Navbar computed position:', window.getComputedStyle(navbar).position);
  
  // Mark that navbar scroll has been initialized
  window.navbarScrollInitialized = true;
  
  function checkScroll() {
    const scrolled = window.scrollY > 50;
    
    console.log('🔄 Main script scroll check - Position:', window.scrollY, 'Scrolled:', scrolled);
    
    if (scrolled) {
      navbar.classList.add('scrolled');
      console.log('✅ Main script added scrolled class - Classes now:', navbar.className);
    } else {
      navbar.classList.remove('scrolled');
      console.log('❌ Main script removed scrolled class - Classes now:', navbar.className);
    }
    
    // Log computed styles after change
    const computedBg = window.getComputedStyle(navbar).backgroundColor;
    console.log('🎨 Main script navbar background after change:', computedBg);
  }
  
  // Check immediately
  checkScroll();
  
  // Remove any existing scroll listeners to prevent duplicates
  window.removeEventListener('scroll', checkScroll);
  
  // Add single scroll listener
  window.addEventListener('scroll', checkScroll, { passive: true });
  
  console.log('✅ Main script scroll listener attached');
}

// Debug function to test scroll functionality
function testNavbarScroll() {
  const navbar = document.querySelector('.navbar');
  console.log('=== NAVBAR SCROLL DEBUG ===');
  console.log('Navbar element:', navbar);
  console.log('Current scroll position:', window.scrollY);
  console.log('Navbar classes:', navbar ? navbar.className : 'No navbar found');
  console.log('Navbar computed position:', navbar ? window.getComputedStyle(navbar).position : 'No navbar found');
  console.log('Navbar computed background:', navbar ? window.getComputedStyle(navbar).backgroundColor : 'No navbar found');
  console.log('Navbar computed z-index:', navbar ? window.getComputedStyle(navbar).zIndex : 'No navbar found');
  
  // Test adding/removing scrolled class manually
  if (navbar) {
    console.log('Testing manual class toggle...');
    const hadScrolled = navbar.classList.contains('scrolled');
    navbar.classList.toggle('scrolled');
    console.log('After toggle - classes:', navbar.className);
    console.log('After toggle - background:', window.getComputedStyle(navbar).backgroundColor);
    
    // Toggle back after 2 seconds
    setTimeout(() => {
      navbar.classList.toggle('scrolled');
      console.log('Toggled back - classes:', navbar.className);
      console.log('Toggled back - background:', window.getComputedStyle(navbar).backgroundColor);
    }, 2000);
  }
  
  console.log('=== END DEBUG ===');
}

// Make test function globally available
if (typeof window !== 'undefined') {
  window.testNavbarScroll = testNavbarScroll;
}

// Initialize all functions - REMOVED DUPLICATE (see line ~649 for main initialization)

// Update navigation on page load
window.addEventListener("load", setActiveNavLink)


// Hero Slideshow
let slideIndex = 1
let slideTimer
let isTransitioning = false
let totalSlides = 3

function showSlides(n) {
  const slides = document.getElementsByClassName("slide")
  const dots = document.getElementsByClassName("dot")

  if (!slides.length) return

  // Update total slides count
  totalSlides = slides.length

  if (n > totalSlides) {
    slideIndex = 1
  }
  if (n < 1) {
    slideIndex = totalSlides
  }

  // Remove active class from all slides
  for (let i = 0; i < slides.length; i++) {
    slides[i].classList.remove("active")
  }

  // Remove active class from all dots
  for (let i = 0; i < dots.length; i++) {
    dots[i].classList.remove("active")
  }

  // Add active class to current slide and dot
  slides[slideIndex - 1].classList.add("active")
  if (dots.length) {
    dots[slideIndex - 1].classList.add("active")
  }
}

function changeSlide(n) {
  if (isTransitioning) return // Prevent multiple rapid clicks
  
  isTransitioning = true
  
  // Clear the existing timer and restart it
  clearTimeout(slideTimer)
  
  slideIndex += n
  showSlides(slideIndex)
  
  // Reset transition lock quickly for responsive feel
  setTimeout(() => {
    isTransitioning = false
  }, 300) // Reduced from 1500ms to 300ms for faster response
  
  // Restart the 5-second timer from this point
  autoSlide()
}

function currentSlide(n) {
  if (isTransitioning) return // Prevent multiple rapid clicks
  
  isTransitioning = true
  
  // Clear the existing timer and restart it
  clearTimeout(slideTimer)
  
  slideIndex = n
  showSlides(slideIndex)
  
  // Reset transition lock quickly for responsive feel
  setTimeout(() => {
    isTransitioning = false
  }, 300) // Reduced from 1500ms to 300ms for faster response
  
  // Restart the 5-second timer from this point
  autoSlide()
}

function autoSlide() {
  // Clear any existing timer first
  clearTimeout(slideTimer)
  
  slideTimer = setTimeout(() => {
    if (!isTransitioning) { // Only auto-advance if not manually transitioning
      slideIndex++
      // Ensure we move forward correctly
      if (slideIndex > totalSlides) {
        slideIndex = 1
      }
      showSlides(slideIndex)
    }
    autoSlide() // Continue the cycle
  }, 5000) // Change slide every 5 seconds
}

// Initialize slideshow
function initSlideshow() {
  const slides = document.getElementsByClassName("slide")
  if (slides.length) {
    totalSlides = slides.length
    showSlides(slideIndex)
    autoSlide()
  }
}

// Note: Email functionality will be connected to Supabase database

// Handle review form submission - Connected to PHP backend
function setupReviewForm() {
  console.log('setupReviewForm called');
  const reviewForm = document.getElementById("review-form")
  console.log('Review form element:', reviewForm);

  if (reviewForm) {
    console.log('Review form found, attaching event listener');
    
    // Remove any existing event listeners to prevent double submission
    const newForm = reviewForm.cloneNode(true);
    reviewForm.parentNode.replaceChild(newForm, reviewForm);
    
    newForm.addEventListener("submit", function(e) {
      e.preventDefault();
      e.stopPropagation();
      e.stopImmediatePropagation();
      
      console.log('Review form submitted!');

      const reviewMessage = document.getElementById("review-message")
      const submitButton = document.getElementById("submit-review-btn")
      
      // Prevent double submission
      if (submitButton && submitButton.disabled) {
        console.log('Already submitting, ignoring duplicate submit');
        return false;
      }
      
      // Disable button and show loading
      if (submitButton) {
        submitButton.disabled = true
        submitButton.textContent = "Submitting..."
      }

      // Get form data
      const formData = new FormData(newForm)
      
      console.log('Sending to submit_review.php...');

      // Send AJAX request to PHP handler
      fetch('submit_review.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        console.log('Response received:', response.status);
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        // Log debug info to console
        console.log('Server response:', data);
        
        if (data.success) {
          // Success
          reviewMessage.className = "success"
          reviewMessage.textContent = data.message
          reviewMessage.style.display = "block"
          newForm.reset()
          
          setTimeout(() => {
            reviewMessage.style.display = "none"
          }, 5000)
        } else {
          // Error from server
          reviewMessage.className = "error"
          reviewMessage.textContent = data.message
          reviewMessage.style.display = "block"
          
          setTimeout(() => {
            reviewMessage.style.display = "none"
          }, 5000)
        }
      })
      .catch(error => {
        console.error('Error submitting review:', error)
        reviewMessage.className = "error"
        reviewMessage.textContent = "Sorry, there was an error submitting your review. Please try again."
        reviewMessage.style.display = "block"
        
        setTimeout(() => {
          reviewMessage.style.display = "none"
        }, 5000)
      })
      .finally(() => {
        // Re-enable button
        if (submitButton) {
          submitButton.disabled = false
          submitButton.textContent = "Submit Review"
        }
      })
      
      return false;
    })
  } else {
    console.log('Review form NOT found on this page');
  }
}

// Contact form - Connected to Supabase
async function setupContactFormEmail() {
  const contactForm = document.getElementById("contact-form")

  if (contactForm) {
    contactForm.addEventListener("submit", async (e) => {
      e.preventDefault()

      const formMessage = document.getElementById("form-message")
      const submitButton = contactForm.querySelector('button[type="submit"]')
      
      // Disable button and show loading
      if (submitButton) {
        submitButton.disabled = true
        submitButton.textContent = "Sending..."
      }

      // Get form data
      const formData = new FormData(contactForm)
      const contactData = {
        name: formData.get('name'),
        email: formData.get('email'),
        phone: formData.get('phone') || null,
        subject: formData.get('subject'),
        message: formData.get('message'),
        ip_address: await dbHelpers.getUserIP(),
        user_agent: navigator.userAgent
      }

      try {
        // Save to Supabase
        await dbHelpers.saveContactSubmission(contactData)

        // Success
        formMessage.className = "success"
        formMessage.textContent = "Thank you! Your message has been sent successfully. We'll get back to you soon."
        formMessage.style.display = "block"
        contactForm.reset()
        
        setTimeout(() => {
          formMessage.style.display = "none"
        }, 5000)
      } catch (error) {
        console.error('Error submitting contact form:', error)
        formMessage.className = "error"
        formMessage.textContent = "Sorry, there was an error sending your message. Please try WhatsApp or call us directly."
        formMessage.style.display = "block"
        
        setTimeout(() => {
          formMessage.style.display = "none"
        }, 5000)
      } finally {
        // Re-enable button
        if (submitButton) {
          submitButton.disabled = false
          submitButton.textContent = "Send Message"
        }
      }
    })
  }
}

// Update DOMContentLoaded - REMOVED DUPLICATE (see line ~649 for main initialization)


// Handle query form submission on FAQ page
function setupQueryForm() {
  const queryForm = document.getElementById("query-form")

  if (queryForm) {
    queryForm.addEventListener("submit", (e) => {
      e.preventDefault()

      const queryMessage = document.getElementById("query-form-message")
      const formData = new FormData(queryForm)
      const data = Object.fromEntries(formData)

      // Create mailto link
      const subject = encodeURIComponent("FAQ Query: " + data.subject)
      const body = encodeURIComponent(
        `Name: ${data.name}\nEmail: ${data.email}\n\nQuestion:\n${data.message}`,
      )
      const mailtoLink = `mailto:alluriresorts.host@gmail.com?subject=${subject}&body=${body}`

      // Open mailto link
      window.location.href = mailtoLink

      // Show success message
      queryMessage.className = "success"
      queryMessage.textContent = "Your email client will open to send your query. We'll respond as soon as possible!"
      queryMessage.style.display = "block"

      // Reset form
      queryForm.reset()

      // Hide message after 7 seconds
      setTimeout(() => {
        queryMessage.style.display = "none"
      }, 7000)
    })
  }
}

// Initialize image modal
function initImageModal() {
  const modal = document.getElementById('imageModal');
  if (modal) {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        closeImageModal();
      }
    });
  }
  
  // Close modal with Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeImageModal();
    }
  });
}

// Main initialization - All functions called here
document.addEventListener("DOMContentLoaded", () => {
  setActiveNavLink()
  setupMobileMenuClose()
  setupFAQAccordion()
  setupDateInputs()
  setupBookingForm()
  setupContactFormEmail()
  setupReviewForm()
  setupQueryForm()
  presetRoomSelection()
  initializeRazorpay()
  initSlideshow()
  handleNavbarScroll()
  setupVideoMuteToggle()
  initImageModal()

  // Update summary on input changes
  const inputs = document.querySelectorAll(
    'input[name="room_type"], #adults, #children, #extra_persons, #breakfast_addon',
  )
  inputs.forEach((input) => {
    input.addEventListener("change", updateBookingSummary)
  })
})


// Tourism Carousel
let tourismCurrentIndex = 0
let tourismCardsPerView = 3

function updateTourismCardsPerView() {
  if (window.innerWidth <= 768) {
    tourismCardsPerView = 1
  } else if (window.innerWidth <= 1024) {
    tourismCardsPerView = 2
  } else {
    tourismCardsPerView = 3
  }
}

function initTourismCarousel() {
  const track = document.querySelector('.tourism-carousel-track')
  if (!track) return
  
  const cards = track.querySelectorAll('.tourism-card')
  const totalCards = cards.length
  const dotsContainer = document.getElementById('tourism-dots')
  
  if (totalCards === 0) return
  
  updateTourismCardsPerView()
  const totalPages = Math.ceil(totalCards / tourismCardsPerView)
  
  // Create dots
  if (dotsContainer) {
    dotsContainer.innerHTML = ''
    for (let i = 0; i < totalPages; i++) {
      const dot = document.createElement('button')
      dot.className = 'carousel-dot' + (i === 0 ? ' active' : '')
      dot.onclick = () => goToTourismPage(i)
      dot.setAttribute('aria-label', `Go to page ${i + 1}`)
      dotsContainer.appendChild(dot)
    }
  }
  
  updateTourismCarousel()
}

function moveTourismCarousel(direction) {
  const track = document.querySelector('.tourism-carousel-track')
  if (!track) return
  
  const cards = track.querySelectorAll('.tourism-card')
  const totalCards = cards.length
  const totalPages = Math.ceil(totalCards / tourismCardsPerView)
  
  tourismCurrentIndex += direction
  
  if (tourismCurrentIndex < 0) {
    tourismCurrentIndex = totalPages - 1
  } else if (tourismCurrentIndex >= totalPages) {
    tourismCurrentIndex = 0
  }
  
  updateTourismCarousel()
}

function goToTourismPage(pageIndex) {
  tourismCurrentIndex = pageIndex
  updateTourismCarousel()
}

function updateTourismCarousel() {
  const track = document.querySelector('.tourism-carousel-track')
  if (!track) return
  
  const cards = track.querySelectorAll('.tourism-card')
  const totalCards = cards.length
  const totalPages = Math.ceil(totalCards / tourismCardsPerView)
  
  if (totalCards === 0) return
  
  // Get the actual card width and gap from computed styles
  const firstCard = cards[0]
  const trackStyle = window.getComputedStyle(track)
  const gap = parseFloat(trackStyle.gap) || 0
  const cardWidth = firstCard.offsetWidth
  
  // Calculate exact offset: move by (cardWidth + gap) * number of cards per view
  const scrollDistance = (cardWidth + gap) * tourismCardsPerView
  const offset = -tourismCurrentIndex * scrollDistance
  
  track.style.transform = `translateX(${offset}px)`
  
  // Update dots
  const dots = document.querySelectorAll('.carousel-dot')
  dots.forEach((dot, index) => {
    dot.classList.toggle('active', index === tourismCurrentIndex)
  })
}

// Handle window resize for tourism carousel
window.addEventListener('resize', () => {
  const oldCardsPerView = tourismCardsPerView
  updateTourismCardsPerView()
  
  if (oldCardsPerView !== tourismCardsPerView) {
    tourismCurrentIndex = 0
    initTourismCarousel()
  }
})

// Initialize tourism carousel on page load
if (document.querySelector('.tourism-carousel')) {
  window.addEventListener('load', initTourismCarousel)
}


// Dhimsa Video Mute Toggle - FIXED VERSION
function setupVideoMuteToggle() {
  // Wait a bit for DOM to be fully ready
  setTimeout(() => {
    const video = document.getElementById('dhimsa-video');
    const muteBtn = document.getElementById('video-mute-btn');
    const muteIcon = document.getElementById('mute-icon');
    
    if (!video || !muteBtn || !muteIcon) {
      return; // Not on amenities page
    }
    
    console.log('✅ Video mute button found and initializing...');
    
    // Simple toggle function
    function toggleMute() {
      video.muted = !video.muted;
      muteIcon.textContent = video.muted ? '🔇' : '🔊';
      muteBtn.title = video.muted ? 'Click to unmute' : 'Click to mute';
      console.log(video.muted ? '🔇 MUTED' : '🔊 UNMUTED');
    }
    
    // Attach click handler
    muteBtn.onclick = function(e) {
      e.stopPropagation();
      toggleMute();
    };
    
    console.log('✅ Mute button ready! Click the button to toggle sound.');
  }, 100);
}


// Image Modal Functions
function openImageModal(event, src, alt) {
  event.preventDefault();
  event.stopPropagation();
  
  const modal = document.getElementById('imageModal');
  const modalImg = document.getElementById('modalImage');
  const caption = document.getElementById('modalCaption');
  
  modal.style.display = 'block';
  modalImg.src = src;
  caption.textContent = alt;
  
  // Prevent body scroll when modal is open
  document.body.style.overflow = 'hidden';
}

function closeImageModal() {
  const modal = document.getElementById('imageModal');
  modal.style.display = 'none';
  
  // Restore body scroll
  document.body.style.overflow = 'auto';
}

// Image modal initialization moved to main DOMContentLoaded block above


// Guest Highlights Carousel
let guestCurrentIndex = 0;
let guestCardsPerView = 3;

function updateGuestCardsPerView() {
  if (window.innerWidth <= 768) {
    guestCardsPerView = 1;
  } else if (window.innerWidth <= 1024) {
    guestCardsPerView = 2;
  } else {
    guestCardsPerView = 3;
  }
}

function initGuestCarousel() {
  const track = document.querySelector('.guest-carousel-track');
  if (!track) return;
  
  const cards = track.querySelectorAll('.guest-highlight-card');
  const totalCards = cards.length;
  const dotsContainer = document.getElementById('guest-dots');
  
  if (totalCards === 0) return;
  
  updateGuestCardsPerView();
  const totalPages = Math.ceil(totalCards / guestCardsPerView);
  
  // Create dots
  if (dotsContainer) {
    dotsContainer.innerHTML = '';
    for (let i = 0; i < totalPages; i++) {
      const dot = document.createElement('button');
      dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
      dot.onclick = () => goToGuestPage(i);
      dot.setAttribute('aria-label', `Go to page ${i + 1}`);
      dotsContainer.appendChild(dot);
    }
  }
  
  updateGuestCarousel();
}

function moveGuestCarousel(direction) {
  const track = document.querySelector('.guest-carousel-track');
  if (!track) return;
  
  const cards = track.querySelectorAll('.guest-highlight-card');
  const totalCards = cards.length;
  const totalPages = Math.ceil(totalCards / guestCardsPerView);
  
  guestCurrentIndex += direction;
  
  if (guestCurrentIndex < 0) {
    guestCurrentIndex = totalPages - 1;
  } else if (guestCurrentIndex >= totalPages) {
    guestCurrentIndex = 0;
  }
  
  updateGuestCarousel();
}

function goToGuestPage(pageIndex) {
  guestCurrentIndex = pageIndex;
  updateGuestCarousel();
}

function updateGuestCarousel() {
  const track = document.querySelector('.guest-carousel-track');
  if (!track) return;
  
  const cards = track.querySelectorAll('.guest-highlight-card');
  const totalCards = cards.length;
  
  if (totalCards === 0) return;
  
  // Get the actual card width and gap from computed styles
  const firstCard = cards[0];
  const trackStyle = window.getComputedStyle(track);
  const gap = parseFloat(trackStyle.gap) || 0;
  const cardWidth = firstCard.offsetWidth;
  
  // Calculate exact offset: move by (cardWidth + gap) * number of cards per view
  const scrollDistance = (cardWidth + gap) * guestCardsPerView;
  const offset = -guestCurrentIndex * scrollDistance;
  
  track.style.transform = `translateX(${offset}px)`;
  
  // Update dots
  const dots = document.querySelectorAll('#guest-dots .carousel-dot');
  dots.forEach((dot, index) => {
    dot.classList.toggle('active', index === guestCurrentIndex);
  });
}

// Handle window resize for guest carousel
window.addEventListener('resize', () => {
  const oldGuestCardsPerView = guestCardsPerView;
  updateGuestCardsPerView();
  
  if (oldGuestCardsPerView !== guestCardsPerView) {
    guestCurrentIndex = 0;
    initGuestCarousel();
  }
});

// Initialize guest carousel on page load
if (document.querySelector('.guest-carousel')) {
  window.addEventListener('load', initGuestCarousel);
}


// Room Image Modal Functions
let suiteImages = [];
let currentSuiteIndex = 0;

function openRoomImageModal(src, alt) {
  const modal = document.getElementById('roomImageModal');
  const modalImg = document.getElementById('roomModalImage');
  const caption = document.getElementById('roomModalCaption');
  const prevBtn = document.getElementById('modalPrevBtn');
  const nextBtn = document.getElementById('modalNextBtn');
  
  if (modal && modalImg) {
    modal.style.display = 'block';
    modalImg.src = src;
    if (caption) {
      caption.textContent = alt;
    }
    
    // Hide slideshow arrows for regular images
    if (prevBtn) prevBtn.style.display = 'none';
    if (nextBtn) nextBtn.style.display = 'none';
    
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
  }
}

function openSuiteSlideshow() {
  // Define Suite images
  suiteImages = [
    { src: '../assets/images/Suite/4.jpg', alt: 'Suite Room - View 1' },
    { src: '../assets/images/Suite_room_2.jpg', alt: 'Suite Room - View 2' },
    { src: '../assets/images/Suite_Room_3.jpg', alt: 'Suite Room - View 3' }
  ];
  
  openRoomSlideshow(suiteImages);
}

function openDeluxeSlideshow() {
  // Define Deluxe images
  const deluxeImages = [
    { src: '../assets/images/Deluxe/1.jpg', alt: 'Deluxe A/C Room - View 1' },
    { src: '../assets/images/Deluxe_Room_2.jpg', alt: 'Deluxe A/C Room - View 2' }
  ];
  
  openRoomSlideshow(deluxeImages);
}

function openRoomSlideshow(images) {
  suiteImages = images;
  currentSuiteIndex = 0;
  
  const modal = document.getElementById('roomImageModal');
  const modalImg = document.getElementById('roomModalImage');
  const caption = document.getElementById('roomModalCaption');
  const prevBtn = document.getElementById('modalPrevBtn');
  const nextBtn = document.getElementById('modalNextBtn');
  
  if (modal && modalImg) {
    modal.style.display = 'block';
    modalImg.src = suiteImages[currentSuiteIndex].src;
    if (caption) {
      caption.textContent = suiteImages[currentSuiteIndex].alt;
    }
    
    // Show slideshow arrows
    if (prevBtn) prevBtn.style.display = 'flex';
    if (nextBtn) nextBtn.style.display = 'flex';
    
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
  }
}

function changeModalSlide(direction) {
  if (suiteImages.length === 0) return;
  
  currentSuiteIndex += direction;
  
  // Wrap around
  if (currentSuiteIndex >= suiteImages.length) {
    currentSuiteIndex = 0;
  } else if (currentSuiteIndex < 0) {
    currentSuiteIndex = suiteImages.length - 1;
  }
  
  const modalImg = document.getElementById('roomModalImage');
  const caption = document.getElementById('roomModalCaption');
  
  if (modalImg) {
    modalImg.src = suiteImages[currentSuiteIndex].src;
  }
  if (caption) {
    caption.textContent = suiteImages[currentSuiteIndex].alt;
  }
}

function closeRoomImageModal() {
  const modal = document.getElementById('roomImageModal');
  if (modal) {
    modal.style.display = 'none';
    
    // Reset slideshow
    suiteImages = [];
    currentSuiteIndex = 0;
    
    // Restore body scroll
    document.body.style.overflow = '';
  }
}

// Close modal when clicking outside the image
if (document.getElementById('roomImageModal')) {
  document.getElementById('roomImageModal').addEventListener('click', function(event) {
    if (event.target === this) {
      closeRoomImageModal();
    }
  });
  
  // Close modal with Escape key and navigate with arrow keys
  document.addEventListener('keydown', function(event) {
    const modal = document.getElementById('roomImageModal');
    if (modal && modal.style.display === 'block') {
      if (event.key === 'Escape') {
        closeRoomImageModal();
      } else if (event.key === 'ArrowLeft' && suiteImages.length > 0) {
        changeModalSlide(-1);
      } else if (event.key === 'ArrowRight' && suiteImages.length > 0) {
        changeModalSlide(1);
      }
    }
  });
}
// ========================================
// CONSOLIDATED INITIALIZATION SYSTEM
// ========================================

// Centralized initialization function
function initializeApplication() {
  try {
    console.log('🚀 Starting application initialization...');
    
    // Core navigation and UI
    forceActiveStateCheck(); // Use the new force check function
    setupMobileMenuClose();
    handleNavbarScroll();
    
    // Page-specific features
    setupFAQAccordion();
    setupDateInputs();
    setupBookingForm();
    setupContactFormEmail();
    setupReviewForm();
    setupQueryForm();
    
    // Booking system
    presetRoomSelection();
    initializeRazorpay();
    
    // Media and carousels
    initSlideshow();
    initTourismCarousel();
    initGuestCarousel();
    setupVideoMuteToggle();
    initImageModal();
    
    // Update summary on input changes
    const inputs = safeQueryAll(
      'input[name="room_type"], #adults, #children, #extra_persons, #breakfast_addon'
    );
    inputs.forEach((input) => {
      input.addEventListener("change", updateBookingSummary);
    });
    
    console.log('✅ Application initialization complete');
    INIT_STATE.initialized = true;
    
  } catch (error) {
    console.error('❌ Error during application initialization:', error);
  }
}

// Single DOMContentLoaded listener - replaces all others
document.addEventListener("DOMContentLoaded", () => {
  INIT_STATE.domReady = true;
  initializeApplication();
});

// Backup initialization on window load
window.addEventListener("load", () => {
  if (!INIT_STATE.initialized) {
    console.warn('⚠️ Backup initialization triggered');
    initializeApplication();
  }
  forceActiveStateCheck(); // Always force check on load
});

// Error handling for unhandled promise rejections
window.addEventListener('unhandledrejection', (event) => {
  console.error('Unhandled promise rejection:', event.reason);
});

// Error handling for general errors
window.addEventListener('error', (event) => {
  console.error('Global error:', event.error);
});

console.log('📋 Script.js loaded - waiting for DOM ready...');

// Export debug function to window for console testing
if (typeof window !== 'undefined') {
  window.testNavbarScroll = testNavbarScroll;
  window.handleNavbarScroll = handleNavbarScroll;
}
// ========================================
// ANIMATION FIXES - NO GLITCHES
// ========================================

// Improved Navbar Scroll Handler - No Glitches
function handleNavbarScrollFixed() {
  const navbar = document.querySelector('.navbar');
  
  if (!navbar) {
    console.log('❌ Navbar not found in animation-fixes!');
    return;
  }
  
  console.log('✅ Animation-fixes navbar handler initialized');
  console.log('✅ Navbar current classes:', navbar.className);
  console.log('✅ Navbar computed position:', window.getComputedStyle(navbar).position);
  
  let ticking = false;
  let lastScrollY = window.scrollY;
  
  function updateNavbar() {
    const scrollY = window.scrollY;
    const scrolled = scrollY > 50;
    
    console.log('🔄 Animation-fixes scroll check - Position:', scrollY, 'Scrolled:', scrolled);
    
    if (scrolled) {
      navbar.classList.add('scrolled');
      console.log('✅ Animation-fixes added scrolled class - Classes now:', navbar.className);
    } else {
      navbar.classList.remove('scrolled');
      console.log('❌ Animation-fixes removed scrolled class - Classes now:', navbar.className);
    }
    
    // Log computed styles after change
    const computedBg = window.getComputedStyle(navbar).backgroundColor;
    console.log('🎨 Animation-fixes navbar background after change:', computedBg);
    
    lastScrollY = scrollY;
    ticking = false;
  }
  
  function requestTick() {
    if (!ticking) {
      window.requestAnimationFrame(updateNavbar);
      ticking = true;
    }
  }
  
  // Initial check
  updateNavbar();
  
  // Remove any existing scroll listeners to prevent conflicts
  window.removeEventListener('scroll', requestTick);
  
  // Optimized scroll listener with requestAnimationFrame
  window.addEventListener('scroll', requestTick, { passive: true });
  
  console.log('✅ Animation-fixes scroll listener attached successfully');
}

// Smooth Carousel Movement - No Jitter
function fixCarouselAnimations() {
  const carouselTracks = document.querySelectorAll('.tourism-carousel-track, .guest-carousel-track');
  
  carouselTracks.forEach(track => {
    // Force hardware acceleration
    track.style.willChange = 'transform';
    track.style.transform = 'translateZ(0)';
  });
}

// Fix Button Hover Glitches
function fixButtonHovers() {
  const buttons = document.querySelectorAll('.btn, .btn-primary, .nav-book-btn');
  
  buttons.forEach(button => {
    // Remove any pseudo-element animations
    button.style.overflow = 'hidden';
    button.style.willChange = 'box-shadow, filter';
    button.style.transform = 'translateZ(0)';
    button.style.backfaceVisibility = 'hidden';
    
    // Stable hover effect
    button.addEventListener('mouseenter', function() {
      this.style.transform = 'translateZ(0)';
    });
    
    button.addEventListener('mouseleave', function() {
      this.style.transform = 'translateZ(0)';
    });
  });
}

// Smooth Slideshow Transitions
function fixSlideshowTransitions() {
  const slides = document.querySelectorAll('.slide');
  
  slides.forEach(slide => {
    slide.style.willChange = 'opacity';
    slide.style.transform = 'translateZ(0)';
  });
}

// Fix Image Hover Animations
function fixImageHovers() {
  const images = document.querySelectorAll('.feature-card img, .room-card img, .tourism-card img');
  
  images.forEach(img => {
    img.style.willChange = 'transform';
    img.style.backfaceVisibility = 'hidden';
    
    const parent = img.closest('.feature-card, .room-card, .tourism-card');
    if (parent) {
      parent.addEventListener('mouseenter', function() {
        img.style.transform = 'scale(1.05) translateZ(0)';
      });
      
      parent.addEventListener('mouseleave', function() {
        img.style.transform = 'scale(1) translateZ(0)';
      });
    }
  });
}

// Debounce function for resize events
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Handle window resize smoothly
function handleResizeSmooth() {
  const debouncedResize = debounce(() => {
    // Recalculate carousel positions
    if (typeof updateTourismCarousel === 'function') {
      updateTourismCarousel();
    }
    
    // Recalculate guest carousel if exists
    if (typeof updateGuestCarousel === 'function') {
      updateGuestCarousel();
    }
  }, 250);
  
  window.addEventListener('resize', debouncedResize);
}

// Fix Mobile Menu Animation
function fixMobileMenuAnimation() {
  const navToggle = document.getElementById('nav-toggle');
  const navMenu = document.querySelector('.nav-menu');
  
  if (navToggle && navMenu) {
    navMenu.style.transition = 'left 0.3s ease';
    navMenu.style.willChange = 'left';
  }
}

// Prevent Horizontal Scroll - FIXED: Don't use overflow-x hidden as it breaks sticky positioning
function preventHorizontalScroll() {
  // Use max-width instead of overflow-x hidden to prevent horizontal scroll
  // This allows sticky positioning to work properly
  document.documentElement.style.maxWidth = '100vw';
  document.body.style.maxWidth = '100vw';
  document.body.style.width = '100%';
}

// Fix Floating Leaves Animation (if causing issues)
function optimizeFloatingLeaves() {
  const floatingLeaves = document.querySelectorAll('.floating-leaf');
  
  floatingLeaves.forEach(leaf => {
    leaf.style.willChange = 'transform, top';
    leaf.style.transform = 'translateZ(0)';
  });
}

// Smooth Scroll Behavior
function enableSmoothScroll() {
  // Only enable if user hasn't set reduced motion preference
  if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.documentElement.style.scrollBehavior = 'smooth';
  }
}

// Fix Card Hover Animations
function fixCardHovers() {
  const cards = document.querySelectorAll('.feature-card, .room-card, .tourism-card, .amenity-card, .review-card');
  
  cards.forEach(card => {
    card.style.willChange = 'transform, box-shadow';
    card.style.backfaceVisibility = 'hidden';
    card.style.transform = 'translateZ(0)';
  });
}

// Initialize all animation fixes
function initAnimationFixes() {
  console.log('🔧 Initializing animation fixes...');
  
  // Only initialize navbar scroll if not already handled by main script
  if (!window.navbarScrollInitialized) {
    handleNavbarScrollFixed();
    window.navbarScrollInitialized = true;
    console.log('✅ Navbar scroll initialized by animation-fixes');
  } else {
    console.log('⚠️ Navbar scroll already initialized by main script');
  }
  
  // Apply all other fixes
  fixCarouselAnimations();
  fixButtonHovers();
  fixSlideshowTransitions();
  fixImageHovers();
  handleResizeSmooth();
  fixMobileMenuAnimation();
  preventHorizontalScroll();
  optimizeFloatingLeaves();
  enableSmoothScroll();
  fixCardHovers();
  
  console.log('✅ Animation fixes applied successfully!');
}

// Run fixes when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initAnimationFixes);
} else {
  initAnimationFixes();
}

// Re-apply fixes after page load (for dynamic content)
window.addEventListener('load', () => {
  setTimeout(initAnimationFixes, 100);
});

// Export functions for use in other scripts
// Make functions globally available for testing
if (typeof window !== 'undefined') {
  window.testNavbarScroll = function() {
    const navbar = document.querySelector('.navbar');
    console.log('=== ANIMATION-FIXES NAVBAR DEBUG ===');
    console.log('Navbar element:', navbar);
    console.log('Current scroll position:', window.scrollY);
    console.log('Navbar classes:', navbar ? navbar.className : 'No navbar found');
    console.log('Navbar computed position:', navbar ? window.getComputedStyle(navbar).position : 'No navbar found');
    console.log('Navbar computed background:', navbar ? window.getComputedStyle(navbar).backgroundColor : 'No navbar found');
    
    if (navbar) {
      console.log('Testing manual class toggle...');
      const hadScrolled = navbar.classList.contains('scrolled');
      navbar.classList.toggle('scrolled');
      console.log('After toggle - classes:', navbar.className);
      console.log('After toggle - background:', window.getComputedStyle(navbar).backgroundColor);
      
      setTimeout(() => {
        navbar.classList.toggle('scrolled');
        console.log('Toggled back - classes:', navbar.className);
        console.log('Toggled back - background:', window.getComputedStyle(navbar).backgroundColor);
      }, 2000);
    }
    
    console.log('=== END ANIMATION-FIXES DEBUG ===');
  };
  
  window.handleNavbarScrollFixed = handleNavbarScrollFixed;
}



// ========================================
// BOOKING SYSTEM JAVASCRIPT
// ========================================

// Room quantity controls
document.addEventListener('DOMContentLoaded', function() {
    // Quantity button handlers
    const qtyButtons = document.querySelectorAll('.qty-btn');
    qtyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const action = this.dataset.action;
            const roomType = this.dataset.room;
            const display = document.getElementById(`${roomType}-display`);
            const input = document.getElementById(`${roomType}_qty`);
            
            let currentValue = parseInt(input.value) || 0;
            const maxValue = parseInt(input.getAttribute('max')) || 10;
            
            if (action === 'increase' && currentValue < maxValue) {
                currentValue++;
            } else if (action === 'decrease' && currentValue > 0) {
                currentValue--;
            }
            
            input.value = currentValue;
            display.textContent = currentValue;
            
            // Update total rooms count
            updateTotalRoomsCount();
            
            // Update card selection state
            const card = this.closest('.room-selection-card');
            if (currentValue > 0) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });
    });
    
    // Addon card selection
    const addonCards = document.querySelectorAll('.addon-card');
    addonCards.forEach(card => {
        card.addEventListener('click', function() {
            const checkbox = this.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
            
            if (checkbox.checked) {
                this.classList.add('selected');
            } else {
                this.classList.remove('selected');
            }
        });
    });
    
    // Policy modal functions
    window.openPolicyModal = function() {
        document.getElementById('cancellationPolicyModal').style.display = 'block';
    };
    
    window.closePolicyModal = function() {
        document.getElementById('cancellationPolicyModal').style.display = 'none';
    };
    
    // Image modal functions
    window.openImageModal = function(event, src, alt) {
        event.stopPropagation();
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        const caption = document.getElementById('modalCaption');
        
        modal.style.display = 'block';
        modalImg.src = src;
        caption.textContent = alt;
    };
    
    window.closeImageModal = function() {
        document.getElementById('imageModal').style.display = 'none';
    };
    
    // Close modals on outside click
    window.addEventListener('click', function(event) {
        const policyModal = document.getElementById('cancellationPolicyModal');
        const imageModal = document.getElementById('imageModal');
        
        if (event.target === policyModal) {
            policyModal.style.display = 'none';
        }
        if (event.target === imageModal) {
            imageModal.style.display = 'none';
        }
    });
});

// Update total rooms count
function updateTotalRoomsCount() {
    const roomInputs = document.querySelectorAll('[id$="_qty"]');
    let total = 0;
    
    roomInputs.forEach(input => {
        total += parseInt(input.value) || 0;
    });
    
    const countElement = document.getElementById('total_rooms_count');
    if (countElement) {
        countElement.textContent = total;
    }
}

// Calculate nights
function calculateNights() {
    const checkin = document.getElementById('checkin')?.value;
    const checkout = document.getElementById('checkout')?.value;
    const nightsDisplay = document.getElementById('summary_nights');
    
    if (checkin && checkout && nightsDisplay) {
        const checkinDate = new Date(checkin);
        const checkoutDate = new Date(checkout);
        const nights = Math.max(0, Math.ceil((checkoutDate - checkinDate) / (1000 * 60 * 60 * 24)));
        nightsDisplay.textContent = nights;
    }
}

// Coupon verification
const verifyCouponBtn = document.getElementById('verify-coupon-btn');
if (verifyCouponBtn) {
    verifyCouponBtn.addEventListener('click', function() {
        const phone = document.getElementById('customer-phone')?.value;
        const code = document.getElementById('coupon-code')?.value.toUpperCase();
        const messageDiv = document.getElementById('coupon-message');
        const discountSection = document.getElementById('discount-section');
        
        if (!phone || phone.length !== 10) {
            messageDiv.style.display = 'block';
            messageDiv.style.background = '#fee2e2';
            messageDiv.style.color = '#991b1b';
            messageDiv.textContent = 'Please enter a valid 10-digit phone number';
            return;
        }
        
        if (!code) {
            messageDiv.style.display = 'block';
            messageDiv.style.background = '#fee2e2';
            messageDiv.style.color = '#991b1b';
            messageDiv.textContent = 'Please enter a coupon code';
            return;
        }
        
        // Simulate coupon verification (replace with actual API call)
        const validCoupons = {
            'WELCOME50': 50,
            'SAVE20': 20,
            'FAMILY25': 25,
            'WEEKEND10': 10
        };
        
        if (validCoupons[code]) {
            messageDiv.style.display = 'none';
            discountSection.style.display = 'block';
            document.getElementById('discount-amount').textContent = validCoupons[code];
        } else {
            messageDiv.style.display = 'block';
            messageDiv.style.background = '#fee2e2';
            messageDiv.style.color = '#991b1b';
            messageDiv.textContent = 'Invalid coupon code';
            discountSection.style.display = 'none';
        }
    });
}

// Remove coupon
const removeCouponBtn = document.getElementById('remove-coupon-btn');
if (removeCouponBtn) {
    removeCouponBtn.addEventListener('click', function() {
        document.getElementById('discount-section').style.display = 'none';
        document.getElementById('coupon-code').value = '';
        document.getElementById('customer-phone').value = '';
    });
}


// Handle contact form submission - Connected to PHP backend
function setupContactForm() {
  console.log('setupContactForm called');
  const contactForm = document.getElementById("contact-form");
  console.log('Contact form element:', contactForm);

  if (contactForm) {
    console.log('Contact form found, attaching event listener');
    
    // Remove any existing event listeners to prevent double submission
    const newForm = contactForm.cloneNode(true);
    contactForm.parentNode.replaceChild(newForm, contactForm);
    
    newForm.addEventListener("submit", function(e) {
      e.preventDefault();
      e.stopPropagation();
      e.stopImmediatePropagation();
      
      console.log('Contact form submitted!');

      const contactMessage = document.getElementById("contact-message");
      const submitButton = document.getElementById("submit-contact-btn");
      
      // Prevent double submission
      if (submitButton && submitButton.disabled) {
        console.log('Already submitting, ignoring duplicate submit');
        return false;
      }
      
      // Disable button and show loading
      if (submitButton) {
        submitButton.disabled = true;
        submitButton.textContent = "Sending...";
      }

      // Get form data
      const formData = new FormData(newForm);
      
      console.log('Sending to submit_contact.php...');

      // Send AJAX request to PHP handler
      fetch('submit_contact.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        console.log('Response received:', response.status);
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        // Log debug info to console
        console.log('Server response:', data);
        
        if (data.success) {
          // Success
          contactMessage.className = "success";
          contactMessage.textContent = data.message;
          contactMessage.style.display = "block";
          newForm.reset();
          
          setTimeout(() => {
            contactMessage.style.display = "none";
          }, 5000);
        } else {
          // Error from server
          contactMessage.className = "error";
          contactMessage.textContent = data.message;
          contactMessage.style.display = "block";
          
          setTimeout(() => {
            contactMessage.style.display = "none";
          }, 5000);
        }
      })
      .catch(error => {
        console.error('Error submitting contact:', error);
        contactMessage.className = "error";
        contactMessage.textContent = "Sorry, there was an error sending your message. Please try again or contact us directly.";
        contactMessage.style.display = "block";
        
        setTimeout(() => {
          contactMessage.style.display = "none";
        }, 5000);
      })
      .finally(() => {
        // Re-enable button
        if (submitButton) {
          submitButton.disabled = false;
          submitButton.textContent = "Send Message";
        }
      });
      
      return false;
    });
  } else {
    console.log('Contact form NOT found on this page');
  }
}

// Initialize contact form when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', setupContactForm);
} else {
  setupContactForm();
}
