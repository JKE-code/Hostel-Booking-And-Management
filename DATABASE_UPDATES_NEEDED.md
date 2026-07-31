# Database Updates Needed

## Issue: Staff Page Shows Blank

The staff page was trying to query a `created_at` column that doesn't exist in the `admins` table.

### Fix Applied:
- Removed references to `created_at` column in `admin/staff.php`
- The page now works without requiring database changes

### Optional: If you want to track when staff accounts were created

Run this SQL query to add the `created_at` column:

```sql
ALTER TABLE `admins` 
ADD COLUMN `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `role`;
```

## All Other Issues Fixed

1. ✅ **Staff page blank** - Fixed by removing `created_at` column references
2. ✅ **Deactivated add-ons visible** - Fixed by filtering `WHERE status = 'active'` in booking_step4_addons.php
3. ✅ **Max adults/children calculation** - Already working correctly in online booking
4. ✅ **Walk-in mode guest info** - Moved adults/children fields from dates page to new guest info page (Step 3)

## Walk-in Booking Flow Updated

The offline booking flow has been reorganized:

**Old Flow:**
1. Dates & Guest Info (combined)
2. Select Rooms
3. Add-ons
4. Payment

**New Flow:**
1. Select Dates
2. Select Rooms
3. Guest Info (with max adults/children calculation)
4. Add-ons
5. Payment

This matches the online booking flow better and allows proper capacity calculation based on selected rooms.

## No Database Changes Required

All fixes have been implemented in the code without requiring any database schema changes. The system will work correctly with your current database structure.
