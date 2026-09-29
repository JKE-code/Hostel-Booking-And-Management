import 'models/student.dart';
import 'models/room.dart';
import 'models/outpass.dart';
import 'models/complaint.dart';
import 'models/notice.dart';
import 'models/mess_menu.dart';

class MockData {
  static final List<StudentProfile> students = [
    const StudentProfile(
      id: 1,
      name: 'Rahul Sharma',
      email: 'rahul.sharma@student.hitam.org',
      rollNumber: '21B91A0501',
      department: 'CSE',
      yearOfStudy: 4,
      phone: '+91 98765 43210',
      parentName: 'Ramesh Sharma',
      parentPhone: '+91 98765 00001',
      emergencyContact: '+91 98765 00002',
      bloodGroup: 'O+',
      gender: 'male',
      hostelName: 'Boys Hostel',
      hostelCode: 'BH',
      blockName: 'Block A',
      roomNumber: 'A-101',
      bedIdentifier: 'Bed A',
    ),
    const StudentProfile(
      id: 2,
      name: 'Kavya Reddy',
      email: 'kavya.reddy@student.hitam.org',
      rollNumber: '22B91A0412',
      department: 'ECE',
      yearOfStudy: 3,
      phone: '+91 98765 43211',
      parentName: 'Venkat Reddy',
      parentPhone: '+91 98765 00003',
      emergencyContact: '+91 98765 00004',
      bloodGroup: 'B+',
      gender: 'female',
      hostelName: 'Girls Hostel',
      hostelCode: 'GH',
      blockName: 'Block G',
      roomNumber: 'G-201',
      bedIdentifier: 'Bed A',
    ),
    const StudentProfile(
      id: 3,
      name: 'Sai Teja',
      email: 'sai.teja@student.hitam.org',
      rollNumber: '23B91A0589',
      department: 'CSE',
      yearOfStudy: 2,
      phone: '+91 98765 43212',
      parentName: 'Srinivasa Rao',
      parentPhone: '+91 98765 00005',
      emergencyContact: '+91 98765 00006',
      bloodGroup: 'A+',
      gender: 'male',
      hostelName: 'Boys Hostel',
      hostelCode: 'BH',
      blockName: 'Block B',
      roomNumber: 'B-204',
      bedIdentifier: 'Bed B',
    ),
  ];

  static RoomDetails getRoomDetails(StudentProfile student) {
    if (student.hostelCode == 'GH') {
      return const RoomDetails(
        roomNumber: 'G-201',
        hostelName: 'Girls Hostel',
        blockName: 'Block G',
        floorNumber: 2,
        capacity: 3,
        occupied: 2,
        roomType: 'Non-AC',
        myBed: 'Bed A',
        roommates: [
          Roommate(
            name: 'Priya Nair',
            rollNumber: '22B91A0545',
            department: 'CSE',
            yearOfStudy: 3,
            bedIdentifier: 'Bed B',
          ),
        ],
      );
    }

    return const RoomDetails(
      roomNumber: 'A-101',
      hostelName: 'Boys Hostel',
      blockName: 'Block A',
      floorNumber: 1,
      capacity: 3,
      occupied: 3,
      roomType: 'Non-AC',
      myBed: 'Bed A',
      roommates: [
        Roommate(
          name: 'V. Chaitanya',
          rollNumber: '21B91A1204',
          department: 'IT',
          yearOfStudy: 4,
          bedIdentifier: 'Bed B',
        ),
        Roommate(
          name: 'Arjun Verma',
          rollNumber: '21B91A0532',
          department: 'CSE',
          yearOfStudy: 4,
          bedIdentifier: 'Bed C',
        ),
      ],
    );
  }

  static List<Outpass> getInitialOutpasses() {
    final now = DateTime.now();
    return [
      Outpass(
        id: 1048,
        leaveType: 'weekend',
        destination: 'Hyderabad (Home)',
        reason: 'Family gathering over the weekend',
        outDatetime: now.subtract(const Duration(hours: 3)),
        inDatetime: now.add(const Duration(days: 2, hours: 5)),
        status: 'approved',
        approvedByName: 'Dr. K. Srinivas (Warden)',
        approvedAt: now.subtract(const Duration(hours: 6)),
        qrVerificationCode: 'HITAM-GATE-PASS-2026-1048-A9F7',
      ),
      Outpass(
        id: 1052,
        leaveType: 'day_pass',
        destination: 'Gachibowli Market',
        reason: 'Buying electronics project components',
        outDatetime: now.add(const Duration(days: 3, hours: 2)),
        inDatetime: now.add(const Duration(days: 3, hours: 6)),
        status: 'pending',
      ),
      Outpass(
        id: 1021,
        leaveType: 'medical',
        destination: 'Apollo Clinic, Medchal',
        reason: 'Dentist follow-up appointment',
        outDatetime: now.subtract(const Duration(days: 8, hours: 4)),
        inDatetime: now.subtract(const Duration(days: 8)),
        actualReturnDatetime: now.subtract(const Duration(days: 8, hours: 1)),
        status: 'returned',
        approvedByName: 'Dr. K. Srinivas (Warden)',
        approvedAt: now.subtract(const Duration(days: 8, hours: 6)),
        qrVerificationCode: 'HITAM-GATE-PASS-2026-1021-X3K1',
      ),
    ];
  }

