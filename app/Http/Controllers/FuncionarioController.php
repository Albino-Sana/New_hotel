<?php
namespace App\Http\Controllers;

use App\Models\Funcionario;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
class FuncionarioController extends Controller
{
    // Exibe a lista de funcionários
    public function index()
    {
        $funcionarios = Funcionario::with('cargo')->get();
        $cargos = Cargo::all(); // importante aqui também
        return view('funcionarios.index', compact('funcionarios', 'cargos'));
    }

    // Exibe o formulário para criar um novo funcionário
    public function create()
    {
        $cargos = Cargo::all();
        return view('funcionarios.index', compact('cargos'));
    }

    // Armazena o novo funcionário
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:funcionarios,email',
            'cargo_id' => 'required|exists:cargos,id',
            'telefone' => 'nullable|string|max:20',
            'sexo' => 'required|string|max:10',
            'morada' => 'required|string|max:255',
            'nacionalidade' => 'required|string|max:50',
            'naturalidade' => 'required|string|max:50',
            'data_nascimento' => 'required|date',
            'estado_civil' => 'required|string|max:30',
            'salario' => 'required|numeric',
            'turno' => 'required|string|max:30',
            'status' => 'required|in:Ativo,Inativo',
            'tipo_documento' => 'required|in:BI,Passaporte,Outro',
            'numero_documento' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        try {
            $idade = $request->data_nascimento ? Carbon::parse($request->data_nascimento)->age : null;
    
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('fotos_funcionarios', 'public');
            }
    
            Funcionario::create([
                'nome' => $request->nome,
                'email' => $request->email,
                'cargo_id' => $request->cargo_id,
                'telefone' => $request->telefone,
                'sexo' => $request->sexo,
                'morada' => $request->morada,
                'nacionalidade' => $request->nacionalidade,
                'naturalidade' => $request->naturalidade,
                'data_nascimento' => $request->data_nascimento,
                'idade' => $idade,
                'estado_civil' => $request->estado_civil,
                'salario' => $request->salario,
                'turno' => $request->turno,
                'status' => $request->status,
                'tipo_documento' => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'foto' => $fotoPath,
            ]);
    
            return redirect()->route('funcionarios.index')->with('success', 'Funcionário cadastrado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao cadastrar funcionário: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao cadastrar funcionário. Por favor, tente novamente.');
        }
    }
    // Exibe o formulário para editar o funcionário
    public function edit(Funcionario $funcionario)
    {
        $cargos = Cargo::all();
        return view('funcionarios.index', compact('funcionario', 'cargos'));
    }

    // Atualiza as informações do funcionário

    public function update(Request $request, Funcionario $funcionario)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:funcionarios,email,' . $funcionario->id,
            'cargo_id' => 'required|exists:cargos,id',
            'telefone' => 'nullable|string|max:20',
            'sexo' => 'nullable|string|max:10',
            'morada' => 'nullable|string|max:255',
            'nacionalidade' => 'nullable|string|max:50',
            'data_nascimento' => 'nullable|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        try {
            // Calcular nova idade se houver data de nascimento
            $idade = $request->data_nascimento ? Carbon::parse($request->data_nascimento)->age : null;
    
            // Upload nova foto, se houver
            $fotoPath = $funcionario->foto;
    
            if ($request->hasFile('foto')) {
                // Excluir foto antiga
                if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                    Storage::disk('public')->delete($fotoPath);
                }
    
                $fotoPath = $request->file('foto')->store('fotos_funcionarios', 'public');
            }
    
            // Atualiza os dados
            $funcionario->update([
                'nome' => $request->nome,
                'email' => $request->email,
                'cargo_id' => $request->cargo_id,
                'telefone' => $request->telefone,
                'sexo' => $request->sexo,
                'morada' => $request->morada,
                'nacionalidade' => $request->nacionalidade,
                'data_nascimento' => $request->data_nascimento,
                'idade' => $idade,
                'foto' => $fotoPath,
            ]);
    
            return redirect()->route('funcionarios.index')->with('success', 'Funcionário atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar funcionário: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao atualizar funcionário. Tente novamente.');
        }
    }

    // Exclui o funcionário
    public function destroy(Funcionario $funcionario)
    {
        try {
            $funcionario->delete();
            // Mensagem de sucesso usando SweetAlert
            Session::flash('success', 'Funcionário deletado com sucesso!');
            return redirect()->route('funcionarios.index');
        } catch (\Exception $e) {
            // Mensagem de erro em caso de falha
            Session::flash('error', 'Erro ao deletar o funcionário. Tente novamente!');
            return redirect()->route('funcionarios.index');
        }
    }
}
