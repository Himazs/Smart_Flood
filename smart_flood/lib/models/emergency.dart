class Emergency {
  final int id;
  final int userId;
  final String name;
  final double latitude;
  final double longitude;
  final String status;
  final DateTime createdAt;

  Emergency({
    required this.id,
    required this.userId,
    required this.name,
    required this.latitude,
    required this.longitude,
    required this.status,
    required this.createdAt,
  });

  factory Emergency.fromJson(Map<String, dynamic> json) {
    return Emergency(
      id: json['id'],
      userId: json['user_id'],
      name: json['name'],
      latitude: double.parse(json['latitude'].toString()),
      longitude: double.parse(json['longitude'].toString()),
      status: json['status'],
      createdAt: DateTime.parse(json['created_at']),
    );
  }
}
