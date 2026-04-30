import 'package:flutter/material.dart';
import 'package:smart_flood/models/emergency.dart';
import 'package:smart_flood/services/api_service.dart';
import 'package:smart_flood/services/location_service.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';

class GeoLocationScreen extends StatefulWidget {
  @override
  _GeoLocationScreenState createState() => _GeoLocationScreenState();
}

class _GeoLocationScreenState extends State<GeoLocationScreen> {
  List<Emergency> _emergencies = [];
  bool _isLoading = true;
  GoogleMapController? _mapController;
  final Set<Marker> _markers = {};

  @override
  void initState() {
    super.initState();
    _loadEmergencies();
  }

  Future<void> _loadEmergencies() async {
    try {
      final emergencies = await ApiService().getActiveEmergencies();
      setState(() {
        _emergencies = emergencies;
        _isLoading = false;
        _updateMarkers();
      });
    } catch (e) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text('Failed to load emergencies: $e')));
      setState(() => _isLoading = false);
    }
  }

  void _updateMarkers() {
    _markers.clear();
    for (var emergency in _emergencies) {
      _markers.add(
        Marker(
          markerId: MarkerId(emergency.id.toString()),
          position: LatLng(emergency.latitude, emergency.longitude),
          infoWindow: InfoWindow(
            title: emergency.name,
            snippet: 'Emergency reported',
          ),
          icon: BitmapDescriptor.defaultMarkerWithHue(BitmapDescriptor.hueRed),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Geo Location')),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : Column(
              children: [
                Expanded(
                  child: GoogleMap(
                    initialCameraPosition: CameraPosition(
                      target: LatLng(7.8731, 80.7718), // Sri Lanka coordinates
                      zoom: 7.0,
                    ),
                    markers: _markers,
                    onMapCreated: (controller) {
                      setState(() {
                        _mapController = controller;
                      });
                    },
                  ),
                ),
                SizedBox(height: 16),
                Expanded(
                  flex: 0,
                  child: Padding(
                    padding: EdgeInsets.all(16.0),
                    child: Text(
                      'Active Emergencies: ${_emergencies.length}',
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                ),
              ],
            ),
    );
  }
}
