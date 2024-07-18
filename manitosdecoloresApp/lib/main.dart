import 'package:flutter/material.dart';
import 'ListaAlumnos.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Flutter Demo',
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: const Color.fromARGB(193, 61, 9, 152)),
        useMaterial3: true,
      ),
      home: const MyHomePage(title: 'Jardin'),
      debugShowCheckedModeBanner: false,
    );
  }
}

class MyHomePage extends StatefulWidget {
  const MyHomePage({super.key, required this.title});

  final String title;

  @override
  State<MyHomePage> createState() => _MyHomePageState();
}

class _MyHomePageState extends State<MyHomePage> {


  @override
  Widget build(BuildContext context) {
   
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
        title: Text(widget.title),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: <Widget>[
            const Text(
              'Jardin Manitos de colores',
            ),
            const SizedBox(height: 16), // Separación entre el texto y el botón
            
            FloatingActionButton(
              onPressed: () {
                 Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => const ListaAlumnos()),
          );
                // Aquí va la función que se ejecutará cuando se presione el botón
              },
              tooltip: 'Entrar',
              child: const Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [// Icono predeterminado
                   // Separación entre el icono y el texto
                  Text('Entrar'), // Texto personalizado
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}