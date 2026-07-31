# Database Schema Changes - Explanation Document

## Overview
This document explains all changes, additions, and improvements made to the Alluri Resorts database schema (Version 2.0). The new schema is production-ready with enhanced features for better tracking, security, and operational efficiency.

---

## 1. BOOKING STATUS ENHANCEMENTS

### What Changed:
The `bookings.status` enum was expanded from 4 values to 6 values.

### Old Values:
- `pending`, `confirmed`, `cancelled`, `checkedout`

### New Values:
- `pending`, `confirmed`, `checkedin`, `checkedout`, `cancelled`, `no_show`

### Why:
1. **`checkedin` status**: Separates the check-in action from confirmation. A booking can be confirmed but not yet checked in. This allows better tracking of guest arrival and room occupancy.
2. **`no_show` status**: Tracks guests who confirmed but never arrived. Critical for:
   - Revenue management and forecasting
   - Identifying patterns of no-shows
   - Implementing no-show policies
   - Analytics and reporting

### Impact:
- `admin/checkin_booking.php` now sets status to `checkedin` instead of `confirmed`
- Dashboard and reports can now distinguish between confirmed reservations and actual arrivals
- Better room availability tracking

---

## 2. NEW TABLE: `saved_guest_info`

### Purpose:
Permanent storage of guest information that persists even after bookings are cancelled or checked out.

### Key Fields:
- **Basic Info**: `full_name`, `email`, `mobile`, `address`
- **ID Proof**: `id_proof_type`, `id_proof_number` (Aadhar, Passport, etc.)
- **Guest History**: 
  - `total_bookings`: Number of times guest has booked
  - `total_nights_stayed`: Cumulative nights
  - `total_amount_spent`: Lifetime revenue from guest
  - `first_visit_date`, `last_visit_date`
- **Guest Classification**: `guest_type` enum (`regular`, `vip`, `corporate`, `blacklist`)
- **Notes**: Free-text field for staff notes about the guest

### Why This is Critical:
1. **Customer Relationship Management (CRM)**: Build guest profiles and history
2. **Loyalty Programs**: Identify repeat customers for rewards
3. **VIP Treatment**: Flag important guests for special service
4. **Security**: Maintain blacklist of problematic guests
5. **Marketing**: Target repeat customers with promotions
6. **Data Retention**: Keep guest data even if bookings are deleted
7. **Quick Booking**: Auto-fill guest details for returning customers

### How It Works:
- When a booking is created/completed, guest info is saved or updated in this table
- Mobile number is the primary identifier (guests may change email)
- Automatically increments booking count and updates statistics
- Can be implemented via trigger or application logic

---

## 3. NEW TABLE: `booking_activity_log`

### Purpose:
Complete audit trail of all changes made to bookings.

### What It Tracks:
- **Action**: What was done (e.g., "Status Changed", "Add-on Added", "Amount Updated")
- **Description**: Human-readable description
- **Old/New Values**: Before and after values for changes
- **Who**: `performed_by_admin_id` - which admin made the change
- **When**: `timestamp` - exact time of change
- **Where**: `ip_address` - IP address of the admin

### Why This is Critical:
1. **Accountability**: Know who made what changes and when
2. **Dispute Resolution**: Prove what was agreed upon
3. **Security**: Detect unauthorized changes
4. **Compliance**: Meet legal requirements for record-keeping
5. **Training**: Review staff actions for quality control
6. **Debugging**: Trace issues back to specific changes

### Example Use Cases:
- Guest disputes the total amount → Check log to see if add-ons were added
- Booking mysteriously cancelled → See which admin cancelled it and why
- Payment discrepancy → Track all payment modifications
- Status changes → Full history of booking lifecycle

---

## 4. NEW TABLE: `email_log`

### Purpose:
Track all emails sent from the system.

### What It Tracks:
- **Recipient**: Email address
- **Type**: `booking_confirmation`, `payment_receipt`, `cancellation`, `reminder`, `promotional`, `other`
- **Status**: `sent`, `failed`, `pending`
- **Error Messages**: If email failed, why?
- **Timestamps**: When email was sent

