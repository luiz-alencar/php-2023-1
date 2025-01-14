<?php
require_once '../db/conexao.php';
require_once 'matricula.php';
require_once '../model/aluno_dao.php';
require_once '../model/turma_dao.php';

class MatriculaDao
{
    private $conexao;

    public function __construct()
    {
        global $conexao;
        $this->conexao = $conexao->conectar();
    }

    public function inserir(Matricula $matricula)
    {
        // monta SQL
        $sql = 'INSERT INTO tb_matricula (id, id_aluno, id_turma, data_ingresso) VALUES (:id, :id_aluno, :id_turma, :data_ingresso)';

        // preencher SQL com dados do aluno que eu quero inserir
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $matricula->__get('id'));
        $stmt->bindValue(':id_aluno', $matricula-> aluno -> id);
        $stmt->bindValue(':id_turma', $matricula-> turma -> id);
        $stmt->bindValue(':data_ingresso', $matricula->__get('data_ingresso'));
        
        // manda executar SQL
        $stmt->execute();
    }

    public function listar_tudo()
    {
        $sql = 'SELECT * FROM tb_matricula';
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_OBJ);
        $matriculas = array();

        // percorrer resultados
        foreach ($resultados as $item) {

            $alunoDao = new AlunoDao();
            $aluno = $alunoDao -> busca_id($item -> id_aluno);

            //busca o id da turma para converter em um nome.
            $turmaDao = new TurmaDao();
            $turma = $turmaDao -> busca_id($item -> id_turma);

            // instanciar aluno novo
            $novo_matricula = new Matricula($item->id, $aluno, $turma, $item->data_ingresso);
            
            // guardar num novo array
            $matriculas[] = $novo_matricula; 
        }
        // retornar esse novo array
        return $matriculas;
    }
}