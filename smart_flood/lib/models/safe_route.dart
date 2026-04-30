class SafeRoute {
  final int id;
  final String name;
  final String? description;
  final double startLat;
  final double startLng;
  final double endLat;
  final double endLng;
  final int? createdBy;
  final String status;
  final DateTime createdAt;

  SafeRoute({
    required this.id,
    required this.name,
    this.description,
    required this.startLat,
    required this.startLng,
    required this.endLat,
    required this.endLng,
    this.createdBy,
    required this.status,
    required this.createdAt,
  });

  factory SafeRoute.fromJson(Map<String, dynamic> json) {
    return SafeRoute(
      id: json['id'],
      name: json['name'],
      description: json['description'],
      startLat: double.parse(json['start_lat'].toString()),
      startLng: double.parse(json['start_lng'].toString()),
      endLat: double.parse(json['end_lat'].toString()),
      endLng: double.parse(json['end_lng'].toString()),
      createdBy: json['created_by'],
      status: json['status'],
      createdAt: DateTime.parse(json['created_at']),
    );
  }
}
