<?php

require_once '../db/conexao.php';
require_once 'turma.php';

class TurmaDao
{
    private $conexao;

    public function __construct(Conexao $conexao)
    {
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
        $stmt->bindValue(':curso_id', $turma->__get('curso_id'));

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
        
            // instanciar aluno novo
            $novo_turma = new Turma($item->id, $item->nome, $item->curso_id);
            
            // guardar num novo array
            $turmas[] = $novo_turma;
        }
        // retornar esse novo array
        return $turmas;
    }
}