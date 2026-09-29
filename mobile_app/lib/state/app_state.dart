import 'package:flutter/material.dart';
import '../data/mock_data.dart';
import '../data/models/student.dart';
import '../data/models/room.dart';
import '../data/models/outpass.dart';
import '../data/models/complaint.dart';
import '../data/models/notice.dart';
import '../data/models/mess_menu.dart';

class AppState extends ChangeNotifier {
  late StudentProfile _currentStudent;
  late List<Outpass> _outpasses;
  late List<MaintenanceComplaint> _complaints;
  late List<HostelNotice> _notices;
  late List<MealItem> _messMenu;
  int _currentNavIndex = 0;

  AppState() {
    _currentStudent = MockData.students.first;
    _outpasses = MockData.getInitialOutpasses();
    _complaints = MockData.getInitialComplaints();
    _notices = MockData.getNotices();
    _messMenu = MockData.getTodayMessMenu();
  }

  StudentProfile get currentStudent => _currentStudent;
  List<StudentProfile> get allStudents => MockData.students;
  RoomDetails get roomDetails => MockData.getRoomDetails(_currentStudent);
  List<Outpass> get outpasses => _outpasses;
  List<MaintenanceComplaint> get complaints => _complaints;
  List<HostelNotice> get notices => _notices;
  List<MealItem> get messMenu => _messMenu;
  int get currentNavIndex => _currentNavIndex;

  Outpass? get activeOutpass {
    try {
      return _outpasses.firstWhere(
        (o) => o.status == 'approved' && o.actualReturnDatetime == null,
      );
    } catch (_) {
      return null;
    }
  }

  void setNavIndex(int index) {
    _currentNavIndex = index;
    notifyListeners();
  }

  void switchStudent(StudentProfile student) {
    _currentStudent = student;
    notifyListeners();
  }

  void applyOutpass({
    required String leaveType,
    required String destination,
    required String reason,
    required DateTime outDatetime,
    required DateTime inDatetime,
  }) {
    final newId = 1000 + _outpasses.length + 1;
    final newOutpass = Outpass(
      id: newId,
      leaveType: leaveType,
      destination: destination,
      reason: reason,
      outDatetime: outDatetime,
      inDatetime: inDatetime,
      status: 'pending',
    );
    _outpasses.insert(0, newOutpass);
    notifyListeners();
  }

  void addComplaint({
    required String category,
    required String title,
    required String description,
    required String priority,
  }) {
    final newId = 2000 + _complaints.length + 1;
    final newComplaint = MaintenanceComplaint(
      id: newId,
      category: category,
      title: title,
      description: description,
      priority: priority,
      status: 'submitted',
      createdAt: DateTime.now(),
    );
    _complaints.insert(0, newComplaint);
    notifyListeners();
  }
}