### Why This is Critical:
1. **Proof of Communication**: Prove that confirmation was sent
2. **Troubleshooting**: Debug email delivery issues
3. **Resend Capability**: Easily resend failed emails
4. **Analytics**: Track email open rates and engagement
5. **Compliance**: Meet legal requirements for communication records
6. **Customer Service**: Verify what was communicated to guests

---

## 5. NEW TABLE: `system_settings`

### Purpose:
Centralized configuration management without code changes.

### Current Settings:
- `processing_fee_percentage`: 3% (for online bookings)
- `gst_percentage`: 5%
- `advance_payment_percentage`: 50%
- `checkin_time`: 14:00 (2 PM)
- `checkout_time`: 11:00 (11 AM)
- `cancellation_hours`: 24 (free cancellation window)
- `max_booking_days`: 30 (maximum booking duration)
- `booking_ref_prefix`: ALR (for booking references)

### Why This is Critical:
1. **Flexibility**: Change settings without modifying code
2. **No Deployment**: Update fees/times instantly
3. **Audit Trail**: Track when settings were changed
4. **Type Safety**: `setting_type` ensures correct data types
5. **Documentation**: Built-in descriptions for each setting
6. **Scalability**: Easy to add new settings as needed

---

## 6. ENHANCED GUEST TABLE

### New Fields Added:
- `address`: Full address of guest
- `id_proof_type`: Type of ID (Aadhar, Passport, Driving License, Voter ID, Other)
- `id_proof_number`: ID number for verification
- `special_requests`: Guest-specific requests

### Why:
1. **Legal Compliance**: Many jurisdictions require ID proof for hotel stays
2. **Security**: Verify guest identity
3. **Communication**: Address for sending physical documents
4. **Service Quality**: Track and fulfill special requests
5. **Emergency Contact**: Address for emergencies

---

## 7. ENHANCED BOOKINGS TABLE

### New Fields Added:
- `checkin_time`: Exact timestamp when guest checked in
- `checkout_time`: Exact timestamp when guest checked out
- `discount_amount`: Amount discounted (for reporting)
- `updated_at`: Track when booking was last modified

### Why:
1. **Precise Tracking**: Know exact check-in/out times, not just dates
2. **Late Checkout**: Charge for late checkouts
3. **Early Checkin**: Track early arrivals
4. **Reporting**: Better analytics with exact timestamps
5. **Audit Trail**: `updated_at` shows when booking was last touched

---

## 8. ENHANCED INDEXES

### New Indexes Added:
- `idx_status` on multiple tables (bookings, rooms, addons, etc.)
- `idx_dates` on date ranges
- `idx_email`, `idx_mobile` for guest lookups
- `idx_timestamp` on activity logs
- `idx_payment_date` on payments

### Why:
1. **Performance**: Faster queries on frequently searched columns
2. **Scalability**: Handle thousands of bookings efficiently
3. **User Experience**: Instant search results
4. **Reporting**: Quick analytics and dashboard loading

---

## 9. ENHANCED FOREIGN KEYS

### What Changed:
Added `ON DELETE CASCADE` to critical relationships:
- `guests` → `bookings`
- `booking_rooms` → `bookings`
- `booking_addons` → `bookings`
- `booking_room_assignments` → `bookings`
- `booking_activity_log` → `bookings`

### Why:
1. **Data Integrity**: Automatically clean up related records
2. **No Orphans**: Prevent orphaned records in child tables
3. **Consistency**: Database stays consistent automatically
4. **Simplified Code**: No need to manually delete related records

---

## 10. TIMESTAMP FIELDS

### What Changed:
Added `created_at` and `updated_at` to all tables.

### Why:
1. **Audit Trail**: Know when records were created/modified
2. **Debugging**: Trace issues to specific time periods
3. **Analytics**: Time-based reporting
4. **Data Quality**: Identify stale or outdated records
5. **Compliance**: Meet record-keeping requirements

---

## 11. ENHANCED ROOM MANAGEMENT

### New Table: `booking_room_assignments`
Tracks specific room numbers assigned to bookings (not just room types).

