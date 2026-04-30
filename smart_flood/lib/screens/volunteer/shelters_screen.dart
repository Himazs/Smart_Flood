import 'package:flutter/material.dart';
import 'package:smart_flood/models/shelter.dart';
import 'package:smart_flood/services/api_service.dart';
import 'package:smart_flood/widgets/shelter_card.dart';

class SheltersScreen extends StatefulWidget {
  @override
  _SheltersScreenState createState() => _SheltersScreenState();
}

class _SheltersScreenState extends State<SheltersScreen> {
  List<Shelter> _shelters = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadShelters();
  }

  Future<void> _loadShelters() async {
    try {
      final shelters = await ApiService().getShelters();
      setState(() {
        _shelters = shelters;
        _isLoading = false;
      });
    } catch (e) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text('Failed to load shelters: $e')));
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Shelters')),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : _shelters.isEmpty
          ? Center(child: Text('No shelters available'))
          : ListView.builder(
              padding: EdgeInsets.all(16.0),
              itemCount: _shelters.length,
              itemBuilder: (context, index) {
                return ShelterCard(shelter: _shelters[index]);
              },
            ),
    );
  }
}
