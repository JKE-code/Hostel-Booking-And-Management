import 'package:flutter_test/flutter_test.dart';
import 'package:provider/provider.dart';
import 'package:hitam_hostel_app/main.dart';
import 'package:hitam_hostel_app/state/app_state.dart';

void main() {
  testWidgets('HITAM Hostel Resident Portal smoke test', (WidgetTester tester) async {
    await tester.pumpWidget(
      ChangeNotifierProvider(
        create: (_) => AppState(),
        child: const HitamHostelApp(),
      ),
    );

    await tester.pumpAndSettle();

    // Verify Welcome header and Quick Actions are displayed
    expect(find.textContaining('Welcome'), findsOneWidget);
    expect(find.text('Quick Actions'), findsOneWidget);

    // Verify bottom navigation tabs exist
    expect(find.text('Home'), findsOneWidget);
    expect(find.text('Outpass'), findsWidgets);
    expect(find.text('Complaints'), findsWidgets);
    expect(find.text('Notices'), findsOneWidget);
    expect(find.text('Profile'), findsOneWidget);
  });
}
