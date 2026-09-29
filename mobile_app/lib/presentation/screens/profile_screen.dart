import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../core/constants/colors.dart';
import '../../state/app_state.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final state = context.watch<AppState>();
    final student = state.currentStudent;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Resident Profile'),
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          // Profile Header
          Card(
            child: Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                children: [
                  CircleAvatar(
                    radius: 36,
                    backgroundColor: AppColors.primary,
                    child: Text(
                      student.name
                          .split(' ')
                          .map((e) => e[0])
                          .take(2)
                          .join(),
                      style: const TextStyle(
                        fontSize: 22,
                        fontWeight: FontWeight.bold,
                        color: Colors.white,
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  Text(
                    student.name,
                    style: const TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  Text(
                    student.email,
                    style: const TextStyle(
                      fontSize: 12,
                      color: AppColors.textSecondary,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Container(
                    padding: const EdgeInsets.symmetric(
                        horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(
                      color: AppColors.surfaceVariant,
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Text(
                      'Roll No: ${student.rollNumber}',
                      style: const TextStyle(
                        fontSize: 12,
                        fontWeight: FontWeight.w700,
                        color: AppColors.primary,
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),

          // Academic Info Card
          _buildSectionCard(
            title: 'Academic Details',
            icon: Icons.school_rounded,
            items: [
              _buildPair('Department', student.department),
              _buildPair('Year of Study', 'Year ${student.yearOfStudy} (B.Tech)'),
              _buildPair('Blood Group', student.bloodGroup),
              _buildPair('Gender', student.gender.toUpperCase()),
            ],
          ),
          const SizedBox(height: 16),

          // Hostel Info Card
          _buildSectionCard(
            title: 'Hostel Assignment',
            icon: Icons.home_work_rounded,
            items: [
              _buildPair('Hostel', student.hostelName),
              _buildPair('Block / Wing', student.blockName),
              _buildPair('Room Number', student.roomNumber),
              _buildPair('Allocated Bed', student.bedIdentifier),
            ],
          ),
          const SizedBox(height: 16),

          // Guardian Details
          _buildSectionCard(
            title: 'Guardian & Emergency Contact',
            icon: Icons.contact_phone_rounded,
            items: [
              _buildPair('Parent / Guardian', student.parentName),
              _buildPair('Parent Phone', student.parentPhone),
              _buildPair('Emergency Contact', student.emergencyContact),
              _buildPair('Student Phone', student.phone),
            ],
          ),
          const SizedBox(height: 24),

          // Switch Demo Account
          SizedBox(
            width: double.infinity,
            child: OutlinedButton.icon(
              style: OutlinedButton.styleFrom(
                foregroundColor: AppColors.primary,
                side: const BorderSide(color: AppColors.primary),
                padding: const EdgeInsets.symmetric(vertical: 14),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
              ),
              icon: const Icon(Icons.people_alt_outlined),
              label: const Text('Switch Demo Student Account'),
              onPressed: () => _showStudentSwitcher(context, state),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSectionCard({
    required String title,
    required IconData icon,
    required List<Widget> items,
  }) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(icon, size: 20, color: AppColors.primary),
                const SizedBox(width: 8),
                Text(
                  title,
                  style: const TextStyle(
                    fontSize: 15,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
            const Divider(height: 20),
            ...items,
          ],
        ),
      ),
    );
  }

  Widget _buildPair(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 6),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label,
              style: const TextStyle(fontSize: 13, color: AppColors.textSecondary)),
          Text(
            value,
            style: const TextStyle(
              fontSize: 13,
              fontWeight: FontWeight.w600,
              color: AppColors.textPrimary,
            ),
          ),
        ],
      ),
    );
  }

  void _showStudentSwitcher(BuildContext context, AppState state) {
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (_) {
        return SafeArea(
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'Switch Resident Account',
                  style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                ),
                const SizedBox(height: 12),
                ...state.allStudents.map((s) {
                  final isSelected = s.id == state.currentStudent.id;
                  return ListTile(
                    leading: CircleAvatar(
                      backgroundColor: isSelected
                          ? AppColors.primary
                          : AppColors.surfaceVariant,
                      child: Text(
                        s.name[0],
                        style: TextStyle(
                          color: isSelected ? Colors.white : AppColors.primary,
                        ),
                      ),
                    ),
                    title: Text(s.name,
                        style: TextStyle(
                            fontWeight: isSelected
                                ? FontWeight.bold
                                : FontWeight.normal)),
                    subtitle: Text('${s.hostelName} • ${s.roomNumber}'),
                    trailing: isSelected
                        ? const Icon(Icons.check_circle_rounded,
                            color: AppColors.success)
                        : null,
                    onTap: () {
                      state.switchStudent(s);
                      Navigator.pop(context);
                    },
                  );
                }),
              ],
            ),
          ),
        );
      },
    );
  }
}
