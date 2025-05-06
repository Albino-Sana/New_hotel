<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuncionariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();  // ID do funcionário
            $table->string('nome');  // Nome do funcionário
            $table->string('email')->unique();  // E-mail do funcionário
            $table->foreignId('cargo_id')->constrained('cargos');  // Relação com a tabela cargos
            $table->string('telefone')->nullable();  // Número de telefone
            $table->string('sexo', 10)->nullable();  // Sexo (Masculino/Feminino)
            $table->string('morada')->nullable();  // Morada
            $table->string('nacionalidade')->nullable();  // Nacionalidade
            $table->date('data_nascimento')->nullable();  // Data de nascimento
            $table->integer('idade')->nullable();  // Idade (calculada ou fornecida)
            $table->string('naturalidade', 50);  // Naturalidade
            $table->string('estado_civil', 30);  // Estado civil
            $table->decimal('salario', 10, 2);  // Salário
            $table->string('turno', 30);  // Turno (ex: manhã, tarde, noite)
            $table->enum('tipo_documento', ['BI', 'Passaporte', 'Outro']);  // Tipo de documento
            $table->string('numero_documento');  // Número do documento
            $table->enum('status', ['Ativo', 'Inativo'])->default('Ativo');  // Status (Ativo/Inativo)
            $table->string('foto')->nullable();  // Foto
            $table->timestamps();  // Data de criação e atualização
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('funcionarios');
    }
}
