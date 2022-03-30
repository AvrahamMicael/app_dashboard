$(document).ready(() => {
	
    // $("#documentacao").on('click', () => {
    //     $("#pagina").load('documentacao.html')
    // })

    // $("#suporte").on('click', () => {
    //     $("#pagina").load('suporte.html')
    // })

    $("#documentacao").on('click', () => {
        // $.get('documentacao.html', data => {
        //     $("#pagina").html(data)
        // })
        
        $.post('documentacao.html', data => {
            $("#pagina").html(data)
        })
    })
    
    $("#suporte").on('click', () => {
        // $.get('suporte.html', data => {
        //     $("#pagina").html(data)
        // })
        
        $.post('suporte.html', data => {
            $("#pagina").html(data)
        })
    })

})