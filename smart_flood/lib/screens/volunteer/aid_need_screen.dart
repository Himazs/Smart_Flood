import 'package:flutter/material.dart';
import 'package:smart_flood/services/api_service.dart';

class AidNeedScreen extends StatefulWidget {
  @override
  _AidNeedScreenState createState() => _AidNeedScreenState();
}

class _AidNeedScreenState extends State<AidNeedScreen> {
  final _formKey = GlobalKey<FormState>();
  final _titleController = TextEditingController();
  final _descriptionController = TextEditingController();
  final _contactController = TextEditingController();
  bool _isLoading = false;

  Future<void> _submitAidNeed() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);

    try {
      // This would typically call an API endpoint to submit aid needs
      // For now, we'll simulate a successful submission
      await Future.delayed(Duration(seconds: 2));

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Aid need submitted successfully')),
      );
      _titleController.clear();
      _descriptionController.clear();
      _contactController.clear();
    } catch (e) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text('Failed to submit aid need: $e')));
    } finally {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Aid Need')),
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
              TextFormField(
                controller: _contactController,
                decoration: InputDecoration(labelText: 'Contact Number'),
                keyboardType: TextInputType.phone,
                validator: (value) {
                  if (value == null || value.isEmpty)
                    return 'Please enter contact number';
                  return null;
                },
              ),
              SizedBox(height: 16),
              TextFormField(
                controller: _descriptionController,
                decoration: InputDecoration(
                  labelText: 'Description of Aid Needed',
                ),
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
                      onPressed: _submitAidNeed,
                      child: Text('Submit Aid Need'),
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
