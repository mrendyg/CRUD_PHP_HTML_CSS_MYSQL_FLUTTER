import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;

class EditAlumno extends StatefulWidget {
  final int id;

  const EditAlumno({super.key, required this.id});

  @override
  _EditAlumnoState createState() => _EditAlumnoState();
}

class _EditAlumnoState extends State<EditAlumno> {
  final _formKey = GlobalKey<FormState>();
  Map<String, dynamic> alumno = {};

  @override
  void initState() {
    super.initState();
    fetchAlumno();
  }

  Future<void> fetchAlumno() async {
    try {
      final response = await http.get(Uri.parse('http://10.0.2.2/manitosdecolores/APIapp/idGetAlumno.php?id=${widget.id}'));
      if (response.statusCode == 200) {
        setState(() {
          alumno = json.decode(response.body);
        });
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error al obtener el alumno: ${response.statusCode}')),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('No es posible conectar')),
      );
    }
  }

  Future<void> updateAlumno() async {
    if (_formKey.currentState!.validate()) {
      _formKey.currentState!.save();
      try {
        final response = await http.post(
          Uri.parse('http://10.0.2.2/manitosdecolores/APIapp/updateAlumno.php'),
          headers: {'Content-Type': 'application/json'},
          body: json.encode(alumno),
        );

        if (response.statusCode == 200) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Alumno actualizado correctamente')),
          );
        } else {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Error al actualizar el alumno: ${response.statusCode}')),
          );
        }
      } catch (e) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('No es posible conectar')),
        );
      }
    }
  }

    Future<void> deleteAlumno() async {
    if (_formKey.currentState!.validate()) {
      _formKey.currentState!.save();
      try {
        final response = await http.delete(
          Uri.parse('http://10.0.2.2/manitosdecolores/APIapp/deleteAlumno.php?id=${widget.id}'),
          headers: {'Content-Type': 'application/json'},
          body: json.encode(alumno),
        );

        if (response.statusCode == 204) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Alumno Borrado')),
          );
        } else {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Alumno borrado')),
          );
        }
      } catch (e) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('No es posible conectar')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Editar Alumno'),
      ),
      body: alumno.isEmpty
          ? const Center(child: CircularProgressIndicator())
          : Padding(
              padding: const EdgeInsets.all(16.0),
              child: Form(
                key: _formKey,
                child: ListView(
                  children: <Widget>[
                    TextFormField(
                      initialValue: alumno['nombre'],
                      decoration: const InputDecoration(labelText: 'Nombre'),
                      onSaved: (value) {
                        alumno['nombre'] = value!;
                      },
                      validator: (value) {
                        if (value!.isEmpty) {
                          return 'Por favor ingrese el nombre';
                        }
                        return null;
                      },
                    ),
                    TextFormField(
                      initialValue: alumno['apellido_paterno'],
                      decoration: const InputDecoration(labelText: 'Apellido Paterno'),
                      onSaved: (value) {
                        alumno['apellido_paterno'] = value!;
                      },
                      validator: (value) {
                        if (value!.isEmpty) {
                          return 'Por favor ingrese el apellido paterno';
                        }
                        return null;
                      },
                    ),
                    TextFormField(
                      initialValue: alumno['apellido_materno'],
                      decoration: const InputDecoration(labelText: 'Apellido Materno'),
                      onSaved: (value) {
                        alumno['apellido_materno'] = value!;
                      },
                      validator: (value) {
                        if (value!.isEmpty) {
                          return 'Por favor ingrese el apellido materno';
                        }
                        return null;
                      },
                    ),
                    TextFormField(
                      initialValue: alumno['edad'].toString(),
                      decoration: const InputDecoration(labelText: 'Edad'),
                      keyboardType: TextInputType.number,
                      onSaved: (value) {
                        alumno['edad'] = int.parse(value!);
                      },
                      validator: (value) {
                        if (value!.isEmpty) {
                          return 'Por favor ingrese la edad';
                        }
                        return null;
                      },
                    ),
                    TextFormField(
                      initialValue: alumno['CI'].toString(),
                      decoration: const InputDecoration(labelText: 'CI'),
                      keyboardType: TextInputType.number,
                      onSaved: (value) {
                        alumno['CI'] = double.parse(value!);
                      },
                      validator: (value) {
                        if (value!.isEmpty) {
                          return 'Por favor ingrese el CI';
                        }
                        return null;
                      },
                    ),
                    TextFormField(
                      initialValue: alumno['peso'].toString(),
                      decoration: const InputDecoration(labelText: 'Peso'),
                      keyboardType: TextInputType.number,
                      onSaved: (value) {
                        alumno['peso'] = double.parse(value!);
                      },
                      validator: (value) {
                        if (value!.isEmpty) {
                          return 'Por favor ingrese el peso';
                        }
                        return null;
                      },
                    ),
                  
                    // Campos de relación
                    _buildRelatedField('nombre_padre', 'Padre', alumno['id_padre']),
                    _buildRelatedField('nombre_madre', 'Madre', alumno['madre']),
                    _buildRelatedField('nombre_apoderado', 'Apoderado', alumno['apoderado']),
                    // Botón de actualización
                    ElevatedButton(
                      onPressed: updateAlumno,
                      style: const ButtonStyle(
                            backgroundColor: WidgetStatePropertyAll( Color(0xFF00B8D4)),
                            foregroundColor: WidgetStatePropertyAll(Colors.white),
                          ),
                      child: const Text('Actualizar'),
                    ),
                    ElevatedButton(
                      onPressed: deleteAlumno,

                      style: const ButtonStyle(
                            backgroundColor: WidgetStatePropertyAll( Color.fromARGB(255, 255, 0, 0)),
                            foregroundColor: WidgetStatePropertyAll(Colors.white),
                          ),
                      child: const Text('Borrar'),
                    ),
                  ],
                ),
              ),
            ),
    );
  }

  Widget _buildRelatedField(String field, String label, Map<String, dynamic>? relatedData) {
    return TextFormField(
      initialValue: relatedData != null
          ? '${relatedData['nombres']} ${relatedData['apellido_paterno']} ${relatedData['apellido_materno']}'
          : '',
      decoration: InputDecoration(labelText: 'Nombre $label'),
      readOnly: true,
    );
  }
}
