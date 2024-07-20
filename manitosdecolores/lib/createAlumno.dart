import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:manitosdecolores/listaAlumnosPage.dart';

class CreateAlumno extends StatefulWidget {
  const CreateAlumno({super.key});

  @override
  _CreateAlumnoState createState() => _CreateAlumnoState();
}

class _CreateAlumnoState extends State<CreateAlumno> {
  final _formKey = GlobalKey<FormState>();
  final Map<String, dynamic> _alumno = {};

  Future<void> createAlumno() async {
    if (_formKey.currentState!.validate()) {
      _formKey.currentState!.save();
      try {
        final response = await http.post(
          Uri.parse('http://10.0.2.2/manitosdecolores/APIapp/createAlumno.php'),
          headers: {'Content-Type': 'application/json'},
          body: json.encode(_alumno),
        );

        if (response.statusCode == 200) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Alumno creado correctamente')),
          );

          // Redirigir a otra página después de la creación
          Navigator.pushReplacementNamed(context, '/ListaAlumnos');
        } else {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Error al crear el alumno: ${response.statusCode}')),
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
        title: const Text('Crear Alumno'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: ListView(
            children: <Widget>[
              TextFormField(
                decoration: const InputDecoration(labelText: 'Nombre'),
                onSaved: (value) {
                  _alumno['nombre'] = value!;
                },
                validator: (value) {
                  if (value!.isEmpty) {
                    return 'Por favor ingrese el nombre';
                  }
                  return null;
                },
              ),
              TextFormField(
                decoration: const InputDecoration(labelText: 'Apellido Paterno'),
                onSaved: (value) {
                  _alumno['apellido_paterno'] = value!;
                },
                validator: (value) {
                  if (value!.isEmpty) {
                    return 'Por favor ingrese el apellido paterno';
                  }
                  return null;
                },
              ),
              TextFormField(
                decoration: const InputDecoration(labelText: 'Apellido Materno'),
                onSaved: (value) {
                  _alumno['apellido_materno'] = value!;
                },
                validator: (value) {
                  if (value!.isEmpty) {
                    return 'Por favor ingrese el apellido materno';
                  }
                  return null;
                },
              ),
              TextFormField(
                decoration: const InputDecoration(labelText: 'Edad'),
                keyboardType: TextInputType.number,
                onSaved: (value) {
                  _alumno['edad'] = int.parse(value!);
                },
                validator: (value) {
                  if (value!.isEmpty) {
                    return 'Por favor ingrese la edad';
                  }
                  return null;
                },
              ),
              TextFormField(
                decoration: const InputDecoration(labelText: 'CI'),
                keyboardType: TextInputType.number,
                onSaved: (value) {
                  _alumno['CI'] = double.parse(value!);
                },
                validator: (value) {
                  if (value!.isEmpty) {
                    return 'Por favor ingrese el CI';
                  }
                  return null;
                },
              ),
              TextFormField(
                decoration: const InputDecoration(labelText: 'Peso'),
                keyboardType: TextInputType.number,
                onSaved: (value) {
                  _alumno['peso'] = double.parse(value!);
                },
                validator: (value) {
                  if (value!.isEmpty) {
                    return 'Por favor ingrese el peso';
                  }
                  return null;
                },
              ),
              // Campos para las relaciones
              _buildRelatedField('nombre_padre', 'Padre'),
              _buildRelatedField('nombre_madre', 'Madre'),
              _buildRelatedField('nombre_apoderado', 'Apoderado'),
              ElevatedButton(
                onPressed: createAlumno,
                style: const ButtonStyle(
                  backgroundColor: WidgetStatePropertyAll(Color(0xFF00B8D4)),
                  foregroundColor: WidgetStatePropertyAll(Colors.white),
                ),
                child: const Text('Crear'),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildRelatedField(String field, String label) {
    return TextFormField(
      decoration: InputDecoration(labelText: 'Nombre $label'),
      readOnly: true,
    );
  }
}
