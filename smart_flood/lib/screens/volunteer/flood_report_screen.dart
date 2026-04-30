import 'package:flutter/material.dart';
import 'package:smart_flood/models/flood_report.dart';
import 'package:smart_flood/services/api_service.dart';
import 'package:smart_flood/services/location_service.dart';

class FloodReportScreen extends StatefulWidget {
  @override
  _FloodReportScreenState createState() => _FloodReportScreenState();
}

class _FloodReportScreenState extends State<FloodReportScreen> {
  final _formKey = GlobalKey<FormState>();
  final _titleController = TextEditingController();
  final _descriptionController = TextEditingController();
  String _severity = 'medium';
  bool _isLoading = false;

  Future<void> _submitReport() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);

    try {
      final location = await LocationService().getCurrentLocation();

      final report = FloodReport(
        id: 0,
        userId: 0,
        title: _titleController.text,
        latitude: location.latitude,
        longitude: location.longitude,
        severity: _severity,
        description: _descriptionController.text,
        status: 'pending',
        createdAt: DateTime.now(),
      );

      final response = await ApiService().submitFloodReport(report);

      if (response['success']) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Flood report submitted successfully')),
        );
        _titleController.clear();
        _descriptionController.clear();
        setState(() => _severity = 'medium');
      } else {
        ScaffoldMessenger.of(
          context,
        ).showSnackBar(SnackBar(content: Text(response['message'])));
      }
    } catch (e) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text('Failed to submit report: $e')));
    } finally {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Flood Report')),
      body: Padding(
        padding: EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: ListView(
            children: [
              TextFormField(
                controller: _titleController,
                decoration: InputDecoration(labelText: 'Title'),
                validator: (value) {
                  if (value == null || value.isEmpty)
                    return 'Please enter a title';
                  return null;
                },
              ),
              SizedBox(height: 16),
              DropdownButtonFormField<String>(
                value: _severity,
                decoration: InputDecoration(labelText: 'Severity'),
                items: [
                  DropdownMenuItem(value: 'low', child: Text('Low')),
                  DropdownMenuItem(value: 'medium', child: Text('Medium')),
                  DropdownMenuItem(value: 'high', child: Text('High')),
                ],
                onChanged: (value) {
                  setState(() => _severity = value!);
                },
              ),
              SizedBox(height: 16),
              TextFormField(
                controller: _descriptionController,
                decoration: InputDecoration(labelText: 'Description'),
                maxLines: 4,
                validator: (value) {
                  if (value == null || value.isEmpty)
                    return 'Please enter a description';
                  return null;
                },
              ),
              SizedBox(height: 20),
              _isLoading
                  ? Center(child: CircularProgressIndicator())
                  : ElevatedButton(
                      onPressed: _submitReport,
                      child: Text('Submit Report'),
                      style: ElevatedButton.styleFrom(
                        minimumSize: Size(double.infinity, 50),
                      ),
                    ),
            ],
          ),
        ),
      ),
    );
  }
}
