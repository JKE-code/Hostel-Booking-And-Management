import 'package:flutter/material.dart';
import '../../core/constants/colors.dart';

class StatusBadge extends StatelessWidget {
  final String status;

  const StatusBadge({super.key, required this.status});

  @override
  Widget build(BuildContext context) {
    Color bg;
    Color text;
    String label = status.toUpperCase();

    switch (status.toLowerCase()) {
      case 'approved':
      case 'resolved':
        bg = AppColors.successBg;
        text = AppColors.success;
        break;
      case 'pending':
      case 'submitted':
        bg = AppColors.warningBg;
        text = AppColors.warning;
        break;
      case 'in_progress':
        bg = AppColors.infoBg;
        text = AppColors.info;
        label = 'IN PROGRESS';
        break;
      case 'rejected':
      case 'overdue':
        bg = AppColors.errorBg;
        text = AppColors.error;
        break;
      case 'returned':
      case 'closed':
        bg = const Color(0xFFF1F5F9);
        text = const Color(0xFF64748B);
        break;
      default:
        bg = Colors.grey.shade100;
        text = Colors.grey.shade700;
    }

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(
        color: bg,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: text.withAlpha(50)),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: text,
          fontSize: 11,
          fontWeight: FontWeight.w700,
          letterSpacing: 0.5,
        ),
      ),
    );
  }
}
