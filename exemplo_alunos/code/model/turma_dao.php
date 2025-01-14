<?php
require_once '../db/conexao.php';
require_once 'turma.php';
require_once '../model/curso_dao.php';

class TurmaDao
{
    private $conexao;

    public function __construct()
    {
        global $conexao;
        $this->conexao = $conexao->conectar();
    }

    public function inserir(Turma $turma)
    {
        // monta SQL
        $sql = 'INSERT INTO tb_turma (id, nome, curso_id) VALUES (:id, :nome, :curso_id)';

        // preencher SQL com dados do aluno que eu quero inserir
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $turma->__get('id'));
        $stmt->bindValue(':nome', $turma->__get('nome'));
        $stmt->bindValue(':curso_id', $turma-> curso -> id);

        // manda executar SQL
        $stmt->execute();
    }

    public function listar_tudo()
    {
        $sql = 'SELECT * FROM tb_turma';
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_OBJ);
        $turmas = array();

        // percorrer resultados
        foreach ($resultados as $item) {
            
            //busca o id do curso para converter em um nome.
            $cursoDao = new CursoDao();
            $curso = $cursoDao->busca_id($item -> curso_id);
            
            // instanciar aluno novo
            $novo_turma = new Turma($item->id, $item->nome, $curso);
            
            // guardar num novo array
            $turmas[] = $novo_turma;
        }
        // retornar esse novo array
        return $turmas;
    }
    
    public function busca_id($id)
    {
        $sql = 'SELECT * from tb_turma where id = :id';
        $stmt = $this -> conexao -> prepare($sql);
        $stmt -> bindValue(':id', $id);
        $stmt -> execute();
        $resultado = $stmt -> fetch(PDO::FETCH_OBJ);

        $cursoDao = new CursoDao();
        $curso = $cursoDao->busca_id($resultado->curso_id); 

        $novo_turma = new Turma($resultado->id, $resultado->nome, $curso);

        return $novo_turma;
    }
}
