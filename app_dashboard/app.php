<?php

    class Dashboard {
        public $data_inicio;
        public $data_fim;
        public $numero_vendas;
        public $total_vendas;
        public $clientes_ativos;
        public $clientes_inativos;
        public $total_reclamacoes;
        public $total_elogios;
        public $total_sugestoes;
        public $total_despesas;

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
            return $this;
        }
    }

    class Conexao {
        private $host = 'localhost';
        private $dbname = 'dashboard';
        private $user = 'root';
        private $pass = '';

        public function conectar() {
            try {

                $conexao = new PDO(
                    "mysql:host=$this->host;dbname=$this->dbname",
                    "$this->user",
                    "$this->pass"
                );

                $conexao->exec('set charset utf8');

                return $conexao;

            } catch(PDOException $e) {
                echo '<p>'.$e->getMessage().'</p>';
            }
        }
    }

    class Bd {
        private $conexao;
        private $dashboard;

        public function __construct(Conexao $conexao, Dashboard $dashboard) {
            $this->conexao = $conexao->conectar();
            $this->dashboard = $dashboard;
        }

        public function getNumeroVendas() {
            $query = "
                select count(*) as numero_vendas
                from tb_vendas
                where data_venda between :data_inicio and :data_fim
            ";

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
        }

        public function getTotalVendas() {
            $query = "
                select sum(total) as total_vendas
                from tb_vendas
                where data_venda between :data_inicio and :data_fim
            ";

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
        }

        public function getClientes($status) {
            $query = "
                select count(*) as clientes
                from tb_clientes
                where cliente_ativo = '{$status}'
            ";

            $stmt = $this->conexao->query($query);

            return $stmt->fetch(PDO::FETCH_OBJ)->clientes;
        }

        public function getTotalContatos($tipo) {
            $query = "
                select count(*) as contato
                from tb_contatos
                where tipo_contato = '{$tipo}'
            ";
            

            $stmt = $this->conexao->query($query);

            return $stmt->fetch(PDO::FETCH_OBJ)->contato;
        }

        public function getTotalDespesas() {
            $query = "
                select sum(total) as total
                from tb_despesas
                where data_despesa between :data_inicio and :data_fim
            ";

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total;
        }
    }



    $dashboard = new Dashboard();
    $dashboard->__set('data_inicio', '2018-08-01');
    $dashboard->__set('data_fim', '2018-08-31');
    
    //competencia do select
    $competencia = explode('-', $_GET['competencia']);
    $ano = $competencia[0];
    $mes = $competencia[1];

    $dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

    $dashboard->__set('data_inicio', "$ano-$mes-01");
    $dashboard->__set('data_fim', "$ano-$mes-$dias_do_mes");
    //

    $conexao = new Conexao();
    
    $bd = new Bd($conexao, $dashboard);

    
    $dashboard->__set('numero_vendas', $bd->getNumeroVendas());
    $dashboard->__set('total_vendas', $bd->getTotalVendas());
    $dashboard->__set('clientes_ativos', $bd->getClientes(1));
    $dashboard->__set('clientes_inativos', $bd->getClientes(0));
    $dashboard->__set('total_reclamacoes', $bd->getTotalContatos(1));
    $dashboard->__set('total_elogios', $bd->getTotalContatos(2));
    $dashboard->__set('total_sugestoes', $bd->getTotalContatos(3));
    $dashboard->__set('total_despesas', $bd->getTotalDespesas());


    echo json_encode($dashboard);

?>