import 'package:flutter/material.dart';
import 'package:smart_flood/screens/volunteer/profile_screen.dart';
import 'package:smart_flood/screens/volunteer/flood_report_screen.dart';
import 'package:smart_flood/screens/volunteer/donation_screen.dart';
import 'package:smart_flood/screens/volunteer/rescue_teams_screen.dart';
import 'package:smart_flood/screens/volunteer/shelters_screen.dart';
import 'package:smart_flood/screens/volunteer/geo_location_screen.dart';
import 'package:smart_flood/screens/volunteer/aid_need_screen.dart';
import 'package:smart_flood/screens/volunteer/government_messages_screen.dart';

class VolunteerDashboard extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Volunteer Dashboard')),
      body: GridView.count(
        crossAxisCount: 2,
        padding: EdgeInsets.all(16.0),
        children: [
          _buildFeatureCard(
            context,
            'Profile',
            Icons.person,
            Colors.blue,
            () => Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => ProfileScreen()),
            ),
          ),
          _buildFeatureCard(
            context,
            'Flood Report',
            Icons.report,
            Colors.orange,
            () => Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => FloodReportScreen()),
            ),
          ),
          _buildFeatureCard(
            context,
            'Donation',
            Icons.volunteer_activism,
            Colors.green,
            () => Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => DonationScreen()),
            ),
          ),
          _buildFeatureCard(
            context,
            'Rescue Teams',
            Icons.group,
            Colors.purple,
            () => Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => RescueTeamsScreen()),
            ),
          ),
          _buildFeatureCard(
            context,
            'Shelters',
            Icons.home,
            Colors.teal,
            () => Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => SheltersScreen()),
            ),
          ),
          _buildFeatureCard(
            context,
            'Geo Location',
            Icons.location_on,
            Colors.red,
            () => Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => GeoLocationScreen()),
            ),
          ),
          _buildFeatureCard(
            context,
            'Aid Need',
            Icons.help_outline,
            Colors.amber,
            () => Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => AidNeedScreen()),
            ),
          ),
          _buildFeatureCard(
            context,
            'Gov Messages',
            Icons.message,
            Colors.indigo,
            () => Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => GovernmentMessagesScreen(),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildFeatureCard(
    BuildContext context,
    String title,
    IconData icon,
    Color color,
    VoidCallback onTap,
  ) {
    return Card(
      elevation: 4,
      child: InkWell(
        onTap: onTap,
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, size: 40, color: color),
            SizedBox(height: 10),
            Text(
              title,
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
          ],
        ),
      ),
    );
  }
}
