import 'package:flutter/material.dart';
import 'package:smart_flood/models/safe_route.dart';
import 'package:smart_flood/services/api_service.dart';
import 'package:smart_flood/widgets/route_card.dart';

class SafeRoutesScreen extends StatefulWidget {
  @override
  _SafeRoutesScreenState createState() => _SafeRoutesScreenState();
}

class _SafeRoutesScreenState extends State<SafeRoutesScreen> {
  List<SafeRoute> _routes = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadSafeRoutes();
  }

  Future<void> _loadSafeRoutes() async {
    try {
      final routes = await ApiService().getSafeRoutes();
      setState(() {
        _routes = routes;
        _isLoading = false;
      });
    } catch (e) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text('Failed to load safe routes: $e')));
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Safe Routes')),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : _routes.isEmpty
          ? Center(child: Text('No safe routes available'))
          : ListView.builder(
              padding: EdgeInsets.all(16.0),
              itemCount: _routes.length,
              itemBuilder: (context, index) {
                return RouteCard(route: _routes[index]);
              },
            ),
    );
  }
}
