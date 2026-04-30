import 'package:flutter/material.dart';
import 'package:smart_flood/services/api_service.dart';
import 'package:smart_flood/services/location_service.dart';

class EmergencyScreen extends StatefulWidget {
  @override
  _EmergencyScreenState createState() => _EmergencyScreenState();
}

class _EmergencyScreenState extends State<EmergencyScreen> {
  bool _isLoading = false;
  final _nameController = TextEditingController();

  Future<void> _reportEmergency() async {
    if (_nameController.text.isEmpty) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text('Please enter your name')));
      return;
    }

    setState(() => _isLoading = true);

    try {
      final location = await LocationService().getCurrentLocation();
      final response = await ApiService().reportEmergency(
        location.latitude,
        location.longitude,
        _nameController.text,
      );

      if (response['success']) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(
              'Emergency reported successfully! Help is on the way.',
            ),
          ),
        );
        _nameController.clear();
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Failed to report emergency: ${response['message']}'),
          ),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text('Error: $e')));
    } finally {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Emergency')),
      body: Padding(
        padding: EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Text(
              'In case of emergency, press the button below to alert authorities with your location.',
              style: TextStyle(fontSize: 16),
              textAlign: TextAlign.center,
            ),
            SizedBox(height: 20),
            TextField(
              controller: _nameController,
              decoration: InputDecoration(
                labelText: 'Your Name',
                border: OutlineInputBorder(),
              ),
            ),
            SizedBox(height: 20),
            _isLoading
                ? Center(child: CircularProgressIndicator())
                : ElevatedButton.icon(
                    icon: Icon(Icons.warning, size: 30),
                    label: Text(
                      'SEND EMERGENCY ALERT',
                      style: TextStyle(fontSize: 18),
                    ),
                    onPressed: _reportEmergency,
                    style: ElevatedButton.styleFrom(
                      padding: EdgeInsets.symmetric(
                        horizontal: 30,
                        vertical: 20,
                      ),
                      primary: Colors.red,
                    ),
                  ),
          ],
        ),
      ),
    );
  }
}