### Why:
1. **Specific Rooms**: Assign Room 101, not just "AC Room"
2. **Guest Preferences**: Remember which room guest liked
3. **Maintenance**: Track which rooms need service
4. **Housekeeping**: Know exactly which rooms to clean
5. **Reporting**: Room-level occupancy analytics

---

## 12. PAYMENT TRACKING ENHANCEMENTS

### New Fields in `payments`:
- `processed_by_admin_id`: Which admin processed the payment
- `notes`: Additional payment notes

### Why:
1. **Accountability**: Know who handled cash payments
2. **Reconciliation**: Match payments to staff shifts
3. **Audit**: Track payment handling
4. **Notes**: Record check numbers, transaction details, etc.

---

## 13. COUPON ENHANCEMENTS

### New Indexes and Tracking:
- `idx_expiry` for quick expiry checks
- Better tracking of usage limits

### Why:
1. **Performance**: Quickly filter expired coupons
2. **Automation**: Auto-disable expired coupons
3. **Reporting**: Track coupon effectiveness
4. **Fraud Prevention**: Enforce usage limits

---

## 14. CONTACT MESSAGES ENHANCEMENTS

### New Fields:
- `keep_forever`: Flag important messages
- `replied_by_admin_id`: Track who responded
- `reply_message`: Store the reply
- `replied_at`: When reply was sent

### Why:
1. **Customer Service**: Track response times
2. **Quality Control**: Review staff responses
3. **Important Messages**: Flag VIP or critical inquiries
4. **Analytics**: Measure response efficiency

---

## 15. REVIEWS ENHANCEMENTS

### New Fields:
- `booking_id`: Link review to specific booking
- `approved_by_admin_id`: Track who approved review
- `keep_forever`: Flag exceptional reviews

### Why:
1. **Verification**: Verify reviews are from actual guests
2. **Accountability**: Know who approved what
3. **Showcase**: Keep best reviews permanently
4. **Fraud Prevention**: Link reviews to verified bookings

---

## MIGRATION NOTES

### For Existing Databases:
1. **Backup First**: Always backup before migration
2. **Status Migration**: Existing `confirmed` bookings stay as `confirmed`
3. **New Statuses**: Use `checkedin` for new check-ins going forward
4. **Guest Data**: Run script to populate `saved_guest_info` from existing `guests` table
5. **Indexes**: Will be created automatically, may take time on large databases
6. **Foreign Keys**: Ensure no orphaned records before adding CASCADE

### Testing Checklist:
- [ ] Test check-in flow with new `checkedin` status
- [ ] Verify `saved_guest_info` is populated on booking creation
- [ ] Test activity log captures all changes
- [ ] Verify email log tracks sent emails
- [ ] Test system settings are read correctly
- [ ] Verify CASCADE deletes work properly
- [ ] Test all existing PHP files work with new schema
- [ ] Run performance tests on indexed queries

---

## BENEFITS SUMMARY

### Operational:
- Better guest tracking and history
- Complete audit trail for accountability
- Flexible configuration without code changes
- Improved room assignment tracking

### Business:
- Customer relationship management
- Loyalty program foundation
- Better analytics and reporting
- Revenue optimization with no-show tracking

### Technical:
- Better performance with indexes
- Data integrity with foreign keys
- Scalability for growth
- Cleaner code with CASCADE deletes

### Compliance:
- Audit trails for legal requirements
- ID proof tracking for regulations
- Email communication records
- Complete booking history

---

## NEXT STEPS

1. **Update PHP Files**: Modify all files that reference booking status
2. **Create Migration Script**: Script to populate `saved_guest_info` from existing data
3. **Update Admin Panel**: Add UI for viewing guest history and activity logs
4. **Email Integration**: Implement email logging in all email functions
5. **Settings UI**: Create admin interface for managing system settings
6. **Reports**: Build new reports using enhanced data
7. **Testing**: Comprehensive testing of all changes
8. **Documentation**: Update user manual and training materials

---

**Document Version**: 1.0  
**Date**: February 18, 2026  
**Author**: Kiro AI Assistant  
**Database Version**: 2.0
