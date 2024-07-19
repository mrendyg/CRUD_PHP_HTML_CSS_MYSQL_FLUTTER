import 'package:flutter/material.dart';
import 'package:manitosdecolores/listaAlumnosPage.dart';
import 'main.dart';


class LoginPage extends StatelessWidget {
  const LoginPage({super.key});

  @override
  Widget build(BuildContext context) {
    String passwordLogin = '';

    
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'Login',
      home: Scaffold(
        body: SingleChildScrollView( // Agregar SingleChildScrollView
          child: Padding(
            padding: const EdgeInsets.fromLTRB(20.0, 60.0, 20.0, 10.0),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                const Column(
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    Text(
                      'MANITOS DE COLORES',
                      style: TextStyle(
                        color: Color.fromARGB(255, 0, 60, 255),
                        fontSize: 25,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    Text(
                      'Jardin infantil',
                      style: TextStyle(
                        fontSize: 15,
                        color: Color.fromARGB(255, 0, 255, 149),
                      ),
                    ),
                  ],
                ),
                Container(
                  alignment: Alignment.center,
                  child: Image.asset(
                    'assets/img/manitos.png',
                    fit: BoxFit.fill,
                  ),
                ),
                const SizedBox(height: 20), // Agregar espacio entre la imagen y los campos de texto
                Column(
                  children: [
                    const TextField(
                      decoration: InputDecoration(
                        labelText: 'Usuario',
                        labelStyle: TextStyle(
                          fontSize: 18,
                        ),
                      ),
                    ),
                    
                    TextField(
                      obscureText: true,
                      onChanged: (value){
                        passwordLogin = value;
                      },
                      decoration: const InputDecoration(
                        labelText: 'Contraseña',
                        labelStyle: TextStyle(
                          fontSize: 18,
                        ),
                      ),
                    ),
                    const SizedBox(height: 20), // Agregar espacio entre los campos de texto y los botones
                    
                    const SizedBox(height: 20),
                    
                  ],
                  
                ),
                Column(
                  children: [
                    ElevatedButton(onPressed:() {
                      Navigator.push(context, MaterialPageRoute(builder: (context) => const ListaAlumnos()));
                    },
                    style: ElevatedButton.styleFrom(
                            backgroundColor: Color.fromARGB(255, 0, 166, 211),
                            foregroundColor: Colors.white,
                            padding: const EdgeInsets.symmetric(horizontal: 100, vertical: 20),
                            minimumSize: const Size(double.infinity, 50),
                          ),

                     child: const Text(
                            'Entrar',
                            textAlign: TextAlign.justify,
                            ),)
                  ],
                )
                
              ],
              
            ),
          ),
        ),
      ),
    );
  }
}