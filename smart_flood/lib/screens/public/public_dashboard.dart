import 'package:flutter/material.dart';
import 'package:smart_flood/screens/public/emergency_screen.dart';
import 'package:smart_flood/screens/public/shelters_screen.dart';
import 'package:smart_flood/screens/public/weather_screen.dart';
import 'package:smart_flood/screens/public/safe_routes_screen.dart';
import 'package:smart_flood/screens/public/government_news_screen.dart';
import 'package:smart_flood/screens/public/safety_tips_screen.dart';
import 'package:smart_flood/screens/public/app_instructions_screen.dart';

class PublicDashboard extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Public Dashboard')),
      body: GridView.count(
        crossAxisCount: 2,
        padding: EdgeInsets.all(16.0),
        children: [
          _buildFeatureCard(
            context,
            'Emergency',
            Icons.warning,
            Colors.red,
            () => Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => EmergencyScreen()),
            ),
          ),
          _buildFeatureCard(
            context,
            'Shelters',
            Icons.home,
            Colors.blue,
            () => Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => SheltersScreen()),
            ),
          ),
          _buildFeatureCard(
            context,
            'Weather',
            Icons.cloud,
            Colors.orange,
            () => Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => WeatherScreen()),
            ),
          ),
          _buildFeatureCard(
            context,
            'Safe Routes',
            Icons.directions,
            Colors.green,
            () => Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => SafeRoutesScreen()),
            ),
          ),
          _buildFeatureCard(
            context,
            'Gov News',
            Icons.newspaper,
            Colors.purple,
            () => Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => GovernmentNewsScreen()),
            ),
          ),
          _buildFeatureCard(
            context,
            'Safety Tips',
            Icons.security,
            Colors.teal,
            () => Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => SafetyTipsScreen()),
            ),
          ),
          _buildFeatureCard(
            context,
            'How to Use',
            Icons.help,
            Colors.brown,
            () => Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => AppInstructionsScreen()),
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
