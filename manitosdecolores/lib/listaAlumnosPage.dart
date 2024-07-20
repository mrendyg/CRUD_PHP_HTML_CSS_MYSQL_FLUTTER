import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'loginPage.dart';


class ListaAlumnos extends StatelessWidget {
  const ListaAlumnos({super.key});

  Future<List<Map<String, dynamic>>> fetchAlumnos() async {
    final response = await http.get(Uri.parse('http://10.0.2.2/manitosdecolores/APIapp/getAlumnos.php'));


    if (response.statusCode == 200) {
      List<dynamic> data = json.decode(response.body);
      return data.cast<Map<String, dynamic>>();
    } else {
      throw Exception('Error al cargar datos de Alumnos');
    }
  }

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'Manitos de colores',
      home: Scaffold(
        appBar: AppBar(
          title: const Text('Lista de Alumnos'),
        ),
        body: FutureBuilder<List<Map<String, dynamic>>>(
          future: fetchAlumnos(),
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return const Center(child: CircularProgressIndicator());
            } else if (snapshot.hasError) {
              return Center(child: Text('Error: ${snapshot.error}'));
            } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
              return const Center(child: Text('No hay datos disponibles'));
            } else {
              return ListView.builder(
                itemCount: snapshot.data!.length,
                itemBuilder: (context, index) {
                  var alumno = snapshot.data![index];
                  return ListTile(
                    title: Text('${alumno['nombre']} ${alumno['apellido_paterno']} ${alumno['apellido_materno']}'),
                    trailing: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        ElevatedButton(
                          onPressed: () {
                            Navigator.push(
                              context, 
                              MaterialPageRoute(builder: (context) => const LoginPage())
                            );
                          },
                          style: const ButtonStyle(
                            backgroundColor: WidgetStatePropertyAll(Color(0xFF00B8D4)),
                            foregroundColor: WidgetStatePropertyAll(Colors.white),
                          ),
                          child: const Text('Ver'),
                        ),
                        const SizedBox(width: 8),
                        
                      ],
                    ),
                  );
                },
              );
            }
          },
        ),
      ),
    );
  }
}
