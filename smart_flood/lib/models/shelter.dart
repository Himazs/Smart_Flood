class Shelter {
  final int id;
  final String name;
  final double latitude;
  final double longitude;
  final String address;
  final int capacity;
  final int currentOccupancy;
  final String inchargeName;
  final String inchargePhone;
  final DateTime createdAt;

  Shelter({
    required this.id,
    required this.name,
    required this.latitude,
    required this.longitude,
    required this.address,
    required this.capacity,
    required this.currentOccupancy,
    required this.inchargeName,
    required this.inchargePhone,
    required this.createdAt,
  });

  factory Shelter.fromJson(Map<String, dynamic> json) {
    return Shelter(
      id: json['id'],
      name: json['name'],
      latitude: double.parse(json['latitude'].toString()),
      longitude: double.parse(json['longitude'].toString()),
      address: json['address'],
      capacity: json['capacity'],
      currentOccupancy: json['current_occupancy'] ?? 0,
      inchargeName: json['incharge_name'],
      inchargePhone: json['incharge_phone'],
      createdAt: DateTime.parse(json['created_at']),
    );
  }

  double get occupancyPercentage {
    return capacity > 0 ? (currentOccupancy / capacity) * 100 : 0;
  }
}
