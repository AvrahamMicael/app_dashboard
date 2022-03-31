$(document).ready(() => {

    $("#documentacao").on('click', () => {
        $("#pagina").load('documentacao.html')
    })
    
    $("#suporte").on('click', () => {
        $("#pagina").load('suporte.html')
    })

    //dashboard click
    $("#dashboard").on('click', () => {
        $("#pagina").load('pagina.html')
    })

    //ajax - select de competencia
    $('select').on('change', e => {
        if($(e.target).val() == '') return false

        const competencia = $(e.target).val()

        $.ajax({
            type: 'get',
            url: 'app.php',
            data: `competencia=${competencia}`,
            dataType: 'json',
            success: dados => {
                $("#numero_vendas").html(dados.numero_vendas)
                $("#total_vendas").html(dados.total_vendas)
                $("#clientes_ativos").html(dados.clientes_ativos)
                $("#clientes_inativos").html(dados.clientes_inativos)
                $("#total_reclamacoes").html(dados.total_reclamacoes)
                $("#total_elogios").html(dados.total_elogios)
                $("#total_sugestoes").html(dados.total_sugestoes)
                $("#total_despesas").html(dados.total_despesas)
            },
            error: erro => console.log(erro.statusText)
        })

    })

})