  static List<MaintenanceComplaint> getInitialComplaints() {
    final now = DateTime.now();
    return [
      MaintenanceComplaint(
        id: 2041,
        category: 'electrical',
        title: 'Study Lamp socket loose connection',
        description: 'Socket near Bed A sparks when plugging in laptop charger. Needs socket board replacement.',
        priority: 'high',
        status: 'in_progress',
        wardenRemarks: 'Assigned to Electrician Ramesh. Work expected today by 4 PM.',
        createdAt: now.subtract(const Duration(days: 1, hours: 3)),
      ),
      MaintenanceComplaint(
        id: 2035,
        category: 'wifi',
        title: 'Weak Wi-Fi signal in Block A Corner',
        description: 'Speed drops below 1 Mbps during evening peak hours. Access point might need reset.',
        priority: 'medium',
        status: 'submitted',
        createdAt: now.subtract(const Duration(days: 3)),
      ),
      MaintenanceComplaint(
        id: 1988,
        category: 'plumbing',
        title: 'Bathroom tap leaking continuously',
        description: 'Washbasin tap has dripping water issue in 1st floor common washroom.',
        priority: 'low',
        status: 'resolved',
        wardenRemarks: 'Washer replaced by plumbing staff.',
        createdAt: now.subtract(const Duration(days: 10)),
        resolvedAt: now.subtract(const Duration(days: 9)),
        resolutionRating: 5,
      ),
    ];
  }

  static List<HostelNotice> getNotices() {
    final now = DateTime.now();
    return [
      HostelNotice(
        id: 301,
        title: 'Mess Menu Committee Feedback Meeting',
        content: 'All hostel residents are invited to the dining hall this Wednesday at 7:30 PM to discuss upcoming menu revisions for the next month.',
        category: 'general',
        targetAudience: 'all',
        isPinned: true,
        createdAt: now.subtract(const Duration(hours: 12)),
      ),
      HostelNotice(
        id: 302,
        title: 'Biometric Gate Registration Deadline',
        content: 'Students who have not linked their mobile gate pass QR with security desk must report to Hostel Office between 4 PM and 7 PM.',
        category: 'rule',
        targetAudience: 'all',
        isPinned: true,
        createdAt: now.subtract(const Duration(days: 2)),
      ),
      HostelNotice(
        id: 303,
        title: 'Quarterly Electrical Maintenance Schedule',
        content: 'Boys Hostel Block A & B will have scheduled backup generator testing on Saturday between 10 AM and 1 PM. Plan your study hours accordingly.',
        category: 'maintenance',
        targetAudience: 'boys_hostel',
        isPinned: false,
        createdAt: now.subtract(const Duration(days: 4)),
      ),
    ];
  }

  static List<MealItem> getTodayMessMenu() {
    return const [
      MealItem(
        dayOfWeek: 'Monday',
        mealType: 'Breakfast',
        title: 'South Indian Breakfast',
        items: ['Idli & Medu Vada', 'Sambar', 'Coconut Chutney', 'Tomato Chutney', 'Tea / Coffee / Milk'],
        specialNote: 'Boiled eggs available at counter',
      ),
      MealItem(
        dayOfWeek: 'Monday',
        mealType: 'Lunch',
        title: 'Full Meals Buffet',
        items: ['Steamed Rice', 'Dal Tadka', 'Aloo Gobi Masala', 'Rasam', 'Curd & Pickle', 'Papad'],
      ),
      MealItem(
        dayOfWeek: 'Monday',
        mealType: 'Snacks',
        title: 'Evening Refreshments',
        items: ['Onion Pakoda / Samosa', 'Green Chutney', 'Special Masala Chai', 'Biscuits'],
      ),
      MealItem(
        dayOfWeek: 'Monday',
        mealType: 'Dinner',
        title: 'North-South Dinner Platter',
        items: ['Hot Phulka Roti', 'Paneer Butter Masala', 'Veg Pulao', 'Curd Rice', 'Gulab Jamun dessert'],
        specialNote: 'Chicken Curry special for non-veg coupon holders',
      ),
    ];
  }
}
