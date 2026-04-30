class FloodReport {
  final int id;
  final int? userId;
  final String title;
  final double latitude;
  final double longitude;
  final String severity;
  final String description;
  final String status;
  final DateTime createdAt;

  FloodReport({
    required this.id,
    this.userId,
    required this.title,
    required this.latitude,
    required this.longitude,
    required this.severity,
    required this.description,
    required this.status,
    required this.createdAt,
  });

  factory FloodReport.fromJson(Map<String, dynamic> json) {
    return FloodReport(
      id: json['id'],
      userId: json['user_id'],
      title: json['title'],
      latitude: double.parse(json['latitude'].toString()),
      longitude: double.parse(json['longitude'].toString()),
      severity: json['severity'],
      description: json['description'],
      status: json['status'],
      createdAt: DateTime.parse(json['created_at']),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'title': title,
      'latitude': latitude,
      'longitude': longitude,
      'severity': severity,
      'description': description,
    };
  }
}
