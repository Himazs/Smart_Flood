import 'package:flutter/material.dart';
import 'package:smart_flood/models/shelter.dart';

class ShelterCard extends StatelessWidget {
  final Shelter shelter;

  const ShelterCard({required this.shelter});

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
              shelter.name,
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            SizedBox(height: 8.0),
            Text('Address: ${shelter.address}'),
            SizedBox(height: 4.0),
            Text(
              'Incharge: ${shelter.inchargeName} (${shelter.inchargePhone})',
            ),
            SizedBox(height: 4.0),
            Text('Capacity: ${shelter.currentOccupancy}/${shelter.capacity}'),
            SizedBox(height: 8.0),
            LinearProgressIndicator(
              value: shelter.occupancyPercentage / 100,
              backgroundColor: Colors.grey[300],
              valueColor: AlwaysStoppedAnimation<Color>(
                shelter.occupancyPercentage > 90
                    ? Colors.red
                    : shelter.occupancyPercentage > 70
                    ? Colors.orange
                    : Colors.green,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
