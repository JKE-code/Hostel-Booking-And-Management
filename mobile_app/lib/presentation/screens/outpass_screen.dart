import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../../core/constants/colors.dart';
import '../../state/app_state.dart';
import '../widgets/status_badge.dart';
import 'qr_view_screen.dart';

class OutpassScreen extends StatelessWidget {
  const OutpassScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final state = context.watch<AppState>();
    final student = state.currentStudent;
    final outpasses = state.outpasses;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Hostel Outpasses & Leaves'),
      ),
      body: outpasses.isEmpty
          ? const Center(child: Text('No outpasses requested yet.'))
          : ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: outpasses.length,
              itemBuilder: (context, index) {
                final o = outpasses[index];
                final df = DateFormat('dd MMM, hh:mm a');

                return Card(
                  margin: const EdgeInsets.only(bottom: 12),
                  child: Padding(
                    padding: const EdgeInsets.all(16),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Text(
                              o.formattedType,
                              style: const TextStyle(
                                fontSize: 15,
                                fontWeight: FontWeight.bold,
                                color: AppColors.primary,
                              ),
                            ),
                            StatusBadge(status: o.status),
                          ],
                        ),
                        const SizedBox(height: 8),
                        Text(
                          'Destination: ${o.destination}',
                          style: const TextStyle(
                            fontSize: 14,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          o.reason,
                          style: const TextStyle(
                            fontSize: 12,
                            color: AppColors.textSecondary,
                          ),
                        ),
                        const Divider(height: 20),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                const Text(
                                  'Exit Time',
                                  style: TextStyle(
                                      fontSize: 10, color: AppColors.textMuted),
                                ),
                                Text(
                                  df.format(o.outDatetime),
                                  style: const TextStyle(
                                      fontSize: 12, fontWeight: FontWeight.w600),
                                ),
                              ],
                            ),
                            Column(
                              crossAxisAlignment: CrossAxisAlignment.end,
                              children: [
                                const Text(
                                  'Expected Return',
                                  style: TextStyle(
                                      fontSize: 10, color: AppColors.textMuted),
                                ),
                                Text(
                                  df.format(o.inDatetime),
                                  style: const TextStyle(
                                      fontSize: 12, fontWeight: FontWeight.w600),
                                ),
                              ],
                            ),
                          ],
                        ),
                        if (o.isApproved) ...[
                          const SizedBox(height: 12),
                          SizedBox(
                            width: double.infinity,
                            child: OutlinedButton.icon(
                              style: OutlinedButton.styleFrom(
                                foregroundColor: AppColors.primary,
                                side: const BorderSide(color: AppColors.primary),
                                shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(10),
                                ),
                              ),
                              icon: const Icon(Icons.qr_code_rounded, size: 18),
                              label: const Text('View Gate Security QR'),
                              onPressed: () {
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                    builder: (_) => GatePassQrScreen(
                                      outpass: o,
                                      student: student,
                                    ),
                                  ),
                                );
                              },
                            ),
                          ),
                        ],
                      ],
                    ),
                  ),
                );
              },
            ),
      floatingActionButton: FloatingActionButton.extended(
        backgroundColor: AppColors.accentDark,
        foregroundColor: Colors.white,
        icon: const Icon(Icons.add_rounded),
        label: const Text('Apply Outpass'),
        onPressed: () => _showApplyModal(context, state),
      ),
    );
  }

  void _showApplyModal(BuildContext context, AppState state) {
    final formKey = GlobalKey<FormState>();
    String leaveType = 'day_pass';
    String destination = '';
    String reason = '';

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      builder: (ctx) {
        return Padding(
          padding: EdgeInsets.only(
            top: 20,
            left: 20,
            right: 20,
            bottom: MediaQuery.of(ctx).viewInsets.bottom + 20,
          ),
          child: Form(
            key: formKey,
            child: SingleChildScrollView(
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Apply for Gate Pass / Leave',
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 16),
                  DropdownButtonFormField<String>(
                    initialValue: leaveType,
                    decoration: const InputDecoration(labelText: 'Leave Type'),
                    items: const [
                      DropdownMenuItem(
                          value: 'day_pass', child: Text('Day Pass (City Visit)')),
                      DropdownMenuItem(
                          value: 'weekend', child: Text('Weekend Leave (Home)')),
                      DropdownMenuItem(
                          value: 'emergency', child: Text('Emergency')),
                      DropdownMenuItem(
                          value: 'medical', child: Text('Medical / Clinic')),
                      DropdownMenuItem(
                          value: 'academic', child: Text('Academic / Project')),
                    ],
                    onChanged: (val) {
                      if (val != null) leaveType = val;
                    },
                  ),
                  const SizedBox(height: 12),
                  TextFormField(
                    decoration: const InputDecoration(
                      labelText: 'Destination',
                      hintText: 'e.g. Hyderabad City or Home Address',
                    ),
                    validator: (val) =>
                        val == null || val.trim().isEmpty ? 'Required' : null,
                    onSaved: (val) => destination = val ?? '',
                  ),
                  const SizedBox(height: 12),
                  TextFormField(
                    decoration: const InputDecoration(
                      labelText: 'Purpose / Reason',
                      hintText: 'Brief explanation for warden verification',
                    ),
                    maxLines: 2,
                    validator: (val) =>
                        val == null || val.trim().isEmpty ? 'Required' : null,
                    onSaved: (val) => reason = val ?? '',
                  ),
                  const SizedBox(height: 20),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: () {
                        if (formKey.currentState!.validate()) {
                          formKey.currentState!.save();
                          final now = DateTime.now();
                          state.applyOutpass(
                            leaveType: leaveType,
                            destination: destination,
                            reason: reason,
                            outDatetime: now.add(const Duration(hours: 1)),
                            inDatetime: now.add(const Duration(hours: 6)),
                          );
                          Navigator.pop(ctx);
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(
                              content: Text('Outpass submitted for Warden review!'),
                              backgroundColor: AppColors.success,
                            ),
                          );
                        }
                      },
                      child: const Text('Submit Application'),
                    ),
                  ),
                ],
              ),
            ),
          ),
        );
      },
    );
  }
}
