import 'package:flutter/material.dart';
import 'package:smart_flood/models/safe_route.dart';

class RouteCard extends StatelessWidget {
  final SafeRoute route;

  const RouteCard({required this.route});

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: EdgeInsets.only(bottom: 16.0),
      child: Padding(
        padding: EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              route.name,
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            SizedBox(height: 8.0),
            if (route.description != null) Text(route.description!),
            SizedBox(height: 8.0),
            Text(
              'Status: ${route.status.toUpperCase()}',
              style: TextStyle(
                color: route.status == 'active' ? Colors.green : Colors.grey,
                fontWeight: FontWeight.bold,
              ),
            ),
            SizedBox(height: 8.0),
            Text(
              'Created: ${_formatDate(route.createdAt)}',
              style: TextStyle(fontSize: 12, color: Colors.grey),
            ),
          ],
        ),
      ),
    );
  }

  String _formatDate(DateTime date) {
    return '${date.day}/${date.month}/${date.year}';
  }
}
