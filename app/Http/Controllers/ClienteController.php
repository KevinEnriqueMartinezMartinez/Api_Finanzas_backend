<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Gasto;
use App\Models\Ingreso;
use App\Models\Meses;
use App\Models\Categoria;

class ClienteController extends Controller
{
    
    public function index()
    {
        return Cliente::all();
    }


    
    public function store(Request $request)
    {
        return Cliente::create($request->all());
    }

    
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->nombre = $request->nombre;
        $cliente->apellido = $request->apellido;
        $cliente->nacimiento = $request->nacimiento;
        $cliente->edad = $request->edad;
        $cliente->update();

        return $cliente;
    }

    
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();
    }

   public function getClientes()
{
    // Utilizando select para obtener solo nombre y apellido
    $clientes = Cliente::select('nombre', 'apellido')->get();
    
    return response()->json($clientes);
}
// ClienteController.php

public function createGasto(Request $request)
{
    $validated = $request->validate([
        'cliente_id' => 'required|exists:clientes,id',
        'monto' => 'required|numeric|min:0',
        'descripcion' => 'required|string|max:255',
        'fecha' => 'required|date',
        'mes_id' => 'required|integer|min:1',
        'categoria_id' => 'required|exists:categorias,id',

    ]);

    $gasto = Gasto::create($validated);

    return response()->json([
        'message' => 'Gasto registrado correctamente',
        'gasto' => $gasto,
    ], 201);
}

public function createIngreso(Request $request)
{
    $validated = $request->validate([
        'cliente_id' => 'required|exists:clientes,id',
        'monto' => 'required|numeric|min:0',
        'descripcion' => 'required|string|max:255',
        'fecha' => 'required|date',
        'mes_id' => 'required|integer|min:1',
    ]);

    $gasto = Ingreso::create($validated);

    return response()->json([
        'message' => 'Ingreso registrado correctamente',
        'gasto' => $gasto,
    ], 201);
}

    public function getGastos()
{
    $listaGastos = Gasto::with('cliente:id,nombre')->select('cliente_id', 'descripcion', 'monto', 'fecha')
    ->orderBy('id', 'desc') ->get();

    // Transformar la colección para devolver el nombre del cliente en lugar del cliente_id
    $listaGastos = $listaGastos->map(function($gasto) {
        return [
            'cliente' => $gasto->cliente->nombre ?? 'Sin cliente',
            'descripcion' => $gasto->descripcion,
            'monto' => $gasto->monto,
            'fecha' => $gasto->fecha,
        ];
    });

    return response()->json($listaGastos);
}

  public function getMeses()
{
    
    $meses = Meses::select('id', 'nombre')->get();
    
    return response()->json($meses);
}

public function getMes($mesId)
{
    // Obtiene los gastos que coincidan con el mes_id recibido
    $gastos = Gasto::with('cliente:id,nombre')
        ->where('mes_id', $mesId)
        ->select('cliente_id', 'descripcion', 'monto', 'fecha')
        ->get();

    // Transformar los datos para devolver el nombre del cliente
    $gastos = $gastos->map(function($gasto) {
        return [
            'cliente' => $gasto->cliente->nombre ?? 'Sin cliente',
            'descripcion' => $gasto->descripcion,
            'monto' => $gasto->monto,
            'fecha' => $gasto->fecha,
        ];
    });

    return response()->json($gastos);
}


   public function getIngresos()
{
    $listaIngresos = Ingreso::with('cliente:id,nombre')->select('cliente_id', 'monto', 'descripcion', 'fecha')
    ->orderBy('id', 'desc') ->get();

    // Transformar la colección para devolver el nombre del cliente en lugar del cliente_id
    $listaIngresos = $listaIngresos->map(function($ingreso) {
        return [
            'cliente' => $ingreso->cliente->nombre ?? 'Sin cliente',
            'monto' => $ingreso->monto,
            'descripcion' => $ingreso->descripcion,
            'fecha' => $ingreso->fecha,
        ];
    });

    return response()->json($listaIngresos);
}


public function getMesIngresos($mesId)
{
    // Obtiene los gastos que coincidan con el mes_id recibido
    $ingresos = Ingreso::with('cliente:id,nombre')
        ->where('mes_id', $mesId)
        ->select('cliente_id', 'descripcion', 'monto', 'fecha')
        ->get();

    // Transformar los datos para devolver el nombre del cliente
    $ingresos = $ingresos->map(function($ingresos) {
        return [
            'cliente' => $ingresos->cliente->nombre ?? 'Sin cliente',
            'descripcion' => $ingresos->descripcion,
            'monto' => $ingresos->monto,
            'fecha' => $ingresos->fecha,
        ];
    });

    return response()->json($ingresos);
}


public function getGastosPorMes()
{
    $resultados = Gasto::join('meses', 'gastos.mes_id', '=', 'meses.id')
        ->select('meses.nombre as mes', DB::raw('SUM(gastos.monto) as total_gasto'))
        ->groupBy('gastos.mes_id', 'meses.nombre')
        ->orderBy('gastos.mes_id')
        ->get();

    return response()->json($resultados);
}

public function getGastosAltos()
{
    $gastosAltos = Gasto::select('monto', 'descripcion', 'cliente_id')
        ->with(['cliente:id,nombre,apellido'])
        ->orderByDesc('monto')
        ->take(5)
        ->get();

    return response()->json($gastosAltos);
}

public function getCategorias()
{
    
    $categoria = Categoria::select('id', 'nombre')->get();
    
    return response()->json($categoria);
}

public function getGastosPorCategoria()
{
    $gastosPorCategoria = Gasto::join('categorias', 'gastos.categoria_id', '=', 'categorias.id')
        ->select('categorias.nombre as categoria', DB::raw('SUM(gastos.monto) as total'))
        ->groupBy('categorias.nombre')
        ->orderByDesc('total')
        ->get();

    return response()->json($gastosPorCategoria);
}






}
