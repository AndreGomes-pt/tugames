// Função para carregar os dados da tabela com filtros
function carregarTabela(params = {}) {
    let queryParams = $.param(params);
    $.ajax({
        url: 'table_data.php?' + queryParams, 
        method: 'GET',
        dataType: 'html', 
        success: function(data) {
            // Insere o HTML retornado no tbody da tabela
            $('#table-tbody').html(data);
        },
        error: function(err) {
            console.log('Erro ao carregar dados da tabela', err);
        }
    });
}

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.onmouseenter = Swal.stopTimer;
      toast.onmouseleave = Swal.resumeTimer;
    }
  });
//Função que lida com as ações comuns (editar, eliminar, etc.)
/*
              Params
         [0]      [1]         --> dados adicionais
          |        |            
          V        V             
         id   sweetAlert-msg
*/
function action (url, params = {}, showMsg,callback){
    let queryParams = $.param(params);

    /*console.log("Url --> " + url);
    console.log("Show Msg --> " + showMsg);
    console.log("ID --> " + params.id);
    console.log("Msg --> " + params.message);
    console.log("Params Query -->" + queryParams);*/


    if(showMsg){
        Swal.fire({
            title: params.message,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                //Caso seja confirmado proceder com o jquery
                $.ajax({
                    url: url + '?' + queryParams, 
                    method: 'GET',
                    dataType: 'json', 
                    success: function(response) {
                        if (response.success) {
                            Toast.fire({
                                icon: "success",
                                title: response.message
                            });
                            // Executa a função de callback se definida
                            if (typeof callback === 'function') {
                                callback(); // Chama a função callback
                            }
                        } else {
                            // Em caso de sucesso, mas com mensagem de erro
                            Toast.fire({
                                icon: "warning",
                                title: response.message
                            });
                        }
                    },
                    error: function(err) {
                        console.log(err.responseText);
                        Toast.fire({
                            icon: "error",
                            title: "Ocurreu um erro : " + err.status
                          });
                    }
                });
            }
        });
    }else{
        $.ajax({
            url: url + '?' + queryParams, 
            method: 'GET',
            dataType: 'html', 
            success: function(data) {
                Toast.fire({
                    icon: "success",
                    title: "Operação Efetuada com sucesso"
                  });
                // Executa a função de callback se definida
                if (typeof callback === 'function') {
                    callback(); // Chama a função callback
                }
            },
            error: function(err) {
                console.log(err.responseText);
                Toast.fire({
                    icon: "error",
                    title: "Ocurreu um erro : " + err.status
                });
            }
        });
    }
}



$(document).ready(function() {
    carregarTabela();
});
